<?php
declare(strict_types=1);

/**
 * Small global helpers available everywhere after bootstrap.
 */

if (!function_exists('url')) {
    /**
     * Build a URL that respects a subdirectory install (e.g. /ceit-drams).
     */
    function url(string $path = ''): string
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '/';
        $base = rtrim(str_replace('\\', '/', dirname($script)), '/');

        if ($base === '.' || $base === '/') {
            $base = '';
        }

        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('e')) {
    /**
     * Escape output for safe HTML rendering.
     */
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
