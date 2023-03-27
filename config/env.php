<?php

return [
    'auth' => [
        // Personal access client
        'pa_client_secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
        'pa_client_id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),

        // Password grant client
        'pg_client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
        'pg_client_id' => env('PASSPORT_PASSWORD_GRANT_ACCESS_CLIENT_ID'),
    ]
];
