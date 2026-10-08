<?php
// app/Helpers/helpers.php
// Helper functions untuk view, routing, format data, dan keamanan

use App\Core\Security;

if (!function_exists('base_path_url')) {
    function base_path_url(): string {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $base = str_replace('\\', '/', dirname($scriptName));
        return ($base === '/' || $base === '.') ? '' : rtrim($base, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $base = base_path_url();
        $path = ltrim($path, '/');
        return $path === '' ? ($base === '' ? '/' : $base) : "{$base}/{$path}";
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        return url($path);
    }
}

if (!function_exists('e')) {
    function e(?string $value): string {
        return Security::escape($value);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        return Security::generateCsrfToken();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        $token = csrf_token();
        return '<input type="hidden" name="_csrf_token" value="' . $token . '">';
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah($amount, bool $withPrefix = true): string {
        $formatted = number_format((float)$amount, 0, ',', '.');
        return $withPrefix ? "Rp {$formatted}" : $formatted;
    }
}

if (!function_exists('format_date')) {
    function format_date(?string $date, bool $withTime = false): string {
        if (!$date) return '-';
        $timestamp = strtotime($date);
        if (!$timestamp) return '-';

        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $d = date('d', $timestamp);
        $m = $months[(int)date('m', $timestamp)];
        $y = date('Y', $timestamp);

        if ($withTime) {
            $time = date('H:i', $timestamp);
            return "{$d} {$m} {$y}, {$time} WIB";
        }
        return "{$d} {$m} {$y}";
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array {
        Security::startSession();
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool {
        return auth_user() !== null;
    }
}

if (!function_exists('has_role')) {
    function has_role(array|string $roles): bool {
        $user = auth_user();
        if (!$user) return false;
        $roles = (array)$roles;
        return in_array($user['role'], $roles, true);
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '') {
        Security::startSession();
        $val = $_SESSION['old_input'][$key] ?? $default;
        return e($val);
    }
}

if (!function_exists('flash_get')) {
    function flash_get(string $key) {
        Security::startSession();
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}
