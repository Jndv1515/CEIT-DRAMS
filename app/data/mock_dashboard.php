<?php
declare(strict_types=1);

/**
 * Temporary dashboard data. Replace with database-backed queries later.
 * Scenarios cover both individual students (thesis/capstone) and
 * student organization requests.
 */
return [
    'user' => [
        'name' => 'Keith Montenegro',
        'student_number' => '2021-00432',
        'course' => 'BS Computer Science',
        'organization' => 'CEIT Student Council',
        'organization_role' => 'Events Coordinator',
        'capstone_title' => 'Document Request and Approval Management System',
    ],

    'stats' => [
        ['label' => 'Pending Requests', 'value' => '04'],
        ['label' => 'Approved', 'value' => '09'],
        ['label' => 'Available Documents', 'value' => '06'],
    ],

    'recentRequests' => [
        [
            'document' => 'Thesis Proposal Approval',
            'category' => 'Capstone · Individual',
            'date' => 'Nov 18, 2026',
            'status' => 'pending',
        ],
        [
            'document' => 'Activity Proposal — CEIT Week',
            'category' => 'Student Organization',
            'date' => 'Nov 17, 2026',
            'status' => 'approved',
        ],
        [
            'document' => 'Capstone Title Change Request',
            'category' => 'Capstone · Individual',
            'date' => 'Nov 14, 2026',
            'status' => 'pending',
        ],
        [
            'document' => 'Venue Reservation Form',
            'category' => 'Student Organization',
            'date' => 'Nov 10, 2026',
            'status' => 'approved',
        ],
        [
            'document' => 'Certificate of Enrollment',
            'category' => 'Student Service',
            'date' => 'Nov 06, 2026',
            'status' => 'pending',
        ],
    ],
];
