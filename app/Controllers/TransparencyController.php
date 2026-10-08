<?php
// app/Controllers/TransparencyController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Donation;
use App\Models\Disbursement;
use App\Models\Beneficiary;
use App\Models\Setting;

class TransparencyController extends Controller {
    protected Donation $donationModel;
    protected Disbursement $disbursementModel;
    protected Beneficiary $beneficiaryModel;
    protected Setting $settingModel;

    public function __construct() {
        parent::__construct();
        $this->donationModel = new Donation();
        $this->disbursementModel = new Disbursement();
        $this->beneficiaryModel = new Beneficiary();
        $this->settingModel = new Setting();
    }

    public function index(): void {
        $totalDonation = $this->donationModel->getTotalVerifiedAmount();
        $totalDisbursed = $this->disbursementModel->getTotalDisbursedAmount();
        $balance = $totalDonation - $totalDisbursed;
        $totalBeneficiaries = $this->beneficiaryModel->count('beneficiaries', 'deleted_at IS NULL');
        $totalDonors = $this->donationModel->getTotalVerifiedCount();

        $recentDonations = $this->donationModel->getAllForAdmin('verified');
        $recentDonations = array_slice($recentDonations, 0, 15);

        $recentDisbursements = $this->disbursementModel->getAll();
        $recentDisbursements = array_slice($recentDisbursements, 0, 15);

        $monthlyIncomes = $this->donationModel->getMonthlyStats();
        $monthlyExpenses = $this->disbursementModel->getMonthlyStats();
        $settings = $this->settingModel->getAllAsMap();

        $this->render('public/transparency', [
            'title' => 'Transparansi Keuangan & Penyaluran - Yayasan Peduli Kasih Sesama',
            'stats' => [
                'totalDonation' => $totalDonation,
                'totalDisbursed' => $totalDisbursed,
                'balance' => $balance,
                'beneficiariesCount' => $totalBeneficiaries,
                'donorCount' => $totalDonors
            ],
            'recentDonations' => $recentDonations,
            'recentDisbursements' => $recentDisbursements,
            'monthlyIncomes' => $monthlyIncomes,
            'monthlyExpenses' => $monthlyExpenses,
            'settings' => $settings
        ]);
    }

    public function printReport(): void {
        $totalDonation = $this->donationModel->getTotalVerifiedAmount();
        $totalDisbursed = $this->disbursementModel->getTotalDisbursedAmount();
        $balance = $totalDonation - $totalDisbursed;

        $allDonations = $this->donationModel->getAllForAdmin('verified');
        $allDisbursements = $this->disbursementModel->getAll();
        $settings = $this->settingModel->getAllAsMap();

        $this->render('public/transparency_report_print', [
            'title' => 'Laporan Pertanggungjawaban Keuangan & Penyaluran Bantuan',
            'stats' => [
                'totalDonation' => $totalDonation,
                'totalDisbursed' => $totalDisbursed,
                'balance' => $balance,
            ],
            'donations' => $allDonations,
            'disbursements' => $allDisbursements,
            'settings' => $settings
        ], 'print');
    }
}
