<?php

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_zonaavz_send_mail' => array(
        'classname' => 'local_zonaavz_external',
        'methodname' => 'send_mail',
        'classpath' => 'local/zonaavz/classes/external.php',
        'description' => 'Creates a local_mail message and dispatches its Moodle notifications.',
        'type' => 'write',
        'capabilities' => 'local/zonaavz:sendmail',
    ),
);
