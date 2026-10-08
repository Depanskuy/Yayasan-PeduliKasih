<?php
// app/Controllers/DonorDashboardController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Donation;

class DonorDashboardController extends Controller {
    protected Donation $donationModel;

    public function __construct() {
        parent::__construct();
        $this->donationModel = new Donation();
    }

    public function index(): void {
        $user = auth_user();
        if (!$user) {
            $this->redirect(url('login'));
        }

        $donations = $this->donationModel->getUserDonations((int)$user['id']);

        $totalContributed = 0;
        foreach ($donations as $d) {
            if ($d['payment_status'] === 'verified') {
                $totalContributed += (float)$d['amount'];
            }
        }

        $this->render('donations/donor_dashboard', [
            'title' => 'Ruang Donatur - ' . $user['name'],
            'user' => $user,
            'donations' => $donations,
            'totalContributed' => $totalContributed
        ], 'dashboard');
    }
}
