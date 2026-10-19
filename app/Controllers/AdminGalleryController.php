<?php
// app/Controllers/AdminGalleryController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\Gallery;
use App\Models\VolunteerEvent;

class AdminGalleryController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $model = new Gallery();
        $this->render('admin/gallery', [
            'title'     => 'Galeri',
            'items'     => $model->all(),
            'counts'    => $model->countByType(),
            'campaigns' => (new Campaign())->all(),
            'events'    => (new VolunteerEvent())->all(),
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrfOrFail('admin/gallery');

        $title     = (string) $this->input('title');
        $desc      = (string) $this->input('description');
        $mediaType = (string) ($this->input('media_type') ?: 'photo');
        $videoUrl  = (string) $this->input('media_url');
        $campaign  = (int) $this->input('campaign_id');
        $event     = (int) $this->input('event_id');

        if (mb_strlen($title) < 3) {
            Helper::setFlash('error', 'Judul minimal 3 karakter.');
            $this->redirect('admin/gallery');
        }
        if (!in_array($mediaType, ['photo', 'video'], true)) {
            Helper::setFlash('error', 'Tipe media tidak valid.');
            $this->redirect('admin/gallery');
        }

        $mediaUrl = null;
        if ($mediaType === 'photo') {
            $mediaUrl = Helper::upload($_FILES['image'] ?? null, 'gallery');
            if (!$mediaUrl) {
                Helper::setFlash('error', 'Pilih file gambar (JPG/PNG/WEBP, maks 5 MB).');
                $this->redirect('admin/gallery');
            }
        } else {
            if (!filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                Helper::setFlash('error', 'URL video tidak valid.');
                $this->redirect('admin/gallery');
            }
            $mediaUrl = $videoUrl;
        }

        $id = (new Gallery())->create([
            'title'       => $title,
            'description' => $desc,
            'media_type'  => $mediaType,
            'media_url'   => $mediaUrl,
            'campaign_id' => $campaign,
            'event_id'    => $event,
            'uploaded_by' => (int) $this->currentUser()['id'],
        ]);

        AuditLog::record((int) $this->currentUser()['id'], 'GALLERY_CREATE', "Menambah media galeri #{$id}: {$title}");
        Helper::setFlash('success', 'Media galeri berhasil ditambahkan.');
        $this->redirect('admin/gallery');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrfOrFail('admin/gallery');
        $model = new Gallery();
        $item = $model->find((int) $id);
        if (!$item) {
            Helper::setFlash('error', 'Media tidak ditemukan.');
            $this->redirect('admin/gallery');
        }

        // Hapus file fisik bila media foto hasil upload.
        if ($item['media_type'] === 'photo' && !empty($item['media_url'])) {
            $path = __DIR__ . '/../../public/uploads/gallery/' . $item['media_url'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $model->delete((int) $id);
        AuditLog::record((int) $this->currentUser()['id'], 'GALLERY_DELETE', "Menghapus media galeri #{$id}: {$item['title']}");

        Helper::setFlash('success', 'Media galeri berhasil dihapus.');
        $this->redirect('admin/gallery');
    }
}
