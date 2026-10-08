<?php
// app/Controllers/VolunteerController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Models\VolunteerEvent;
use App\Models\VolunteerRegistration;
use App\Models\VolunteerReport;
use App\Models\Campaign;

class VolunteerController extends Controller {
    protected VolunteerEvent $eventModel;
    protected VolunteerRegistration $registrationModel;
    protected VolunteerReport $reportModel;
    protected Campaign $campaignModel;

    public function __construct() {
        parent::__construct();
        $this->eventModel = new VolunteerEvent();
        $this->registrationModel = new VolunteerRegistration();
        $this->reportModel = new VolunteerReport();
        $this->campaignModel = new Campaign();
    }

    // Public: List Event Relawan
    public function events(): void {
        $events = $this->eventModel->getAllActive();
        $this->render('volunteers/events', [
            'title' => 'Kegiatan Relawan Sosial - Yayasan Peduli Kasih Sesama',
            'events' => $events
        ]);
    }

    // Public: Detail Event
    public function eventDetail(string $slug): void {
        $event = $this->eventModel->findBySlug($slug);
        if (!$event) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Kegiatan Relawan Tidak Ditemukan']);
            return;
        }

        $user = auth_user();
        $existingReg = null;
        if ($user) {
            $existingReg = $this->registrationModel->findByEventAndUser((int)$event['id'], (int)$user['id']);
        }

        $this->render('volunteers/event_detail', [
            'title' => $event['title'] . ' - Relawan Peduli Kasih',
            'event' => $event,
            'existingReg' => $existingReg,
            'user' => $user
        ]);
    }

    // Public/Volunteer: Daftar Kegiatan
    public function registerEvent(string $slug): void {
        $user = auth_user();
        if (!$user) {
            $this->redirect(url('login'), ['error' => 'Silakan login terlebih dahulu untuk mendaftar sebagai relawan kegiatan.']);
        }

        $event = $this->eventModel->findBySlug($slug);
        if (!$event) {
            $this->redirect(url('volunteer/events'), ['error' => 'Kegiatan relawan tidak ditemukan.']);
        }

        $existing = $this->registrationModel->findByEventAndUser((int)$event['id'], (int)$user['id']);
        if ($existing) {
            $this->redirect(url('volunteer/events/' . $slug), ['info' => 'Anda telah terdaftar pada kegiatan ini.']);
        }

        $data = $this->validate($_POST, [
            'motivation' => 'required|min:10',
        ]);

        $this->registrationModel->register([
            'event_id' => (int)$event['id'],
            'user_id' => (int)$user['id'],
            'motivation' => $data['motivation'],
            'skills' => $_POST['skills'] ?? null,
        ]);

        $this->audit('VOLUNTEER_REGISTERED', "Relawan {$user['name']} mendaftar pada kegiatan '{$event['title']}'");

        $this->redirect(url('volunteer/dashboard'), [
            'success' => 'Pendaftaran relawan berhasil dikirim! Menunggu konfirmasi panitia.'
        ]);
    }

    // Relawan: Dashboard Portal Relawan
    public function dashboard(): void {
        $user = auth_user();
        if (!$user) {
            $this->redirect(url('login'));
        }

        $registrations = $this->registrationModel->getUserRegistrations((int)$user['id']);
        $reports = $this->reportModel->getReportsByUser((int)$user['id']);

        $this->render('volunteers/dashboard', [
            'title' => 'Portal Relawan - ' . $user['name'],
            'registrations' => $registrations,
            'reports' => $reports,
            'user' => $user
        ], 'dashboard');
    }

    // Relawan: Absensi Hadir di Lokasi
    public function attendance(int $regId): void {
        $user = auth_user();
        $this->registrationModel->markAttendance($regId);
        $this->audit('VOLUNTEER_ATTENDANCE', "Relawan {$user['name']} melakukan absensi kehadiran kegiatan (Reg ID: {$regId})");

        $this->redirect(url('volunteer/dashboard'), ['success' => 'Absensi kehadiran berhasil dicatat! Selamat bertugas.']);
    }

    // Relawan: Kirim Laporan Lapangan
    public function submitReport(): void {
        $user = auth_user();
        $data = $this->validate($_POST, [
            'event_id' => 'required|numeric',
            'report_title' => 'required|min:5',
            'activity_summary' => 'required|min:15',
            'hours_spent' => 'required|numeric',
        ]);

        $docImage = null;
        if (!empty($_FILES['documentation_image']['name'])) {
            $check = Security::validateUpload($_FILES['documentation_image'], ['image/jpeg', 'image/png', 'image/webp']);
            if ($check['valid']) {
                $docImage = 'volrep_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
                $dest = dirname(__DIR__, 2) . '/public/uploads/' . $docImage;
                move_uploaded_file($_FILES['documentation_image']['tmp_name'], $dest);
            }
        }

        $this->reportModel->createReport([
            'event_id' => (int)$data['event_id'],
            'user_id' => (int)$user['id'],
            'report_title' => $data['report_title'],
            'activity_summary' => $data['activity_summary'],
            'hours_spent' => (float)$data['hours_spent'],
            'documentation_image' => $docImage,
        ]);

        $this->audit('VOLUNTEER_REPORT_SUBMITTED', "Relawan {$user['name']} mengirim laporan kegiatan lapangan: '{$data['report_title']}'");

        $this->redirect(url('volunteer/dashboard'), ['success' => 'Laporan kegiatan lapangan berhasil dikirim!']);
    }

    // Admin: List Events
    public function adminEvents(): void {
        $events = $this->eventModel->getAllForAdmin();
        $this->render('volunteers/admin_events', [
            'title' => 'Manajemen Kegiatan Relawan - Panel Admin',
            'events' => $events
        ], 'dashboard');
    }

    // Admin: Create Event
    public function createEvent(): void {
        $campaigns = $this->campaignModel->getAllActive();
        $this->render('volunteers/create_event', [
            'title' => 'Tambah Kegiatan Relawan Baru',
            'campaigns' => $campaigns
        ], 'dashboard');
    }

    // Admin: Store Event
    public function storeEvent(): void {
        $data = $this->validate($_POST, [
            'title' => 'required|min:5',
            'description' => 'required',
            'location' => 'required',
            'event_date' => 'required',
            'registration_deadline' => 'required',
            'quota' => 'required|numeric',
        ]);

        $banner = null;
        if (!empty($_FILES['banner_image']['name'])) {
            $check = Security::validateUpload($_FILES['banner_image'], ['image/jpeg', 'image/png', 'image/webp']);
            if ($check['valid']) {
                $banner = 'volev_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
                $dest = dirname(__DIR__, 2) . '/public/uploads/' . $banner;
                move_uploaded_file($_FILES['banner_image']['tmp_name'], $dest);
            }
        }

        $slug = slugify($data['title']);
        $user = auth_user();

        $this->eventModel->createEvent([
            'campaign_id' => !empty($_POST['campaign_id']) ? (int)$_POST['campaign_id'] : null,
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'],
            'location' => $data['location'],
            'event_date' => $data['event_date'],
            'event_time' => $_POST['event_time'] ?? '08:00 - 15:00 WIB',
            'quota' => (int)$data['quota'],
            'registration_deadline' => $data['registration_deadline'],
            'banner_image' => $banner,
            'status' => 'open',
            'created_by' => (int)$user['id']
        ]);

        $this->audit('VOLUNTEER_EVENT_CREATED', "Membuat kegiatan relawan baru: '{$data['title']}'");

        $this->redirect(url('admin/volunteers/events'), ['success' => 'Kegiatan relawan berhasil ditambahkan!']);
    }

    // Admin: Review Pendaftar Event
    public function applicants(int $eventId): void {
        $event = $this->eventModel->findById($eventId);
        if (!$event) {
            $this->redirect(url('admin/volunteers/events'), ['error' => 'Event tidak ditemukan.']);
        }

        $applicants = $this->registrationModel->getEventApplicants($eventId);

        $this->render('volunteers/admin_applicants', [
            'title' => 'Seleksi Relawan: ' . $event['title'],
            'event' => $event,
            'applicants' => $applicants
        ], 'dashboard');
    }

    // Admin: Seleksi Relawan (Approve / Reject)
    public function updateApplicantStatus(int $id): void {
        $status = $_POST['status'] ?? 'pending';
        $adminNotes = $_POST['admin_notes'] ?? null;
        $eventId = (int)$_POST['event_id'];

        $this->registrationModel->updateStatus($id, $status, $adminNotes);
        $this->audit('VOLUNTEER_STATUS_UPDATED', "Status pendaftaran relawan ID {$id} diubah menjadi '{$status}'");

        $this->redirect(url('admin/volunteers/events/' . $eventId . '/applicants'), [
            'success' => "Status relawan berhasil diubah menjadi: {$status}."
        ]);
    }

    // Admin: Review Laporan Lapangan Relawan
    public function adminReports(): void {
        $reports = $this->reportModel->getAllForAdmin();
        $this->render('volunteers/admin_reports', [
            'title' => 'Laporan Lapangan Relawan - Panel Admin',
            'reports' => $reports
        ], 'dashboard');
    }

    // Admin: Beri Feedback Laporan Lapangan
    public function feedbackReport(int $id): void {
        $feedback = trim($_POST['admin_feedback'] ?? 'Laporan diterima dengan baik.');
        $this->reportModel->updateFeedback($id, 'approved', $feedback);

        $this->audit('VOLUNTEER_REPORT_APPROVED', "Admin menyetujui laporan relawan ID {$id} dengan feedback");
        $this->redirect(url('admin/volunteers/reports'), ['success' => 'Feedback berhasil disimpan dan laporan disetujui!']);
    }
}
