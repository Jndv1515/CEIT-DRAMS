<?php
$queueQuery = static function (string $status = 'all') use ($statusFilter, $search, $sort): string {
    $params = [];

    if ($status !== 'all') {
        $params['status'] = $status;
    }
    if ($search !== '') {
        $params['q'] = $search;
    }
    if ($sort !== 'date-desc') {
        $params['sort'] = $sort;
    }

    return $params === [] ? '' : '?' . http_build_query($params);
};
$hasFilters = $statusFilter !== 'all' || $search !== '' || $sort !== 'date-desc';
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
        <a class="card stat-card <?= $statusFilter === $card['status'] ? 'is-selected' : '' ?>"
           href="<?= url('/admin') ?><?= $queueQuery($card['status']) ?>"
           <?= $statusFilter === $card['status'] ? 'aria-current="page"' : '' ?>>
            <span class="stat-icon" aria-hidden="true"><?= icon($card['icon']) ?></span>
            <span class="stat-value"><?= str_pad((string) ($counts[$card['status']] ?? 0), 2, '0', STR_PAD_LEFT) ?></span>
            <span class="stat-label"><?= e($card['label']) ?></span>
        </a>
    <?php endforeach; ?>
</section>

<nav class="view-tabs" role="tablist" aria-label="Filter request queue">
    <?php foreach (array_merge(['all'], request_statuses()) as $status): ?>
        <a class="view-tab <?= $statusFilter === $status ? 'active' : '' ?>"
           href="<?= url('/admin') ?><?= $queueQuery($status) ?>"
           role="tab" aria-selected="<?= $statusFilter === $status ? 'true' : 'false' ?>">
            <?= $status === 'all' ? 'All' : e(ucfirst($status)) ?>
        </a>
    <?php endforeach; ?>
</nav>

<section class="card queue-tools-card" aria-label="Queue tools">
    <form class="queue-tools" method="get" action="<?= url('/admin') ?>">
        <input type="search" name="q" value="<?= e($search) ?>"
               placeholder="Search reference, document, requester…" aria-label="Search requests">
        <?php if ($statusFilter !== 'all'): ?>
            <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
        <?php endif; ?>
        <label class="sr-only" for="queue-sort">Sort requests</label>
        <select id="queue-sort" name="sort">
            <option value="date-desc" <?= $sort === 'date-desc' ? 'selected' : '' ?>>Newest first</option>
            <option value="date-asc" <?= $sort === 'date-asc' ? 'selected' : '' ?>>Oldest first</option>
            <option value="document-asc" <?= $sort === 'document-asc' ? 'selected' : '' ?>>Document A–Z</option>
            <option value="status-asc" <?= $sort === 'status-asc' ? 'selected' : '' ?>>Status</option>
        </select>
        <button type="submit" class="btn-ghost btn-sm">Apply</button>
        <?php if ($hasFilters): ?>
            <a class="action-link" href="<?= url('/admin') ?>">Clear</a>
        <?php endif; ?>
    </form>
    <p class="queue-count">Showing <?= count($requests) ?> of <?= (int) $totalRequests ?> requests</p>
</section>

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
                <span role="columnheader">Requested By</span>
                <span role="columnheader">Date</span>
                <span role="columnheader">Status</span>
                <span role="columnheader">Actions</span>
            </div>

            <?php foreach ($requests as $request): ?>
                <?php $status = (string) ($request['status'] ?? 'pending'); ?>
                <div class="request-row" role="row"
                     data-request-id="<?= e((string) ($request['id'] ?? '')) ?>" tabindex="-1">
                    <span role="cell" data-label="Document">
                        <span class="request-document"><?= e((string) ($request['document'] ?? '')) ?></span>
                        <span class="request-category"><?= e((string) ($request['category'] ?? '')) ?></span>
                    </span>
                    <span role="cell" data-label="Requested By">
                        <span class="request-document"><?= e((string) ($request['requester_name'] ?? '')) ?></span>
                        <span class="request-category"><?= e((string) ($request['requester_number'] ?? '')) ?></span>
                    </span>
                    <span role="cell" data-label="Date"><?= e((string) ($request['date'] ?? '')) ?></span>
                    <span role="cell" data-label="Status">
                        <span class="status status-<?= e($status) ?>"><?= e(ucfirst($status)) ?></span>
                    </span>
                    <span role="cell" data-label="Actions">
                        <span class="action-links">
                            <a class="action-link"
                               href="<?= url('/request-details') ?>?id=<?= e((string) ($request['id'] ?? '')) ?>&amp;from=admin">View</a>
                            <?php require __DIR__ . '/../components/decision-actions.php'; ?>
                        </span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

