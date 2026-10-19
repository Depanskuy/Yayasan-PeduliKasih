<?php
// app/Core/Model.php
namespace App\Core;

use PDO;

class Model
{
    /** @var PDO|null Koneksi tunggal (singleton) */
    protected static ?PDO $connection = null;

    /** @var PDO */
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = self::connection();
    }

    /** Koneksi PDO bersama. */
    public static function connection(): PDO
    {
        if (self::$connection === null) {
            self::$connection = require __DIR__ . '/../../config/database.php';
        }
        return self::$connection;
    }

    protected function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $row = $this->query($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    protected function scalar(string $sql, array $params = [], $default = 0)
    {
        $value = $this->query($sql, $params)->fetchColumn();
        return $value === false || $value === null ? $default : $value;
    }
}
