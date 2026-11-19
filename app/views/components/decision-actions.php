<?php
/**
 * Shared staff decision controls for the queue and the request details page.
 * Expects $request; $statusFilter is optional.
 */
$decisionId = (string) ($request['id'] ?? '');
$decisionDocument = (string) ($request['document'] ?? '');
$decisionStatus = (string) ($request['status'] ?? 'pending');
$decisionFilter = (string) ($statusFilter ?? 'all');

$renderDecisionButton = static function (
    string $status,
    string $label,
    string $variant,
    string $iconName,
    bool $confirm = false
) use ($decisionId, $decisionDocument, $decisionFilter): void {
    $buttonClass = $variant === 'approve'
        ? 'btn-primary'
        : ($variant === 'reject' ? 'btn-danger' : 'btn-ghost');
    ?>
    <form class="inline-form" method="post" action="<?= url('/admin/action') ?>" data-decision-form>
        <input type="hidden" name="id" value="<?= e($decisionId) ?>">
        <input type="hidden" name="document" value="<?= e($decisionDocument) ?>">
        <input type="hidden" name="status" value="<?= e($status) ?>">
        <input type="hidden" name="filter" value="<?= e($decisionFilter) ?>">
        <input type="hidden" name="q" value="<?= e((string) ($search ?? '')) ?>">
        <input type="hidden" name="sort" value="<?= e((string) ($sort ?? '')) ?>">
        <input type="hidden" name="reason" value="" data-reason>
        <button type="submit"
                class="btn-sm <?= $buttonClass ?> btn-icon"
                data-action="<?= e($status) ?>"
                <?= $confirm ? 'data-confirm="true"' : '' ?>
                aria-label="<?= e($label . ' ' . $decisionDocument . ' (' . $decisionId . ')') ?>">
            <?= icon($iconName) ?>
            <span><?= e($label) ?></span>
        </button>
    </form>
    <?php
};

if ($decisionStatus === 'pending' || $decisionStatus === 'processing') {
    $renderDecisionButton('approved', 'Approve', 'approve', 'check');
    $renderDecisionButton('forwarded', 'Forward', 'forward', 'forward');
    $renderDecisionButton('rejected', 'Reject', 'reject', 'x', true);
} else {
    $renderDecisionButton('pending', 'Reset', 'reset', 'rotate', true);
}
