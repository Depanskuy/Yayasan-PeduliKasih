<?php
// app/Models/VolunteerFieldReport.php
namespace App\Models;

use App\Core\Model;

class VolunteerFieldReport extends Model
{
    public function allWithRelations(): array
    {
        return $this->fetchAll(
            'SELECT r.*, u.name AS user_name, u.email AS user_email, e.title AS event_title
             FROM volunteer_field_reports r
             JOIN users u ON u.id = r.user_id
             JOIN volunteer_events e ON e.id = r.event_id
             ORDER BY r.created_at DESC'
        );
    }

    public function byUser(int $userId): array
    {
        return $this->fetchAll(
            'SELECT r.*, e.title AS event_title, e.event_date, e.location
             FROM volunteer_field_reports r
             JOIN volunteer_events e ON e.id = r.event_id
             WHERE r.user_id = ?
             ORDER BY r.created_at DESC',
            [$userId]
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT r.*, u.name AS user_name, e.title AS event_title
             FROM volunteer_field_reports r
             JOIN users u ON u.id = r.user_id
             JOIN volunteer_events e ON e.id = r.event_id
             WHERE r.id = ?',
            [$id]
        );
    }

    public function existsFor(int $eventId, int $userId): bool
    {
        return (bool) $this->scalar(
            'SELECT COUNT(*) FROM volunteer_field_reports WHERE event_id = ? AND user_id = ?',
            [$eventId, $userId]
        );
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO volunteer_field_reports
                    (event_id, user_id, report_title, activity_summary, hours_spent, documentation_image, status, created_at)
                VALUES
                    (:event_id, :user_id, :report_title, :activity_summary, :hours_spent, :documentation_image, :status, NOW())';
        $this->query($sql, [
            ':event_id'            => (int) $data['event_id'],
            ':user_id'             => (int) $data['user_id'],
            ':report_title'        => $data['report_title'],
            ':activity_summary'    => $data['activity_summary'],
            ':hours_spent'         => (float) $data['hours_spent'],
            ':documentation_image' => $data['documentation_image'] ?? null,
            ':status'              => 'submitted',
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateStatus(int $id, string $status, ?string $feedback): int
    {
        return $this->query(
            'UPDATE volunteer_field_reports SET status = ?, admin_feedback = ? WHERE id = ?',
            [$status, $feedback, $id]
        )->rowCount();
    }

    public function countByStatus(): array
    {
        $rows = $this->fetchAll('SELECT status, COUNT(*) AS total FROM volunteer_field_reports GROUP BY status');
        $out = ['submitted' => 0, 'approved' => 0];
        foreach ($rows as $row) {
            $out[$row['status']] = (int) $row['total'];
        }
        return $out;
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM volunteer_field_reports');
    }
}
