// Global light/dark theme control.

(function () {
    'use strict';

    var STORAGE_KEY = 'ceit-drams-theme';
    var root = document.documentElement;

    function apply(theme) {
        var action = theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode';

        root.setAttribute('data-theme', theme);

        // The visible label and icon are driven by CSS, so they never flash on load.
        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            button.setAttribute('aria-label', action);
        });
    }

    apply(root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light');

    document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            var next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';

            try {
                localStorage.setItem(STORAGE_KEY, next);
            } catch (e) {
                /* Theme still applies for this page view. */
            }

            apply(next);
        });
    });
})();
