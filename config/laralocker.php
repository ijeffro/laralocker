<?php

/*
|--------------------------------------------------------------------------
| LaraLocker: a Laravel API connector for Learning Locker®
|--------------------------------------------------------------------------
|
| Create a client in Learning Locker (Settings → Clients) and copy its key
| and secret here. The client's scopes decide what the connector may do:
| "API All" for the /api/v2 resources, "xAPI All" (or read/write) for
| statements.
|
| Docs: https://docs.learninglocker.net/http-clients/
|
*/

return [

    // The root of the Learning Locker install, without /api or /data.
    'url' => env('LEARNING_LOCKER_URL'),

    'key' => env('LEARNING_LOCKER_KEY'),

    'secret' => env('LEARNING_LOCKER_SECRET'),

    // Seconds to wait for Learning Locker before giving up.
    'timeout' => (int) env('LEARNING_LOCKER_TIMEOUT', 30),

    'xapi' => [

        // Sent as X-Experience-API-Version on every xAPI request.
        'version' => '1.0.3',

        // Language map key used for verb displays and activity names.
        'language' => env('LEARNING_LOCKER_LANGUAGE', 'en-GB'),

        // Home page used for account actors: xAPI::actor(['name' => ..., 'account' => $id]).
        'homepage' => env('LEARNING_LOCKER_ACTOR_HOMEPAGE', env('APP_URL')),

    ],

];
