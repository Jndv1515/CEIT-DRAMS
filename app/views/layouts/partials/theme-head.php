<?php
/**
 * Applied before the stylesheet loads so the saved theme never flashes.
 */
?>
<script>
    (function () {
        try {
            var theme = localStorage.getItem('ceit-drams-theme');
            if (theme === 'light' || theme === 'dark') {
                document.documentElement.setAttribute('data-theme', theme);
            }
        } catch (e) {
            /* Storage may be unavailable; the dark default still applies. */
        }
    })();
</script>
