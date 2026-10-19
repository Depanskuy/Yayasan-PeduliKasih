<?php
// app/Controllers/HomeController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Beneficiary;
use App\Models\Campaign;
use App\Models\Disbursement;
use App\Models\Donation;
use App\Models\Setting;

class HomeController extends Controller
{
    protected string $layout = 'public';

    public function index(): void
    {
        $settings     = (new Setting())->all();
        $campaigns    = new Campaign();
        $donations    = new Donation();
        $beneficiaries = new Beneficiary();
        $disbursements = new Disbursement();

        $featured = $campaigns->featured(3);
        $recent   = $donations->recentVerified(5);

        $stats = [
            'donations'     => $donations->totalVerified(),
            'campaigns'     => $campaigns->total(),
            'beneficiaries' => $beneficiaries->total(),
            'disbursed'     => $disbursements->total(),
        ];

        $this->render('home/index', [
            'title'      => 'Beranda',
            'settings'   => $settings,
            'featured'   => $featured,
            'recent'     => $recent,
            'stats'      => $stats,
        ]);
    }

    public function about(): void
    {
        $settings = (new Setting())->all();
        $this->render('home/about', [
            'title'    => 'Tentang Kami',
            'settings' => $settings,
        ]);
    }

    public function contact(): void
    {
        $settings = (new Setting())->all();
        $this->render('home/contact', [
            'title'    => 'Kontak',
            'settings' => $settings,
        ]);
    }
}
