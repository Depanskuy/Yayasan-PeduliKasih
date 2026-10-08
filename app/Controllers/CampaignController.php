<?php
// app/Controllers/CampaignController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Donation;

class CampaignController extends Controller {
    protected Campaign $campaignModel;
    protected CampaignCategory $categoryModel;

    public function __construct() {
        parent::__construct();
        $this->campaignModel = new Campaign();
        $this->categoryModel = new CampaignCategory();
    }

    // Public: List All Campaigns
    public function index(): void {
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $search = isset($_GET['q']) ? trim($_GET['q']) : null;

        $campaigns = $this->campaignModel->getAllActive(null, $categoryId, $search);
        $categories = $this->categoryModel->getAll();

        $this->render('campaigns/index', [
            'title' => 'Program & Penggalangan Dana - Yayasan Peduli Kasih Sesama',
            'campaigns' => $campaigns,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'searchQuery' => $search
        ]);
    }

    // Public: Campaign Detail
    public function show(string $slug): void {
        $campaign = $this->campaignModel->findBySlug($slug);
        if (!$campaign) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Campaign Tidak Ditemukan']);
            return;
        }

        $updates = $this->campaignModel->getUpdates((int)$campaign['id']);
        $donors = $this->campaignModel->getDonors((int)$campaign['id'], 15);
        $relatedCampaigns = $this->campaignModel->getAllActive(3, (int)$campaign['category_id']);

        $this->render('campaigns/show', [
            'title' => $campaign['title'] . ' - Yayasan Peduli Kasih Sesama',
            'campaign' => $campaign,
            'updates' => $updates,
            'donors' => $donors,
            'relatedCampaigns' => $relatedCampaigns
        ]);
    }

    // Admin: List Campaigns
    public function adminIndex(): void {
        $campaigns = $this->campaignModel->getAllForAdmin();
        $this->render('campaigns/admin_index', [
            'title' => 'Manajemen Campaign - Panel Admin',
            'campaigns' => $campaigns
        ], 'dashboard');
    }

    // Admin: Create Campaign
    public function create(): void {
        $categories = $this->categoryModel->getAll();
        $this->render('campaigns/create', [
            'title' => 'Tambah Campaign Baru - Panel Admin',
            'categories' => $categories
        ], 'dashboard');
    }

    // Admin: Store Campaign
    public function store(): void {
        $data = $this->validate($_POST, [
            'title' => 'required|min:5',
            'category_id' => 'required|numeric',
            'target_amount' => 'required|numeric',
            'short_description' => 'required',
            'story' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $bannerName = null;
        if (!empty($_FILES['banner_image']['name'])) {
            $check = Security::validateUpload($_FILES['banner_image'], ['image/jpeg', 'image/png', 'image/webp']);
            if (!$check['valid']) {
                $this->redirect(url('admin/campaigns/create'), ['error' => $check['error']]);
            }
            $bannerName = 'camp_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
            $dest = dirname(__DIR__, 2) . '/public/uploads/' . $bannerName;
            move_uploaded_file($_FILES['banner_image']['tmp_name'], $dest);
        }

        $slug = slugify($data['title']);
        // Check uniqueness of slug
        $existing = $this->campaignModel->findBySlug($slug);
        if ($existing) {
            $slug .= '-' . time();
        }

        $user = auth_user();
        $campaignId = $this->campaignModel->createCampaign([
            'category_id' => (int)$data['category_id'],
            'title' => $data['title'],
            'slug' => $slug,
            'short_description' => $data['short_description'],
            'story' => $data['story'],
            'target_amount' => (float)$data['target_amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'banner_image' => $bannerName,
            'status' => $_POST['status'] ?? 'active',
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'created_by' => (int)$user['id']
        ]);

        $this->audit('CAMPAIGN_CREATE', "Membuat campaign baru: '{$data['title']}' (ID: {$campaignId})");

        $this->redirect(url('admin/campaigns'), ['success' => 'Campaign berhasil ditambahkan!']);
    }

    // Admin: Edit Campaign
    public function edit(int $id): void {
        $campaign = $this->campaignModel->findById($id);
        if (!$campaign) {
            $this->redirect(url('admin/campaigns'), ['error' => 'Campaign tidak ditemukan.']);
        }
        $categories = $this->categoryModel->getAll();
        $this->render('campaigns/edit', [
            'title' => 'Edit Campaign: ' . $campaign['title'],
            'campaign' => $campaign,
            'categories' => $categories
        ], 'dashboard');
    }

    // Admin: Update Campaign
    public function update(int $id): void {
        $campaign = $this->campaignModel->findById($id);
        if (!$campaign) {
            $this->redirect(url('admin/campaigns'), ['error' => 'Campaign tidak ditemukan.']);
        }

        $data = $this->validate($_POST, [
            'title' => 'required|min:5',
            'category_id' => 'required|numeric',
            'target_amount' => 'required|numeric',
            'short_description' => 'required',
            'story' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $updateData = [
            'category_id' => (int)$data['category_id'],
            'title' => $data['title'],
            'short_description' => $data['short_description'],
            'story' => $data['story'],
            'target_amount' => (float)$data['target_amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $_POST['status'] ?? 'active',
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ];

        if (!empty($_FILES['banner_image']['name'])) {
            $check = Security::validateUpload($_FILES['banner_image'], ['image/jpeg', 'image/png', 'image/webp']);
            if ($check['valid']) {
                $bannerName = 'camp_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
                $dest = dirname(__DIR__, 2) . '/public/uploads/' . $bannerName;
                if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $dest)) {
                    $updateData['banner_image'] = $bannerName;
                }
            }
        }

        $this->campaignModel->updateCampaign($id, $updateData);
        $this->audit('CAMPAIGN_UPDATE', "Memperbarui data campaign: '{$data['title']}' (ID: {$id})");

        $this->redirect(url('admin/campaigns'), ['success' => 'Data campaign berhasil diperbarui!']);
    }

    // Admin: Add Campaign Update
    public function addUpdate(int $id): void {
        $campaign = $this->campaignModel->findById($id);
        if (!$campaign) {
            $this->redirect(url('admin/campaigns'), ['error' => 'Campaign tidak ditemukan.']);
        }

        $title = trim($_POST['update_title'] ?? '');
        $content = trim($_POST['update_content'] ?? '');

        if (empty($title) || empty($content)) {
            $this->redirect(url('admin/campaigns/edit/' . $id), ['error' => 'Judul dan isi kabar perkembangan wajib diisi.']);
        }

        $user = auth_user();
        $this->campaignModel->addUpdate($id, $title, $content, (int)$user['id']);
        $this->audit('CAMPAIGN_PROGRESS_UPDATE', "Menambahkan update kabar progres untuk campaign '{$campaign['title']}'");

        $this->redirect(url('admin/campaigns/edit/' . $id), ['success' => 'Kabar perkembangan terbaru berhasil dipublikasikan!']);
    }
}
