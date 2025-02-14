<?php

// Essential stuff for database connection
date_default_timezone_set("Asia/Manila");
set_time_limit(1000);

// Simple ENV Loader
// $_ENV = array_merge($_ENV, parse_ini_file(__DIR__ . '/../.env'));

//Constants for Database Connection
define("SERVER", "localhost");
define("DATABASE", "quickbuy");
define("USER", "root");
define("PASSWORD", "");
define("DRIVER", "mysql");


// Naggawa tayo ng class for database connections with the metho "connect()"
class Connection
{
    private $connectionString = DRIVER . ":host=" . SERVER . ";dbname=" . DATABASE . "; charset=utf8mb4";
    private $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false
    ];


    public function connect()
    {
        return new \PDO($this->connectionString, USER, PASSWORD, $this->options);
    }
}

?>