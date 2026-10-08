<?php
// app/Core/AuditLogger.php
namespace App\Core;

class AuditLogger {
    public static function log(string $action, string $description, ?int $userId = null): void {
        try {
            $pdo = Database::getInstance();
            if ($userId === null && isset($_SESSION['user']['id'])) {
                $userId = (int)$_SESSION['user']['id'];
            }

            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip = trim($parts[0]);
            }

            $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Agent', 0, 255);

            $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, description, ip_address, user_agent, created_at) VALUES (:user_id, :action, :description, :ip, :ua, NOW())");
            $stmt->execute([
                ':user_id' => $userId,
                ':action' => $action,
                ':description' => $description,
                ':ip' => $ip,
                ':ua' => $userAgent
            ]);
        } catch (\Throwable $e) {
            // Silently ignore log errors to avoid halting main request
            error_log("AuditLog error: " . $e->getMessage());
        }
    }
}
