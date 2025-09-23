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
});

