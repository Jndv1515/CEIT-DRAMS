<section class="card">
    <h1><?= http_response_code() === 404 ? '404 — Page not found' : 'Something went wrong' ?></h1>
    <p class="muted">The page you requested could not be displayed.</p>
    <a class="btn" href="<?= url('/') ?>">Back to home</a>
</section>
