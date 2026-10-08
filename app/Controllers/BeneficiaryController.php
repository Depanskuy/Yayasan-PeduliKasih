<?php
// app/Controllers/BeneficiaryController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Beneficiary;

class BeneficiaryController extends Controller {
    protected Beneficiary $beneficiaryModel;

    public function __construct() {
        parent::__construct();
        $this->beneficiaryModel = new Beneficiary();
    }

    public function index(): void {
        $category = $_GET['category'] ?? null;
        $status = $_GET['status'] ?? null;
        $beneficiaries = $this->beneficiaryModel->getAll($category, $status);

        $this->render('beneficiaries/index', [
            'title' => 'Data Penerima Manfaat (Mustahik) - Panel Admin',
            'beneficiaries' => $beneficiaries,
            'currentCategory' => $category,
            'currentStatus' => $status
        ], 'dashboard');
    }

    public function create(): void {
        $this->render('beneficiaries/create', [
            'title' => 'Tambah Data Mustahik / Penerima Manfaat'
        ], 'dashboard');
    }

    public function store(): void {
        $data = $this->validate($_POST, [
            'nik' => 'required|min:10',
            'name' => 'required|min:3',
            'category' => 'required',
            'address' => 'required'
        ]);

        $id = $this->beneficiaryModel->createBeneficiary([
            'nik' => $data['nik'],
            'name' => $data['name'],
            'category' => $data['category'],
            'phone' => $_POST['phone'] ?? null,
            'address' => $data['address'],
            'village' => $_POST['village'] ?? null,
            'district' => $_POST['district'] ?? null,
            'city' => $_POST['city'] ?? 'Jakarta',
            'eligibility_status' => $_POST['eligibility_status'] ?? 'verified',
            'notes' => $_POST['notes'] ?? null,
        ]);

        $this->audit('BENEFICIARY_CREATE', "Menambahkan data penerima manfaat: {$data['name']} (NIK: {$data['nik']})");

        $this->redirect(url('admin/beneficiaries'), ['success' => 'Data penerima manfaat berhasil disimpan!']);
    }

    public function detail(int $id): void {
        $beneficiary = $this->beneficiaryModel->findById($id);
        if (!$beneficiary) {
            $this->redirect(url('admin/beneficiaries'), ['error' => 'Data penerima manfaat tidak ditemukan.']);
        }

        $history = $this->beneficiaryModel->getAssistanceHistory($id);

        $this->render('beneficiaries/detail', [
            'title' => 'Detail Mustahik: ' . $beneficiary['name'],
            'beneficiary' => $beneficiary,
            'history' => $history
        ], 'dashboard');
    }

    public function edit(int $id): void {
        $beneficiary = $this->beneficiaryModel->findById($id);
        if (!$beneficiary) {
            $this->redirect(url('admin/beneficiaries'), ['error' => 'Data penerima manfaat tidak ditemukan.']);
        }

        $this->render('beneficiaries/edit', [
            'title' => 'Edit Mustahik: ' . $beneficiary['name'],
            'beneficiary' => $beneficiary
        ], 'dashboard');
    }

    public function update(int $id): void {
        $beneficiary = $this->beneficiaryModel->findById($id);
        if (!$beneficiary) {
            $this->redirect(url('admin/beneficiaries'), ['error' => 'Data penerima manfaat tidak ditemukan.']);
        }

        $data = $this->validate($_POST, [
            'nik' => 'required|min:10',
            'name' => 'required|min:3',
            'category' => 'required',
            'address' => 'required'
        ]);

        $this->beneficiaryModel->updateBeneficiary($id, [
            'nik' => $data['nik'],
            'name' => $data['name'],
            'category' => $data['category'],
            'phone' => $_POST['phone'] ?? null,
            'address' => $data['address'],
            'village' => $_POST['village'] ?? null,
            'district' => $_POST['district'] ?? null,
            'city' => $_POST['city'] ?? 'Jakarta',
            'eligibility_status' => $_POST['eligibility_status'] ?? 'verified',
            'notes' => $_POST['notes'] ?? null,
        ]);

        $this->audit('BENEFICIARY_UPDATE', "Memperbarui data penerima manfaat: {$data['name']} (ID: {$id})");

        $this->redirect(url('admin/beneficiaries'), ['success' => 'Data penerima manfaat berhasil diperbarui!']);
    }
}
