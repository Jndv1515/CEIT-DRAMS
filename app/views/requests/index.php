<section class="page-header">
    <div>
        <p class="page-eyebrow">Document Forward Request Module</p>
        <h1 class="page-title">My Requests</h1>
        <p class="page-subtitle">All submitted requests and sample records in one queue.</p>
    </div>
    <a class="btn" href="<?= url('/request') ?>">Request a Document</a>
</section>

<section class="card">
    <div class="request-table" role="table" aria-label="My document requests">
        <div class="request-row request-head" role="row">
            <span role="columnheader">Document</span>
            <span role="columnheader">Date Requested</span>
            <span role="columnheader">Status</span>
        </div>

        <?php foreach ($requests as $request): ?>
            <div class="request-row" role="row">
                <span role="cell" data-label="Document">
                    <span class="request-document"><?= e($request['document']) ?></span>
                    <span class="request-category">
                        <?= e($request['category']) ?>
                        <?php if (isset($request['id'])): ?>
                            &middot; <?= e($request['id']) ?>
                        <?php endif; ?>
                    </span>
                    <span class="requester-badge requester-badge-<?= e($request['type']) ?>">
                        <?= e(ucfirst($request['type'])) ?>
                    </span>
                </span>
                <span role="cell" data-label="Date Requested"><?= e($request['date']) ?></span>
                <span role="cell" data-label="Status">
                    <span class="status status-<?= e($request['status']) ?>">
                        <?= e(ucfirst($request['status'])) ?>
                    </span>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</section>
