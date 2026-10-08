<?php
// app/Models/VolunteerEvent.php
namespace App\Models;

use App\Core\Model;

class VolunteerEvent extends Model {
    protected string $table = 'volunteer_events';

    public function getAllActive(): array {
        $sql = "SELECT ve.*, c.title AS campaign_title,
                (SELECT COUNT(*) FROM volunteer_registrations WHERE event_id = ve.id AND status != 'rejected') AS registered_count
                FROM volunteer_events ve
                LEFT JOIN campaigns c ON ve.campaign_id = c.id
                WHERE ve.status = 'open'
                ORDER BY ve.event_date ASC";
        return $this->fetchAll($sql);
    }

    public function getAllForAdmin(): array {
        $sql = "SELECT ve.*, c.title AS campaign_title, u.name AS creator_name,
                (SELECT COUNT(*) FROM volunteer_registrations WHERE event_id = ve.id) AS total_applicants,
                (SELECT COUNT(*) FROM volunteer_registrations WHERE event_id = ve.id AND status = 'approved') AS approved_count
                FROM volunteer_events ve
                LEFT JOIN campaigns c ON ve.campaign_id = c.id
                JOIN users u ON ve.created_by = u.id
                ORDER BY ve.id DESC";
        return $this->fetchAll($sql);
    }

    public function findBySlug(string $slug): ?array {
        $sql = "SELECT ve.*, c.title AS campaign_title,
                (SELECT COUNT(*) FROM volunteer_registrations WHERE event_id = ve.id AND status = 'approved') AS approved_count
                FROM volunteer_events ve
                LEFT JOIN campaigns c ON ve.campaign_id = c.id
                WHERE ve.slug = :slug LIMIT 1";
        return $this->fetch($sql, [':slug' => $slug]);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT ve.*, c.title AS campaign_title 
                FROM volunteer_events ve
                LEFT JOIN campaigns c ON ve.campaign_id = c.id
                WHERE ve.id = :id LIMIT 1";
        return $this->fetch($sql, [':id' => $id]);
    }

    public function createEvent(array $data): int {
        return $this->insert($this->table, [
            'campaign_id' => !empty($data['campaign_id']) ? $data['campaign_id'] : null,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'location' => $data['location'],
            'event_date' => $data['event_date'],
            'event_time' => $data['event_time'] ?? '08:00 - 15:00 WIB',
            'quota' => $data['quota'] ?? 20,
            'registration_deadline' => $data['registration_deadline'],
            'banner_image' => $data['banner_image'] ?? null,
            'status' => $data['status'] ?? 'open',
            'created_by' => $data['created_by']
        ]);
    }

    public function updateEvent(int $id, array $data): bool {
        return $this->update($this->table, $id, $data);
    }
}
