<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $mockData = require APP_PATH . '/data/mock_dashboard.php';

        $this->view('home/index', array_merge([
            'title' => 'Dashboard',
            'showSiteChrome' => false,
        ], $mockData));
    }
}
