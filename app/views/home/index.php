<section class="dashboard-header">
    <div>
        <p class="dashboard-eyebrow">CVSU - CEIT</p>
        <h1 class="dashboard-title">Student Dashboard</h1>
    </div>
    <a class="btn" href="<?= url('/logout') ?>">Logout</a>
</section>

<section class="dashboard-welcome">
    <img src="<?= url('assets/images/cvsu_logo.png') ?>" alt="CVSU CEIT logo" class="dashboard-logo">
    <div>
        <p class="dashboard-greeting">Welcome, <strong><?= e($user['name']) ?></strong></p>
        <p class="dashboard-meta">
            <?= e($user['student_number']) ?> &middot; <?= e($user['course']) ?>
        </p>
        <p class="dashboard-meta">
            <?= e($user['organization']) ?> &middot; <?= e($user['organization_role']) ?>
        </p>
        <p class="dashboard-meta dashboard-capstone">
            Capstone: <?= e($user['capstone_title']) ?>
        </p>
    </div>
</section>

<section class="stat-grid" aria-label="Request summary">
    <?php foreach ($stats as $stat): ?>
        <article class="card stat-card">
            <p class="stat-value"><?= e($stat['value']) ?></p>
            <h2 class="stat-label"><?= e($stat['label']) ?></h2>
        </article>
    <?php endforeach; ?>
</section>

<div class="dashboard-grid">
    <section class="card dashboard-card">
        <h2 class="card-title">Quick Actions</h2>
        <a class="btn" href="<?= url('/dashboard') ?>">Request a Document</a>
    </section>

    <section class="card dashboard-card">
        <div class="card-heading">
            <h2 class="card-title">Recent Requests</h2>
            <a class="card-link" href="<?= url('/dashboard') ?>">View All</a>
        </div>

        <div class="request-table" role="table" aria-label="Recent document requests">
            <div class="request-row request-head" role="row">
                <span role="columnheader">Document</span>
                <span role="columnheader">Date Requested</span>
                <span role="columnheader">Status</span>
            </div>

            <?php foreach ($recentRequests as $request): ?>
                <div class="request-row" role="row">
                    <span role="cell">
                        <span class="request-document"><?= e($request['document']) ?></span>
                        <span class="request-category"><?= e($request['category']) ?></span>
                    </span>
                    <span role="cell"><?= e($request['date']) ?></span>
                    <span role="cell">
                        <span class="status status-<?= e($request['status']) ?>">
                            <?= e(ucfirst($request['status'])) ?>
                        </span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
