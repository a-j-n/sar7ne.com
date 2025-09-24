document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('theme-dialog-trigger');
    const dialog = document.getElementById('theme-dialog');
    if (!trigger || !dialog) return;

    const overlay = dialog.querySelector('[data-dialog-overlay]');
    const closeButtons = Array.from(dialog.querySelectorAll('[data-dialog-close]'));
    const choices = Array.from(dialog.querySelectorAll('.theme-choice'));

    let lastFocused = null;

    function openDialog() {
        lastFocused = document.activeElement;
        dialog.classList.remove('hidden');
        dialog.setAttribute('aria-hidden', 'false');

        // focus first focusable element inside dialog
        const focusable = getFocusableElements(dialog);
        if (focusable.length) focusable[0].focus();

        document.addEventListener('keydown', handleKeyDown);
    }

    function closeDialog() {
        dialog.classList.add('hidden');
        dialog.setAttribute('aria-hidden', 'true');
        document.removeEventListener('keydown', handleKeyDown);
        if (lastFocused && typeof lastFocused.focus === 'function') {
            lastFocused.focus();
        }
    }

    function handleKeyDown(e) {
        if (e.key === 'Escape') {
            e.preventDefault();
            closeDialog();
            return;
        }

        if (e.key === 'Tab') {
            // focus trap
            const focusable = getFocusableElements(dialog);
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }
    }

    function getFocusableElements(container) {
        const selectors = 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])';
        return Array.from(container.querySelectorAll(selectors)).filter(el => el.offsetParent !== null);
    }

    function isSystemDark() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    function applyThemeImmediate(theme) {
        if (theme === 'system') {
            if (isSystemDark()) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } else if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    function persistTheme(theme) {
        try {
            // Persist cookie for 1 year, path=/ so it applies to whole site
            const expires = new Date();
            expires.setFullYear(expires.getFullYear() + 1);
            document.cookie = 'theme=' + encodeURIComponent(theme) + '; expires=' + expires.toUTCString() + '; path=/';
        } catch (e) {
            // ignore
        }
    }

    // Open dialog on trigger click
    trigger.addEventListener('click', function (e) {
        e.preventDefault();
        openDialog();
    });

    // Close on overlay click
    if (overlay) {
        overlay.addEventListener('click', function () {
            closeDialog();
        });
    }

    // Close on close buttons
    closeButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeDialog();
        });
    });

    // Choice handlers
    choices.forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const theme = el.dataset.theme;
            const href = el.dataset.href || el.getAttribute('data-href') || null;

            applyThemeImmediate(theme);
            persistTheme(theme);

            // update visual selection state inside dialog
            choices.forEach(c => c.classList.remove('font-semibold', 'bg-slate-100', 'dark:bg-slate-800'));
            el.classList.add('font-semibold', 'bg-slate-100', 'dark:bg-slate-800');

            // Close dialog. Server persistence optional; UI already updated.
            closeDialog();
            if (href) {
                // still hit server route to keep backend cookie in sync (no-op if same)
                fetch(href, { credentials: 'same-origin' }).catch(() => {});
            }
        });
    });

    // Listen for system preference changes if cookie set to system
    try {
        const cookieTheme = (document.cookie || '').split(';').map(s => s.trim()).find(s => s.startsWith('theme='));
        const value = cookieTheme ? decodeURIComponent(cookieTheme.split('=')[1]) : null;
        if (value === 'system') {
            if (window.matchMedia) {
                const mq = window.matchMedia('(prefers-color-scheme: dark)');
                mq.addEventListener?.('change', () => applyThemeImmediate('system'));
            }
        }
    } catch (err) {
        // ignore
    }
});
