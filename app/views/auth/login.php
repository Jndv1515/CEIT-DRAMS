<div class="auth-shell">
    <div class="auth-layout">
        <section class="auth-brand-panel">
            <a class="auth-brand" href="<?= url('/') ?>">
                <span class="auth-logo-wrap">
                    <img src="<?= url('assets/images/cvsu_logo.png') ?>" alt="App Logo" class="auth-logo">
                </span>
                <span class="sr-only">CvSU Feedback</span>
            </a>

            <p class="auth-brand-name">CVSU - CEIT</p>
            <h2 class="auth-brand-title">Document Request and Approval Management System</h2>
            <p class="auth-tagline">Request. Track. Approve.</p>
        </section>

        <section class="auth-form-panel">
            <div class="auth-tabs">
                <span class="active">Login</span>
                <a href="<?= url('/register') ?>">Registration</a>
            </div>

            <h1 class="auth-title">Login</h1>

            <!-- TODO: Authentication is not wired up yet; this is the UI scaffold. to be wired up by the backend -->
            <form method="post" action="<?= url('/login') ?>" class="auth-form" id="basic-login-form">
                <div class="field">
                    <label for="email">Email / Student No.</label>
                    <input type="text" id="email" name="email" placeholder="Email / Student No."
                           autocomplete="username" autofocus required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password"
                           autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <p class="auth-switch">
                Don't have an account? <a href="<?= url('/register') ?>">Register here</a>
            </p>
        </section>
    </div>
</div>
