<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;

require __DIR__ . '/app/config/config.php';
require __DIR__ . '/app/helpers.php';

$router = new Router();

// The login screen is the default display at /.
$router->get('/', [AuthController::class, 'showLogin']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/logout', [AuthController::class, 'logout']);

// Placeholder destination after authentication is implemented.
$router->get('/dashboard', [HomeController::class, 'index']);

$router->dispatch();
