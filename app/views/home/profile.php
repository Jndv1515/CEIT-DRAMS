<?php
$profileUser = $user ?? [];
$profileName = (string) ($profileUser['name'] ?? '');

$initials = '';
foreach (preg_split('/\s+/', trim($profileName)) ?: [] as $part) {
    $initials .= strtoupper(substr($part, 0, 1));
}
$initials = substr($initials, 0, 2) ?: 'CD';

$fields = array_values(array_filter([
    ['label' => 'Full name', 'value' => $profileUser['name'] ?? ''],
    ['label' => 'Student number', 'value' => $profileUser['student_number'] ?? ''],
    ['label' => 'Course', 'value' => $profileUser['course'] ?? ''],
    ['label' => 'Organization', 'value' => $profileUser['organization'] ?? ''],
    ['label' => 'Organization role', 'value' => $profileUser['organization_role'] ?? ''],
], static fn (array $field): bool => trim((string) $field['value']) !== ''));

$counts = array_fill_keys(request_statuses(), 0);
foreach ($requests as $request) {
    $status = (string) ($request['status'] ?? 'pending');
    if (isset($counts[$status])) {
        $counts[$status]++;
    }
}
?>
<section class="page-header">
    <div>
        <p class="page-eyebrow">Account</p>
        <h1 class="page-title">Profile</h1>
        <p class="page-subtitle">Your CEIT DRAMS account details and request activity.</p>
    </div>
</section>

<section class="card profile-hero">
    <span class="profile-avatar" aria-hidden="true"><?= e($initials) ?></span>
    <div class="profile-identity">
        <p class="page-eyebrow"><?= e(ucfirst((string) ($profileUser['role'] ?? 'student'))) ?> account</p>
        <h2 class="profile-name"><?= e($profileName) ?></h2>
        <?php
        $subline = array_filter([
            (string) ($profileUser['student_number'] ?? ''),
            (string) ($profileUser['course'] ?? ''),
        ], static fn (string $value): bool => $value !== '');
        ?>
        <p class="profile-subline"><?= e(implode(' · ', $subline)) ?></p>
    </div>
    <div class="profile-hero-actions">
        <a class="btn-ghost btn-sm" href="<?= url('/requests') ?>">My requests</a>
    </div>
</section>

<section class="card profile-details-card">
    <div class="profile-section-head">
        <h2>Personal information</h2>

    </div>

    <?php if ($fields === []): ?>
        <?= empty_state([
            'title' => 'No profile details yet',
            'message' => 'Account details will appear here once they are available.',
            'icon' => 'info',
        ]) ?>
    <?php else: ?>
        <dl class="detail-list profile-details">
            <?php foreach ($fields as $field): ?>
                <div>
                    <dt><?= e($field['label']) ?></dt>
                    <dd><?= e((string) $field['value']) ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>
</section>

<section class="card profile-details-card">
    <div class="profile-section-head">
        <h2>Request activity</h2>
        <a class="action-link" href="<?= url('/requests') ?>">View all</a>
    </div>

    <div class="profile-stats">
        <a class="profile-stat" href="<?= url('/requests') ?>">
            <strong><?= str_pad((string) count($requests), 2, '0', STR_PAD_LEFT) ?></strong>
            <span>Total requests</span>
        </a>
        <a class="profile-stat" href="<?= url('/requests') ?>">
            <strong><?= str_pad((string) $counts['pending'], 2, '0', STR_PAD_LEFT) ?></strong>
            <span>Pending</span>
        </a>
        <a class="profile-stat" href="<?= url('/requests') ?>">
            <strong><?= str_pad((string) $counts['approved'], 2, '0', STR_PAD_LEFT) ?></strong>
            <span>Approved</span>
        </a>
        <a class="profile-stat" href="<?= url('/documents') ?>">
            <strong><?= str_pad((string) ($documentCount ?? 0), 2, '0', STR_PAD_LEFT) ?></strong>
            <span>Available documents</span>
        </a>
    </div>

    <p class="profile-note">
        <?= icon('info') ?>
        <span>Profile editing will be enabled once authentication and the database layer are connected. Details shown here come from demo data.</span>
    </p>
</section>
