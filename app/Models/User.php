<?php
// app/Models/User.php
namespace App\Models;

use App\Core\Model;

class User extends Model {
    public function findByEmail(string $email) {
        $stmt = $this->query('SELECT * FROM users WHERE email = ?', [$email]);
        return $stmt->fetch();
    }

    public function create(array $data) {
        $sql = "INSERT INTO users (name, email, password, phone, role, status) VALUES (:name, :email, :password, :phone, :role, :status)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':phone' => $data['phone'] ?? null,
            ':role' => $data['role'] ?? 'donatur',
            ':status' => 'active',
        ]);
        return $this->pdo->lastInsertId();
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
?>
