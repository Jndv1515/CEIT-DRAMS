<?php
declare(strict_types=1);

/**
 * Returns a shared PDO instance connected to ceit_drams.
 * Credentials are read from environment variables (set in .env or server config).
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $host = (string) (getenv('DB_HOST') ?: '127.0.0.1');
        $port = (string) (getenv('DB_PORT') ?: '3306');
        $name = (string) (getenv('DB_NAME') ?: 'ceit_drams');
        $user = (string) (getenv('DB_USER') ?: 'root');
        $pass = (string) (getenv('DB_PASS') ?: '');

        $pdo = new PDO(
            "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }

    return $pdo;
}
