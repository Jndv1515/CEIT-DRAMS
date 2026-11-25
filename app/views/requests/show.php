<section class="page-header">
    <h1 class="page-title">Request Details</h1>
    <a class="btn-secondary" href="<?= url($backUrl ?? '/requests') ?>"><?= e($backLabel ?? 'Back to Requests') ?></a>
</section>

<?php if (!empty($fromAdmin)): ?>
    <section class="card decision-bar" aria-label="Staff decision">
        <div class="decision-bar-meta">
            <span class="page-eyebrow">Staff review</span>
            <strong><?= e($request['document']) ?></strong>
            <span class="status status-<?= e((string) ($request['status'] ?? 'pending')) ?>">
                <?= e(ucfirst((string) ($request['status'] ?? 'pending'))) ?>
            </span>
        </div>
        <span class="action-links">
            <?php require __DIR__ . '/../components/decision-actions.php'; ?>
        </span>
    </section>
<?php endif; ?>

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
                <?php if (!empty($request['requester_name'])): ?>
                    <span style="margin-left:.5rem"><?= e($request['requester_name']) ?> &middot; <?= e($request['requester_number'] ?? '') ?></span>
                <?php endif; ?>
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
                       aria-label="Download <?= e($request['attachment_name']) ?>">
                        <?= icon('paperclip') ?> <?= e($request['attachment_name']) ?>
                    </a>
                </dd>
            </div>
        <?php endif; ?>
    </dl>
</section>
