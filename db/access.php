<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/achievement:manageachievements' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'manager' => CAP_ALLOW,
        ]
    ]
];
