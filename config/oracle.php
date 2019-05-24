<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS_TIBS', ''),
        'host'           => env('DB_HOST_TIBS', ''),
        'port'           => env('DB_PORT_TIBS', '1521'),
        'database'       => env('DB_DATABASE_TIBS', ''),
        'username'       => env('DB_USERNAME_TIBS', ''),
        'password'       => env('DB_PASSWORD_TIBS', ''),
        'charset'        => env('DB_CHARSET_TIBS', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX_TIBS', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX_TIBS', ''),
        //'edition'        => env('DB_EDITION', 'ora$base'),
        //'server_version' => env('DB_SERVER_VERSION', '10g'),
    ],
];
