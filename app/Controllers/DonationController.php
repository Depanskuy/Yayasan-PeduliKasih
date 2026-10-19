<?php
// app/Controllers/DonationController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\Donation;

class DonationController extends Controller
{
    protected string $layout = 'public';

    public function store(string $id): void
    {
        $campaignId = (int) $id;
        $campaign   = (new Campaign())->find($campaignId);

        if (!$campaign) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Kampanye tidak ditemukan']);
            return;
        }

        $this->verifyCsrfOrFail('campaigns/' . $campaignId);

        $donorName    = (string) $this->input('donor_name');
        $donorEmail   = strtolower((string) $this->input('donor_email'));
        $donorPhone   = (string) $this->input('donor_phone');
        $amountRaw    = (string) $this->input('amount');
        $amount       = (float) preg_replace('/[^0-9]/', '', $amountRaw);
        $method       = (string) $this->input('payment_method');
        $doa          = (string) $this->input('doa_message');
        $anonymous    = !empty($_POST['is_anonymous']);
        $proofFile    = Helper::upload($_FILES['payment_proof'] ?? null, 'proofs');

        $methods = ['bca', 'mandiri', 'bri', 'gopay', 'ovo', 'dana', 'qris', 'cash_offline'];

        $errors = [];
        if (mb_strlen($donorName) < 3) {
            $errors[] = 'Nama donatur minimal 3 karakter.';
        }
        if (!filter_var($donorEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email donatur tidak valid.';
        }
        if ($amount < 10000) {
            $errors[] = 'Nominal donasi minimal Rp 10.000.';
        }
        if (!in_array($method, $methods, true)) {
            $errors[] = 'Metode pembayaran tidak valid.';
        }

        if ($errors) {
            Helper::setFlash('error', implode(' ', $errors));
            Helper::setOld(compact('donor_name', 'donor_email', 'donor_phone', 'doa_message', 'amount'));
            $this->redirect('campaigns/' . $campaignId);
        }

        $user = $this->currentUser();
        $donationId = (new Donation())->create([
            'campaign_id'    => $campaignId,
            'user_id'        => $user['id'] ?? null,
            'donor_name'     => $anonymous ? 'Hamba Allah' : $donorName,
            'donor_email'    => $donorEmail,
            'donor_phone'    => $donorPhone,
            'amount'         => $amount,
            'payment_method' => $method,
            'payment_status' => 'pending',
            'payment_proof'  => $proofFile,
            'doa_message'    => $doa,
            'is_anonymous'   => $anonymous ? 1 : 0,
        ]);

        AuditLog::record(
            $user['id'] ?? null,
            'DONATION_CREATE',
            "Donasi #{$donationId} sebesar " . Helper::rupiah($amount) . " untuk kampanye #{$campaignId}"
        );

        Helper::clearOld();
        Helper::setFlash(
            'success',
            'Terima kasih! Donasi #' . $donationId . ' tercatat dan menunggu verifikasi admin.'
        );
        $this->redirect('campaigns/' . $campaignId);
    }

    public function monthly(): void
    {
        $this->json([
            'status' => 'success',
            'data'   => (new Donation())->monthly(),
        ]);
    }
}
