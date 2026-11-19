<section class="page-header">
    <h1 class="page-title">My Requests</h1>
</section>

<nav class="view-tabs" role="tablist" aria-label="Request views">
    <a class="view-tab <?= $activeView === 'requests' ? 'active' : '' ?>"
       href="<?= url('/requests') ?>"
       role="tab" aria-selected="<?= $activeView === 'requests' ? 'true' : 'false' ?>">My Requests</a>
    <a class="view-tab <?= $activeView === 'documents' ? 'active' : '' ?>"
       href="<?= url('/requests') ?>?view=documents"
       role="tab" aria-selected="<?= $activeView === 'documents' ? 'true' : 'false' ?>">Available Documents</a>
</nav>

<?php if ($activeView === 'documents'): ?>
    <section class="card" role="tabpanel" aria-label="Available documents">
        <div class="request-table request-table-actions" role="table" aria-label="Available document types">
            <div class="request-row request-head" role="row">
                <span role="columnheader">Document</span>
                <span role="columnheader">Category</span>
                <span role="columnheader">Requester</span>
                <span role="columnheader">Actions</span>
            </div>

            <?php foreach ($documents as $document): ?>
                <div class="request-row" role="row">
                    <span role="cell" data-label="Document">
                        <span class="request-document"><?= e($document['name']) ?></span>
                    </span>
                    <span role="cell" data-label="Category"><?= e($document['category']) ?></span>
                    <span role="cell" data-label="Requester">
                        <span class="requester-badge requester-badge-<?= e($document['type']) ?>">
                            <?= e(ucfirst($document['type'])) ?>
                        </span>
                    </span>
                    <span role="cell" data-label="Actions">
                        <span class="action-links">
                            <a class="action-link"
                               href="<?= url('/request') ?>?document=<?= rawurlencode($document['name']) ?>">Request</a>
                        </span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <section class="card" role="tabpanel" aria-label="My requests">
        <div class="request-table request-table-actions" role="table" aria-label="My document requests">
            <div class="request-row request-head" role="row">
                <span role="columnheader">Document</span>
                <span role="columnheader">Date Requested</span>
                <span role="columnheader">Status</span>
                <span role="columnheader">Actions</span>
            </div>

            <?php foreach ($requests as $request): ?>
                <div class="request-row" role="row">
                    <span role="cell" data-label="Document">
                        <span class="request-document"><?= e($request['document']) ?></span>
                    </span>
                    <span role="cell" data-label="Date Requested"><?= e($request['date']) ?></span>
                    <span role="cell" data-label="Status">
                        <span class="status status-<?= e($request['status']) ?>">
                            <?= e(ucfirst($request['status'])) ?>
                        </span>
                    </span>
                    <span role="cell" data-label="Actions">
                        <span class="action-links">
                            <?php if (!empty($request['id'])): ?>
                                <a class="action-link"
                                   href="<?= url('/request-details') ?>?id=<?= e($request['id']) ?>">View</a>
                            <?php endif; ?>
                            <?php if (!empty($request['attachment_name'])): ?>
                                <a class="action-link"
                                   href="<?= url('/attachment') ?>?request=<?= e($request['id']) ?>"
                                   target="_blank" rel="noopener">Download</a>
                            <?php endif; ?>
                        </span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
