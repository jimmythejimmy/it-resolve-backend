<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Set FRONTEND_URL di .env sesuai domain frontend Anda.
    | Contoh produksi: FRONTEND_URL=https://itresolve.perusahaan.com
    | Contoh lokal   : FRONTEND_URL=http://localhost:3000
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),
        'https://it-resolve-frontend.vercel.app',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Content-Disposition'],

    'max_age' => 0,

    'supports_credentials' => true,
];
