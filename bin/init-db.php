<?php
// bin/init-db.php
// Script otomatis inisialisasi tabel dan data awal database jika database masih kosong

require_once __DIR__ . '/../config/config.php';

echo "[DB-INIT] Memeriksa status database...\n";

try {
    $pdo = require __DIR__ . '/../config/database.php';
    if (!$pdo) {
        echo "[DB-INIT] Koneksi database belum tersedia. Melewati auto-import.\n";
        exit(0);
    }

    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $exists = $stmt->fetch();

    if (!$exists) {
        echo "[DB-INIT] Tabel belum ditemukan. Mengimpor database.sql ke MySQL...\n";
        $sqlPath = __DIR__ . '/../database.sql';
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            // Split dan eksekusi query
            $pdo->exec($sql);
            echo "[DB-INIT] Berhasil mengimpor database.sql!\n";
        } else {
            echo "[DB-INIT] File database.sql tidak ditemukan di: {$sqlPath}\n";
        }
    } else {
        echo "[DB-INIT] Database sudah terisi (tabel 'users' sudah ada). Siap digunakan.\n";
    }
} catch (\Throwable $e) {
    echo "[DB-INIT] Info / Peringatan: " . $e->getMessage() . "\n";
}
