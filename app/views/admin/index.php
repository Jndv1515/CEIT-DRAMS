<?php
$actionButton = static function (array $request, string $status, string $label, string $variant) use ($statusFilter): void {
    ?>
    <form class="inline-form" method="post" action="<?= url('/admin/action') ?>">
        <input type="hidden" name="id" value="<?= e((string) ($request['id'] ?? '')) ?>">
        <input type="hidden" name="status" value="<?= e($status) ?>">
        <input type="hidden" name="filter" value="<?= e($statusFilter) ?>">
        <button type="submit" class="action-button action-button-<?= e($variant) ?>"><?= e($label) ?></button>
    </form>
    <?php
};
?>
<section class="page-header">
    <div>
        <p class="page-eyebrow">Admin / Staff</p>
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Review, approve, reject, and forward document requests.</p>
    </div>
</section>

<section class="stat-grid" aria-label="Request summary">
    <?php
    $cards = [
        ['status' => 'pending', 'label' => 'Pending', 'icon' => 'clock'],
        ['status' => 'approved', 'label' => 'Approved', 'icon' => 'check'],
        ['status' => 'forwarded', 'label' => 'Forwarded', 'icon' => 'forward'],
        ['status' => 'rejected', 'label' => 'Rejected', 'icon' => 'x'],
    ];
    ?>
    <?php foreach ($cards as $card): ?>
        <a class="card stat-card" href="<?= url('/admin') ?>?status=<?= e($card['status']) ?>">
            <span class="stat-icon" aria-hidden="true"><?= icon($card['icon']) ?></span>
            <span class="stat-value"><?= str_pad((string) ($counts[$card['status']] ?? 0), 2, '0', STR_PAD_LEFT) ?></span>
            <span class="stat-label"><?= e($card['label']) ?></span>
        </a>
    <?php endforeach; ?>
</section>

<nav class="view-tabs" role="tablist" aria-label="Filter request queue">
    <?php foreach (array_merge(['all'], request_statuses()) as $status): ?>
        <a class="view-tab <?= $statusFilter === $status ? 'active' : '' ?>"
           href="<?= url('/admin') ?><?= $status === 'all' ? '' : '?status=' . e($status) ?>"
           role="tab" aria-selected="<?= $statusFilter === $status ? 'true' : 'false' ?>">
            <?= $status === 'all' ? 'All' : e(ucfirst($status)) ?>
        </a>
    <?php endforeach; ?>
</nav>

<section class="card" role="tabpanel" aria-label="Request queue">
    <div class="request-table queue-table" role="table" aria-label="Admin request queue">
        <div class="request-row request-head" role="row">
            <span role="columnheader">Document</span>
            <span role="columnheader">Category</span>
            <span role="columnheader">Requester</span>
            <span role="columnheader">Date Requested</span>
            <span role="columnheader">Status</span>
            <span role="columnheader">Actions</span>
        </div>

        <?php if ($requests === []): ?>
            <div class="request-row queue-empty">
                <span role="cell">No requests in this view.</span>
            </div>
        <?php else: ?>
            <?php foreach ($requests as $request): ?>
                <?php $status = (string) ($request['status'] ?? 'pending'); ?>
                <div class="request-row" role="row">
                    <span role="cell" data-label="Document">
                        <span class="request-document"><?= e((string) ($request['document'] ?? '')) ?></span>
                    </span>
                    <span role="cell" data-label="Category"><?= e((string) ($request['category'] ?? '')) ?></span>
                    <span role="cell" data-label="Requester">
                        <span class="requester-badge requester-badge-<?= e((string) ($request['type'] ?? '')) ?>">
                            <?= e(ucfirst((string) ($request['type'] ?? ''))) ?>
                        </span>
                    </span>
                    <span role="cell" data-label="Date Requested"><?= e((string) ($request['date'] ?? '')) ?></span>
                    <span role="cell" data-label="Status">
                        <span class="status status-<?= e($status) ?>"><?= e(ucfirst($status)) ?></span>
                    </span>
                    <span role="cell" data-label="Actions">
                        <span class="action-links">
                            <a class="action-link"
                               href="<?= url('/request-details') ?>?id=<?= e((string) ($request['id'] ?? '')) ?>&amp;from=admin">View</a>
                            <?php if ($status === 'pending'): ?>
                                <?php $actionButton($request, 'approved', 'Approve', 'approve'); ?>
                                <?php $actionButton($request, 'forwarded', 'Forward', 'forward'); ?>
                                <?php $actionButton($request, 'rejected', 'Reject', 'reject'); ?>
                            <?php else: ?>
                                <?php $actionButton($request, 'pending', 'Reset', 'reset'); ?>
                            <?php endif; ?>
                        </span>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
