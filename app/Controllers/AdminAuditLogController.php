<?php
// app/Controllers/AdminAuditLogController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuditLog;

class AdminAuditLogController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $model = new AuditLog();
        $filters = [
            'action' => (string) ($this->input('action') ?? ''),
            'search' => (string) ($this->input('q') ?? ''),
            'limit'  => 200,
        ];

        $this->render('admin/audit_logs', [
            'title'   => 'Audit Log',
            'logs'    => $model->all(array_filter($filters)),
            'actions' => $model->actions(),
            'total'   => $model->total(),
            'filters' => $filters,
        ]);
    }
}
