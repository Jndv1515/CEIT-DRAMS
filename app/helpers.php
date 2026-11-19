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

if (!function_exists('icon')) {
    /**
     * Return a small inline SVG icon. Unknown names fall back to a document.
     */
    function icon(string $name): string
    {
        $paths = [
            'clock' => '<circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path>',
            'check' => '<path d="M5 13l4 4L19 7"></path>',
            'document' => '<path d="M7 3h7l5 5v13H7z"></path><path d="M14 3v5h5"></path><path d="M10 13h6M10 17h6"></path>',
        ];

        $body = $paths[$name] ?? $paths['document'];

        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" '
            . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
            . $body
            . '</svg>';
    }
}
