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
        'default_environment' => 'development',
        'development' => [
            'adapter' => DRIVER,
            'host' => SERVER,
            'name' => DATABASE,
            'user' => USER,
            'pass' => PASSWORD,
            'port' => '3306',
            'charset' => 'utf8mb4',
            'mysql_attr_init_command' => 'SET sql_mode = "STRICT_ALL_TABLES,NO_AUTO_VALUE_ON_ZERO"'
        ]
    ]
];