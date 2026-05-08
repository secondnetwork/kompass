<?php

return [

    'relying_party_id' => parse_url(config('app.url'), PHP_URL_HOST),

    'allowed_origins' => array_unique(array_filter([
        config('app.url'),
        // Include the https:// variant so WebAuthn works when APP_URL uses http://
        'https://'.parse_url(config('app.url'), PHP_URL_HOST),
    ])),

    'user_handle_secret' => env('PASSKEYS_USER_HANDLE_SECRET', config('app.key')),

    'timeout' => 60000,

    'guard' => 'web',

    'middleware' => ['web'],

    'throttle' => 'throttle:6,1',

    'redirect' => '/admin/dashboard',

];
