<?php
/**
 * Applied before the stylesheet loads so the saved theme never flashes.
 */
?>
<script>
    (function () {
        try {
            var theme = localStorage.getItem('ceit-drams-theme');
            document.documentElement.setAttribute('data-theme', theme === 'dark' ? 'dark' : 'light');
        } catch (e) {
            /* Storage may be unavailable; the light default still applies. */
            document.documentElement.setAttribute('data-theme', 'light');
        }
    })();
</script>
