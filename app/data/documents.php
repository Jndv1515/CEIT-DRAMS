<?php
declare(strict_types=1);

/**
 * Document types available for requests. Replace with a database table later.
 */
return [
    [
        'name' => 'Thesis Proposal',
        'category' => 'Thesis · Individual',
        'type' => 'student',
        'description' => 'Proposal form for individual thesis and capstone projects, including the research scope and advisory details.',
        'format' => 'PDF',
        'size' => '248 KB',
        'eligibility' => 'Enrolled thesis or capstone students',
    ],
    [
        'name' => 'Event Proposal',
        'category' => 'Student Organization',
        'type' => 'organization',
        'description' => 'Request form for organization-led events, activities, and off-campus gatherings.',
        'format' => 'DOCX',
        'size' => '86 KB',
        'eligibility' => 'Registered student organizations',
    ],
    [
        'name' => 'Venue Reservation',
        'category' => 'Facility Request',
        'type' => 'organization',
        'description' => 'Reserve campus venues and facilities for approved organization activities.',
        'format' => 'PDF',
        'size' => '132 KB',
        'eligibility' => 'Registered student organizations',
    ],
    [
        'name' => 'Clearance Form',
        'category' => 'Student Service',
        'type' => 'student',
        'description' => 'Exit clearance form for graduating students and students with pending obligations.',
        'format' => 'PDF',
        'size' => '96 KB',
        'eligibility' => 'Students with active records',
    ],
];
