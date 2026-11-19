<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $mockData = require APP_PATH . '/data/mock_dashboard.php';

        $context = $_GET['context'] ?? 'personal';
        $filter = $_GET['filter'] ?? 'all';

        $context = in_array($context, ['personal', 'organization'], true) ? $context : 'personal';
        $filter = in_array($filter, ['all', 'student', 'organization'], true) ? $filter : 'all';

        if ($filter !== 'all') {
            $mockData['recentRequests'] = array_values(array_filter(
                $mockData['recentRequests'],
                static fn (array $request): bool => $request['type'] === $filter
            ));
        }

        $this->view('home/index', array_merge([
            'title' => 'Dashboard',
            'showSiteChrome' => false,
            'context' => $context,
            'filter' => $filter,
        ], $mockData));
    }
}
