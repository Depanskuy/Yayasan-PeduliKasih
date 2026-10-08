<?php
// app/Core/Security.php
namespace App\Core;

class Security {
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            // Set secure cookie params if possible
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            session_start();
        }
    }

    public static function generateCsrfToken(): string {
        self::startSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCsrfToken(?string $token): bool {
        self::startSession();
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function escape(?string $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public static function validateUpload(array $file, array $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'], int $maxSizeBytes = 5242880): array {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => 'Gagal mengunggah berkas. Kode error: ' . $file['error']];
        }

        if ($file['size'] > $maxSizeBytes) {
            $mb = round($maxSizeBytes / 1048576, 1);
            return ['valid' => false, 'error' => "Ukuran berkas melebihi batas maksimal ({$mb}MB)"];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes)) {
            return ['valid' => false, 'error' => 'Format berkas tidak diizinkan. Hanya menerima: ' . implode(', ', $allowedMimes)];
        }

        // Check file extension as second layer
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
        if (!in_array($ext, $allowedExts)) {
            return ['valid' => false, 'error' => 'Ekstensi berkas tidak valid.'];
        }

        return ['valid' => true, 'mime' => $mime, 'extension' => $ext];
    }
}
