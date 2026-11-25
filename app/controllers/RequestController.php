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
        auth_guard();
        if ((auth_user()['role'] ?? '') === 'admin') {
            $this->redirect('/admin');
        }
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
        auth_guard();
        if ((auth_user()['role'] ?? '') === 'admin') {
            $this->redirect('/admin');
        }
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

        $user = auth_user();
        $type = $selected['type'];

        $db = db();
        $db->beginTransaction();
        $today = date('Ymd');
        $seqStmt = $db->prepare(
            "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(id, '-', -1) AS UNSIGNED)), 0) + 1
             FROM document_requests WHERE id LIKE :prefix FOR UPDATE"
        );
        $seqStmt->execute([':prefix' => 'DR-' . $today . '-%']);
        $seq = (int) $seqStmt->fetchColumn();

        store_request(array_merge([
            'id'       => 'DR-' . $today . '-' . str_pad((string) $seq, 4, '0', STR_PAD_LEFT),
            'user_id'  => $user['id'],
            'document' => $selected['name'],
            'category' => $selected['category'],
            'type'     => $type,
            'status'   => 'pending',
            'purpose'  => $purpose,
            'notes'    => $notes,
        ], $this->handleAttachment()));
        $db->commit();

        set_flash('Request submitted. It now appears in your recent requests.', 'success');
        $this->redirect('/dashboard');
    }

    public function index(): void
    {
        auth_guard();
        if ((auth_user()['role'] ?? '') === 'admin') {
            $this->redirect('/admin');
        }
        $view = (string) ($_GET['view'] ?? 'requests');
        $view = in_array($view, ['requests', 'documents'], true) ? $view : 'requests';
        $this->renderTracker($view);
    }

    /**
     * Build the enriched catalog with access state derived from the queue.
     *
     * @return array<int, array<string, mixed>>
     */
    private function catalogDocuments(): array
    {
        $documents = require APP_PATH . '/data/documents.php';
        $requests = queue_requests();
        $catalog = [];

        foreach ($documents as $document) {
            $matching = array_values(array_filter(
                $requests,
                static fn (array $request): bool => ($request['document'] ?? '') === $document['name']
            ));

            $approved = null;
            $pending = null;
            $rejected = null;

            foreach ($matching as $request) {
                $status = (string) ($request['status'] ?? 'pending');

                if ($status === 'approved' && $approved === null) {
                    $approved = $request;
                } elseif (in_array($status, ['pending', 'processing'], true) && $pending === null) {
                    $pending = $request;
                } elseif ($status === 'rejected' && $rejected === null) {
                    $rejected = $request;
                }
            }

            if ($approved !== null) {
                $state = 'available';
                $stateLabel = 'Available to download';
            } elseif ($pending !== null) {
                $state = 'pending';
                $stateLabel = 'Request pending';
            } elseif ($rejected !== null) {
                $state = 'rejected';
                $stateLabel = 'Request again';
            } else {
                $state = 'required';
                $stateLabel = 'Request required';
            }

            $chosen = $approved ?? $pending ?? $rejected;
            $hasAttachment = $chosen !== null && !empty($chosen['attachment_name']);
            $extension = $hasAttachment
                ? strtolower((string) pathinfo((string) $chosen['attachment_name'], PATHINFO_EXTENSION))
                : '';

            $catalog[] = array_merge($document, [
                'state' => $state,
                'stateLabel' => $stateLabel,
                'stateClass' => $state === 'rejected' ? 'pending' : ($state === 'required' ? 'restricted' : $state),
                'requestId' => (string) ($chosen['id'] ?? ''),
                'attachmentName' => (string) ($chosen['attachment_name'] ?? ''),
                'hasAttachment' => $hasAttachment,
                'previewable' => $hasAttachment && in_array($extension, ['pdf', 'png', 'jpg', 'jpeg'], true),
            ]);
        }

        return $catalog;
    }

    private function renderTracker(string $view): void
    {
        $allDocuments = $this->catalogDocuments();
        $catalog = $allDocuments;
        $search = '';
        $typeFilter = 'all';
        $accessFilter = 'all';
        $sort = 'name-asc';

        if ($view === 'documents') {
            $search = trim((string) ($_GET['q'] ?? ''));
            $typeFilter = (string) ($_GET['type'] ?? 'all');
            $accessFilter = (string) ($_GET['access'] ?? 'all');
            $sort = (string) ($_GET['sort'] ?? 'name-asc');

            $typeFilter = in_array($typeFilter, ['all', 'student', 'organization'], true) ? $typeFilter : 'all';
            $accessFilter = in_array($accessFilter, ['all', 'available', 'pending', 'rejected', 'required'], true)
                ? $accessFilter
                : 'all';
            $sort = in_array($sort, ['name-asc', 'category-asc'], true) ? $sort : 'name-asc';

            if ($search !== '') {
                $catalog = array_values(array_filter(
                    $catalog,
                    static function (array $document) use ($search): bool {
                        foreach (['name', 'category', 'description', 'eligibility'] as $field) {
                            if (stripos((string) ($document[$field] ?? ''), $search) !== false) {
                                return true;
                            }
                        }

                        return false;
                    }
                ));
            }

            if ($typeFilter !== 'all') {
                $catalog = array_values(array_filter(
                    $catalog,
                    static fn (array $document): bool => ($document['type'] ?? '') === $typeFilter
                ));
            }

            if ($accessFilter !== 'all') {
                $catalog = array_values(array_filter(
                    $catalog,
                    static fn (array $document): bool => ($document['state'] ?? '') === $accessFilter
                ));
            }

            usort($catalog, static function (array $a, array $b) use ($sort): int {
                if ($sort === 'category-asc') {
                    return strcasecmp((string) ($a['category'] ?? ''), (string) ($b['category'] ?? ''))
                        ?: strcasecmp((string) ($a['name'] ?? ''), (string) ($b['name'] ?? ''));
                }

                return strcasecmp((string) ($a['name'] ?? ''), (string) ($b['name'] ?? ''));
            });
        }

        $this->view('requests/index', [
            'title' => $view === 'documents' ? 'Available Documents' : 'My Requests',
            'activeNav' => $view === 'documents' ? 'documents' : 'requests',
            'activeView' => $view,
            'requests' => queue_requests(),
            'documents' => $catalog,
            'totalDocuments' => count($allDocuments),
            'search' => $search,
            'typeFilter' => $typeFilter,
            'accessFilter' => $accessFilter,
            'sort' => $sort,
        ], 'layouts/dashboard');
    }

    public function show(): void
    {
        auth_guard();
        $id        = (string) ($_GET['id'] ?? '');
        $isAdmin   = (auth_user()['role'] ?? '') === 'admin';
        $fromAdmin = $isAdmin && ($_GET['from'] ?? '') === 'admin';

        // Admins can only view via ?from=admin
        if ($isAdmin && !$fromAdmin) {
            $this->redirect('/admin');
        }

        $request = find_request($id);

        if ($request === null) {
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        $this->view('requests/show', [
            'title'     => 'Request Details',
            'activeNav' => $fromAdmin ? 'admin' : 'requests',
            'fromAdmin' => $fromAdmin,
            'request'   => $request,
            'backUrl'   => $fromAdmin ? '/admin' : '/requests',
            'backLabel' => $fromAdmin ? 'Back to Admin' : 'Back to Requests',
        ], $fromAdmin ? 'layouts/admin' : 'layouts/dashboard');
    }

    public function documents(): void
    {
        auth_guard();
        if ((auth_user()['role'] ?? '') === 'admin') {
            $this->redirect('/admin');
        }
        $this->renderTracker('documents');
    }

    public function attachment(): void
    {
        auth_guard();
        $id = (string) ($_GET['request'] ?? '');
        $request = find_request($id);

        if ($request === null || empty($request['attachment_path'])) {
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        $path = BASE_PATH . '/storage/uploads/' . $request['attachment_path'];
        if (!is_file($path)) {
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        $name = (string) ($request['attachment_name'] ?? 'attachment');
        $type = (string) ($request['attachment_type'] ?? 'application/octet-stream');
        $disposition = ($_GET['inline'] ?? '') === '1' ? 'inline' : 'attachment';

        header('Content-Type: ' . $type);
        header('Content-Disposition: ' . $disposition . '; filename="' . addcslashes($name, '"\\') . '"');
        header('Content-Length: ' . (string) filesize($path));
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
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
        set_flash($message, 'error');
        $this->redirect('/request');
    }
}
