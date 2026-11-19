<div class="auth-shell">
    <div class="auth-panel">
        <a class="auth-brand" href="<?= url('/') ?>">
            <span class="auth-logo-wrap">
                <img src="<?= url('assets/images/cvsu_logo.png') ?>" alt="App Logo" class="auth-logo">
            </span>
            <span class="sr-only">CvSU Feedback</span>
        </a>

        <div class="auth-stack">
            <div class="auth-badge">CEIT Document Request and Approval Management System</div>

            <!-- TODO: Authentication is not wired up yet; this is the UI scaffold. to be wired up by the backend -->
            <form method="post" action="<?= url('/login') ?>" class="auth-form" id="basic-login-form">
                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" placeholder="Email address"
                           autocomplete="email" autofocus required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password"
                           autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn-submit">Log in</button>
            </form>

            <p class="auth-switch">
                Don't have an account? <a href="<?= url('/register') ?>">Register</a>
            </p>
        </div>
    </div>
</div>
