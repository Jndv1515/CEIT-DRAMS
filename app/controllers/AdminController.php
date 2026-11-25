<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class AdminController extends Controller
{
    public function index(): void
    {
        admin_guard();
        $requests = queue_requests();
        $filter = (string) ($_GET['status'] ?? 'all');
        $allowed = array_merge(['all'], request_statuses());
        $filter = in_array($filter, $allowed, true) ? $filter : 'all';
        $search = trim((string) ($_GET['q'] ?? ''));
        $sort = (string) ($_GET['sort'] ?? 'date-desc');
        $sorts = ['date-desc', 'date-asc', 'document-asc', 'status-asc'];
        $sort = in_array($sort, $sorts, true) ? $sort : 'date-desc';

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

        if ($search !== '') {
            $filtered = array_values(array_filter(
                $filtered,
                static function (array $request) use ($search): bool {
                    foreach (['id', 'document', 'category', 'type'] as $field) {
                        if (stripos((string) ($request[$field] ?? ''), $search) !== false) {
                            return true;
                        }
                    }

                    return false;
                }
            ));
        }

        $statusOrder = request_statuses();
        usort($filtered, static function (array $a, array $b) use ($sort, $statusOrder): int {
            if ($sort === 'document-asc') {
                return strcasecmp((string) ($a['document'] ?? ''), (string) ($b['document'] ?? ''));
            }

            if ($sort === 'status-asc') {
                $aIndex = array_search((string) ($a['status'] ?? 'pending'), $statusOrder, true);
                $bIndex = array_search((string) ($b['status'] ?? 'pending'), $statusOrder, true);

                return (int) $aIndex <=> (int) $bIndex;
            }

            $aTime = (int) (strtotime((string) ($a['date'] ?? '')) ?: 0);
            $bTime = (int) (strtotime((string) ($b['date'] ?? '')) ?: 0);

            return $sort === 'date-asc' ? $aTime <=> $bTime : $bTime <=> $aTime;
        });

        $this->view('admin/index', [
            'title' => 'Admin Dashboard',
            'activeNav' => $filter === 'pending' ? 'queue' : 'admin',
            'statusFilter' => $filter,
            'search' => $search,
            'sort' => $sort,
            'totalRequests' => count($requests),
            'requests' => $filtered,
            'counts' => $counts,
        ], 'layouts/admin');
    }

    public function action(): void
    {
        admin_guard();
        $id = trim((string) ($_POST['id'] ?? ''));
        $status = trim((string) ($_POST['status'] ?? ''));
        $filter = trim((string) ($_POST['filter'] ?? 'all'));
        $reason = trim((string) ($_POST['reason'] ?? ''));
        $search = trim((string) ($_POST['q'] ?? ''));
        $sort = trim((string) ($_POST['sort'] ?? ''));

        $queryParts = [];
        if (in_array($filter, request_statuses(), true)) {
            $queryParts['status'] = $filter;
        }
        if ($search !== '') {
            $queryParts['q'] = $search;
        }
        if (in_array($sort, ['date-asc', 'document-asc', 'status-asc'], true)) {
            $queryParts['sort'] = $sort;
        }
        $query = $queryParts === [] ? '' : '?' . http_build_query($queryParts);

        if (!in_array($status, request_statuses(), true) || find_request($id) === null) {
            set_flash('That request could not be updated.', 'error');
            $this->redirect('/admin' . $query);
        }

        if ($status === 'rejected' && $reason === '') {
            set_flash('A reason is required when rejecting a request.', 'error');
            $this->redirect('/admin' . $query);
        }

        update_request_status($id, $status, $reason);

        $_SESSION['focus_request_id'] = $id;
        set_flash('Request ' . $id . ' marked as ' . $status . '.', 'success');
        $this->redirect('/admin' . $query);
    }
}
