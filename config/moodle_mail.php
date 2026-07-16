<?php

return [
    'enabled' => env('MOODLE_LOCAL_MAIL_ENABLED', true),
    'function' => env('MOODLE_LOCAL_MAIL_FUNCTION', 'local_zonaavz_send_mail'),
    'timeout' => (int) env('MOODLE_LOCAL_MAIL_TIMEOUT', 15),
];
