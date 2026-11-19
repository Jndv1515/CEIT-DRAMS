<?php
declare(strict_types=1);

/**
 * Application-wide configuration and bootstrap.
 */

define('APP_NAME', 'CEIT - Document Request and Approval Management System');
define('APP_PATH', dirname(__DIR__));
define('BASE_PATH', dirname(APP_PATH));
define('VIEWS_PATH', APP_PATH . '/views');
define('APP_DEBUG', true);

date_default_timezone_set('UTC');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Minimal PSR-4-style autoloader for App\ classes.
 */
spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'App\\Core\\' => APP_PATH . '/core/',
        'App\\Controllers\\' => APP_PATH . '/controllers/',
        'App\\Models\\' => APP_PATH . '/models/',
    ];

    foreach ($prefixes as $prefix => $directory) {
        if (strpos($class, $prefix) !== 0) {
            continue;
        }

        $relative = substr($class, strlen($prefix));
        $file = $directory . str_replace('\\', '/', $relative) . '.php';

        if (is_file($file)) {
            require $file;
        }

        return;
    }
});
