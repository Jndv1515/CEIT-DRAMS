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

            <!-- TODO: Registration is not wired up yet; this is the UI scaffold. -->
            <form method="post" action="<?= url('/register') ?>" class="auth-form" id="register-form">
                <div class="field">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name" placeholder="Full name"
                           autocomplete="name" required>
                </div>

                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" placeholder="Email address"
                           autocomplete="email" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password"
                           autocomplete="new-password" required>
                </div>

                <div class="field">
                    <label for="password-confirmation">Confirm password</label>
                    <input type="password" id="password-confirmation" name="password_confirmation"
                           placeholder="Confirm password" autocomplete="new-password" required>
                </div>

                <button type="submit" class="btn-submit">Register</button>
            </form>

            <p class="auth-switch">
                Already have an account? <a href="<?= url('/login') ?>">Log in</a>
            </p>
        </div>
    </div>
</div>
