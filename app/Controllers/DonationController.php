<?php
// app/Controllers/DonationController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\Setting;

class DonationController extends Controller {
    protected Donation $donationModel;
    protected Campaign $campaignModel;
    protected Setting $settingModel;

    public function __construct() {
        parent::__construct();
        $this->donationModel = new Donation();
        $this->campaignModel = new Campaign();
        $this->settingModel = new Setting();
    }

    // Public: Form Donasi
    public function create(string $campaignSlug): void {
        $campaign = $this->campaignModel->findBySlug($campaignSlug);
        if (!$campaign) {
            $this->redirect(url('campaigns'), ['error' => 'Campaign tidak ditemukan.']);
        }

        $user = auth_user();
        $settings = $this->settingModel->getAllAsMap();

        $this->render('donations/create', [
            'title' => 'Salurkan Donasi: ' . $campaign['title'],
            'campaign' => $campaign,
            'user' => $user,
            'settings' => $settings
        ]);
    }

    // Public: Simpan Donasi
    public function store(): void {
        $data = $this->validate($_POST, [
            'campaign_id' => 'required|numeric',
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required',
            'donor_name' => 'required',
            'donor_email' => 'required|email',
        ]);

        $campaign = $this->campaignModel->findById((int)$data['campaign_id']);
        if (!$campaign) {
            $this->redirect(url('campaigns'), ['error' => 'Campaign tidak valid.']);
        }

        $isAnonymous = isset($_POST['is_anonymous']) ? 1 : 0;
        $donationCode = $this->donationModel->generateDonationCode();
        $user = auth_user();

        $donationId = $this->donationModel->createDonation([
            'donation_code' => $donationCode,
            'campaign_id' => (int)$data['campaign_id'],
            'user_id' => $user ? (int)$user['id'] : null,
            'donor_name' => $isAnonymous ? 'Hamba Allah' : $data['donor_name'],
            'donor_email' => $data['donor_email'],
            'donor_phone' => $_POST['donor_phone'] ?? null,
            'amount' => (float)$data['amount'],
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'doa_message' => $_POST['doa_message'] ?? null,
            'is_anonymous' => $isAnonymous,
        ]);

        $this->audit('DONATION_INITIATED', "Donasi baru {$donationCode} senilai " . format_rupiah($data['amount']) . " dibuat untuk {$campaign['title']}");

        $this->redirect(url('donation/payment/' . $donationCode), [
            'success' => 'Terima kasih atas niat mulia Anda. Silakan selesaikan pembayaran donasi.'
        ]);
    }

    // Public: Instruksi Pembayaran & Upload Bukti Transfer
    public function payment(string $code): void {
        $donation = $this->donationModel->findByCode($code);
        if (!$donation) {
            $this->redirect(url('campaigns'), ['error' => 'Data donasi tidak ditemukan.']);
        }

        $settings = $this->settingModel->getAllAsMap();
        $this->render('donations/payment', [
            'title' => 'Instruksi Pembayaran Donasi #' . $donation['donation_code'],
            'donation' => $donation,
            'settings' => $settings
        ]);
    }

    // Public: Upload Bukti Transfer
    public function uploadProof(string $code): void {
        $donation = $this->donationModel->findByCode($code);
        if (!$donation) {
            $this->redirect(url('campaigns'), ['error' => 'Data donasi tidak ditemukan.']);
        }

        if (empty($_FILES['payment_proof']['name'])) {
            $this->redirect(url('donation/payment/' . $code), ['error' => 'Silakan pilih berkas foto bukti transfer.']);
        }

        $check = Security::validateUpload($_FILES['payment_proof'], ['image/jpeg', 'image/png', 'image/webp']);
        if (!$check['valid']) {
            $this->redirect(url('donation/payment/' . $code), ['error' => $check['error']]);
        }

        $filename = 'proof_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
        $dest = dirname(__DIR__, 2) . '/public/uploads/' . $filename;
        if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $dest)) {
            $this->redirect(url('donation/payment/' . $code), ['error' => 'Gagal menyimpan berkas ke server.']);
        }

        $this->donationModel->update('donations', (int)$donation['id'], [
            'payment_proof' => $filename
        ]);

        $this->audit('PAYMENT_PROOF_UPLOAD', "Bukti transfer diunggah untuk donasi {$donation['donation_code']}");

        $this->redirect(url('donation/payment/' . $code), [
            'success' => 'Bukti pembayaran berhasil diunggah! Tim keuangan kami akan segera memverifikasinya.'
        ]);
    }

    // Public: Kwitansi Resmi Donasi
    public function receipt(string $code): void {
        $donation = $this->donationModel->findByCode($code);
        if (!$donation) {
            $this->redirect(url('/'), ['error' => 'Data donasi tidak ditemukan.']);
        }

        $settings = $this->settingModel->getAllAsMap();
        $this->render('donations/receipt', [
            'title' => 'Kwitansi Donasi Resmi #' . $donation['donation_code'],
            'donation' => $donation,
            'settings' => $settings
        ], 'print');
    }

    // Public: E-Sertifikat Penghargaan Donatur
    public function certificate(string $code): void {
        $donation = $this->donationModel->findByCode($code);
        if (!$donation || $donation['payment_status'] !== 'verified') {
            $this->redirect(url('/'), ['error' => 'Sertifikat hanya dapat diunduh untuk donasi yang telah terverifikasi.']);
        }

        $settings = $this->settingModel->getAllAsMap();
        $this->render('donations/certificate', [
            'title' => 'E-Sertifikat Penghargaan Donatur #' . $donation['donation_code'],
            'donation' => $donation,
            'settings' => $settings
        ], 'print');
    }

    // Admin/Staff: Daftar Seluruh Donasi
    public function adminIndex(): void {
        $status = $_GET['status'] ?? null;
        $donations = $this->donationModel->getAllForAdmin($status);

        $this->render('donations/admin_index', [
            'title' => 'Manajemen Donasi & Verifikasi - Panel Admin',
            'donations' => $donations,
            'currentStatus' => $status
        ], 'dashboard');
    }

    // Admin/Staff: Verifikasi Donasi
    public function verify(int $id): void {
        $user = auth_user();
        $success = $this->donationModel->verifyDonation($id, (int)$user['id']);

        if ($success) {
            $donation = $this->donationModel->findById($id);
            $this->audit('DONATION_VERIFIED', "Donasi #{$donation['donation_code']} senilai " . format_rupiah($donation['amount']) . " telah diverifikasi oleh {$user['name']}");
            $this->redirect(url('admin/donations'), ['success' => "Donasi #{$donation['donation_code']} berhasil diverifikasi dan masuk laporan kas!"]);
        } else {
            $this->redirect(url('admin/donations'), ['error' => 'Gagal memverifikasi donasi. Periksa status donasi saat ini.']);
        }
    }

    // Admin/Staff: Tolak Donasi
    public function reject(int $id): void {
        $user = auth_user();
        $reason = trim($_POST['rejection_reason'] ?? 'Bukti transfer tidak valid atau dana tidak masuk.');

        $this->donationModel->rejectDonation($id, (int)$user['id'], $reason);
        $donation = $this->donationModel->findById($id);

        $this->audit('DONATION_REJECTED', "Donasi #{$donation['donation_code']} ditolak oleh {$user['name']}. Alasan: {$reason}");
        $this->redirect(url('admin/donations'), ['warning' => "Donasi #{$donation['donation_code']} telah ditandai ditolak."]);
    }

    // Admin/Staff: Input Donasi Tunai / Offline
    public function offlineCreate(): void {
        $campaigns = $this->campaignModel->getAllActive();
        $this->render('donations/offline', [
            'title' => 'Input Donasi Offline / Kasir Tunai',
            'campaigns' => $campaigns
        ], 'dashboard');
    }

    public function offlineStore(): void {
        $data = $this->validate($_POST, [
            'campaign_id' => 'required|numeric',
            'amount' => 'required|numeric|min:5000',
            'donor_name' => 'required',
        ]);

        $donationCode = $this->donationModel->generateDonationCode();
        $user = auth_user();

        $donationId = $this->donationModel->createDonation([
            'donation_code' => $donationCode,
            'campaign_id' => (int)$data['campaign_id'],
            'user_id' => null,
            'donor_name' => $data['donor_name'],
            'donor_email' => $_POST['donor_email'] ?? 'offline@pedulikasih.org',
            'donor_phone' => $_POST['donor_phone'] ?? null,
            'amount' => (float)$data['amount'],
            'payment_method' => 'cash_offline',
            'payment_status' => 'pending',
            'doa_message' => $_POST['doa_message'] ?? 'Donasi tunai langsung di kantor sekretariat',
            'is_anonymous' => isset($_POST['is_anonymous']) ? 1 : 0,
        ]);

        // Auto verify offline cash by staff
        $this->donationModel->verifyDonation($donationId, (int)$user['id']);
        $this->audit('OFFLINE_DONATION_RECORDED', "Pencatatan donasi tunai offline #{$donationCode} senilai " . format_rupiah($data['amount']) . " oleh staf {$user['name']}");

        $this->redirect(url('admin/donations'), ['success' => "Donasi offline #{$donationCode} berhasil dicatat dan diverifikasi!"]);
    }
}
