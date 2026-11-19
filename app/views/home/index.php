<div class="dashboard-shell">
    <aside class="dashboard-sidebar">
        <a class="sidebar-brand" href="<?= url('/dashboard') ?>">
            <img src="<?= url('assets/images/cvsu_logo.png') ?>" alt="" class="sidebar-logo">
            <span>CEIT <strong>DRAMS</strong></span>
        </a>

        <nav class="sidebar-nav" aria-label="Dashboard sections">
            <a class="active" href="<?= url('/dashboard') ?>" aria-current="page">Overview</a>
            <a href="<?= url('/dashboard') ?>">Requests</a>
            <a href="<?= url('/dashboard') ?>">Documents</a>
            <a href="<?= url('/dashboard') ?>">Organizations</a>
            <a href="<?= url('/dashboard') ?>">Profile</a>
        </nav>

        <a class="sidebar-logout" href="<?= url('/logout') ?>">Logout</a>
    </aside>

    <div class="dashboard-content">
        <section class="dashboard-header">
            <div>
                <p class="dashboard-eyebrow">CVSU - CEIT</p>
                <h1 class="dashboard-title">Student Dashboard</h1>
            </div>
        </section>

        <section class="dashboard-welcome">
            <img src="<?= url('assets/images/cvsu_logo.png') ?>" alt="CVSU CEIT logo" class="dashboard-logo">
            <div>
                <p class="dashboard-brand-name">CVSU - CEIT</p>
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
                <a class="card stat-card" href="<?= url('/dashboard') ?>">
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
                            <span role="cell" data-label="Document">
                                <span class="request-document"><?= e($request['document']) ?></span>
                                <span class="request-category"><?= e($request['category']) ?></span>
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
    </div>
</div>
