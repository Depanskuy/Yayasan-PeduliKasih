<?php
// app/Core/Model.php
namespace App\Core;

class Model {
    /** @var \PDO */
    protected $pdo;

    public function __construct() {
        // Load PDO instance from config (singleton pattern)
        $this->pdo = require __DIR__ . '/../../config/database.php';
    }

    protected function query(string $sql, array $params = []): \PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
?>
