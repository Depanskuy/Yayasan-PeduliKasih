<?php
// config/config.php
// Konfigurasi umum aplikasi.

return [
    'app_name' => 'Yayasan Peduli Kasih Sesama',
    'app_short' => 'Peduli Kasih Sesama',
    'app_tagline' => 'Menyalurkan Amanah, Merajut Kasih, Mencerahkan Masa Depan',
    // Base URL otomatis (mis. http://localhost:8080 atau /Project_PGAI/public)
    'base_url' => rtrim((function () {
        $https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        $scheme = $https ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        // Deteksi base path: ambil direktori dari SCRIPT_NAME (public/)
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $base   = rtrim(str_replace('\\', '/', dirname($script)), '/');
        // Jika dijalankan dari root public, dirname adalah /Project_PGAI/public
        return $scheme . '://' . $host . $base;
    })(), '/'),
    'timezone' => 'Asia/Jakarta',
    'currency_prefix' => 'Rp ',
];
