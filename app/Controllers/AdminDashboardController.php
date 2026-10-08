<?php
// app/Controllers/AdminDashboardController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Donation;
use App\Models\Disbursement;
use App\Models\Campaign;
use App\Models\Beneficiary;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Setting;

class AdminDashboardController extends Controller {
    protected Donation $donationModel;
    protected Disbursement $disbursementModel;
    protected Campaign $campaignModel;
    protected Beneficiary $beneficiaryModel;
    protected User $userModel;
    protected AuditLog $auditLogModel;
    protected Setting $settingModel;

    public function __construct() {
        parent::__construct();
        $this->donationModel = new Donation();
        $this->disbursementModel = new Disbursement();
        $this->campaignModel = new Campaign();
        $this->beneficiaryModel = new Beneficiary();
        $this->userModel = new User();
        $this->auditLogModel = new AuditLog();
        $this->settingModel = new Setting();
    }

    public function index(): void {
        $totalDonation = $this->donationModel->getTotalVerifiedAmount();
        $totalDisbursed = $this->disbursementModel->getTotalDisbursedAmount();
        $balance = $totalDonation - $totalDisbursed;

        $pendingDonations = $this->donationModel->getAllForAdmin('pending');
        $activeCampaigns = $this->campaignModel->count('campaigns', "status = 'active' AND deleted_at IS NULL");
        $totalBeneficiaries = $this->beneficiaryModel->count('beneficiaries', 'deleted_at IS NULL');
        $totalVolunteers = $this->userModel->count('users', "role = 'volunteer' AND deleted_at IS NULL");

        $recentLogs = $this->auditLogModel->getAll(8);
        $monthlyIncomes = $this->donationModel->getMonthlyStats();
        $monthlyExpenses = $this->disbursementModel->getMonthlyStats();

        $this->render('dashboard/index', [
            'title' => 'Dashboard Utama - Panel Pengurus Yayasan',
            'stats' => [
                'totalDonation' => $totalDonation,
                'totalDisbursed' => $totalDisbursed,
                'balance' => $balance,
                'pendingCount' => count($pendingDonations),
                'activeCampaigns' => $activeCampaigns,
                'totalBeneficiaries' => $totalBeneficiaries,
                'totalVolunteers' => $totalVolunteers
            ],
            'pendingDonations' => array_slice($pendingDonations, 0, 5),
            'recentLogs' => $recentLogs,
            'monthlyIncomes' => $monthlyIncomes,
            'monthlyExpenses' => $monthlyExpenses
        ], 'dashboard');
    }

    public function auditLogs(): void {
        $logs = $this->auditLogModel->getAll(100);
        $this->render('dashboard/audit_logs', [
            'title' => 'Audit Log & Rekam Aktivitas Sistem - Panel Admin',
            'logs' => $logs
        ], 'dashboard');
    }

    public function users(): void {
        $role = $_GET['role'] ?? null;
        $users = $this->userModel->getAllUsers($role);

        $this->render('users/index', [
            'title' => 'Manajemen Pengguna & Staf - Panel Admin',
            'users' => $users,
            'currentRole' => $role
        ], 'dashboard');
    }

    public function createUser(): void {
        $this->render('users/create', [
            'title' => 'Tambah Pengguna Baru'
        ], 'dashboard');
    }

    public function storeUser(): void {
        $data = $this->validate($_POST, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $existing = $this->userModel->findByEmail($data['email']);
        if ($existing) {
            $this->redirect(url('admin/users/create'), ['error' => 'Email telah digunakan oleh pengguna lain.']);
        }

        $id = $this->userModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $_POST['phone'] ?? null,
            'role' => $data['role'],
            'status' => $_POST['status'] ?? 'active',
            'address' => $_POST['address'] ?? null,
        ]);

        $this->audit('USER_CREATED', "Membuat akun pengguna baru: {$data['name']} ({$data['role']})");

        $this->redirect(url('admin/users'), ['success' => 'Akun pengguna berhasil ditambahkan!']);
    }

    public function settings(): void {
        $settings = $this->settingModel->getAllAsMap();
        $this->render('dashboard/settings', [
            'title' => 'Pengaturan Yayasan & Rekening Bank',
            'settings' => $settings
        ], 'dashboard');
    }

    public function updateSettings(): void {
        $fields = [
            'org_name', 'org_tagline', 'org_email', 'org_phone', 'org_address', 'org_legal',
            'bank_bca', 'bank_mandiri', 'bank_bri', 'qris_nmid',
            'social_instagram', 'social_facebook', 'social_youtube'
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $group = str_starts_with($field, 'bank_') || str_starts_with($field, 'qris_') ? 'payment' : (str_starts_with($field, 'social_') ? 'social' : 'general');
                $this->settingModel->set($field, trim($_POST[$field]), $group);
            }
        }

        $this->audit('SETTINGS_UPDATED', "Pengurus memperbarui pengaturan yayasan & konfigurasi rekening");

        $this->redirect(url('admin/settings'), ['success' => 'Pengaturan yayasan berhasil diperbarui!']);
    }
}
