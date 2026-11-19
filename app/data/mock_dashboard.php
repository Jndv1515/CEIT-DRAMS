<?php
declare(strict_types=1);

/**
 * Temporary dashboard data. Replace with database-backed queries later.
 * Scenarios cover both individual students and student organizations
 * in one centralized request queue.
 */
return [
    'user' => [
        'name' => 'Keith Montenegro',
        'student_number' => '2021-00432',
        'course' => 'BS Computer Science',
        'organization' => 'CEIT Student Council',
        'organization_role' => 'Events Coordinator',
    ],

    'stats' => [
        [
            'label' => 'Pending Requests',
            'value' => '01',
            'icon' => 'clock',
            'trend' => '+2 this week',
        ],
        [
            'label' => 'Approved',
            'value' => '01',
            'icon' => 'check',
            'trend' => '+3 this month',
        ],
        [
            'label' => 'Available Documents',
            'value' => '03',
            'icon' => 'document',
            'trend' => 'All request forms',
        ],
    ],

    'recentRequests' => [
        [
            'id' => 'DR-20261118-0001',
            'document' => 'Thesis Proposal',
            'category' => 'Thesis · Individual',
            'type' => 'student',
            'date' => 'Nov 18, 2026',
            'status' => 'pending',
        ],
        [
            'id' => 'DR-20261115-0002',
            'document' => 'Event Proposal',
            'category' => 'Student Organization',
            'type' => 'organization',
            'date' => 'Nov 15, 2026',
            'status' => 'approved',
        ],
        [
            'id' => 'DR-20261110-0003',
            'document' => 'Clearance Form',
            'category' => 'Student Service',
            'type' => 'student',
            'date' => 'Nov 10, 2026',
            'status' => 'rejected',
        ],
    ],
];
