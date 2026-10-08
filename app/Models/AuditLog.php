<?php
// app/Models/AuditLog.php
namespace App\Models;

use App\Core\Model;

class AuditLog extends Model {
    protected string $table = 'audit_logs';

    public function getAll(int $limit = 50): array {
        $sql = "SELECT al.*, u.name AS user_name, u.role AS user_role, u.email AS user_email 
                FROM audit_logs al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.id DESC LIMIT " . (int)$limit;
        return $this->fetchAll($sql);
    }
}
