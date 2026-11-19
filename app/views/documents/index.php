<section class="page-header">
    <div>
        <p class="page-eyebrow">Document Forward Request Module</p>
        <h1 class="page-title">My Documents</h1>
        <p class="page-subtitle">Document types available to request.</p>
    </div>
    <a class="btn" href="<?= url('/request') ?>">Request a Document</a>
</section>

<section class="card">
    <div class="request-table" role="table" aria-label="Available documents">
        <div class="request-row request-head" role="row">
            <span role="columnheader">Document</span>
            <span role="columnheader">Category</span>
            <span role="columnheader">Requester</span>
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
            </div>
        <?php endforeach; ?>
    </div>
</section>
