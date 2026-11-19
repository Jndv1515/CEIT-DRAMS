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
        'course' => 'BS Information Technology',
        'organization' => 'CEIT Student Council',
        'organization_role' => 'Senator for Operations and Implementation',
        'capstone_title' => 'Smart Attendance Monitoring System',
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
