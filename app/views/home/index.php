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
        <p class="dashboard-brand-name">CVSU - CEIT</p>
        <p class="dashboard-greeting">Welcome, <strong>Keith Montenegro</strong></p>
    </div>
</section>

<section class="stat-grid" aria-label="Request summary">
    <article class="card stat-card">
        <p class="stat-value">03</p>
        <h2 class="stat-label">Pending Requests</h2>
    </article>

    <article class="card stat-card">
        <p class="stat-value">12</p>
        <h2 class="stat-label">Approved</h2>
    </article>

    <article class="card stat-card">
        <p class="stat-value">05</p>
        <h2 class="stat-label">Available</h2>
    </article>
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

            <div class="request-row" role="row">
                <span role="cell">Thesis Proposal</span>
                <span role="cell">Nov 18, 2026</span>
                <span role="cell"><span class="status status-pending">Pending</span></span>
            </div>

            <div class="request-row" role="row">
                <span role="cell">Event Proposal</span>
                <span role="cell">Nov 17, 2026</span>
                <span role="cell"><span class="status status-approved">Approved</span></span>
            </div>
        </div>
    </section>
</div>
