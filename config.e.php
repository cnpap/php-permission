<?php

return [
    'db' => [
        'admin' => [
            'database'  => 'database',
            'username'  => 'root',
            'password'  => 'password',
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'port'      => '3306',
            'collation' => 'utf8mb4_unicode_ci',
            'charset'   => 'utf8mb4',
            'prefix'    => ''
        ],
        'mall' => [
            'database'  => 'database',
            'username'  => 'root',
            'password'  => 'password',
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'port'      => '3306',
            'collation' => 'utf8mb4_unicode_ci',
            'charset'   => 'utf8mb4',
            'prefix'    => ''
        ]
    ],
    'app' => [
        'debug'   => true,
        'secret'  => 'custom secret',
        'expired' => 72000
    ],
    'flysystem' => [
        's3' => [
            'username' => '',
            'password' => '',
            'schema'   => '',
            'region'   => '',
            'version'  => 'latest'
        ]
    ]
];