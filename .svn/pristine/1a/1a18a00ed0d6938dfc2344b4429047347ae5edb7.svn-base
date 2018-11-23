<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'workbook'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', '123'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'mysql_concern' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_CONCERN', '127.0.0.1'),
            'port' => env('DB_PORT_CONCERN', '3306'),
            'database' => env('DB_DATABASE_CONCERN', 'forge'),
            'username' => env('DB_USERNAME_CONCERN', 'forge'),
            'password' => env('DB_PASSWORD_CONCERN', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'mysql_main' => [
          'driver' => 'mysql',
          'host' => env('DB_HOST_MAIN', '127.0.0.1'),
          'port' => env('DB_PORT_MAIN', '3306'),
          'database' => env('DB_DATABASE_MAIN', 'forge'),
          'username' => env('DB_USERNAME_MAIN', 'forge'),
          'password' => env('DB_PASSWORD_MAIN', ''),
          'charset' => 'utf8mb4',
          'collation' => 'utf8mb4_unicode_ci',
          'prefix' => '',
          'strict' => true,
          'engine' => null,
        ],
      'mysql_main_jiajiao' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST_MAIN', '127.0.0.1'),
        'port' => env('DB_PORT_MAIN', '3306'),
        'database' => env('DB_DATABASE_MAIN', 'forge'),
        'username' => env('DB_USERNAME_MAIN', 'forge'),
        'password' => env('DB_PASSWORD_MAIN', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
      ],
        'mysql_local' => [
          'driver' => 'mysql',
          'host' => env('DB_HOST_LOCAL', '127.0.0.1'),
          'port' => env('DB_PORT_LOCAL', '3306'),
          'database' => env('DB_DATABASE_LOCAL', 'forge'),
          'username' => env('DB_USERNAME_LOCAL', 'forge'),
          'password' => env('DB_PASSWORD_LOCAL', ''),
          'charset' => 'utf8mb4',
          'collation' => 'utf8mb4_unicode_ci',
          'prefix' => '',
          'strict' => true,
          'engine' => null,
        ],
        'mysql_main_rds' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_MAIN_RDS', '127.0.0.1'),
            'port' => env('DB_PORT_MAIN_RDS', '3306'),
            'database' => env('DB_DATABASE_MAIN_RDS', 'forge'),
            'username' => env('DB_USERNAME_MAIN_RDS', 'forge'),
            'password' => env('DB_PASSWORD_MAIN_RDS', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'mysql_main_rds_tiku' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_MAIN_RDS_TIKU', '127.0.0.1'),
            'port' => env('DB_PORT_MAIN_RDS_TIKU', '3306'),
            'database' => env('DB_DATABASE_MAIN_RDS_TIKU', 'forge'),
            'username' => env('DB_USERNAME_MAIN_RDS_TIKU', 'forge'),
            'password' => env('DB_PASSWORD_MAIN_RDS_TIKU', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'mysql_main_rds_jiajiao' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_MAIN_RDS_JIAJIAO', '127.0.0.1'),
            'port' => env('DB_PORT_MAIN_RDS_JIAJIAO', '3306'),
            'database' => env('DB_DATABASE_MAIN_RDS_JIAJIAO', 'forge'),
            'username' => env('DB_USERNAME_MAIN_RDS_JIAJIAO', 'forge'),
            'password' => env('DB_PASSWORD_MAIN_RDS_JIAJIAO', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'mysql_zjb' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_ZJB', '127.0.0.1'),
            'port' => env('DB_PORT_ZJB', '3306'),
            'database' => env('DB_DATABASE_ZJB', 'forge'),
            'username' => env('DB_USERNAME_ZJB', 'forge'),
            'password' => env('DB_PASSWORD_ZJB', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'mysql_zjb_lww' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_ZJB_LWW', '127.0.0.1'),
            'port' => env('DB_PORT_ZJB_LWW', '3306'),
            'database' => env('DB_DATABASE_ZJB_LWW', 'forge'),
            'username' => env('DB_USERNAME_ZJB_LWW', 'forge'),
            'password' => env('DB_PASSWORD_ZJB_LWW', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'mysql_05wang' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_05wang', '127.0.0.1'),
            'port' => env('DB_PORT_05wang', '3306'),
            'database' => env('DB_DATABASE_05wang', 'forge'),
            'username' => env('DB_USERNAME_05wang', 'forge'),
            'password' => env('DB_PASSWORD_05wang', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],


        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],
        'session' => [
          'host'     => env('REDIS_HOST', '127.0.0.1'),
          'password' => env('REDIS_PASSWORD', null),
          'port'     => env('REDIS_PORT', 6379),
          'database' => 1,
        ],

    ],

];
