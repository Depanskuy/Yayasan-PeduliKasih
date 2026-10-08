<?php
// app/Models/VolunteerRegistration.php
namespace App\Models;

use App\Core\Model;

class VolunteerRegistration extends Model {
    protected string $table = 'volunteer_registrations';

    public function findByEventAndUser(int $eventId, int $userId): ?array {
        return $this->fetch("SELECT * FROM volunteer_registrations WHERE event_id = :eid AND user_id = :uid LIMIT 1", [
            ':eid' => $eventId,
            ':uid' => $userId
        ]);
    }

    public function getUserRegistrations(int $userId): array {
        $sql = "SELECT vr.*, ve.title AS event_title, ve.event_date, ve.event_time, ve.location, ve.slug AS event_slug 
                FROM volunteer_registrations vr
                JOIN volunteer_events ve ON vr.event_id = ve.id
                WHERE vr.user_id = :uid
                ORDER BY ve.event_date DESC";
        return $this->fetchAll($sql, [':uid' => $userId]);
    }

    public function getEventApplicants(int $eventId): array {
        $sql = "SELECT vr.*, u.name AS volunteer_name, u.email AS volunteer_email, u.phone AS volunteer_phone 
                FROM volunteer_registrations vr
                JOIN users u ON vr.user_id = u.id
                WHERE vr.event_id = :eid
                ORDER BY vr.created_at ASC";
        return $this->fetchAll($sql, [':eid' => $eventId]);
    }

    public function register(array $data): int {
        return $this->insert($this->table, [
            'event_id' => $data['event_id'],
            'user_id' => $data['user_id'],
            'motivation' => $data['motivation'],
            'skills' => $data['skills'] ?? null,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function updateStatus(int $id, string $status, ?string $adminNotes = null): bool {
        $updateData = ['status' => $status];
        if ($adminNotes !== null) {
            $updateData['admin_notes'] = $adminNotes;
        }
        return $this->update($this->table, $id, $updateData);
    }

    public function markAttendance(int $id): bool {
        return $this->update($this->table, $id, [
            'status' => 'attended',
            'attendance_time' => date('Y-m-d H:i:s')
        ]);
    }
}
