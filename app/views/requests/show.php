<section class="page-header">
    <h1 class="page-title">Request Details</h1>
    <a class="btn-secondary" href="<?= url('/requests') ?>">Back to Requests</a>
</section>

<section class="card request-card">
    <dl class="detail-list">
        <div>
            <dt>Document</dt>
            <dd><?= e($request['document']) ?></dd>
        </div>
        <div>
            <dt>Reference</dt>
            <dd><?= e($request['id']) ?></dd>
        </div>
        <div>
            <dt>Date Requested</dt>
            <dd><?= e($request['date']) ?></dd>
        </div>
        <div>
            <dt>Status</dt>
            <dd>
                <span class="status status-<?= e($request['status']) ?>">
                    <?= e(ucfirst($request['status'])) ?>
                </span>
            </dd>
        </div>
        <div>
            <dt>Category</dt>
            <dd><?= e($request['category']) ?></dd>
        </div>
        <div>
            <dt>Requester</dt>
            <dd>
                <span class="requester-badge requester-badge-<?= e($request['type']) ?>">
                    <?= e(ucfirst($request['type'])) ?>
                </span>
            </dd>
        </div>
        <?php if (!empty($request['purpose'])): ?>
            <div>
                <dt>Purpose</dt>
                <dd><?= nl2br(e($request['purpose'])) ?></dd>
            </div>
        <?php endif; ?>
        <?php if (!empty($request['notes'])): ?>
            <div>
                <dt>Additional Notes</dt>
                <dd><?= nl2br(e($request['notes'])) ?></dd>
            </div>
        <?php endif; ?>
        <?php if (!empty($request['attachment_name'])): ?>
            <div>
                <dt>Attachment</dt>
                <dd>
                    <a class="attachment-link"
                       href="<?= url('/attachment') ?>?request=<?= e($request['id']) ?>"
                       target="_blank" rel="noopener">
                        <?= icon('document') ?> <?= e($request['attachment_name']) ?>
                    </a>
                </dd>
            </div>
        <?php endif; ?>
    </dl>
</section>
