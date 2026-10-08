<?php
// app/Models/User.php
namespace App\Models;

use App\Core\Model;
use App\Core\Security;

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->fetch("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1", [
            ':email' => $email
        ]);
    }

    public function findById(int $id): ?array {
        return $this->fetch("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL LIMIT 1", [
            ':id' => $id
        ]);
    }

    public function getAllUsers(?string $role = null): array {
        $sql = "SELECT id, name, email, phone, role, status, created_at FROM users WHERE deleted_at IS NULL";
        $params = [];
        if ($role) {
            $sql .= " AND role = :role";
            $params[':role'] = $role;
        }
        $sql .= " ORDER BY id DESC";
        return $this->fetchAll($sql, $params);
    }

    public function create(array $data): int {
        return $this->insert($this->table, [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Security::hashPassword($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'] ?? 'donatur',
            'status' => $data['status'] ?? 'active',
            'address' => $data['address'] ?? null,
            'bio' => $data['bio'] ?? null,
        ]);
    }

    public function updateUser(int $id, array $data): bool {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'bio' => $data['bio'] ?? null,
        ];
        if (isset($data['role'])) {
            $updateData['role'] = $data['role'];
        }
        if (isset($data['status'])) {
            $updateData['status'] = $data['status'];
        }
        if (!empty($data['password'])) {
            $updateData['password'] = Security::hashPassword($data['password']);
        }
        return $this->update($this->table, $id, $updateData);
    }

    public function softDelete(int $id): bool {
        return $this->query("UPDATE users SET deleted_at = NOW() WHERE id = :id", [':id' => $id])->rowCount() > 0;
    }
}
