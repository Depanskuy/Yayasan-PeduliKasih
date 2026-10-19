<?php
// app/Models/VolunteerEvent.php
namespace App\Models;

use App\Core\Model;

class VolunteerEvent extends Model
{
    public function all(array $filters = []): array
    {
        $sql = "SELECT e.*,
                       (SELECT COUNT(*) FROM volunteer_registrations r WHERE r.event_id = e.id) AS registered_count
                FROM volunteer_events e
                WHERE 1 = 1";
        $params = [];
        if (!empty($filters['status'])) {
            $sql .= ' AND e.status = ?';
            $params[] = $filters['status'];
        }
        $sql .= ' ORDER BY e.event_date ASC';
        return $this->fetchAll($sql, $params);
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM volunteer_events WHERE id = ?', [$id]);
    }

    public function isRegistered(int $eventId, int $userId): bool
    {
        return (bool) $this->scalar(
            'SELECT COUNT(*) FROM volunteer_registrations WHERE event_id = ? AND user_id = ?',
            [$eventId, $userId]
        );
    }

    public function registrationFor(int $eventId, int $userId): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM volunteer_registrations WHERE event_id = ? AND user_id = ?',
            [$eventId, $userId]
        );
    }

    public function register(int $eventId, int $userId, string $motivation, ?string $skills): bool
    {
        try {
            $this->query(
                'INSERT INTO volunteer_registrations (event_id, user_id, motivation, skills, status, created_at)
                 VALUES (?, ?, ?, ?, ?, NOW())',
                [$eventId, $userId, $motivation, $skills, 'pending']
            );
            return true;
        } catch (\PDOException $e) {
            // Duplicate (UNIQUE event_id + user_id)
            return false;
        }
    }

    public function registrationsWithUser(?int $eventId = null): array
    {
        $sql = 'SELECT r.*, u.name AS user_name, u.email AS user_email, e.title AS event_title
                FROM volunteer_registrations r
                JOIN users u ON u.id = r.user_id
                JOIN volunteer_events e ON e.id = r.event_id';
        $params = [];
        if ($eventId !== null) {
            $sql .= ' WHERE r.event_id = ?';
            $params[] = $eventId;
        }
        $sql .= ' ORDER BY r.created_at DESC';
        return $this->fetchAll($sql, $params);
    }

    public function updateRegistrationStatus(int $registrationId, string $status): int
    {
        return $this->query(
            'UPDATE volunteer_registrations SET status = ?, updated_at = NOW() WHERE id = ?',
            [$status, $registrationId]
        )->rowCount();
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM volunteer_events');
    }

    public function totalRegistrations(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM volunteer_registrations');
    }
}
