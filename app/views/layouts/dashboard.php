<?php
/** @var string $content */
$activeNav = $activeNav ?? 'dashboard';
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
    <?php require __DIR__ . '/partials/theme-head.php'; ?>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>?v=<?= filemtime(BASE_PATH . '/assets/css/style.css') ?>">
</head>
<body>
    <div class="dashboard-shell">
        <aside class="dashboard-sidebar">
            <a class="sidebar-brand" href="<?= url('/dashboard') ?>">
                <img src="<?= url('assets/images/cvsu_logo.png') ?>" alt="" class="sidebar-logo">
                <span>CEIT <strong>DRAMS</strong></span>
            </a>

            <nav class="sidebar-nav" aria-label="Dashboard sections">
                <a class="<?= $isActive('dashboard') ?>" href="<?= url('/dashboard') ?>"<?= $isCurrent('dashboard') ?>>Dashboard</a>
                <a class="<?= $isActive('request') ?>" href="<?= url('/request') ?>"<?= $isCurrent('request') ?>>Request Document</a>
                <a class="<?= $isActive('requests') ?>" href="<?= url('/requests') ?>"<?= $isCurrent('requests') ?>>My Requests</a>
                <a class="<?= $isActive('documents') ?>" href="<?= url('/documents') ?>"<?= $isCurrent('documents') ?>>Available Documents</a>
                <a class="<?= $isActive('profile') ?>" href="<?= url('/profile') ?>"<?= $isCurrent('profile') ?>>Profile</a>
            </nav>

            <div class="sidebar-utilities">
                <?php require __DIR__ . '/partials/theme-sidebar.php'; ?>
                <a class="sidebar-logout" href="<?= url('/logout') ?>">Logout</a>
            </div>
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

    <script src="<?= url('assets/js/theme.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/theme.js') ?>"></script>
    <script src="<?= url('assets/js/app.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/app.js') ?>"></script>
    <script src="<?= url('assets/js/documents.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/documents.js') ?>"></script>
</body>
</html>
