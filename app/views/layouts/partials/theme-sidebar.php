<?php
/** Theme toggle for the dashboard and admin sidebar utility zone. */
?>
<button type="button"
        class="theme-toggle"
        data-theme-toggle
        aria-label="Switch to dark mode">
    <span class="theme-toggle-icon" aria-hidden="true">
        <span class="theme-icon theme-icon-sun"><?= icon('sun') ?></span>
        <span class="theme-icon theme-icon-moon"><?= icon('moon') ?></span>
    </span>
    <span class="theme-toggle-label" aria-hidden="true">
        <span class="theme-destination-dark">Dark</span>
        <span class="theme-destination-light">Light</span>
    </span>
</button>
