<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // This might look different for you

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'https://curameet.duckdns.org/'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
