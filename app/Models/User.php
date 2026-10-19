<?php
// app/Models/User.php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT id, name, email, phone, role, avatar, status, created_at
             FROM users WHERE deleted_at IS NULL ORDER BY created_at DESC'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE id = ? AND deleted_at IS NULL', [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ? AND deleted_at IS NULL', [$email]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (name, email, password, phone, role, bio, status, created_at)
                VALUES (:name, :email, :password, :phone, :role, :bio, :status, NOW())';
        $this->query($sql, [
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':password' => $data['password'],
            ':phone'    => $data['phone'] ?? null,
            ':role'     => $data['role'] ?? 'donatur',
            ':bio'      => $data['bio'] ?? null,
            ':status'   => 'active',
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateStatus(int $id, string $status): int
    {
        return $this->query(
            'UPDATE users SET status = :status, updated_at = NOW() WHERE id = :id',
            [':status' => $status, ':id' => $id]
        )->rowCount();
    }

    public function updateProfile(int $id, array $data): int
    {
        return $this->query(
            'UPDATE users SET name = :name, phone = :phone, address = :address, bio = :bio, updated_at = NOW()
             WHERE id = :id',
            [
                ':name' => $data['name'],
                ':phone' => $data['phone'] ?? null,
                ':address' => $data['address'] ?? null,
                ':bio' => $data['bio'] ?? null,
                ':id' => $id,
            ]
        )->rowCount();
    }

    public function countsByRole(): array
    {
        $rows = $this->fetchAll('SELECT role, COUNT(*) AS total FROM users WHERE deleted_at IS NULL GROUP BY role');
        $out = ['superadmin' => 0, 'staff' => 0, 'donatur' => 0, 'volunteer' => 0];
        foreach ($rows as $row) {
            $out[$row['role']] = (int) $row['total'];
        }
        return $out;
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM users WHERE deleted_at IS NULL');
    }
}
