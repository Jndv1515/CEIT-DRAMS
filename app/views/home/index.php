<section class="dashboard-header">
    <div>
        <h1 class="dashboard-title">Document Requests Dashboard</h1>
    </div>

    <div class="context-switcher" role="group" aria-label="Dashboard context">
        <a class="context-btn <?= $context === 'personal' ? 'active' : '' ?>"
           href="<?= url('/dashboard') ?>?context=personal&amp;filter=all"
           <?= $context === 'personal' ? 'aria-pressed="true"' : '' ?>>Personal</a>
        <a class="context-btn <?= $context === 'organization' ? 'active' : '' ?>"
           href="<?= url('/dashboard') ?>?context=organization&amp;filter=organization"
           <?= $context === 'organization' ? 'aria-pressed="true"' : '' ?>>CEIT Student Council</a>
    </div>
</section>

<section class="dashboard-welcome" aria-labelledby="welcome-heading">
    <img src="<?= url('assets/images/cvsu_logo.png') ?>" alt="" class="dashboard-logo">
    <div>
        <h2 id="welcome-heading" class="dashboard-greeting">
            Welcome,
            <strong><?= e($context === 'organization' ? $user['organization'] : $user['name']) ?></strong>
        </h2>

        <?php if ($context === 'organization'): ?>
            <p class="dashboard-meta">
                <?= e($user['organization_role']) ?> &middot; <?= e($user['course']) ?>
            </p>
            <p class="dashboard-meta">Representative: <?= e($user['name']) ?></p>
        <?php else: ?>
            <p class="dashboard-meta">
                <?= e($user['student_number']) ?> &middot; <?= e($user['course']) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<section class="stat-grid" aria-label="Request summary">
    <?php foreach ($stats as $stat): ?>
        <a class="card stat-card" href="<?= url('/requests') ?>">
            <span class="stat-icon" aria-hidden="true"><?= icon($stat['icon']) ?></span>
            <span class="stat-value"><?= e($stat['value']) ?></span>
            <span class="stat-label"><?= e($stat['label']) ?></span>
            <span class="stat-trend"><?= e($stat['trend']) ?></span>
        </a>
    <?php endforeach; ?>
</section>

<div class="dashboard-grid">
    <section class="card dashboard-card">
        <h2 class="card-title">Quick Actions</h2>
        <a class="btn" href="<?= url('/request') ?>">Request a Document</a>
    </section>

    <section class="card dashboard-card">
        <h2 class="card-title">Recent Requests</h2>

        <div class="filter-chips" role="group" aria-label="Filter requests">
            <a class="chip <?= $filter === 'all' ? 'active' : '' ?>"
               href="<?= url('/dashboard') ?>?context=<?= e($context) ?>&amp;filter=all">All</a>
            <a class="chip <?= $filter === 'student' ? 'active' : '' ?>"
               href="<?= url('/dashboard') ?>?context=<?= e($context) ?>&amp;filter=student">Student</a>
            <a class="chip <?= $filter === 'organization' ? 'active' : '' ?>"
               href="<?= url('/dashboard') ?>?context=<?= e($context) ?>&amp;filter=organization">Organization</a>
        </div>

        <div class="request-table" role="table" aria-label="Recent document requests">
            <div class="request-row request-head" role="row">
                <span role="columnheader">Document</span>
                <span role="columnheader">Date Requested</span>
                <span role="columnheader">Status</span>
            </div>

            <?php foreach ($recentRequests as $request): ?>
                <div class="request-row" role="row">
                    <span role="cell" data-label="Document">
                        <span class="request-document"><?= e($request['document']) ?></span>
                        <span class="request-category"><?= e($request['category']) ?></span>
                        <span class="requester-badge requester-badge-<?= e($request['type']) ?>">
                            <?= e(ucfirst($request['type'])) ?>
                        </span>
                        <?php if (!empty($request['attachment_name'])): ?>
                            <a class="attachment-link"
                               href="<?= url('/attachment') ?>?request=<?= e($request['id']) ?>"
                               aria-label="Download <?= e($request['attachment_name']) ?>">
                                <?= icon('paperclip') ?> <?= e($request['attachment_name']) ?>
                            </a>
                        <?php endif; ?>
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
</div>
