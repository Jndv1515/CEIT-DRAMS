<section class="page-header">
    <h1 class="page-title"><?= e($title ?? 'My Requests') ?></h1>
</section>

<nav class="view-tabs" role="tablist" aria-label="Request views">
    <a class="view-tab <?= $activeView === 'requests' ? 'active' : '' ?>"
       href="<?= url('/requests') ?>"
       role="tab" aria-selected="<?= $activeView === 'requests' ? 'true' : 'false' ?>">My Requests</a>
    <a class="view-tab <?= $activeView === 'documents' ? 'active' : '' ?>"
       href="<?= url('/documents') ?>"
       role="tab" aria-selected="<?= $activeView === 'documents' ? 'true' : 'false' ?>">Available Documents</a>
</nav>

<?php if ($activeView === 'documents'): ?>
    <section class="card catalog-tools-card" aria-label="Document filters">
        <form class="catalog-tools" method="get" action="<?= url('/documents') ?>">
            <input type="search" name="q" value="<?= e($search) ?>"
                   placeholder="Search documents…" aria-label="Search documents">
            <label class="sr-only" for="catalog-type">Requester type</label>
            <select id="catalog-type" name="type">
                <option value="all" <?= $typeFilter === 'all' ? 'selected' : '' ?>>All requesters</option>
                <option value="student" <?= $typeFilter === 'student' ? 'selected' : '' ?>>Students</option>
                <option value="organization" <?= $typeFilter === 'organization' ? 'selected' : '' ?>>Organizations</option>
            </select>
            <label class="sr-only" for="catalog-access">Availability</label>
            <select id="catalog-access" name="access">
                <option value="all" <?= $accessFilter === 'all' ? 'selected' : '' ?>>Any availability</option>
                <option value="available" <?= $accessFilter === 'available' ? 'selected' : '' ?>>Available to download</option>
                <option value="pending" <?= $accessFilter === 'pending' ? 'selected' : '' ?>>Request pending</option>
                <option value="rejected" <?= $accessFilter === 'rejected' ? 'selected' : '' ?>>Request again</option>
                <option value="required" <?= $accessFilter === 'required' ? 'selected' : '' ?>>Request required</option>
            </select>
            <label class="sr-only" for="catalog-sort">Sort documents</label>
            <select id="catalog-sort" name="sort">
                <option value="name-asc" <?= $sort === 'name-asc' ? 'selected' : '' ?>>Name A–Z</option>
                <option value="category-asc" <?= $sort === 'category-asc' ? 'selected' : '' ?>>Category A–Z</option>
            </select>
            <button type="submit" class="btn-ghost btn-sm">Apply</button>
            <?php if ($search !== '' || $typeFilter !== 'all' || $accessFilter !== 'all' || $sort !== 'name-asc'): ?>
                <a class="action-link" href="<?= url('/documents') ?>">Clear</a>
            <?php endif; ?>
        </form>
        <p class="queue-count">Showing <?= count($documents) ?> of <?= (int) $totalDocuments ?> documents</p>
    </section>

    <section role="tabpanel" aria-label="Available documents">
        <?php if ($documents === []): ?>
            <div class="card">
                <?= empty_state([
                    'title' => 'No matching documents',
                    'message' => 'Try a different search term or clear the filters to see the full catalog.',
                    'actionLabel' => 'Clear filters',
                    'actionUrl' => url('/documents'),
                    'icon' => 'document',
                ]) ?>
            </div>
        <?php else: ?>
            <div class="document-grid">
                <?php foreach ($documents as $document): ?>
                    <article class="card document-card">
                        <div class="document-card-top">
                            <span class="document-card-icon" aria-hidden="true"><?= icon('document') ?></span>
                            <span class="badge badge-<?= e($document['stateClass']) ?>"><?= e($document['stateLabel']) ?></span>
                        </div>
                        <h2 class="document-card-title"><?= e($document['name']) ?></h2>
                        <p class="document-card-category"><?= e($document['category']) ?></p>
                        <p class="document-card-description"><?= e($document['description']) ?></p>
                        <dl class="document-meta">
                            <div>
                                <dt>Format</dt>
                                <dd><?= e($document['format']) ?></dd>
                            </div>
                            <div>
                                <dt>Size</dt>
                                <dd><?= e($document['size']) ?></dd>
                            </div>
                            <div>
                                <dt>Eligibility</dt>
                                <dd><?= e($document['eligibility']) ?></dd>
                            </div>
                        </dl>
                        <div class="document-card-footer">
                            <span class="requester-badge requester-badge-<?= e($document['type']) ?>">
                                <?= e(ucfirst($document['type'])) ?>
                            </span>
                            <span class="action-links">
                                <?php if ($document['state'] === 'available' && $document['requestId'] !== ''): ?>
                                    <a class="action-link"
                                       href="<?= url('/request-details') ?>?id=<?= e($document['requestId']) ?>">View request</a>
                                    <?php if ($document['hasAttachment']): ?>
                                        <?php if ($document['previewable']): ?>
                                            <button type="button" class="action-link link-button"
                                                    data-preview="true"
                                                    data-title="<?= e($document['name']) ?>"
                                                    data-category="<?= e($document['category']) ?>"
                                                    data-description="<?= e($document['description']) ?>"
                                                    data-format="<?= e($document['format']) ?>"
                                                    data-size="<?= e($document['size']) ?>"
                                                    data-src="<?= e(url('/attachment') . '?request=' . rawurlencode($document['requestId']) . '&inline=1') ?>"
                                                    data-download="<?= e(url('/attachment') . '?request=' . rawurlencode($document['requestId'])) ?>">Preview</button>
                                        <?php endif; ?>
                                        <a class="action-link attachment-action"
                                           href="<?= url('/attachment') ?>?request=<?= e($document['requestId']) ?>"
                                           aria-label="Download <?= e($document['attachmentName']) ?>">
                                            <?= icon('paperclip') ?> Download
                                        </a>
                                    <?php endif; ?>
                                <?php elseif ($document['state'] === 'pending' && $document['requestId'] !== ''): ?>
                                    <a class="action-link"
                                       href="<?= url('/request-details') ?>?id=<?= e($document['requestId']) ?>">Track request</a>
                                <?php else: ?>
                                    <a class="action-link"
                                       href="<?= url('/request') ?>?document=<?= rawurlencode($document['name']) ?>"
                                       aria-label="Request <?= e($document['name']) ?>">
                                        <?= $document['state'] === 'rejected' ? 'Request again' : 'Request' ?>
                                    </a>
                                <?php endif; ?>
                            </span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <dialog class="preview-dialog" id="document-preview" aria-labelledby="preview-title">
        <div class="preview-head">
            <div>
                <p class="page-eyebrow">Document preview</p>
                <h2 id="preview-title">Document</h2>
                <p class="preview-category" id="preview-category"></p>
            </div>
            <button type="button" class="btn-ghost btn-sm" id="preview-close" aria-label="Close preview">
                <?= icon('x') ?> Close
            </button>
        </div>
        <div class="preview-body" id="preview-body"></div>
        <div class="preview-foot">
            <p class="preview-meta" id="preview-meta"></p>
            <a class="btn-primary btn-sm" id="preview-download" href="#">Download</a>
        </div>
    </dialog>
<?php else: ?>
    <section class="card" role="tabpanel" aria-label="My requests">
        <?php if ($requests === []): ?>
            <?= empty_state([
                'title' => 'No requests yet',
                'message' => 'Submit a document request and track its status here.',
                'actionLabel' => 'Request a Document',
                'actionUrl' => url('/request'),
                'icon' => 'document',
            ]) ?>
        <?php else: ?>
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
                                    <a class="action-link attachment-action"
                                       href="<?= url('/attachment') ?>?request=<?= e($request['id']) ?>"
                                       aria-label="Download supporting file for <?= e($request['document']) ?>">
                                        <?= icon('paperclip') ?> Supporting file
                                    </a>
                                <?php endif; ?>
                            </span>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
