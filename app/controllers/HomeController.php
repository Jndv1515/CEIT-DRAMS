<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        auth_guard();
        if ((auth_user()['role'] ?? '') === 'admin') {
            $this->redirect('/admin');
        }

        $user = auth_user();

        $statsStmt = db()->prepare(
            'SELECT
                SUM(status IN ("pending","processing")) AS pending,
                SUM(status = "approved")                AS approved
             FROM document_requests WHERE user_id = :uid'
        );
        $statsStmt->execute([':uid' => $user['id']]);
        $counts = $statsStmt->fetch();

        $stats = [
            ['label' => 'Pending Requests',    'value' => str_pad((string) ((int)($counts['pending'] ?? 0)), 2, '0', STR_PAD_LEFT),  'icon' => 'clock',    'trend' => ''],
            ['label' => 'Approved',            'value' => str_pad((string) ((int)($counts['approved'] ?? 0)), 2, '0', STR_PAD_LEFT), 'icon' => 'check',    'trend' => ''],
            ['label' => 'Available Documents', 'value' => str_pad((string) count(require APP_PATH . '/data/documents.php'), 2, '0', STR_PAD_LEFT), 'icon' => 'document', 'trend' => 'All request forms'],
        ];

        $filter = $_GET['filter'] ?? 'all';
        $filter = in_array($filter, ['all', 'student', 'organization'], true) ? $filter : 'all';

        $recentRequests = queue_requests();
        if ($filter !== 'all') {
            $recentRequests = array_values(array_filter(
                $recentRequests,
                static fn (array $r): bool => $r['type'] === $filter
            ));
        }

        $this->view('home/index', [
            'title'          => 'Dashboard',
            'activeNav'      => 'dashboard',
            'filter'         => $filter,
            'user'           => $user,
            'stats'          => $stats,
            'recentRequests' => $recentRequests,
        ], 'layouts/dashboard');
    }

    public function profile(): void
    {
        auth_guard();
        if ((auth_user()['role'] ?? '') === 'admin') {
            $this->redirect('/admin');
        }

        $user = auth_user();

        $this->view('home/profile', [
            'title'         => 'Profile',
            'activeNav'     => 'profile',
            'user'          => $user,
            'requests'      => queue_requests(),
            'documentCount' => count(require APP_PATH . '/data/documents.php'),
        ], 'layouts/dashboard');
    }
}
