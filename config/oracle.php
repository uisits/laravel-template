<?php

return [
    'oracle_cdm_pvt' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS_3', ''),
        'host'           => env('DB_HOST_3', ''),
        'port'           => env('DB_PORT_3', '1521'),
        'database'       => env('DB_DATABASE_3', ''),
        'username'       => env('DB_USERNAME_3', ''),
        'password'       => env('DB_PASSWORD_3', ''),
        'charset'        => env('DB_CHARSET_3', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX_3', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX_3', ''),
        'server_version' => env('DB_SERVER_VERSION_3', '11g'),
    ],

    'oracle_cdm' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS_4', ''),
        'host'           => env('DB_HOST_4', ''),
        'port'           => env('DB_PORT_4', '1521'),
        'database'       => env('DB_DATABASE_4', ''),
        'username'       => env('DB_USERNAME_4', ''),
        'password'       => env('DB_PASSWORD_4', ''),
        'charset'        => env('DB_CHARSET_4', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX_4', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX_4', ''),
        'server_version' => env('DB_SERVER_VERSION_4', '11g'),
    ],

    'course-evals' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS_5', ''),
        'host'           => env('DB_HOST_5', ''),
        'port'           => env('DB_PORT_5', '1521'),
        'database'       => env('DB_DATABASE_5', ''),
        'username'       => env('DB_USERNAME_5', ''),
        'password'       => env('DB_PASSWORD_5', ''),
        'charset'        => env('DB_CHARSET_5', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX_5', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX_5', ''),
        'server_version' => env('DB_SERVER_VERSION_5', '11g'),
    ],

    'reporting' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS_6', ''),
        'host'           => env('DB_HOST_6', ''),
        'port'           => env('DB_PORT_6', '1521'),
        'database'       => env('DB_DATABASE_6', ''),
        'username'       => env('DB_USERNAME_6', ''),
        'password'       => env('DB_PASSWORD_6', ''),
        'charset'        => env('DB_CHARSET_6', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX_6', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX_6', ''),
        'server_version' => env('DB_SERVER_VERSION_6', '11g'),
    ],
];
