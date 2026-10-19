<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Core\Middleware;
use App\Models\AuditLog;
use App\Models\User;

class AuthController extends Controller
{
    protected string $layout = 'public';

    public function showLogin(): void
    {
        $this->render('auth/login', ['title' => 'Masuk']);
    }

    public function login(): void
    {
        $this->verifyCsrfOrFail('login');

        $email    = strtolower((string) $this->input('email'));
        $password = (string) ($_POST['password'] ?? '');

        $errors = [];
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email tidak valid.';
        }
        if ($password === '') {
            $errors[] = 'Password wajib diisi.';
        }
        if ($errors) {
            Helper::setFlash('error', implode(' ', $errors));
            Helper::setOld(['email' => $email]);
            $this->redirect('login');
        }

        $user = (new User())->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            AuditLog::record(null, 'LOGIN_FAILED', "Percobaan login gagal untuk email {$email}");
            Helper::setFlash('error', 'Email atau password salah.');
            Helper::setOld(['email' => $email]);
            $this->redirect('login');
        }
        if (($user['status'] ?? 'active') !== 'active') {
            Helper::setFlash('error', 'Akun Anda tidak aktif. Hubungi administrator.');
            $this->redirect('login');
        }

        Helper::ensureSession();
        session_regenerate_id(true);
        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];

        AuditLog::record((int) $user['id'], 'LOGIN', 'Login berhasil sebagai ' . $user['role']);
        Helper::clearOld();
        Helper::setFlash('success', 'Selamat datang, ' . $user['name'] . '!');

        if (in_array($user['role'], ['superadmin', 'staff'], true)) {
            $this->redirect('admin/dashboard');
        }
        $this->redirect('');
    }

    public function showRegister(): void
    {
        $this->render('auth/register', ['title' => 'Daftar']);
    }

    public function register(): void
    {
        $this->verifyCsrfOrFail('register');

        $name     = (string) $this->input('name');
        $email    = strtolower((string) $this->input('email'));
        $phone    = (string) $this->input('phone');
        $password = (string) ($_POST['password'] ?? '');
        $confirm  = (string) ($_POST['password_confirm'] ?? '');

        $errors = [];
        if (mb_strlen($name) < 3) {
            $errors[] = 'Nama minimal 3 karakter.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email tidak valid.';
        }
        if (mb_strlen($password) < 6) {
            $errors[] = 'Password minimal 6 karakter.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }

        $userModel = new User();
        if (!$errors && $userModel->findByEmail($email)) {
            $errors[] = 'Email sudah terdaftar. Silakan masuk.';
        }

        if ($errors) {
            Helper::setFlash('error', implode(' ', $errors));
            Helper::setOld(compact('name', 'email', 'phone'));
            $this->redirect('register');
        }

        $id = $userModel->create([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => 'donatur',
        ]);

        $user = $userModel->find($id);
        Helper::ensureSession();
        session_regenerate_id(true);
        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];

        AuditLog::record((int) $user['id'], 'REGISTER', 'Registrasi akun baru sebagai ' . $user['role']);
        Helper::clearOld();
        Helper::setFlash('success', 'Pendaftaran berhasil. Selamat bergabung!');
        $this->redirect('');
    }

    public function logout(): void
    {
        Helper::ensureSession();
        $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
        if ($userId) {
            AuditLog::record($userId, 'LOGOUT', 'Keluar dari aplikasi');
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        Helper::ensureSession();
        Helper::setFlash('success', 'Anda telah keluar.');
        $this->redirect('');
    }
}
