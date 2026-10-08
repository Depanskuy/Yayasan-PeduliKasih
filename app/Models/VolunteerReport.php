<?php
// app/Models/VolunteerReport.php
namespace App\Models;

use App\Core\Model;

class VolunteerReport extends Model {
    protected string $table = 'volunteer_field_reports';

    public function getReportsByUser(int $userId): array {
        $sql = "SELECT vfr.*, ve.title AS event_title 
                FROM volunteer_field_reports vfr
                JOIN volunteer_events ve ON vfr.event_id = ve.id
                WHERE vfr.user_id = :uid
                ORDER BY vfr.created_at DESC";
        return $this->fetchAll($sql, [':uid' => $userId]);
    }

    public function getAllForAdmin(): array {
        $sql = "SELECT vfr.*, ve.title AS event_title, u.name AS volunteer_name 
                FROM volunteer_field_reports vfr
                JOIN volunteer_events ve ON vfr.event_id = ve.id
                JOIN users u ON vfr.user_id = u.id
                ORDER BY vfr.created_at DESC";
        return $this->fetchAll($sql);
    }

    public function createReport(array $data): int {
        return $this->insert($this->table, [
            'event_id' => $data['event_id'],
            'user_id' => $data['user_id'],
            'report_title' => $data['report_title'],
            'activity_summary' => $data['activity_summary'],
            'hours_spent' => $data['hours_spent'],
            'documentation_image' => $data['documentation_image'] ?? null,
            'status' => 'submitted',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function updateFeedback(int $id, string $status, ?string $feedback): bool {
        return $this->update($this->table, $id, [
            'status' => $status,
            'admin_feedback' => $feedback
        ]);
    }
}
