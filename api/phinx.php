<?php

require __DIR__ . '/config/database.php';
// Create database if it doesn't exist
try {
    $pdo = new PDO(DRIVER . ":host=" . SERVER, USER, PASSWORD);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DATABASE);
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'quickbuy',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ]
];