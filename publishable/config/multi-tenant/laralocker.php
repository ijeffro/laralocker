<?php

/*
|---------------------------------------------------------------------------------
|
| LaraLocker = Laravel + Learning Locker || A Laravel Package for Learning Locker.
|
|---------------------------------------------------------------------------------
*/

return [

    /*
    |-----------------------------------------------------------------------------
    | Learning Locker (LRS) Settings
    |-----------------------------------------------------------------------------
    |
    | Specify Learning Locker Settings
    |
    | Docs: http://docs.learninglocker.net/
    |
    */

    'learning-locker' => [

        /*
        |-------------------------------------------------------------------------
        | Learning Locker Client Creadencials
        |-------------------------------------------------------------------------
        |
        | Specify Client Creadencials for Learning Locker
        | Docs: http://docs.learninglocker.net/http-clients/
        |
        | URL Example: http://saas.learninglocker.net/
        | Key Example: f04d57806f2ea7fa635da72cdd7bb25855ef396e
        | Secret Example: 506bf62d7e5970254f668f372020d7629ac1b2e3
        |
        */

        'api' => [
            'url' => env('LEARNING_LOCKER_URL'),
            'key' => env('LEARNING_LOCKER_KEY'),
            'secret' => env('LEARNING_LOCKER_SECRET'),
        ],


        /*
        |-------------------------------------------------------------------------
        | xAPI Statement Settings
        |-------------------------------------------------------------------------
        |
        | Specify xAPI Statement Settings for Learning Locker
        | Docs: http://docs.learninglocker.net/http-statements/
        |
        | Actor Type Example: mbox, account
        | Hompage Example: https://ht2.curatr3.com
        | Endpoint Example: https://ht2.curatr3.com
        |
        */
        
        'statements' => [
            'endpoint' => env('APP_URL'),
            'actor_type' => 'mbox',
            'hompage' => env('APP_URL'),
        ],


    ],
    

    /*
    |-----------------------------------------------------------------------------
    | Laravel Framework Settings
    |-----------------------------------------------------------------------------
    |
    | Specify Laravel Framework Settings
    |
    | Docs: https://laravel.com/docs/
    |
    */

    'laravel' => [
        'route' => [
            'api' => [
                'prefix' => 'laralocker',
            ],
            'web' => [
                'prefix' => 'laralocker',
            ],
        ],

    ],


    /*
    |-----------------------------------------------------------------------------
    | Config xAPI Database Data
    |-----------------------------------------------------------------------------
    |
    | Specify database tables and columns related to xAPI Statements data
    |
    */
    'database' => [

        'organisations' => [
            'model' => Organisations::class,
            'table' => 'organisations',
            'columns' => [
                'name' => [
                    'fullname' => null,
                    'firstname' => 'fname',
                    'lastname' =>'lname',
                ],
                'account' => [
                    'name' => 'uuid'
                ]
            ],
        ],
        'journeys' => [
            'model' => Journey::class,
            'table' => 'journeys',
            'columns' => [
                'name' => 'name',
            ],
        ],
        'actors' => [
            'model' => User::class,
            'table' => 'users',
            'columns' => [
                'name' => [
                    'fullname' => null,
                    'firstname' => 'fname',
                    'lastname' =>'lname',
                ],
                'account' => [
                    'name' => 'uuid',
                    'homepage' => env('APP_URL'),
                ],
            ],
        ],
        'verbs' => [
            'table' => 'learning_activity_types',
            'columns' => [
                'verb' => 'verb'
            ],
        ],
        'activities' => [
            'table' => 'learning_activity',
            'columns' => [
                'name' => 'name',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Config xAPI Statement Export
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */
    'export' => [
        'enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | xAPI Statement Queue Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */
    'queue' => [
        'enabled' => true,
    ],


    /*
    |--------------------------------------------------------------------------
    | Storage Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */
    'storage' => [
        'disk' => [
            'subfolder' => env('FILESYSTEM_DRIVER', 'public'),
        ],
        'database' => [
            'name' => env('DB_DATABASE'),
            'user' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ],
        's3' => [
            'bucket' => env('S3_BUCKET'),
            'region' => env('S3_REGION'),
            'subfolder' => env('AWS_SUBFOLDER'),
            'access_key' => env('AWS_ACCESS_KEY_ID'),
            'access_secret' => env('AWS_SECRET_ACCESS_KEY')
        ],
        
    ],

];
