<?php

return [
    'domain' => env('CLIENT_ACCESS_DOMAIN', ''),
    'excluded_ids' => array_filter(array_map('intval', explode(',', env('CLIENT_ACCESS_EXCLUDED_IDS', '1')))),
];
