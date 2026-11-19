<?php
declare(strict_types=1);

/**
 * Temporary dashboard data. Replace with database-backed queries later.
 */
return [
    'user' => [
        'name' => 'Keith Montenegro',
        'student_number' => '2021-00432',
        'course' => 'BS Computer Science',
    ],

    'stats' => [
        ['label' => 'Pending Requests', 'value' => '03'],
        ['label' => 'Approved', 'value' => '12'],
        ['label' => 'Available', 'value' => '05'],
    ],

    'recentRequests' => [
        ['document' => 'Thesis Proposal', 'date' => 'Nov 18, 2026', 'status' => 'pending'],
        ['document' => 'Event Proposal', 'date' => 'Nov 17, 2026', 'status' => 'approved'],
        ['document' => 'Certificate of Enrollment', 'date' => 'Nov 14, 2026', 'status' => 'pending'],
        ['document' => 'Transcript of Records', 'date' => 'Nov 10, 2026', 'status' => 'approved'],
    ],
];
