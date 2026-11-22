<?php
/** @var string $content */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'CEIT DRAMS') ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <?php require __DIR__ . '/partials/theme-head.php'; ?>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>?v=<?= filemtime(BASE_PATH . '/assets/css/style.css') ?>">
</head>
<body>
    <?php require __DIR__ . '/partials/theme-icon.php'; ?>

    <?php if ($showSiteChrome ?? true): ?>
        <?php require __DIR__ . '/header.php'; ?>
    <?php endif; ?>

    <main class="container">
        <?= $content ?>
    </main>

    <?php if ($showSiteChrome ?? true): ?>
        <?php require __DIR__ . '/footer.php'; ?>
    <?php endif; ?>

    <script src="<?= url('assets/js/theme.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/theme.js') ?>"></script>
    <script src="<?= url('assets/js/app.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/app.js') ?>"></script>
</body>
</html>
