<?php
// app/Controllers/HomeController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Disbursement;
use App\Models\Beneficiary;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Setting;

class HomeController extends Controller {
    public function index(): void {
        $campaignModel = new Campaign();
        $donationModel = new Donation();
        $disbursementModel = new Disbursement();
        $beneficiaryModel = new Beneficiary();
        $articleModel = new Article();
        $galleryModel = new Gallery();
        $settingModel = new Setting();

        $featuredCampaigns = $campaignModel->getFeatured(3);
        $recentCampaigns = $campaignModel->getAllActive(6);
        $totalDonationAmount = $donationModel->getTotalVerifiedAmount();
        $totalDisbursedAmount = $disbursementModel->getTotalDisbursedAmount();
        $cashBalance = $totalDonationAmount - $totalDisbursedAmount;
        $totalBeneficiaries = $beneficiaryModel->count('beneficiaries', 'deleted_at IS NULL');
        $totalDonors = $donationModel->getTotalVerifiedCount();

        $recentArticles = $articleModel->getAllPublished(null, 3);
        $galleries = $galleryModel->getAll(6);
        $settings = $settingModel->getAllAsMap();

        $this->render('public/home', [
            'title' => 'Yayasan Peduli Kasih Sesama - Menyalurkan Amanah, Merajut Kasih',
            'featuredCampaigns' => $featuredCampaigns,
            'recentCampaigns' => $recentCampaigns,
            'stats' => [
                'totalDonations' => $totalDonationAmount,
                'totalDisbursed' => $totalDisbursedAmount,
                'balance' => $cashBalance,
                'beneficiariesCount' => $totalBeneficiaries,
                'donorCount' => $totalDonors,
                'campaignCount' => $campaignModel->count('campaigns', "status = 'active' AND deleted_at IS NULL"),
            ],
            'recentArticles' => $recentArticles,
            'galleries' => $galleries,
            'settings' => $settings
        ]);
    }

    public function about(): void {
        $settingModel = new Setting();
        $settings = $settingModel->getAllAsMap();
        $this->render('public/about', [
            'title' => 'Tentang Kami - Yayasan Peduli Kasih Sesama',
            'settings' => $settings
        ]);
    }

    public function contact(): void {
        $settingModel = new Setting();
        $settings = $settingModel->getAllAsMap();
        $this->render('public/contact', [
            'title' => 'Hubungi Kami - Yayasan Peduli Kasih Sesama',
            'settings' => $settings
        ]);
    }
}
