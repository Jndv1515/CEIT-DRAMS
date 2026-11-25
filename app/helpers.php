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

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_verify')) {
    function csrf_verify(): void
    {
        $token = (string) ($_POST['_csrf'] ?? '');
        if (!hash_equals((string) ($_SESSION['csrf_token'] ?? ''), $token)) {
            http_response_code(403);
            exit('Invalid CSRF token.');
        }
    }
}

if (!function_exists('request_statuses')) {
    function request_statuses(): array
    {
        return ['pending', 'processing', 'approved', 'rejected', 'forwarded'];
    }
}

if (!function_exists('store_request')) {
    function store_request(array $request): void
    {
        $stmt = db()->prepare(
            'INSERT INTO document_requests
             (id, user_id, document, category, type, purpose, notes, status,
              attachment_name, attachment_path, attachment_type, attachment_size)
             VALUES (:id, :user_id, :document, :category, :type, :purpose, :notes, :status,
              :attachment_name, :attachment_path, :attachment_type, :attachment_size)'
        );
        $stmt->execute([
            ':id'              => $request['id'],
            ':user_id'         => $request['user_id'],
            ':document'        => $request['document'],
            ':category'        => $request['category'],
            ':type'            => $request['type'],
            ':purpose'         => $request['purpose'],
            ':notes'           => $request['notes'] ?? '',
            ':status'          => $request['status'] ?? 'pending',
            ':attachment_name' => $request['attachment_name'] ?? '',
            ':attachment_path' => $request['attachment_path'] ?? '',
            ':attachment_type' => $request['attachment_type'] ?? '',
            ':attachment_size' => $request['attachment_size'] ?? 0,
        ]);
    }
}

if (!function_exists('queue_requests')) {
    /**
     * All requests — scoped to the logged-in user if student, all if admin.
     *
     * @return array<int, array<string, mixed>>
     */
    function queue_requests(): array
    {
        $user = auth_user();
        if ($user === null) {
            return [];
        }

        if ($user['role'] === 'admin') {
            $stmt = db()->query(
                'SELECT r.*, u.name AS requester_name, u.student_number AS requester_number
                 FROM document_requests r
                 JOIN users u ON u.id = r.user_id
                 ORDER BY r.submitted_at DESC'
            );
        } else {
            $stmt = db()->prepare(
                'SELECT r.*, u.name AS requester_name, u.student_number AS requester_number
                 FROM document_requests r
                 JOIN users u ON u.id = r.user_id
                 WHERE r.user_id = :uid
                 ORDER BY r.submitted_at DESC'
            );
            $stmt->execute([':uid' => $user['id']]);
        }

        $rows = $stmt->fetchAll();
        $result = [];

        foreach ($rows as $row) {
            $result[] = array_merge($row, [
                'date' => date('M d, Y', strtotime($row['submitted_at'])),
            ]);
        }

        return $result;
    }
}

if (!function_exists('find_request')) {
    function find_request(string $id): ?array
    {
        $user = auth_user();
        if ($user === null) {
            return null;
        }

        if ($user['role'] === 'admin') {
            $stmt = db()->prepare(
                'SELECT r.*, u.name AS requester_name, u.student_number AS requester_number
                 FROM document_requests r
                 JOIN users u ON u.id = r.user_id
                 WHERE r.id = :id LIMIT 1'
            );
            $stmt->execute([':id' => $id]);
        } else {
            $stmt = db()->prepare(
                'SELECT r.*, u.name AS requester_name, u.student_number AS requester_number
                 FROM document_requests r
                 JOIN users u ON u.id = r.user_id
                 WHERE r.id = :id AND r.user_id = :uid LIMIT 1'
            );
            $stmt->execute([':id' => $id, ':uid' => $user['id']]);
        }

        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        return array_merge($row, [
            'date' => date('M d, Y', strtotime($row['submitted_at'])),
        ]);
    }
}

if (!function_exists('update_request_status')) {
    function update_request_status(string $id, string $status, string $reason = ''): void
    {
        if (!in_array($status, request_statuses(), true)) {
            return;
        }

        $stmt = db()->prepare(
            'UPDATE document_requests SET status = :status, rejection_reason = :reason WHERE id = :id'
        );
        $stmt->execute([':status' => $status, ':reason' => $reason, ':id' => $id]);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_verify')) {
    function csrf_verify(): void
    {
        $token = (string) ($_POST['_csrf'] ?? '');
        if (!hash_equals((string) ($_SESSION['csrf_token'] ?? ''), $token)) {
            http_response_code(419);
            die('Invalid CSRF token. Go back and try again.');
        }
    }
}

if (!function_exists('auth_user')) {
    /**
     * @return array<string, string>|null
     */
    function auth_user(): ?array
    {
        $user = $_SESSION['auth_user'] ?? null;
        return is_array($user) ? $user : null;
    }
}

if (!function_exists('auth_guard')) {
    /**
     * Redirect to /login if no user is authenticated.
     */
    function auth_guard(): void
    {
        if (auth_user() === null) {
            header('Location: ' . url('/login'));
            exit;
        }
    }
}

if (!function_exists('admin_guard')) {
    /**
     * Redirect to /dashboard if the logged-in user is not an admin.
     */
    function admin_guard(): void
    {
        auth_guard();
        $user = auth_user();
        if (($user['role'] ?? 'student') !== 'admin') {
            header('Location: ' . url('/dashboard'));
            exit;
        }
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
            'sun' => '<circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"></path>',
            'moon' => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"></path>',
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
