<?php
// app/Controllers/AdminController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\AuditLog;
use App\Models\Beneficiary;
use App\Models\Campaign;
use App\Models\Disbursement;
use App\Models\Donation;
use App\Models\User;
use App\Models\VolunteerEvent;

class AdminController extends Controller
{
    protected string $layout = 'admin';

    /* ============================ Dashboard ============================ */

    public function dashboard(): void
    {
        $campaigns     = new Campaign();
        $donations     = new Donation();
        $beneficiaries = new Beneficiary();
        $disbursements = new Disbursement();
        $users         = new User();
        $volunteers    = new VolunteerEvent();

        $this->render('admin/dashboard', [
            'title'             => 'Dashboard',
            'stats'             => [
                'donations'       => $donations->totalVerified(),
                'campaigns'       => $campaigns->total(),
                'beneficiaries'   => $beneficiaries->total(),
                'disbursed'       => $disbursements->total(),
                'users'           => $users->total(),
                'pendingDonations'=> $donations->countByStatus()['pending'] ?? 0,
            ],
            'campaignStatus'    => $campaigns->countByStatus(),
            'donationStatus'    => $donations->countByStatus(),
            'roleCounts'        => $users->countsByRole(),
            'recentDonations'   => $donations->recent(6),
            'recentDisburse'    => $disbursements->recent(5),
            'campaignProgress'  => $campaigns->all(['status' => 'active', 'limit' => 5]),
            'volunteerStats'    => [
                'events'        => $volunteers->total(),
                'registrations' => $volunteers->totalRegistrations(),
            ],
        ]);
    }

    /* ============================== Users ============================== */

    public function users(): void
    {
        $this->render('admin/users', [
            'title' => 'Manajemen User',
            'users' => (new User())->all(),
            'roleCounts' => (new User())->countsByRole(),
        ]);
    }

    public function updateUserStatus(string $id): void
    {
        $this->verifyCsrfOrFail('admin/users');
        $status = (string) $this->input('status');
        if (!in_array($status, ['active', 'inactive'], true)) {
            Helper::setFlash('error', 'Status tidak valid.');
            $this->redirect('admin/users');
        }
        $affected = (new User())->updateStatus((int) $id, $status);
        if ($affected) {
            AuditLog::record((int) $this->currentUser()['id'], 'USER_STATUS', "Mengubah status user #{$id} menjadi {$status}");
        }
        Helper::setFlash(
            $affected ? 'success' : 'error',
            $affected ? 'Status user berhasil diperbarui.' : 'User tidak ditemukan.'
        );
        $this->redirect('admin/users');
    }

    /* ============================ Campaigns ============================ */

    public function campaigns(): void
    {
        $this->render('admin/campaigns', [
            'title'     => 'Kampanye',
            'campaigns' => (new Campaign())->all(),
            'statusCounts' => (new Campaign())->countByStatus(),
        ]);
    }

    public function createCampaign(): void
    {
        $this->render('admin/campaign_form', [
            'title'      => 'Tambah Kampanye',
            'campaign'   => null,
            'categories' => (new Campaign())->categories(),
        ]);
    }

    public function storeCampaign(): void
    {
        $this->verifyCsrfOrFail('admin/campaigns/create');
        $data = $this->campaignPayload();

        if ($error = $this->validateCampaign($data)) {
            Helper::setFlash('error', $error);
            Helper::setOld($data);
            $this->redirect('admin/campaigns/create');
        }

        $data['created_by'] = (int) $this->currentUser()['id'];
        $banner = Helper::upload($_FILES['banner_image'] ?? null, 'campaigns');
        if ($banner) {
            $data['banner_image'] = $banner;
        }

        (new Campaign())->create($data);
        AuditLog::record((int) $data['created_by'], 'CAMPAIGN_CREATE', "Menambah kampanye: {$data['title']}");
        Helper::clearOld();
        Helper::setFlash('success', 'Kampanye berhasil ditambahkan.');
        $this->redirect('admin/campaigns');
    }

    public function editCampaign(string $id): void
    {
        $campaign = (new Campaign())->find((int) $id);
        if (!$campaign) {
            Helper::setFlash('error', 'Kampanye tidak ditemukan.');
            $this->redirect('admin/campaigns');
        }
        $this->render('admin/campaign_form', [
            'title'      => 'Edit Kampanye',
            'campaign'   => $campaign,
            'categories' => (new Campaign())->categories(),
        ]);
    }

    public function updateCampaign(string $id): void
    {
        $this->verifyCsrfOrFail('admin/campaigns/' . $id . '/edit');
        $campaign = (new Campaign())->find((int) $id);
        if (!$campaign) {
            Helper::setFlash('error', 'Kampanye tidak ditemukan.');
            $this->redirect('admin/campaigns');
        }

        $data = $this->campaignPayload();
        if ($error = $this->validateCampaign($data)) {
            Helper::setFlash('error', $error);
            Helper::setOld($data);
            $this->redirect('admin/campaigns/' . $id . '/edit');
        }

        (new Campaign())->update((int) $id, $data);
        AuditLog::record((int) $this->currentUser()['id'], 'CAMPAIGN_UPDATE', "Memperbarui kampanye #{$id}: {$data['title']}");
        Helper::clearOld();
        Helper::setFlash('success', 'Kampanye berhasil diperbarui.');
        $this->redirect('admin/campaigns');
    }

    public function deleteCampaign(string $id): void
    {
        $this->verifyCsrfOrFail('admin/campaigns');
        $campaign = (new Campaign())->find((int) $id);
        $affected = (new Campaign())->delete((int) $id);
        if ($affected && $campaign) {
            AuditLog::record((int) $this->currentUser()['id'], 'CAMPAIGN_DELETE', "Menghapus kampanye #{$id}: {$campaign['title']}");
        }
        Helper::setFlash(
            $affected ? 'success' : 'error',
            $affected ? 'Kampanye berhasil dihapus.' : 'Kampanye tidak ditemukan.'
        );
        $this->redirect('admin/campaigns');
    }

    private function campaignPayload(): array
    {
        return [
            'category_id'       => (int) $this->input('category_id'),
            'title'             => (string) $this->input('title'),
            'slug'              => Helper::slugify((string) $this->input('title')),
            'short_description' => (string) $this->input('short_description'),
            'story'             => (string) $this->input('story'),
            'target_amount'     => (float) preg_replace('/[^0-9]/', '', (string) $this->input('target_amount')),
            'start_date'        => (string) $this->input('start_date'),
            'end_date'          => (string) $this->input('end_date'),
            'status'            => (string) ($this->input('status') ?: 'active'),
            'is_featured'       => !empty($_POST['is_featured']) ? 1 : 0,
        ];
    }

    private function validateCampaign(array $data): ?string
    {
        if ($data['category_id'] <= 0) return 'Kategori wajib dipilih.';
        if (mb_strlen($data['title']) < 5) return 'Judul minimal 5 karakter.';
        if (mb_strlen($data['short_description']) < 10) return 'Deskripsi singkat minimal 10 karakter.';
        if (mb_strlen($data['story']) < 20) return 'Cerita minimal 20 karakter.';
        if ($data['target_amount'] <= 0) return 'Target dana harus lebih dari 0.';
        if (empty($data['start_date']) || empty($data['end_date'])) return 'Tanggal mulai dan selesai wajib diisi.';
        if (strtotime($data['end_date']) < strtotime($data['start_date'])) return 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
        return null;
    }

    /* ============================ Donations ============================ */

    public function donations(): void
    {
        $filters = [
            'status' => (string) ($this->input('status') ?? ''),
            'search' => (string) ($this->input('q') ?? ''),
        ];
        $donations = new Donation();

        $this->render('admin/donations', [
            'title'        => 'Donasi',
            'donations'    => $donations->all(array_filter($filters)),
            'statusCounts' => $donations->countByStatus(),
            'filters'      => $filters,
        ]);
    }

    public function verifyDonation(string $id): void
    {
        $this->verifyCsrfOrFail('admin/donations');
        $adminId = (int) $this->currentUser()['id'];
        $ok = (new Donation())->verify((int) $id, $adminId);
        if ($ok) {
            AuditLog::record($adminId, 'DONATION_VERIFY', "Memverifikasi donasi #{$id}");
        }
        Helper::setFlash($ok ? 'success' : 'error', $ok ? 'Donasi berhasil diverifikasi.' : 'Donasi tidak ditemukan.');
        $this->redirect('admin/donations');
    }

    public function rejectDonation(string $id): void
    {
        $this->verifyCsrfOrFail('admin/donations');
        $reason = (string) ($this->input('rejection_reason') ?: 'Ditolak oleh admin');
        $adminId = (int) $this->currentUser()['id'];
        $ok = (new Donation())->reject((int) $id, $adminId, $reason);
        if ($ok) {
            AuditLog::record($adminId, 'DONATION_REJECT', "Menolak donasi #{$id}. Alasan: {$reason}");
        }
        Helper::setFlash($ok ? 'success' : 'error', $ok ? 'Donasi ditolak.' : 'Donasi tidak ditemukan.');
        $this->redirect('admin/donations');
    }

    /* ========================== Disbursements ========================== */

    public function disbursements(): void
    {
        $this->render('admin/disbursements', [
            'title'        => 'Penyaluran Dana',
            'disbursements'=> (new Disbursement())->all(),
            'total'        => (new Disbursement())->total(),
        ]);
    }

    public function createDisbursement(): void
    {
        $this->render('admin/disbursement_form', [
            'title'         => 'Tambah Penyaluran',
            'campaigns'     => (new Campaign())->all(),
            'beneficiaries' => (new Beneficiary())->all(),
        ]);
    }

    public function storeDisbursement(): void
    {
        $this->verifyCsrfOrFail('admin/disbursements/create');
        $data = [
            'campaign_id'       => (int) $this->input('campaign_id'),
            'beneficiary_id'    => (int) $this->input('beneficiary_id'),
            'title'             => (string) $this->input('title'),
            'assistance_type'   => (string) $this->input('assistance_type'),
            'amount'            => (float) preg_replace('/[^0-9]/', '', (string) $this->input('amount')),
            'recipient_count'   => (int) $this->input('recipient_count'),
            'disbursement_date' => (string) $this->input('disbursement_date'),
            'notes'             => (string) $this->input('notes'),
            'recorded_by'       => (int) $this->currentUser()['id'],
        ];

        $types = ['tunai', 'sembako', 'pendidikan', 'kesehatan', 'renovasi', 'tanggap_darurat'];
        if ($data['campaign_id'] <= 0) $this->failBack('Kampanye wajib dipilih.', 'admin/disbursements/create', $data);
        if (mb_strlen($data['title']) < 5) $this->failBack('Judul minimal 5 karakter.', 'admin/disbursements/create', $data);
        if (!in_array($data['assistance_type'], $types, true)) $this->failBack('Jenis bantuan tidak valid.', 'admin/disbursements/create', $data);
        if ($data['amount'] <= 0) $this->failBack('Nominal harus lebih dari 0.', 'admin/disbursements/create', $data);
        if (empty($data['disbursement_date'])) $this->failBack('Tanggal penyaluran wajib diisi.', 'admin/disbursements/create', $data);

        $doc = Helper::upload($_FILES['documentation_image'] ?? null, 'disbursements');
        if ($doc) {
            $data['documentation_image'] = $doc;
        }

        (new Disbursement())->create($data);
        AuditLog::record((int) $data['recorded_by'], 'DISBURSEMENT_CREATE', "Mencatat penyaluran: {$data['title']} (" . Helper::rupiah($data['amount']) . ')');
        Helper::clearOld();
        Helper::setFlash('success', 'Data penyaluran berhasil disimpan.');
        $this->redirect('admin/disbursements');
    }

    /* =========================== Beneficiaries ========================= */

    public function beneficiaries(): void
    {
        $model = new Beneficiary();
        $filters = [
            'category' => (string) ($this->input('kategori') ?? ''),
            'search'   => (string) ($this->input('q') ?? ''),
        ];

        $this->render('admin/beneficiaries', [
            'title'        => 'Penerima Manfaat',
            'beneficiaries'=> $model->all(array_filter($filters)),
            'byCategory'   => $model->countByCategory(),
            'categories'   => Beneficiary::categoryLabels(),
            'filters'      => $filters,
        ]);
    }

    public function createBeneficiary(): void
    {
        $this->render('admin/beneficiary_form', [
            'title'      => 'Tambah Penerima Manfaat',
            'categories' => Beneficiary::categoryLabels(),
        ]);
    }

    public function storeBeneficiary(): void
    {
        $this->verifyCsrfOrFail('admin/beneficiaries/create');
        $data = [
            'nik'                => (string) $this->input('nik'),
            'name'               => (string) $this->input('name'),
            'category'           => (string) $this->input('category'),
            'phone'              => (string) $this->input('phone'),
            'address'            => (string) $this->input('address'),
            'village'            => (string) $this->input('village'),
            'district'           => (string) $this->input('district'),
            'city'               => (string) ($this->input('city') ?: 'Jakarta'),
            'eligibility_status' => (string) ($this->input('eligibility_status') ?: 'verified'),
            'notes'              => (string) $this->input('notes'),
        ];

        if (mb_strlen($data['nik']) < 6) $this->failBack('NIK minimal 6 karakter.', 'admin/beneficiaries/create', $data);
        if (mb_strlen($data['name']) < 3) $this->failBack('Nama minimal 3 karakter.', 'admin/beneficiaries/create', $data);
        if (!array_key_exists($data['category'], Beneficiary::categoryLabels())) $this->failBack('Kategori tidak valid.', 'admin/beneficiaries/create', $data);
        if (mb_strlen($data['address']) < 5) $this->failBack('Alamat minimal 5 karakter.', 'admin/beneficiaries/create', $data);

        try {
            (new Beneficiary())->create($data);
        } catch (\PDOException $e) {
            $this->failBack('NIK sudah terdaftar atau data tidak valid.', 'admin/beneficiaries/create', $data);
        }

        Helper::clearOld();
        Helper::setFlash('success', 'Penerima manfaat berhasil ditambahkan.');
        AuditLog::record((int) $this->currentUser()['id'], 'BENEFICIARY_CREATE', "Menambah penerima manfaat: {$data['name']}");
        $this->redirect('admin/beneficiaries');
    }

    /* ============================ Volunteers =========================== */

    public function volunteers(): void
    {
        $model = new VolunteerEvent();
        $this->render('admin/volunteers', [
            'title'         => 'Relawan',
            'events'        => $model->all(),
            'registrations' => $model->registrationsWithUser(),
        ]);
    }

    public function updateVolunteerStatus(string $id): void
    {
        $this->verifyCsrfOrFail('admin/volunteers');
        $status = (string) $this->input('status');
        if (!in_array($status, ['pending', 'approved', 'rejected', 'attended'], true)) {
            Helper::setFlash('error', 'Status tidak valid.');
            $this->redirect('admin/volunteers');
        }
        $affected = (new VolunteerEvent())->updateRegistrationStatus((int) $id, $status);
        if ($affected) {
            AuditLog::record((int) $this->currentUser()['id'], 'VOLUNTEER_STATUS', "Mengubah status pendaftaran relawan #{$id} menjadi {$status}");
        }
        Helper::setFlash(
            $affected ? 'success' : 'error',
            $affected ? 'Status pendaftaran relawan diperbarui.' : 'Pendaftaran tidak ditemukan.'
        );
        $this->redirect('admin/volunteers');
    }

    /* ============================= Helpers ============================= */

    private function failBack(string $message, string $route, array $old = []): void
    {
        Helper::setFlash('error', $message);
        if ($old) {
            Helper::setOld($old);
        }
        $this->redirect($route);
    }
}
