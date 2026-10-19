<?php
// app/Controllers/CampaignController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\Donation;

class CampaignController extends Controller
{
    protected string $layout = 'public';

    public function index(): void
    {
        $model      = new Campaign();
        $categories = $model->categories();

        $filters = [
            'status'      => 'active',
            'category_id' => (int) ($this->input('kategori') ?? 0),
            'search'      => (string) ($this->input('q') ?? ''),
        ];
        if ($filters['category_id'] <= 0) {
            unset($filters['category_id']);
        }

        $campaigns = $model->all($filters);
        // Fallback bila filter status menyembunyikan data seed berstatus lain.
        if (!$campaigns) {
            $campaigns = $model->all(['search' => $filters['search']]);
        }

        $this->render('campaigns/index', [
            'title'      => 'Kampanye',
            'campaigns'  => $campaigns,
            'categories' => $categories,
            'filters'    => $filters,
        ]);
    }

    public function show(string $id): void
    {
        $model    = new Campaign();
        $campaign = $model->find((int) $id);

        if (!$campaign) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Kampanye tidak ditemukan']);
            return;
        }

        $donations = (new Donation())->byCampaign((int) $campaign['id'], 8);
        $updates   = (new CampaignUpdate())->byCampaign((int) $campaign['id']);
        $others    = array_values(array_filter(
            $model->featured(4),
            fn ($c) => (int) $c['id'] !== (int) $campaign['id']
        ));

        $this->render('campaigns/show', [
            'title'     => $campaign['title'],
            'campaign'  => $campaign,
            'donations' => $donations,
            'updates'   => $updates,
            'others'    => array_slice($others, 0, 3),
        ]);
    }
}
