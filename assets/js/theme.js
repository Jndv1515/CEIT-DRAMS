// Global light/dark theme control.

(function () {
    'use strict';

    var STORAGE_KEY = 'ceit-drams-theme';
    var root = document.documentElement;

    function apply(theme) {
        var isLight = theme === 'light';
        root.setAttribute('data-theme', theme);

        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            if (button.getAttribute('data-theme-toggle') === 'switch') {
                button.setAttribute('aria-checked', isLight ? 'true' : 'false');
            } else {
                button.setAttribute(
                    'aria-label',
                    isLight ? 'Switch to dark mode' : 'Switch to light mode'
                );
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
