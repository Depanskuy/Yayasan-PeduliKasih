<?php
// config/config.php
// Konfigurasi aplikasi dan helper pembaca environment (.env)

if (!function_exists('load_env')) {
    function load_env($filePath) {
        if (!file_exists($filePath)) {
            return;
        }
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                // Strip quotes
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }
                if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                    putenv("{$key}={$value}");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
    }
}

// Load .env dari root proyek
load_env(dirname(__DIR__) . '/.env');

if (!function_exists('env')) {
    function env($key, $default = null) {
        $val = getenv($key);
        if ($val === false) {
            $val = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        }
        if ($val === 'true' || $val === '(true)') return true;
        if ($val === 'false' || $val === '(false)') return false;
        if ($val === 'null' || $val === '(null)') return null;
        if ($val === 'empty' || $val === '(empty)') return '';
        return $val;
    }
}

$mysqlUrl = env('MYSQL_URL', env('DATABASE_URL'));
$dbHost = env('DB_HOST', env('MYSQLHOST', '127.0.0.1'));
$dbPort = env('DB_PORT', env('MYSQLPORT', '3306'));
$dbName = env('DB_DATABASE', env('MYSQLDATABASE', 'yayasan_pedulikasih'));
$dbUser = env('DB_USERNAME', env('MYSQLUSER', 'root'));
$dbPass = env('DB_PASSWORD', env('MYSQLPASSWORD', ''));

if ($mysqlUrl && is_string($mysqlUrl)) {
    $parsed = parse_url($mysqlUrl);
    if ($parsed && isset($parsed['host'])) {
        $dbHost = $parsed['host'];
        if (isset($parsed['port'])) $dbPort = (string)$parsed['port'];
        if (isset($parsed['user'])) $dbUser = $parsed['user'];
        if (isset($parsed['pass'])) $dbPass = $parsed['pass'];
        if (isset($parsed['path'])) $dbName = ltrim($parsed['path'], '/');
    }
}

return [
    'app_name' => env('APP_NAME', 'Yayasan Peduli Kasih Sesama'),
    'app_url' => env('APP_URL', 'http://localhost:8000'),
    'app_env' => env('APP_ENV', 'local'),
    'debug' => env('APP_DEBUG', true),
    'db' => [
        'host' => $dbHost,
        'port' => $dbPort,
        'database' => $dbName,
        'username' => $dbUser,
        'password' => $dbPass,
    ]
];
