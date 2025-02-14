<?php

require __DIR__ . '/config/database.php';

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => DRIVER,
            'host' => SERVER,
            'name' => DATABASE,
            'user' => USER,
            'pass' => PASSWORD,
            'port' => '3306',
            'charset' => 'utf8mb4',
        ]
    ]
];