// Global light/dark theme control.

(function () {
    'use strict';

    var STORAGE_KEY = 'ceit-drams-theme';
    var root = document.documentElement;

    function apply(theme) {
        var isLight = theme === 'light';
        var destination = isLight ? 'Dark' : 'Light';
        var action = isLight ? 'Switch to dark mode' : 'Switch to light mode';

        root.setAttribute('data-theme', theme);

        // The short label and icon show the destination; the accessible name stays explicit.
        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            button.setAttribute('aria-label', action);

            var label = button.querySelector('.theme-toggle-label');
            if (label) {
                label.textContent = destination;
            }
        });
    }

    apply(root.getAttribute('data-theme') === 'light' ? 'light' : 'dark');

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
