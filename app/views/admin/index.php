<?php
$actionButton = static function (
    array $request,
    string $status,
    string $label,
    string $variant,
    string $iconName,
    bool $confirm = false
) use ($statusFilter): void {
    $id = (string) ($request['id'] ?? '');
    $document = (string) ($request['document'] ?? '');
    $buttonClass = $variant === 'approve'
        ? 'btn-primary'
        : ($variant === 'reject' ? 'btn-danger' : 'btn-ghost');
    ?>
    <form class="inline-form" method="post" action="<?= url('/admin/action') ?>" data-decision-form>
        <input type="hidden" name="id" value="<?= e($id) ?>">
        <input type="hidden" name="document" value="<?= e($document) ?>">
        <input type="hidden" name="status" value="<?= e($status) ?>">
        <input type="hidden" name="filter" value="<?= e($statusFilter) ?>">
        <input type="hidden" name="reason" value="" data-reason>
        <button type="submit"
                class="btn-sm <?= $buttonClass ?> btn-icon"
                data-action="<?= e($status) ?>"
                <?= $confirm ? 'data-confirm="true"' : '' ?>
                aria-label="<?= e($label . ' ' . $document . ' (' . $id . ')') ?>">
            <?= icon($iconName) ?>
            <span><?= e($label) ?></span>
        </button>
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
        ['status' => 'processing', 'label' => 'Processing', 'icon' => 'progress'],
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
    <?php if ($requests === []): ?>
        <?= empty_state([
            'title' => 'No requests in this view',
            'message' => $statusFilter === 'all'
                ? 'New document requests will appear here as soon as they are submitted.'
                : 'Try another status filter to see the rest of the queue.',
            'actionLabel' => $statusFilter === 'all' ? 'Refresh queue' : 'Clear filters',
            'actionUrl' => url('/admin'),
            'icon' => 'document',
        ]) ?>
    <?php else: ?>
        <div class="request-table queue-table" role="table" aria-label="Admin request queue">
            <div class="request-row request-head" role="row">
                <span role="columnheader">Document</span>
                <span role="columnheader">Category</span>
                <span role="columnheader">Requester</span>
                <span role="columnheader">Date Requested</span>
                <span role="columnheader">Status</span>
                <span role="columnheader">Actions</span>
            </div>

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
                            <?php if ($status === 'pending' || $status === 'processing'): ?>
                                <?php $actionButton($request, 'approved', 'Approve', 'approve', 'check'); ?>
                                <?php $actionButton($request, 'forwarded', 'Forward', 'forward', 'forward'); ?>
                                <?php $actionButton($request, 'rejected', 'Reject', 'reject', 'x', true); ?>
                            <?php else: ?>
                                <?php $actionButton($request, 'pending', 'Reset', 'reset', 'rotate', true); ?>
                            <?php endif; ?>
                        </span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

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
