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
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
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
                <a class="<?= $isActive('documents') ?>" href="<?= url('/documents') ?>"<?= $isCurrent('documents') ?>>My Documents</a>
                <a class="<?= $isActive('profile') ?>" href="<?= url('/dashboard') ?>"<?= $isCurrent('profile') ?>>Profile</a>
            </nav>

            <a class="sidebar-logout" href="<?= url('/logout') ?>">Logout</a>
        </aside>

        <div class="dashboard-content">
            <?php $flash = take_flash(); ?>
            <?php if ($flash !== null): ?>
                <div class="flash" role="status"><?= e($flash) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>
