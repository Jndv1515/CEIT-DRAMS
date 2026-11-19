<section class="page-header">
    <h1 class="page-title">My Requests</h1>
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
                    <span class="request-document">
                        <?= e($request['document']) ?>
                        <?php if (!empty($request['attachment_name'])): ?>
                            <a class="attachment-icon"
                               href="<?= url('/attachment') ?>?request=<?= e($request['id']) ?>"
                               target="_blank" rel="noopener"
                               title="<?= e($request['attachment_name']) ?>"
                               aria-label="Open <?= e($request['attachment_name']) ?>">
                                <?= icon('document') ?>
                            </a>
                        <?php endif; ?>
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
