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

if (!function_exists('set_flash')) {
    /**
     * @param 'info'|'success'|'error' $type
     */
    function set_flash(string $message, string $type = 'info'): void
    {
        if (!in_array($type, ['info', 'success', 'error'], true)) {
            $type = 'info';
        }

        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
    }
}

if (!function_exists('take_flash')) {
    /**
     * @return array{message: string, type: string}|null
     */
    function take_flash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        // Backwards compatibility with flashes stored as plain strings.
        if (is_string($flash)) {
            return ['message' => $flash, 'type' => 'info'];
        }

        if (!is_array($flash) || !isset($flash['message'])) {
            return null;
        }

        $type = (string) ($flash['type'] ?? 'info');

        return [
            'message' => (string) $flash['message'],
            'type' => in_array($type, ['info', 'success', 'error'], true) ? $type : 'info',
        ];
    }
}

if (!function_exists('submitted_requests')) {
    /**
     * @return array<int, array<string, string>>
     */
    function submitted_requests(): array
    {
        return $_SESSION['document_requests'] ?? [];
    }
}

if (!function_exists('store_request')) {
    /**
     * @param array<string, string> $request
     */
    function store_request(array $request): void
    {
        if (!isset($_SESSION['document_requests']) || !is_array($_SESSION['document_requests'])) {
            $_SESSION['document_requests'] = [];
        }

        array_unshift($_SESSION['document_requests'], $request);
    }
}

if (!function_exists('request_statuses')) {
    /**
     * @return array<int, string>
     */
    function request_statuses(): array
    {
        return ['pending', 'processing', 'approved', 'rejected', 'forwarded'];
    }
}

if (!function_exists('queue_requests')) {
    /**
     * All requests in one queue, with session status overrides applied.
     *
     * @return array<int, array<string, mixed>>
     */
    function queue_requests(): array
    {
        $mockData = require APP_PATH . '/data/mock_dashboard.php';
        $overrides = $_SESSION['request_statuses'] ?? [];

        if (!is_array($overrides)) {
            $overrides = [];
        }

        $requests = [];

        foreach (array_merge(submitted_requests(), $mockData['recentRequests']) as $request) {
            $id = (string) ($request['id'] ?? '');
            $status = $overrides[$id] ?? null;

            if ($id !== '' && is_string($status) && in_array($status, request_statuses(), true)) {
                $request['status'] = $status;
            }

            $requests[] = $request;
        }

        return $requests;
    }
}

if (!function_exists('find_request')) {
    /**
     * @return array<string, mixed>|null
     */
    function find_request(string $id): ?array
    {
        foreach (queue_requests() as $request) {
            if (($request['id'] ?? '') === $id) {
                return $request;
            }
        }

        return null;
    }
}

if (!function_exists('update_request_status')) {
    function update_request_status(string $id, string $status): void
    {
        if (!in_array($status, request_statuses(), true)) {
            return;
        }

        if (!isset($_SESSION['request_statuses']) || !is_array($_SESSION['request_statuses'])) {
            $_SESSION['request_statuses'] = [];
        }

        $_SESSION['request_statuses'][$id] = $status;

        if (!isset($_SESSION['document_requests']) || !is_array($_SESSION['document_requests'])) {
            return;
        }

        foreach ($_SESSION['document_requests'] as &$request) {
            if (($request['id'] ?? '') === $id) {
                $request['status'] = $status;
            }
        }
        unset($request);
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
            'progress' => '<path d="M12 3a9 9 0 1 1-9 9"></path><path d="M12 7v5l3 2"></path>',
            'check' => '<path d="M5 13l4 4L19 7"></path>',
            'x' => '<path d="M6 6l12 12M18 6L6 18"></path>',
            'forward' => '<path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path>',
            'rotate' => '<path d="M3 12a9 9 0 1 0 3-6.7"></path><path d="M3 4v5h5"></path>',
            'paperclip' => '<path d="M21 11.5 12.5 20a5 5 0 0 1-7-7l9-9a3.5 3.5 0 0 1 5 5l-9 9a2 2 0 0 1-3-3l8-8"></path>',
            'alert' => '<circle cx="12" cy="12" r="9"></circle><path d="M12 8v5M12 16h.01"></path>',
            'info' => '<circle cx="12" cy="12" r="9"></circle><path d="M12 11v5M12 8h.01"></path>',
            'document' => '<path d="M7 3h7l5 5v13H7z"></path><path d="M14 3v5h5"></path><path d="M10 13h6M10 17h6"></path>',
        ];

        $body = $paths[$name] ?? $paths['document'];

        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" '
            . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
            . $body
            . '</svg>';
    }
}

if (!function_exists('empty_state')) {
    /**
     * Reusable empty state with an optional action link.
     *
     * @param array{title?: string, message?: string, actionLabel?: string, actionUrl?: string, icon?: string} $options
     */
    function empty_state(array $options = []): string
    {
        $title = (string) ($options['title'] ?? 'Nothing here yet');
        $message = (string) ($options['message'] ?? '');
        $actionLabel = (string) ($options['actionLabel'] ?? '');
        $actionUrl = (string) ($options['actionUrl'] ?? '');
        $iconName = (string) ($options['icon'] ?? 'document');

        $html = '<div class="empty-state" role="status">'
            . '<span class="empty-state-icon" aria-hidden="true">' . icon($iconName) . '</span>'
            . '<p class="empty-state-title">' . e($title) . '</p>';

        if ($message !== '') {
            $html .= '<p class="empty-state-message">' . e($message) . '</p>';
        }

        if ($actionLabel !== '' && $actionUrl !== '') {
            $html .= '<a class="btn-ghost btn-sm" href="' . e($actionUrl) . '">' . e($actionLabel) . '</a>';
        }

        return $html . '</div>';
    }
}
