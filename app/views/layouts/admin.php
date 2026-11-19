<?php
/** @var string $content */
$activeNav = $activeNav ?? 'admin';
$isActive = static fn (string $key): string => $activeNav === $key ? ' active' : '';
$isCurrent = static fn (string $key): string => $activeNav === $key ? ' aria-current="page"' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'CEIT DRAMS') ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>?v=<?= filemtime(BASE_PATH . '/assets/css/style.css') ?>">
</head>
<body>
    <div class="dashboard-shell">
        <aside class="dashboard-sidebar">
            <a class="sidebar-brand" href="<?= url('/admin') ?>">
                <img src="<?= url('assets/images/cvsu_logo.png') ?>" alt="" class="sidebar-logo">
                <span>CEIT <strong>DRAMS</strong></span>
            </a>

            <nav class="sidebar-nav" aria-label="Admin sections">
                <a class="<?= $isActive('admin') ?>" href="<?= url('/admin') ?>"<?= $isCurrent('admin') ?>>Dashboard</a>
                <a class="<?= $isActive('queue') ?>" href="<?= url('/admin') ?>?status=pending"<?= $isCurrent('queue') ?>>Pending Queue</a>
                <a class="<?= $isActive('requester') ?>" href="<?= url('/dashboard') ?>"<?= $isCurrent('requester') ?>>Requester View</a>
            </nav>

            <a class="sidebar-logout" href="<?= url('/logout') ?>">Logout</a>
        </aside>

        <div class="dashboard-content">
            <?php $flash = take_flash(); ?>
            <?php if ($flash !== null): ?>
                <div class="flash flash-<?= e($flash['type']) ?>" role="<?= $flash['type'] === 'error' ? 'alert' : 'status' ?>">
                    <?= icon($flash['type'] === 'success' ? 'check' : ($flash['type'] === 'error' ? 'alert' : 'info')) ?>
                    <span class="flash-message"><?= e($flash['message']) ?></span>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <script src="<?= url('assets/js/app.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/app.js') ?>"></script>
    <script src="<?= url('assets/js/admin.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/admin.js') ?>"></script>
</body>
</html>
