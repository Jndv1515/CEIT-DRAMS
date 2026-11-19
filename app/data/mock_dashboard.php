<?php
declare(strict_types=1);

/**
 * Temporary dashboard data. Replace with database-backed queries later.
 * Scenarios cover both individual students (thesis/capstone) and
 * student organization requests in one centralized queue.
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
        [
            'label' => 'Pending Requests',
            'value' => '04',
            'icon' => 'clock',
            'trend' => '+2 this week',
        ],
        [
            'label' => 'Approved',
            'value' => '09',
            'icon' => 'check',
            'trend' => '+3 this month',
        ],
        [
            'label' => 'Available Documents',
            'value' => '06',
            'icon' => 'document',
            'trend' => '2 new forms',
        ],
    ],

    'recentRequests' => [
        [
            'document' => 'Capstone Proposal Approval',
            'category' => 'Capstone · Individual',
            'type' => 'student',
            'date' => 'Nov 18, 2026',
            'status' => 'pending',
        ],
        [
            'document' => 'Activity Proposal',
            'category' => 'Student Organization',
            'type' => 'organization',
            'date' => 'Nov 17, 2026',
            'status' => 'approved',
        ],
        [
            'document' => 'Capstone Title Change Request',
            'category' => 'Capstone · Individual',
            'type' => 'student',
            'date' => 'Nov 14, 2026',
            'status' => 'pending',
        ],
        [
            'document' => 'Venue Reservation Form',
            'category' => 'Student Organization',
            'type' => 'organization',
            'date' => 'Nov 10, 2026',
            'status' => 'approved',
        ],
        [
            'document' => 'Certificate of Enrollment',
            'category' => 'Student Service',
            'type' => 'student',
            'date' => 'Nov 06, 2026',
            'status' => 'pending',
        ],
    ],
];
