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
                <a href="<?= url('/login') ?>">Login</a>
                <span class="active">Registration</span>
            </div>

            <h1 class="auth-title">Create account</h1>

            <!-- TODO: Registration is not wired up yet; this is the UI scaffold. -->
            <form method="post" action="<?= url('/register') ?>" class="auth-form" id="register-form">
                <div class="field">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name" placeholder="Full name"
                           autocomplete="name" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email"
                           autocomplete="email" required>
                </div>

                <div class="field">
                    <label for="student-number">Student No.</label>
                    <input type="text" id="student-number" name="student_number" placeholder="Student No."
                           autocomplete="off" required>
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
                Already have an account? <a href="<?= url('/login') ?>">Login here</a>
            </p>
        </section>
    </div>
</div>
