<?php
// app/Controllers/VolunteerController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\AuditLog;
use App\Models\VolunteerEvent;
use App\Models\VolunteerFieldReport;

class VolunteerController extends Controller
{
    protected string $layout = 'public';

    public function listEvents(): void
    {
        $model  = new VolunteerEvent();
        $events = $model->all();

        $registered = [];
        $user = $this->currentUser();
        if ($user) {
            foreach ($events as $event) {
                $registered[(int) $event['id']] = $model->isRegistered((int) $event['id'], (int) $user['id']);
            }
        }

        $this->render('volunteer/events', [
            'title'      => 'Kegiatan Relawan',
            'events'     => $events,
            'registered' => $registered,
        ]);
    }

    public function showRegister(string $id): void
    {
        $model = new VolunteerEvent();
        $event = $model->find((int) $id);

        if (!$event) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Kegiatan tidak ditemukan']);
            return;
        }

        $user = $this->currentUser();
        $already = $user ? $model->isRegistered((int) $event['id'], (int) $user['id']) : false;

        $this->render('volunteer/register', [
            'title'   => 'Daftar Relawan',
            'event'   => $event,
            'already' => $already,
            'user'    => $user,
        ]);
    }

    public function register(string $id): void
    {
        $eventId = (int) $id;
        $model   = new VolunteerEvent();
        $event   = $model->find($eventId);

        if (!$event) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Kegiatan tidak ditemukan']);
            return;
        }

        $this->verifyCsrfOrFail('volunteer/events/' . $eventId . '/register');

        $user = $this->currentUser();
        if (!$user) {
            $this->redirect('login');
        }

        if ($event['status'] !== 'open') {
            Helper::setFlash('error', 'Pendaftaran untuk kegiatan ini sudah ditutup.');
            $this->redirect('volunteer/events');
        }
        if (strtotime($event['registration_deadline']) < strtotime(date('Y-m-d'))) {
            Helper::setFlash('error', 'Batas pendaftaran kegiatan ini sudah lewat.');
            $this->redirect('volunteer/events');
        }

        $motivation = (string) $this->input('motivation');
        $skills     = (string) $this->input('skills');

        if (mb_strlen($motivation) < 10) {
            Helper::setFlash('error', 'Motivasi minimal 10 karakter.');
            Helper::setOld(compact('motivation', 'skills'));
            $this->redirect('volunteer/events/' . $eventId . '/register');
        }

        if ($model->isRegistered($eventId, (int) $user['id'])) {
            Helper::setFlash('error', 'Anda sudah terdaftar pada kegiatan ini.');
            $this->redirect('volunteer/events');
        }

        $ok = $model->register($eventId, (int) $user['id'], $motivation, $skills ?: null);

        if ($ok) {
            AuditLog::record((int) $user['id'], 'VOLUNTEER_REGISTER', "Mendaftar sebagai relawan pada kegiatan #{$eventId}: {$event['title']}");
        }

        Helper::clearOld();
        Helper::setFlash(
            $ok ? 'success' : 'error',
            $ok ? 'Pendaftaran relawan berhasil. Menunggu persetujuan admin.' : 'Gagal mendaftar. Silakan coba lagi.'
        );
        $this->redirect('volunteer/events');
    }

    /* ===================== Laporan kegiatan lapangan ===================== */

    public function myReports(): void
    {
        $user = $this->currentUser();
        if (!$user) {
            $this->redirect('login');
        }

        $eventModel = new VolunteerEvent();
        $reportModel = new VolunteerFieldReport();

        $reportable = [];
        foreach ($eventModel->all() as $event) {
            $registration = $eventModel->registrationFor((int) $event['id'], (int) $user['id']);
            if ($registration && in_array($registration['status'], ['approved', 'attended'], true)) {
                $reportable[] = [
                    'event'        => $event,
                    'registration' => $registration,
                    'has_report'   => $reportModel->existsFor((int) $event['id'], (int) $user['id']),
                ];
            }
        }

        $this->render('volunteer/reports', [
            'title'      => 'Laporan Saya',
            'reports'    => $reportModel->byUser((int) $user['id']),
            'reportable' => $reportable,
        ]);
    }

    public function showReport(string $id): void
    {
        $user = $this->currentUser();
        $eventId = (int) $id;
        $eventModel = new VolunteerEvent();
        $event = $eventModel->find($eventId);

        if (!$event) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Kegiatan tidak ditemukan']);
            return;
        }

        $registration = $eventModel->registrationFor($eventId, (int) $user['id']);
        if (!$registration || !in_array($registration['status'], ['approved', 'attended'], true)) {
            Helper::setFlash('error', 'Anda belum disetujui sebagai relawan pada kegiatan ini.');
            $this->redirect('volunteer/reports');
        }

        if ((new VolunteerFieldReport())->existsFor($eventId, (int) $user['id'])) {
            Helper::setFlash('error', 'Laporan untuk kegiatan ini sudah dikirim.');
            $this->redirect('volunteer/reports');
        }

        $this->render('volunteer/report_form', [
            'title' => 'Kirim Laporan Kegiatan',
            'event' => $event,
            'user'  => $user,
        ]);
    }

    public function storeReport(string $id): void
    {
        $eventId = (int) $id;
        $this->verifyCsrfOrFail('volunteer/events/' . $eventId . '/report');

        $user = $this->currentUser();
        if (!$user) {
            $this->redirect('login');
        }

        $eventModel = new VolunteerEvent();
        $event = $eventModel->find($eventId);
        if (!$event) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Kegiatan tidak ditemukan']);
            return;
        }

        $registration = $eventModel->registrationFor($eventId, (int) $user['id']);
        if (!$registration || !in_array($registration['status'], ['approved', 'attended'], true)) {
            Helper::setFlash('error', 'Anda belum disetujui sebagai relawan pada kegiatan ini.');
            $this->redirect('volunteer/reports');
        }

        $reportModel = new VolunteerFieldReport();
        if ($reportModel->existsFor($eventId, (int) $user['id'])) {
            Helper::setFlash('error', 'Laporan untuk kegiatan ini sudah dikirim.');
            $this->redirect('volunteer/reports');
        }

        $title   = (string) $this->input('report_title');
        $summary = (string) $this->input('activity_summary');
        $hours   = (float) str_replace(',', '.', (string) $this->input('hours_spent'));

        if (mb_strlen($title) < 5) {
            Helper::setFlash('error', 'Judul laporan minimal 5 karakter.');
            Helper::setOld(compact('title', 'summary', 'hours'));
            $this->redirect('volunteer/events/' . $eventId . '/report');
        }
        if (mb_strlen($summary) < 20) {
            Helper::setFlash('error', 'Ringkasan kegiatan minimal 20 karakter.');
            Helper::setOld(compact('title', 'summary', 'hours'));
            $this->redirect('volunteer/events/' . $eventId . '/report');
        }
        if ($hours <= 0 || $hours > 24) {
            Helper::setFlash('error', 'Durasi kegiatan harus antara 0 dan 24 jam.');
            Helper::setOld(compact('title', 'summary', 'hours'));
            $this->redirect('volunteer/events/' . $eventId . '/report');
        }

        $image = Helper::upload($_FILES['documentation_image'] ?? null, 'volunteers');

        $reportModel->create([
            'event_id'            => $eventId,
            'user_id'             => (int) $user['id'],
            'report_title'        => $title,
            'activity_summary'    => $summary,
            'hours_spent'         => $hours,
            'documentation_image' => $image,
        ]);

        AuditLog::record((int) $user['id'], 'FIELD_REPORT_CREATE', "Mengirim laporan kegiatan #{$eventId}: {$title}");

        Helper::clearOld();
        Helper::setFlash('success', 'Laporan kegiatan berhasil dikirim. Menunggu review admin.');
        $this->redirect('volunteer/reports');
    }
}
