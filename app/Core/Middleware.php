<?php
// app/Core/Middleware.php
namespace App\Core;

class Middleware
{
    /** Pastikan user sudah login. */
    public static function auth(): void
    {
        Helper::ensureSession();
        if (empty($_SESSION['user_id'])) {
            Helper::setFlash('error', 'Silakan masuk terlebih dahulu.');
            $next = $_SERVER['REQUEST_URI'] ?? '';
            $target = 'login' . ($next && $next !== '/login' ? '?next=' . urlencode($next) : '');
            header('Location: ' . Helper::url($target));
            exit;
        }
    }

    /** Pastikan user BELUM login (halaman login/register). */
    public static function guest(): void
    {
        Helper::ensureSession();
        if (!empty($_SESSION['user_id'])) {
            $role = $_SESSION['user_role'] ?? '';
            $target = in_array($role, ['superadmin', 'staff'], true) ? 'admin/dashboard' : '';
            header('Location: ' . Helper::url($target));
            exit;
        }
    }

    /** Pastikan user memiliki salah satu role. */
    public static function role(array $allowedRoles): void
    {
        Helper::ensureSession();
        $role = $_SESSION['user_role'] ?? null;
        if (!in_array($role, $allowedRoles, true)) {
            http_response_code(403);
            echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>403</title></head><body style="font-family:sans-serif;background:#F8FBF9;color:#10231C;display:flex;align-items:center;justify-content:center;height:100vh;margin:0"><div style="text-align:center"><h1 style="font-size:3rem;margin:0">403</h1><p style="color:#5E756B">Akses ditolak – hak akses tidak cukup.</p><a href="' . Helper::url('') . '" style="color:#059669">Kembali ke beranda</a></div></body></html>';
            exit;
        }
    }
}
