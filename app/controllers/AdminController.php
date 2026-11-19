<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class AdminController extends Controller
{
    public function index(): void
    {
        $requests = queue_requests();
        $filter = (string) ($_GET['status'] ?? 'all');
        $allowed = array_merge(['all'], request_statuses());
        $filter = in_array($filter, $allowed, true) ? $filter : 'all';

        $counts = array_fill_keys(request_statuses(), 0);
        foreach ($requests as $request) {
            $status = (string) ($request['status'] ?? 'pending');
            if (isset($counts[$status])) {
                $counts[$status]++;
            }
        }

        $filtered = $filter === 'all'
            ? $requests
            : array_values(array_filter(
                $requests,
                static fn (array $request): bool => ($request['status'] ?? '') === $filter
            ));

        $this->view('admin/index', [
            'title' => 'Admin Dashboard',
            'activeNav' => $filter === 'pending' ? 'queue' : 'admin',
            'statusFilter' => $filter,
            'requests' => $filtered,
            'counts' => $counts,
        ], 'layouts/admin');
    }

    public function action(): void
    {
        $id = trim((string) ($_POST['id'] ?? ''));
        $status = trim((string) ($_POST['status'] ?? ''));
        $filter = trim((string) ($_POST['filter'] ?? 'all'));
        $reason = trim((string) ($_POST['reason'] ?? ''));

        $allowed = array_merge(['all'], request_statuses());
        $query = in_array($filter, $allowed, true) && $filter !== 'all'
            ? '?status=' . rawurlencode($filter)
            : '';

        if (!in_array($status, request_statuses(), true) || find_request($id) === null) {
            set_flash('That request could not be updated.', 'error');
            $this->redirect('/admin' . $query);
        }

        if ($status === 'rejected' && $reason === '') {
            set_flash('A reason is required when rejecting a request.', 'error');
            $this->redirect('/admin' . $query);
        }

        update_request_status($id, $status);

        if ($reason !== '') {
            if (!isset($_SESSION['request_decisions']) || !is_array($_SESSION['request_decisions'])) {
                $_SESSION['request_decisions'] = [];
            }

            $_SESSION['request_decisions'][$id] = [
                'status' => $status,
                'reason' => $reason,
                'at' => date('c'),
            ];
        }

        set_flash('Request ' . $id . ' marked as ' . $status . '.', 'success');
        $this->redirect('/admin' . $query);
    }
}
