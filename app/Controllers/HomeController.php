<?php
// app/Controllers/HomeController.php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        // Fetch some data for dashboard, e.g., counts
        $pdo = require __DIR__ . '/../../../config/database.php';
        $stmt = $pdo->query('SELECT COUNT(*) as total_donations FROM donations');
        $donations = $stmt->fetch();
        $stmt = $pdo->query('SELECT COUNT(*) as total_campaigns FROM campaigns');
        $campaigns = $stmt->fetch();
        $stmt = $pdo->query('SELECT COUNT(*) as total_beneficiaries FROM beneficiaries');
        $beneficiaries = $stmt->fetch();
        $data = [
            'totalDonations' => $donations['total_donations'] ?? 0,
            'totalCampaigns' => $campaigns['total_campaigns'] ?? 0,
            'totalBeneficiaries' => $beneficiaries['total_beneficiaries'] ?? 0,
        ];
        $this->render('home/index', $data);
    }
}
?>
