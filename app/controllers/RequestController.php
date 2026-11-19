<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class RequestController extends Controller
{
    /** @var array<string, string> */
    private const ALLOWED_FILES = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
    ];

    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    public function create(): void
    {
        $documents = require APP_PATH . '/data/documents.php';
        $selected = trim((string) ($_GET['document'] ?? ''));
        $valid = false;

        foreach ($documents as $document) {
            if ($document['name'] === $selected) {
                $valid = true;
                break;
            }
        }

        $this->view('requests/create', [
            'title' => 'Request a Document',
            'activeNav' => 'request',
            'documents' => $documents,
            'selectedDocument' => $valid ? $selected : '',
        ], 'layouts/dashboard');
    }

    public function store(): void
    {
        $documentName = trim((string) ($_POST['document'] ?? ''));
        $purpose = trim((string) ($_POST['purpose'] ?? ''));
        $notes = trim((string) ($_POST['notes'] ?? ''));

        $documents = require APP_PATH . '/data/documents.php';
        $selected = null;

        foreach ($documents as $document) {
            if ($document['name'] === $documentName) {
                $selected = $document;
                break;
            }
        }

        if ($selected === null || $purpose === '') {
            $this->fail('Please choose a document and enter the purpose.');
        }

        // The selected document is the source of truth for the requester type.
        $type = $selected['type'];
        $count = count(submitted_requests()) + 1;

        store_request(array_merge([
            'id' => 'DR-' . date('Ymd') . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT),
            'document' => $selected['name'],
            'category' => $selected['category'],
            'type' => $type,
            'date' => date('M d, Y'),
            'status' => 'pending',
            'purpose' => $purpose,
            'notes' => $notes,
        ], $this->handleAttachment()));

        set_flash('Request submitted. It now appears in your recent requests.');
        $this->redirect($type === 'organization'
            ? '/dashboard?context=organization&filter=organization'
            : '/dashboard?context=personal&filter=all');
    }

    public function index(): void
    {
        $view = $_GET['view'] ?? 'requests';
        $view = in_array($view, ['requests', 'documents'], true) ? $view : 'requests';

        $mockData = require APP_PATH . '/data/mock_dashboard.php';

        $this->view('requests/index', [
            'title' => 'My Requests',
            'activeNav' => 'requests',
            'activeView' => $view,
            'requests' => array_merge(submitted_requests(), $mockData['recentRequests']),
            'documents' => require APP_PATH . '/data/documents.php',
        ], 'layouts/dashboard');
    }

    public function show(): void
    {
        $id = (string) ($_GET['id'] ?? '');
        $mockData = require APP_PATH . '/data/mock_dashboard.php';
        $request = null;

        foreach (array_merge(submitted_requests(), $mockData['recentRequests']) as $item) {
            if (($item['id'] ?? '') === $id) {
                $request = $item;
                break;
            }
        }

        if ($request === null) {
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        $this->view('requests/show', [
            'title' => 'Request Details',
            'activeNav' => 'requests',
            'request' => $request,
        ], 'layouts/dashboard');
    }

    public function documents(): void
    {
        $this->redirect('/requests?view=documents');
    }

    /**
     * Stream a stored attachment for a request in the current session.
     */
    public function attachment(): void
    {
        $id = (string) ($_GET['request'] ?? '');

        foreach (submitted_requests() as $request) {
            if (($request['id'] ?? '') !== $id || empty($request['attachment_path'])) {
                continue;
            }

            $path = BASE_PATH . '/storage/uploads/' . $request['attachment_path'];
            if (!is_file($path)) {
                break;
            }

            $name = (string) ($request['attachment_name'] ?? 'attachment');
            $type = (string) ($request['attachment_type'] ?? 'application/octet-stream');

            header('Content-Type: ' . $type);
            header('Content-Disposition: attachment; filename="' . addcslashes($name, "\"\\") . '"');
            header('Content-Length: ' . (string) filesize($path));
            header('X-Content-Type-Options: nosniff');
            readfile($path);
            exit;
        }

        http_response_code(404);
        require VIEWS_PATH . '/errors/404.php';
    }

    /**
     * Validate and move the optional upload. Returns metadata for the session.
     *
     * @return array<string, string|int>
     */
    private function handleAttachment(): array
    {
        if (!isset($_FILES['attachment']) || !is_array($_FILES['attachment'])) {
            return [];
        }

        $file = $_FILES['attachment'];
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($error === UPLOAD_ERR_NO_FILE) {
            return [];
        }

        if ($error !== UPLOAD_ERR_OK) {
            $this->fail('The file could not be uploaded. Please try again.');
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > self::MAX_FILE_SIZE) {
            $this->fail('Please upload a file smaller than 5 MB.');
        }

        $original = basename((string) ($file['name'] ?? 'attachment'));
        $original = preg_replace('/[^A-Za-z0-9._-]+/', '_', $original) ?: 'attachment';
        $extension = strtolower((string) pathinfo($original, PATHINFO_EXTENSION));

        if (!isset(self::ALLOWED_FILES[$extension])) {
            $this->fail('Allowed file types: PDF, DOC, DOCX, PNG, JPG, or JPEG.');
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        $detected = null;

        if ($tmpName !== '' && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $detected = finfo_file($finfo, $tmpName);
                finfo_close($finfo);
            }
        }

        $allowedMimes = [self::ALLOWED_FILES[$extension], 'application/octet-stream'];
        if ($extension === 'docx') {
            $allowedMimes[] = 'application/zip';
        }

        if ($detected !== null && !in_array($detected, $allowedMimes, true)) {
            $this->fail('The uploaded file does not match its extension.');
        }

        $stored = bin2hex(random_bytes(8)) . '.' . $extension;
        $directory = BASE_PATH . '/storage/uploads';

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (!move_uploaded_file($tmpName, $directory . '/' . $stored)) {
            $this->fail('The file could not be saved. Please try again.');
        }

        return [
            'attachment_name' => $original,
            'attachment_path' => $stored,
            'attachment_type' => self::ALLOWED_FILES[$extension],
            'attachment_size' => $size,
        ];
    }

    private function fail(string $message): void
    {
        set_flash($message);
        $this->redirect('/request');
    }
}
