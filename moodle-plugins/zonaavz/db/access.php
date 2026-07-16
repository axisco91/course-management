<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/zonaavz:sendmail' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS | RISK_PERSONAL,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
    ),
);
