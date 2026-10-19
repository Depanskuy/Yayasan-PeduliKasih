<?php
// app/Controllers/AdminCampaignUpdateController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\CampaignUpdate;

class AdminCampaignUpdateController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $this->render('admin/campaign_updates', [
            'title'     => 'Kabar Kampanye',
            'updates'   => (new CampaignUpdate())->allWithCampaign(),
            'campaigns' => (new Campaign())->all(),
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrfOrFail('admin/campaign-updates');

        $campaignId = (int) $this->input('campaign_id');
        $title      = (string) $this->input('title');
        $content    = (string) $this->input('content');

        if ($campaignId <= 0) {
            Helper::setFlash('error', 'Pilih kampanye terlebih dahulu.');
            $this->redirect('admin/campaign-updates');
        }
        if (mb_strlen($title) < 5) {
            Helper::setFlash('error', 'Judul kabar minimal 5 karakter.');
            $this->redirect('admin/campaign-updates');
        }
        if (mb_strlen($content) < 20) {
            Helper::setFlash('error', 'Isi kabar minimal 20 karakter.');
            $this->redirect('admin/campaign-updates');
        }
        if (!(new Campaign())->find($campaignId)) {
            Helper::setFlash('error', 'Kampanye tidak ditemukan.');
            $this->redirect('admin/campaign-updates');
        }

        $image = Helper::upload($_FILES['image'] ?? null, 'updates');

        $id = (new CampaignUpdate())->create([
            'campaign_id' => $campaignId,
            'title'       => $title,
            'content'     => $content,
            'image'       => $image,
            'posted_by'   => (int) $this->currentUser()['id'],
        ]);

        AuditLog::record((int) $this->currentUser()['id'], 'CAMPAIGN_UPDATE_CREATE', "Menambah kabar kampanye #{$id}: {$title}");
        Helper::setFlash('success', 'Kabar kampanye berhasil dipublikasikan.');
        $this->redirect('admin/campaign-updates');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrfOrFail('admin/campaign-updates');
        $model = new CampaignUpdate();
        $update = $model->find((int) $id);
        if (!$update) {
            Helper::setFlash('error', 'Kabar tidak ditemukan.');
            $this->redirect('admin/campaign-updates');
        }

        if (!empty($update['image'])) {
            $path = __DIR__ . '/../../public/uploads/updates/' . $update['image'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $model->delete((int) $id);
        AuditLog::record((int) $this->currentUser()['id'], 'CAMPAIGN_UPDATE_DELETE', "Menghapus kabar kampanye #{$id}: {$update['title']}");

        Helper::setFlash('success', 'Kabar kampanye berhasil dihapus.');
        $this->redirect('admin/campaign-updates');
    }
}
