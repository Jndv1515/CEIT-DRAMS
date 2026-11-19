<?php
declare(strict_types=1);

/**
 * Backwards-compatible entry point for old /login.php links.
 */

require __DIR__ . '/app/config/config.php';
require __DIR__ . '/app/helpers.php';

header('Location: ' . url('/login'), true, 301);
exit;
