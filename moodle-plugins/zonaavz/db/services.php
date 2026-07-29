<?php

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_zonaavz_site_info' => array(
        'classname' => 'local_zonaavz_provisioning_external',
        'methodname' => 'site_info',
        'classpath' => 'local/zonaavz/classes/provisioning_external.php',
        'description' => 'Returns connector and Moodle compatibility information.',
        'type' => 'read',
        'capabilities' => 'local/zonaavz:sendmail',
    ),
    'local_zonaavz_list_courses' => array(
        'classname' => 'local_zonaavz_provisioning_external',
        'methodname' => 'list_courses',
        'classpath' => 'local/zonaavz/classes/provisioning_external.php',
        'description' => 'Lists courses that can be used as templates or manual links.',
        'type' => 'read',
        'capabilities' => 'local/zonaavz:sendmail',
    ),
    'local_zonaavz_list_categories' => array(
        'classname' => 'local_zonaavz_provisioning_external',
        'methodname' => 'list_categories',
        'classpath' => 'local/zonaavz/classes/provisioning_external.php',
        'description' => 'Lists Moodle course categories available as destinations.',
        'type' => 'read',
        'capabilities' => 'local/zonaavz:sendmail',
    ),
    'local_zonaavz_provision_course' => array(
        'classname' => 'local_zonaavz_provisioning_external',
        'methodname' => 'provision_course',
        'classpath' => 'local/zonaavz/classes/provisioning_external.php',
        'description' => 'Duplicates or synchronises a managed ZonaAvz course.',
        'type' => 'write',
        'capabilities' => 'local/zonaavz:sendmail',
    ),
    'local_zonaavz_send_mail' => array(
        'classname' => 'local_zonaavz_external',
        'methodname' => 'send_mail',
        'classpath' => 'local/zonaavz/classes/external.php',
        'description' => 'Creates a local_mail message and dispatches its Moodle notifications.',
        'type' => 'write',
        'capabilities' => 'local/zonaavz:sendmail',
    ),
);
