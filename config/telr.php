<?php

return [
    'mode'      => env('TELR_MODE', 'test'),
    'test'      => [
        'store_id'  => env('TELR_TEST_STORE_ID'),
        'auth_key'  => env('TELR_TEST_AUTH_KEY'),
        'base_url'  => env('TELR_TEST_BASE_URL'),
    ],
    'live'      => [
        'store_id'  => env('TELR_LIVE_STORE_ID'),
        'auth_key'  => env('TELR_LIVE_AUTH_KEY'),
        'base_url'  => env('TELR_LIVE_BASE_URL'),
    ],
];
