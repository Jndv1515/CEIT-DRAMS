<?php
/** Theme switch for the dashboard and admin sidebar utility zone. */
?>
<button type="button"
        class="theme-switch"
        data-theme-toggle="switch"
        role="switch"
        aria-checked="false"
        aria-label="Light mode">
    <span class="theme-switch-icon" aria-hidden="true">
        <span class="theme-icon theme-icon-moon"><?= icon('moon') ?></span>
        <span class="theme-icon theme-icon-sun"><?= icon('sun') ?></span>
    </span>
    <span class="theme-switch-label">Light mode</span>
    <span class="theme-switch-track" aria-hidden="true">
        <span class="theme-switch-thumb"></span>
    </span>
</button>
