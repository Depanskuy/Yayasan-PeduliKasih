<?php
// app/Models/Gallery.php
namespace App\Models;

use App\Core\Model;

class Gallery extends Model {
    protected string $table = 'gallery';

    public function getAll(int $limit = 24): array {
        $sql = "SELECT g.*, c.title AS campaign_title, ve.title AS event_title, u.name AS uploader_name 
                FROM gallery g
                LEFT JOIN campaigns c ON g.campaign_id = c.id
                LEFT JOIN volunteer_events ve ON g.event_id = ve.id
                JOIN users u ON g.uploaded_by = u.id
                ORDER BY g.id DESC LIMIT " . (int)$limit;
        return $this->fetchAll($sql);
    }

    public function createMedia(array $data): int {
        return $this->insert($this->table, [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'media_type' => $data['media_type'] ?? 'photo',
            'media_url' => $data['media_url'],
            'campaign_id' => !empty($data['campaign_id']) ? $data['campaign_id'] : null,
            'event_id' => !empty($data['event_id']) ? $data['event_id'] : null,
            'uploaded_by' => $data['uploaded_by'],
        ]);
    }
}
