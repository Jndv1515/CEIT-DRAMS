<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class RequestController extends Controller
{
    public function create(): void
    {
        $this->view('requests/create', [
            'title' => 'Request a Document',
            'activeNav' => 'request',
            'documents' => require APP_PATH . '/data/documents.php',
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
            set_flash('Please choose a document and enter the purpose.');
            $this->redirect('/request');
        }

        // The selected document is the source of truth for the requester type.
        $type = $selected['type'];
        $count = count(submitted_requests()) + 1;

        store_request([
            'id' => 'DR-' . date('Ymd') . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT),
            'document' => $selected['name'],
            'category' => $selected['category'],
            'type' => $type,
            'date' => date('M d, Y'),
            'status' => 'pending',
            'purpose' => $purpose,
            'notes' => $notes,
        ]);

        set_flash('Request submitted. It now appears in your recent requests.');
        $this->redirect($type === 'organization'
            ? '/dashboard?context=organization&filter=organization'
            : '/dashboard?context=personal&filter=all');
    }

    public function index(): void
    {
        $mockData = require APP_PATH . '/data/mock_dashboard.php';

        $this->view('requests/index', [
            'title' => 'My Requests',
            'activeNav' => 'requests',
            'requests' => array_merge(submitted_requests(), $mockData['recentRequests']),
        ], 'layouts/dashboard');
    }

    public function documents(): void
    {
        $this->view('documents/index', [
            'title' => 'My Documents',
            'activeNav' => 'documents',
            'documents' => require APP_PATH . '/data/documents.php',
        ], 'layouts/dashboard');
    }
}
