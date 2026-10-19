<?php
// app/Core/Helper.php
namespace App\Core;

class Helper
{
    /** Escape output HTML. */
    public static function e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    /** Format angka menjadi Rupiah. */
    public static function rupiah($amount, bool $short = false): string
    {
        $amount = (float) $amount;
        if ($short) {
            if ($amount >= 1_000_000_000) return 'Rp ' . number_format($amount / 1_000_000_000, 1, ',', '.') . ' M';
            if ($amount >= 1_000_000)     return 'Rp ' . number_format($amount / 1_000_000, 1, ',', '.') . ' jt';
            if ($amount >= 1_000)         return 'Rp ' . number_format($amount / 1_000, 0, ',', '.') . ' rb';
        }
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /** Persentase terkumpul dari target. */
    public static function percent($collected, $target): int
    {
        $target = (float) $target;
        if ($target <= 0) return 0;
        return (int) min(100, round(((float) $collected / $target) * 100));
    }

    /** Format tanggal Indonesia. */
    public static function tanggal($date, bool $withTime = false): string
    {
        if (empty($date) || $date === '0000-00-00') return '-';
        $ts = is_numeric($date) ? (int) $date : strtotime((string) $date);
        if (!$ts) return '-';
        $bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $out = date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
        if ($withTime) $out .= ' ' . date('H:i', $ts);
        return $out;
    }

    /** Teks relatif sederhana ("2 hari lalu"). */
    public static function timeAgo($date): string
    {
        if (empty($date)) return '-';
        $ts = strtotime((string) $date);
        if (!$ts) return '-';
        $diff = time() - $ts;
        if ($diff < 60) return 'baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 2592000) return floor($diff / 86400) . ' hari lalu';
        if ($diff < 31536000) return floor($diff / 2592000) . ' bulan lalu';
        return floor($diff / 31536000) . ' tahun lalu';
    }

    /** Inisial dari nama. */
    public static function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name));
        $out = '';
        foreach (array_slice($parts, 0, 2) as $p) {
            $out .= mb_strtoupper(mb_substr($p, 0, 1));
        }
        return $out ?: '?';
    }

    /* ---------------- Flash messages ---------------- */

    public static function setFlash(string $type, string $message): void
    {
        self::ensureSession();
        $_SESSION['_flash'][$type] = $message;
    }

    public static function getFlash(string $type): ?string
    {
        self::ensureSession();
        if (!empty($_SESSION['_flash'][$type])) {
            $msg = $_SESSION['_flash'][$type];
            unset($_SESSION['_flash'][$type]);
            return $msg;
        }
        return null;
    }

    /** Kompatibilitas: Helper::flash('success') mengambil lalu menghapus. */
    public static function flash(string $type): ?string
    {
        return self::getFlash($type);
    }

    public static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ---------------- CSRF ---------------- */

    public static function csrfToken(): string
    {
        self::ensureSession();
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function csrfField(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::csrfToken() . '">';
    }

    public static function verifyCsrf(?string $token): bool
    {
        self::ensureSession();
        return !empty($_SESSION['_csrf'])
            && is_string($token)
            && hash_equals($_SESSION['_csrf'], $token);
    }

    /* ---------------- URL ---------------- */

    /** URL absolut relatif terhadap base aplikasi. */
    public static function url(string $path = ''): string
    {
        $base = defined('BASE_URL') ? BASE_URL : '';
        return $base . '/' . ltrim($path, '/');
    }

    /** URL aset publik (css/js/img/uploads). */
    public static function asset(string $path): string
    {
        return self::url($path);
    }

    public static function isActive(string $prefix): bool
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $basePath = defined('BASE_PATH') ? BASE_PATH : '';
        if ($basePath !== '' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = trim($uri, '/');
        $prefix = trim($prefix, '/');
        return $prefix === '' ? $uri === '' : ($uri === $prefix || str_starts_with($uri, $prefix . '/'));
    }

    /* ---------------- Misc ---------------- */

    public static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        return $text ?: 'n-a';
    }

    public static function excerpt(string $text, int $limit = 120): string
    {
        $text = trim(strip_tags($text));
        if (mb_strlen($text) <= $limit) return $text;
        return mb_substr($text, 0, $limit) . '…';
    }

    /** Izinkan tag HTML aman untuk konten artikel (ditulis admin). */
    public static function safeHtml(?string $html): string
    {
        $allowed = '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><h4><blockquote>';
        return strip_tags((string) $html, $allowed);
    }

    public static function old(string $key, $default = '')
    {
        $old = $_SESSION['_old'][$key] ?? $default;
        return $old;
    }

    public static function setOld(array $data): void
    {
        self::ensureSession();
        unset($data['_csrf'], $data['password'], $data['password_confirm']);
        $_SESSION['_old'] = $data;
    }

    public static function clearOld(): void
    {
        unset($_SESSION['_old']);
    }

    /* ---------------- Upload ---------------- */

    /**
     * Simpan file upload ke public/uploads/<subdir>.
     * @return string|null Nama file tersimpan, atau null bila tidak ada/gagal.
     */
    public static function upload(?array $file, string $subdir, array $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf']): ?string
    {
        if (empty($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            return null;
        }
        // Batas 5 MB
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            return null;
        }

        $dir = __DIR__ . '/../../public/uploads/' . trim($subdir, '/');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) {
            return null;
        }
        return $filename;
    }

    /** URL publik untuk file upload. */
    public static function uploadUrl(?string $filename, string $subdir, string $fallback = ''): string
    {
        if (empty($filename)) {
            return $fallback;
        }
        return self::url('uploads/' . trim($subdir, '/') . '/' . $filename);
    }

    /** URL media bila file benar-benar ada, selain itu null (untuk fallback placeholder). */
    public static function media(?string $filename, string $subdir): ?string
    {
        if (empty($filename)) {
            return null;
        }
        $rel  = 'uploads/' . trim($subdir, '/') . '/' . $filename;
        $path = __DIR__ . '/../../public/' . $rel;
        return is_file($path) ? self::url($rel) : null;
    }
}
