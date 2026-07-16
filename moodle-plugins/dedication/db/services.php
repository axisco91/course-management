<?php
$functions = [
    'local_dedication_get_dedication' => [
        'classname' => 'local_dedication_external',
        'methodname' => 'get_dedication',
        'classpath' => 'local/dedication/externallib.php',
        'description' => 'Get course dedication and SCORM time for a user',
        'type' => 'read',
        'capabilities' => 'moodle/course:viewparticipants',
    ],
];

$services = [
    'Course Dedication Service' => [
        'functions' => ['local_dedication_get_dedication'],
        'restrictedusers' => 0,
        'enabled' => 1,
    ],
];
