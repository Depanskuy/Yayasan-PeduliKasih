<?php
// app/Core/Model.php
namespace App\Core;

use PDO;
use PDOStatement;

class Model {
    protected PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function fetchColumn(string $sql, array $params = [], int $column = 0) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn($column);
    }

    public function insert(string $table, array $data): int {
        $keys = array_keys($data);
        $columns = implode(', ', array_map(fn($k) => "`{$k}`", $keys));
        $placeholders = implode(', ', array_map(fn($k) => ":{$k}", $keys));

        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $key => $val) {
            $stmt->bindValue(":{$key}", $val);
        }
        $stmt->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update(string $table, int|string $id, array $data, string $idCol = 'id'): bool {
        $setClauses = [];
        foreach (array_keys($data) as $key) {
            $setClauses[] = "`{$key}` = :{$key}";
        }
        $sql = "UPDATE `{$table}` SET " . implode(', ', $setClauses) . " WHERE `{$idCol}` = :__id";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $key => $val) {
            $stmt->bindValue(":{$key}", $val);
        }
        $stmt->bindValue(':__id', $id);
        return $stmt->execute();
    }

    public function delete(string $table, int|string $id, string $idCol = 'id'): bool {
        $stmt = $this->pdo->prepare("DELETE FROM `{$table}` WHERE `{$idCol}` = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function count(string $table, string $where = '', array $params = []): int {
        $sql = "SELECT COUNT(*) FROM `{$table}`" . ($where ? " WHERE {$where}" : "");
        $stmt = $this->query($sql, $params);
        return (int)$stmt->fetchColumn();
    }

    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool {
        return $this->pdo->commit();
    }

    public function rollBack(): bool {
        return $this->pdo->rollBack();
    }
}
