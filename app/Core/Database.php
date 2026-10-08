<?php
// app/Core/Database.php
namespace App\Core;

use PDO;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::$instance = require dirname(__DIR__, 2) . '/config/database.php';
        }
        return self::$instance;
    }
}
