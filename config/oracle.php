<?php

return [
    'oracle' => [
        'driver' => 'oracle',
        'host' => env('DB_HOST'),
        'port' => env('DB_PORT'),
        'database' => '',
        'service_name' => env('DB_SERVICE'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'charset' => '',
        'prefix' => '',
    ],

    'cim_tivoli' => [
        'driver' => 'oracle',
        'host' => env('TIVOLI_DB_HOST'),
        'port' => env('DB_PORT'),
        'database' => '',
        'service_name' => env('TIVOLI_DB_SERVICE'),
        'username' => env('TIVOLI_DB_USERNAME'),
        'password' => env('TIVOLI_DB_PASSWORD'),
        'charset' => '',
        'prefix' => '',
    ],

    'old_system_oracle' => [
        'driver' => 'oracle',
        'host' => env('DB_OLD_SYSTEM_HOST'),
        'port' => env('DB_OLD_SYSTEM_PORT'),
        'database' => '',
        'service_name' => env('DB_OLD_SYSTEM_SERVICE'),
        'username' => env('DB_OLD_SYSTEM_USERNAME'),
        'password' => env('DB_OLD_SYSTEM_PASSWORD'),
        'charset' => '',
        'prefix' => '',
    ],

    'mass_market_commissions' => [
        'driver' => 'oracle',
        'host' => env('DB_MMC_HOST'),
        'port' => env('DB_MMC_PORT'),
        'database' => '',
        'service_name' => env('DB_MMC_SERVICE'),
        'username' => env('DB_MMC_USERNAME'),
        'password' => env('DB_MMC_PASSWORD'),
        'charset' => '',
        'prefix' => '',
    ],
];
