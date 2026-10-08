<?php
// config/database.php
// Konfigurasi koneksi database PDO yang aman dan siap hosting

$config = require __DIR__ . '/config.php';
$db = $config['db'];

static $pdoInstance = null;

if ($pdoInstance === null) {
    $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']};charset=utf8mb4";
    try {
        $pdoInstance = new PDO(
            $dsn,
            $db['username'],
            $db['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]
        );
    } catch (PDOException $e) {
        if ($config['debug']) {
            die("<div style='font-family:sans-serif;padding:20px;background:#fee2e2;color:#991b1b;border-radius:8px;'>
                <h3>Gagal Terhubung ke Database MySQL</h3>
                <p><strong>Pesan:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                <p>Silakan periksa konfigurasi database di file <code>.env</code> Anda.</p>
            </div>");
        } else {
            die("Terjadi kesalahan sistem saat menghubungi database. Silakan hubungi administrator.");
        }
    }
}

return $pdoInstance;
