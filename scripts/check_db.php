<?php
// scripts/check_db.php — verifikasi koneksi & tabel.
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=yayasan_pedulikasih;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo "DB_OK tables=" . count($tables) . "\n";
    echo "LIST=" . implode(',', $tables) . "\n";
    if (in_array('users', $tables, true)) {
        echo "USERS=" . $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() . "\n";
        echo "CAMPAIGNS=" . $pdo->query('SELECT COUNT(*) FROM campaigns')->fetchColumn() . "\n";
        echo "DONATIONS=" . $pdo->query('SELECT COUNT(*) FROM donations')->fetchColumn() . "\n";
    }
} catch (Throwable $e) {
    echo "DB_FAIL: " . $e->getMessage() . "\n";
}
