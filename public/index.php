<?php
// public/index.php
// Front Controller Utama Aplikasi Yayasan Peduli Kasih Sesama

declare(strict_types=1);

// Load konfigurasi awal
$config = require dirname(__DIR__) . '/config/config.php';

if ($config['debug']) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Autoloader: Composer dengan fallback PSR-4 native
$composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
} else {
    spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $baseDir = dirname(__DIR__) . '/app/';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    });
}

// Helpers global
require_once dirname(__DIR__) . '/app/Helpers/helpers.php';

// Start session
\App\Core\Security::startSession();

// Load definisi route
require_once dirname(__DIR__) . '/routes/web.php';

// Dispatch routing
\App\Core\Router::dispatch();
