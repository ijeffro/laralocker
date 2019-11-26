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
  |-------------------------------------------------------------------------------
  | Learning Locker (LRS) Settings
  |-------------------------------------------------------------------------------
  |
  | Specify Learning Locker Settings
  |
  | Docs: http://docs.learninglocker.net/
  |
  */

  'learning-locker' => [

    /*
    |---------------------------------------------------------------------------
    | Learning Locker Connection
    |---------------------------------------------------------------------------
    |
    | Specify Client Creadencials for Learning Locker
    | Docs: http://docs.learninglocker.net/http-clients/
    |
    | URL Example: http://saas.learninglocker.net/
    | Key Example: f04d57806f2ea7fa635da72cdd7bb25855ef396e
    | Secret Example: 506bf62d7e5970254f668f372020d7629ac1b2e3
    |
    */

    'connection' => [

      /*
      |--------------------------------------------------------------------------
      | Primary Learning Locker Connection
      |--------------------------------------------------------------------------
      |
      */

      'primary' => [
        'url' => env('LEARNING_LOCKER_URL'),
        'key' => env('LEARNING_LOCKER_KEY'),
        'secret' => env('LEARNING_LOCKER_SECRET'),
      ],


      /*
      |--------------------------------------------------------------------------
      | Secondary Learning Locker Connection
      |--------------------------------------------------------------------------
      |
      */

      'secondary' => [
        'url' => env('BACKUP_LEARNING_LOCKER_URL'),
        'key' => env('BACKUP_LEARNING_LOCKER_KEY'),
        'secret' => env('BACKUP_LEARNING_LOCKER_SECRET'),
      ],

    ],

  ],


  /*
  |-------------------------------------------------------------------------------
  | Laravel Framework Settings
  |-------------------------------------------------------------------------------
  |
  | Here you can specify attributes related to your application file system
  |
  */

  'laravel' => [
    'settings' => [
      'queue' => false,
      'export' => false,
    ],
    'route' => [
      'api' => [
        'prefix' => 'laralocker',
      ],
      'web' => [
        'prefix' => 'laralocker',
      ],
    ],
  ],

];
