document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('lang-toggle');
    const dialog = document.getElementById('lang-dialog');
    const overlay = document.getElementById('lang-dialog-overlay');
    const closeBtn = document.getElementById('lang-dialog-close');

    if (!btn || !dialog) return;

    const choices = Array.from(dialog.querySelectorAll('.lang-choice'));
    let previousActive = null;

    function setAriaOpen(open) {
        btn.setAttribute('aria-expanded', String(open));
        if (open) {
            dialog.style.display = '';
            dialog.setAttribute('aria-hidden', 'false');
        } else {
            dialog.style.display = 'none';
            dialog.setAttribute('aria-hidden', 'true');
        }
    }

    function openDialog() {
        previousActive = document.activeElement;
        setAriaOpen(true);
        // focus first choice
        if (choices.length) {
            choices[0].focus();
        }
        // add keydown listener for focus trap
        document.addEventListener('keydown', onKeyDown);
        // prevent body scroll
        document.documentElement.style.overflow = 'hidden';
    }

    function closeDialog() {
        setAriaOpen(false);
        if (previousActive && typeof previousActive.focus === 'function') {
            previousActive.focus();
        }
        document.removeEventListener('keydown', onKeyDown);
        document.documentElement.style.overflow = '';
    }

    function onKeyDown(e) {
        if (e.key === 'Escape') {
            e.preventDefault();
            closeDialog();
            return;
        }

        // Focus trapping
        if (e.key === 'Tab') {
            const focusable = choices.filter(c => !c.hasAttribute('disabled'));
            if (focusable.length === 0) return;

            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            const active = document.activeElement;

            if (e.shiftKey) {
                if (active === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (active === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }

        // Arrow navigation
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            const idx = choices.indexOf(document.activeElement);
            if (idx === -1) return;
            const next = e.key === 'ArrowDown' ? (idx + 1) % choices.length : (idx - 1 + choices.length) % choices.length;
            choices[next].focus();
        }

        // Enter/Space to activate
        if (e.key === 'Enter' || e.key === ' ') {
            const active = document.activeElement;
            if (choices.includes(active)) {
                e.preventDefault();
                active.click();
            }
        }
    }

    // Toggle dialog on button click
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        const open = btn.getAttribute('aria-expanded') === 'true';
        if (open) closeDialog(); else openDialog();
    });

    // Close on overlay click
    if (overlay) {
        overlay.addEventListener('click', function () {
            closeDialog();
        });
    }

    // Close on close button
    if (closeBtn) {
        closeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            closeDialog();
        });
    }

    // Handle language choice clicks: set dir immediately then navigate
    choices.forEach(function (link) {
        // ensure they are focusable
        link.setAttribute('tabindex', '0');

        link.addEventListener('click', function (e) {
            e.preventDefault();
            const dir = link.dataset.dir || 'ltr';
            try { document.documentElement.setAttribute('dir', dir); } catch (err) {}
            // navigate to server route to persist session/cookie
            window.location.href = link.href;
        });
    });

    // Accessibility: close dialog on focusout if focus leaves dialog entirely
    dialog.addEventListener('focusout', function (e) {
        // if focus moves outside the dialog and not to dialog children, close
        const related = e.relatedTarget;
        if (!dialog.contains(related)) {
            // small timeout to let other handlers run
            setTimeout(function () {
                if (!dialog.contains(document.activeElement)) {
                    closeDialog();
                }
            }, 10);
        }
    });
});
