document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;

    function currentTheme() {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    }

    function applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        btn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
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

    btn.addEventListener('click', function (e) {
        e.preventDefault();
        const next = currentTheme() === 'dark' ? 'light' : 'dark';

        // Apply immediately for instant UX
        applyTheme(next);

        // Navigate to server route to persist cookie
        // Build URL relative to site root
        const url = '/theme/' + encodeURIComponent(next);
        window.location.href = url;
    });

    // Attach handlers to theme-choice links
    const choices = Array.from(document.querySelectorAll('.theme-choice'));
    choices.forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const theme = el.dataset.theme;
            applyThemeImmediate(theme);
            // navigate to server route to persist cookie
            window.location.href = el.href;
        });
    });

    // If cookie is 'system' we also want to respond to OS changes while the page is open
    try {
        const cookieTheme = (document.cookie || '').split(';').map(s => s.trim()).find(s => s.startsWith('theme='));
        const value = cookieTheme ? decodeURIComponent(cookieTheme.split('=')[1]) : null;
        if (value === 'system') {
            // listen for changes
            if (window.matchMedia) {
                const mq = window.matchMedia('(prefers-color-scheme: dark)');
                mq.addEventListener?.('change', e => {
                    applyThemeImmediate('system');
                });
            }
        }
    } catch (err) {
        // ignore
    }
});
