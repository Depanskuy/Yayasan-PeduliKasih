<?php
// app/Controllers/AdminFieldReportController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\AuditLog;
use App\Models\VolunteerFieldReport;

class AdminFieldReportController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $model = new VolunteerFieldReport();
        $this->render('admin/field_reports', [
            'title'   => 'Laporan Relawan',
            'reports' => $model->allWithRelations(),
            'counts'  => $model->countByStatus(),
        ]);
    }

    public function updateStatus(string $id): void
    {
        $this->verifyCsrfOrFail('admin/field-reports');

        $status   = (string) $this->input('status');
        $feedback = (string) $this->input('admin_feedback');

        if (!in_array($status, ['submitted', 'approved'], true)) {
            Helper::setFlash('error', 'Status tidak valid.');
            $this->redirect('admin/field-reports');
        }

        $model = new VolunteerFieldReport();
        $report = $model->find((int) $id);
        if (!$report) {
            Helper::setFlash('error', 'Laporan tidak ditemukan.');
            $this->redirect('admin/field-reports');
        }

        $model->updateStatus((int) $id, $status, $feedback ?: null);
        AuditLog::record(
            (int) $this->currentUser()['id'],
            'FIELD_REPORT_STATUS',
            "Mengubah status laporan relawan #{$id} menjadi {$status}"
        );

        Helper::setFlash('success', 'Status laporan relawan diperbarui.');
        $this->redirect('admin/field-reports');
    }
}
