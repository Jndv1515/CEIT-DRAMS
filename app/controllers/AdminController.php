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

        if (!in_array($status, request_statuses(), true) || find_request($id) === null) {
            set_flash('That request could not be updated.');
            $this->redirect('/admin');
        }

        update_request_status($id, $status);
        set_flash('Request marked as ' . $status . '.');

        $allowed = array_merge(['all'], request_statuses());
        $query = in_array($filter, $allowed, true) && $filter !== 'all'
            ? '?status=' . rawurlencode($filter)
            : '';

        $this->redirect('/admin' . $query);
    }
}
