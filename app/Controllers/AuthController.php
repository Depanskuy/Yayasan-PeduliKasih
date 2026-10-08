<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Models\User;

class AuthController extends Controller {
    protected User $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function showLogin(): void {
        $this->render('auth/login', [
            'title' => 'Masuk - Yayasan Peduli Kasih Sesama'
        ], 'auth');
    }

    public function login(): void {
        $data = $this->validate($_POST, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = $this->userModel->findByEmail($data['email']);
        if (!$user || !Security::verifyPassword($data['password'], $user['password'])) {
            $this->redirect(url('login'), [
                'error' => 'Email atau kata sandi tidak cocok.',
                'old_email' => $data['email']
            ]);
        }

        if ($user['status'] !== 'active') {
            $this->redirect(url('login'), [
                'error' => 'Akun Anda dinonaktifkan. Silakan hubungi pengurus yayasan.'
            ]);
        }

        // Set session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'phone' => $user['phone'],
            'avatar' => $user['avatar'] ?? null,
        ];

        $this->audit('LOGIN', "Pengguna {$user['name']} ({$user['role']}) berhasil login");

        // Redirect based on role
        if (in_array($user['role'], ['superadmin', 'staff'])) {
            $this->redirect(url('admin/dashboard'), ['success' => "Selamat datang kembali, {$user['name']}!"]);
        } elseif ($user['role'] === 'volunteer') {
            $this->redirect(url('volunteer/dashboard'), ['success' => "Selamat datang kembali di portal relawan, {$user['name']}!"]);
        } else {
            $this->redirect(url('donor/dashboard'), ['success' => "Selamat datang di ruang donatur, {$user['name']}!"]);
        }
    }

    public function showRegister(): void {
        $this->render('auth/register', [
            'title' => 'Daftar Akun - Yayasan Peduli Kasih Sesama'
        ], 'auth');
    }

    public function register(): void {
        $data = $this->validate($_POST, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'phone' => 'required',
            'role' => 'required'
        ]);

        if (!in_array($data['role'], ['donatur', 'volunteer'])) {
            $data['role'] = 'donatur';
        }

        $existing = $this->userModel->findByEmail($data['email']);
        if ($existing) {
            $this->redirect(url('register'), [
                'error' => 'Alamat email sudah terdaftar. Silakan login atau gunakan email lain.'
            ]);
        }

        $userId = $this->userModel->create($data);
        $user = $this->userModel->findById($userId);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'phone' => $user['phone'],
            'avatar' => null,
        ];

        $this->audit('REGISTER', "Pendaftaran akun baru: {$user['name']} sebagai {$user['role']}");

        if ($user['role'] === 'volunteer') {
            $this->redirect(url('volunteer/dashboard'), ['success' => 'Pendaftaran akun relawan berhasil! Selamat bergabung.']);
        } else {
            $this->redirect(url('donor/dashboard'), ['success' => 'Pendaftaran akun donatur berhasil! Selamat bergabung.']);
        }
    }

    public function logout(): void {
        $user = auth_user();
        if ($user) {
            $this->audit('LOGOUT', "Pengguna {$user['name']} logout dari sistem");
        }
        unset($_SESSION['user']);
        session_destroy();
        Security::startSession();
        $this->redirect(url('/'), ['success' => 'Anda telah berhasil keluar dari sistem.']);
    }

    public function profile(): void {
        $currentUser = auth_user();
        if (!$currentUser) {
            $this->redirect(url('login'));
        }
        $user = $this->userModel->findById((int)$currentUser['id']);
        $layout = in_array($user['role'], ['superadmin', 'staff']) ? 'dashboard' : 'main';

        $this->render('users/profile', [
            'title' => 'Profil Saya - Yayasan Peduli Kasih Sesama',
            'user' => $user
        ], $layout);
    }

    public function updateProfile(): void {
        $currentUser = auth_user();
        if (!$currentUser) {
            $this->redirect(url('login'));
        }

        $data = $this->validate($_POST, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $_POST['address'] ?? null,
            'bio' => $_POST['bio'] ?? null,
        ];

        if (!empty($_POST['new_password'])) {
            if (strlen($_POST['new_password']) < 6) {
                $this->redirect(url('profile'), ['error' => 'Kata sandi baru minimal 6 karakter.']);
            }
            $updateData['password'] = $_POST['new_password'];
        }

        $this->userModel->updateUser((int)$currentUser['id'], $updateData);

        // Update session
        $_SESSION['user']['name'] = $data['name'];
        $_SESSION['user']['email'] = $data['email'];
        $_SESSION['user']['phone'] = $data['phone'];

        $this->audit('PROFILE_UPDATE', "Pembaruan profil mandiri oleh {$data['name']}");

        $this->redirect(url('profile'), ['success' => 'Profil berhasil diperbarui.']);
    }
}
