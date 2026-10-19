<?php
// app/Models/Gallery.php
namespace App\Models;

use App\Core\Model;

class Gallery extends Model
{
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT g.*, c.title AS campaign_title, e.title AS event_title, u.name AS uploader_name
             FROM gallery g
             LEFT JOIN campaigns c ON c.id = g.campaign_id
             LEFT JOIN volunteer_events e ON e.id = g.event_id
             LEFT JOIN users u ON u.id = g.uploaded_by
             ORDER BY g.created_at DESC'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM gallery WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO gallery
                    (title, description, media_type, media_url, campaign_id, event_id, uploaded_by, created_at)
                VALUES
                    (:title, :description, :media_type, :media_url, :campaign_id, :event_id, :uploaded_by, NOW())';
        $this->query($sql, [
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? null,
            ':media_type'  => $data['media_type'] ?? 'photo',
            ':media_url'   => $data['media_url'],
            ':campaign_id' => !empty($data['campaign_id']) ? (int) $data['campaign_id'] : null,
            ':event_id'    => !empty($data['event_id']) ? (int) $data['event_id'] : null,
            ':uploaded_by' => (int) $data['uploaded_by'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function delete(int $id): int
    {
        return $this->query('DELETE FROM gallery WHERE id = ?', [$id])->rowCount();
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM gallery');
    }

    public function countByType(): array
    {
        $rows = $this->fetchAll('SELECT media_type, COUNT(*) AS total FROM gallery GROUP BY media_type');
        $out = ['photo' => 0, 'video' => 0];
        foreach ($rows as $row) {
            $out[$row['media_type']] = (int) $row['total'];
        }
        return $out;
    }
}
