<?php
/** @var string $content */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'CEIT DRAMS') ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
    <?php require __DIR__ . '/header.php'; ?>

    <main class="container">
        <?= $content ?>
    </main>

    <?php require __DIR__ . '/footer.php'; ?>

    <script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>
