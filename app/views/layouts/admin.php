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

    <dialog class="confirm-dialog" id="decision-dialog" aria-labelledby="decision-dialog-title" aria-describedby="decision-dialog-description">
        <div class="confirm-dialog-head">
            <h2 id="decision-dialog-title">Confirm action</h2>
            <p id="decision-dialog-description">Are you sure you want to continue?</p>
        </div>
        <div class="confirm-dialog-body" id="decision-dialog-reason-field" hidden>
            <label for="decision-reason">Reason <span class="required">*</span></label>
            <textarea id="decision-reason" minlength="10" placeholder="Explain why this request is being rejected."></textarea>
            <p class="dialog-error" id="decision-dialog-error" hidden>Please enter a reason of at least 10 characters.</p>
        </div>
        <div class="confirm-dialog-actions">
            <button type="button" class="btn-ghost btn-sm" id="decision-cancel">Cancel</button>
            <button type="button" class="btn-danger btn-sm" id="decision-confirm">Confirm</button>
        </div>
    </dialog>

    <?php $focusId = $_SESSION['focus_request_id'] ?? null; ?>
    <?php unset($_SESSION['focus_request_id']); ?>
    <?php if (is_string($focusId) && $focusId !== ''): ?>
        <script>
            (function () {
                var row = document.querySelector('[data-request-id=' + <?= json_encode($focusId, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> + ']');
                if (row) {
                    row.focus();
                    row.scrollIntoView({ block: 'center' });
                }
            })();
        </script>
    <?php endif; ?>

    <script src="<?= url('assets/js/app.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/app.js') ?>"></script>
    <script src="<?= url('assets/js/admin.js') ?>?v=<?= filemtime(BASE_PATH . '/assets/js/admin.js') ?>"></script>
</body>
</html>
