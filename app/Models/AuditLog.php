<?php
// app/Models/AuditLog.php
namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    public function all(array $filters = []): array
    {
        $sql = 'SELECT a.*, u.name AS user_name, u.email AS user_email
                FROM audit_logs a
                LEFT JOIN users u ON u.id = a.user_id
                WHERE 1 = 1';
        $params = [];

        if (!empty($filters['action'])) {
            $sql .= ' AND a.action = ?';
            $params[] = $filters['action'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (a.description LIKE ? OR u.name LIKE ?)';
            $like = '%' . $filters['search'] . '%';
            array_push($params, $like, $like);
        }

        $sql .= ' ORDER BY a.created_at DESC';
        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ' . (int) $filters['limit'];
        }

        return $this->fetchAll($sql, $params);
    }

    public function recent(int $limit = 8): array
    {
        return $this->all(['limit' => $limit]);
    }

    public function actions(): array
    {
        return $this->fetchAll('SELECT DISTINCT action FROM audit_logs ORDER BY action ASC');
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM audit_logs');
    }

    /**
     * Catat aktivitas. Tidak boleh mengganggu alur utama bila gagal.
     */
    public static function record(?int $userId, string $action, string $description): void
    {
        try {
            $pdo = self::connection();
            $stmt = $pdo->prepare(
                'INSERT INTO audit_logs (user_id, action, description, ip_address, user_agent, created_at)
                 VALUES (?, ?, ?, ?, ?, NOW())'
            );
            $stmt->execute([
                $userId,
                substr($action, 0, 100),
                $description,
                $_SERVER['REMOTE_ADDR'] ?? null,
                substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 1000),
            ]);
        } catch (\Throwable $e) {
            // Diamkan: audit log tidak boleh menggagalkan aksi pengguna.
        }
    }
}
