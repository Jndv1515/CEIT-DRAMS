<?php
declare(strict_types=1);

namespace App\Core;

/**
 * A tiny router: maps GET/POST paths to controller methods.
 */
class Router
{
    /** @var array<string, array<string, array{0: string, 1: string}>> */
    private $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[$method]['/' . trim($path, '/')] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = is_string($uri) ? $uri : '/';

        // Strip the subdirectory prefix when the app is not the document root.
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        if ($base !== '' && $base !== '/' && strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }

        $uri = '/' . trim($uri, '/');

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        list($controllerClass, $action) = $this->routes[$method][$uri];

        if (!class_exists($controllerClass) || !method_exists($controllerClass, $action)) {
            http_response_code(500);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        $controller = new $controllerClass();
        $controller->$action();
    }
}
