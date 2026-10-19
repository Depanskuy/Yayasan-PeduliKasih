<?php
// config/database.php
// Mengembalikan instance PDO tunggal (lihat App\Core\Model::connection).

$host    = getenv('DB_HOST') ?: '127.0.0.1';
$port    = getenv('DB_PORT') ?: '3306';
$name    = getenv('DB_NAME') ?: 'yayasan_pedulikasih';
$user    = getenv('DB_USER') ?: 'root';
$pass    = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_STRINGIFY_FETCHES  => false,
    ]);
    $pdo->exec("SET time_zone = '+07:00'");
    return $pdo;
} catch (PDOException $e) {
    http_response_code(500);
    $safeMessage = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Koneksi Database Gagal</title></head>'
        . '<body style="font-family:system-ui,sans-serif;background:#F8FBF9;color:#10231C;padding:3rem;line-height:1.6">'
        . '<h1 style="color:#EF4444">Koneksi database gagal</h1>'
        . '<p style="color:#5E756B">Pastikan MySQL/MariaDB Laragon berjalan dan database <code>yayasan_pedulikasih</code> sudah diimpor dari <code>database.sql</code>.</p>'
        . '<pre style="background:#EEF6F1;border:1px solid #D9E7E0;padding:1rem;border-radius:.75rem;overflow:auto;color:#B91C1C">' . $safeMessage . '</pre>'
        . '</body></html>';
    exit;
}
