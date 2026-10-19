<?php
// scripts/set_passwords.php
// Jalankan: php scripts/set_passwords.php
// Berguna bila database sudah terlanjur diimpor dengan hash password placeholder.

require __DIR__ . '/../vendor/autoload.php';

try {
    $pdo = require __DIR__ . '/../config/database.php';
} catch (Throwable $e) {
    fwrite(STDERR, "Gagal terhubung ke database: {$e->getMessage()}\n");
    exit(1);
}

$password = $argv[1] ?? 'password123';
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare('UPDATE users SET password = ?');
$stmt->execute([$hash]);

echo "Berhasil memperbarui {$stmt->rowCount()} akun.\n";
echo "Password baru untuk semua akun demo: {$password}\n";
echo "Hash: {$hash}\n";
