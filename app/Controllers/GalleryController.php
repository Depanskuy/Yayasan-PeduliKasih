<?php
// app/Controllers/GalleryController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Gallery;
use App\Models\Campaign;

class GalleryController extends Controller {
    protected Gallery $galleryModel;
    protected Campaign $campaignModel;

    public function __construct() {
        parent::__construct();
        $this->galleryModel = new Gallery();
        $this->campaignModel = new Campaign();
    }

    public function index(): void {
        $galleries = $this->galleryModel->getAll(30);
        $this->render('gallery/index', [
            'title' => 'Galeri Dokumentasi Aksi Sosial - Yayasan Peduli Kasih Sesama',
            'galleries' => $galleries
        ]);
    }

    public function adminIndex(): void {
        $galleries = $this->galleryModel->getAll(50);
        $campaigns = $this->campaignModel->getAllActive();

        $this->render('gallery/admin_index', [
            'title' => 'Manajemen Galeri Dokumentasi - Panel Admin',
            'galleries' => $galleries,
            'campaigns' => $campaigns
        ], 'dashboard');
    }

    public function store(): void {
        $data = $this->validate($_POST, [
            'title' => 'required|min:3',
        ]);

        if (empty($_FILES['media_file']['name'])) {
            $this->redirect(url('admin/gallery'), ['error' => 'Silakan pilih berkas foto untuk diunggah.']);
        }

        $check = Security::validateUpload($_FILES['media_file'], ['image/jpeg', 'image/png', 'image/webp']);
        if (!$check['valid']) {
            $this->redirect(url('admin/gallery'), ['error' => $check['error']]);
        }

        $filename = 'gal_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
        $dest = dirname(__DIR__, 2) . '/public/uploads/' . $filename;
        move_uploaded_file($_FILES['media_file']['tmp_name'], $dest);

        $user = auth_user();
        $this->galleryModel->createMedia([
            'title' => $data['title'],
            'description' => $_POST['description'] ?? null,
            'media_type' => 'photo',
            'media_url' => $filename,
            'campaign_id' => !empty($_POST['campaign_id']) ? (int)$_POST['campaign_id'] : null,
            'uploaded_by' => (int)$user['id'],
        ]);

        $this->audit('GALLERY_UPLOAD', "Mengunggah foto dokumentasi baru: '{$data['title']}'");

        $this->redirect(url('admin/gallery'), ['success' => 'Foto dokumentasi berhasil ditambahkan!']);
    }
}
