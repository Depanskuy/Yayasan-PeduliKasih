<?php
// scripts/router.php — router untuk PHP built-in server (testing lokal).
// Jalankan: php -S 127.0.0.1:8000 -t public scripts/router.php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$public = dirname(__DIR__) . '/public';
$file = $public . $path;

// Sajikan aset statis apa adanya.
if ($path !== '/' && is_file($file)) {
    return false;
}

// Normalisasi SCRIPT_NAME agar deteksi base path konsisten.
$_SERVER['SCRIPT_NAME'] = '/index.php';
require $public . '/index.php';
