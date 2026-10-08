<?php
// app/Controllers/DisbursementController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Disbursement;
use App\Models\Campaign;
use App\Models\Beneficiary;

class DisbursementController extends Controller {
    protected Disbursement $disbursementModel;
    protected Campaign $campaignModel;
    protected Beneficiary $beneficiaryModel;

    public function __construct() {
        parent::__construct();
        $this->disbursementModel = new Disbursement();
        $this->campaignModel = new Campaign();
        $this->beneficiaryModel = new Beneficiary();
    }

    public function index(): void {
        $disbursements = $this->disbursementModel->getAll();
        $totalAmount = $this->disbursementModel->getTotalDisbursedAmount();

        $this->render('distributions/index', [
            'title' => 'Penyaluran Bantuan Sosial & Kas Keluar - Panel Admin',
            'disbursements' => $disbursements,
            'totalAmount' => $totalAmount
        ], 'dashboard');
    }

    public function create(): void {
        $campaigns = $this->campaignModel->getAllActive();
        $beneficiaries = $this->beneficiaryModel->getAll();

        $this->render('distributions/create', [
            'title' => 'Catat Penyaluran Bantuan Baru',
            'campaigns' => $campaigns,
            'beneficiaries' => $beneficiaries
        ], 'dashboard');
    }

    public function store(): void {
        $data = $this->validate($_POST, [
            'campaign_id' => 'required|numeric',
            'title' => 'required|min:5',
            'assistance_type' => 'required',
            'amount' => 'required|numeric|min:1000',
            'disbursement_date' => 'required'
        ]);

        $docImage = null;
        if (!empty($_FILES['documentation_image']['name'])) {
            $check = Security::validateUpload($_FILES['documentation_image'], ['image/jpeg', 'image/png', 'image/webp']);
            if ($check['valid']) {
                $docImage = 'disb_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
                $dest = dirname(__DIR__, 2) . '/public/uploads/' . $docImage;
                move_uploaded_file($_FILES['documentation_image']['tmp_name'], $dest);
            }
        }

        $code = $this->disbursementModel->generateDisbursementCode();
        $user = auth_user();

        $this->disbursementModel->createDisbursement([
            'disbursement_code' => $code,
            'campaign_id' => (int)$data['campaign_id'],
            'beneficiary_id' => !empty($_POST['beneficiary_id']) ? (int)$_POST['beneficiary_id'] : null,
            'title' => $data['title'],
            'assistance_type' => $data['assistance_type'],
            'amount' => (float)$data['amount'],
            'recipient_count' => !empty($_POST['recipient_count']) ? (int)$_POST['recipient_count'] : 1,
            'disbursement_date' => $data['disbursement_date'],
            'documentation_image' => $docImage,
            'notes' => $_POST['notes'] ?? null,
            'recorded_by' => (int)$user['id'],
        ]);

        $this->audit('DISBURSEMENT_RECORDED', "Pencatatan penyaluran bantuan #{$code} senilai " . format_rupiah($data['amount']) . " oleh {$user['name']}");

        $this->redirect(url('admin/distributions'), ['success' => "Penyaluran bantuan #{$code} berhasil dicatat!"]);
    }

    public function detail(int $id): void {
        $disbursement = $this->disbursementModel->findById($id);
        if (!$disbursement) {
            $this->redirect(url('admin/distributions'), ['error' => 'Data penyaluran tidak ditemukan.']);
        }

        $this->render('distributions/detail', [
            'title' => 'Berita Acara Penyaluran #' . $disbursement['disbursement_code'],
            'disbursement' => $disbursement
        ], 'dashboard');
    }
}
