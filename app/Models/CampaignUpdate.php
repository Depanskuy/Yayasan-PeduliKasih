<?php
// app/Models/CampaignUpdate.php
namespace App\Models;

use App\Core\Model;

class CampaignUpdate extends Model
{
    public function byCampaign(int $campaignId): array
    {
        return $this->fetchAll(
            'SELECT cu.*, u.name AS poster_name
             FROM campaign_updates cu
             LEFT JOIN users u ON u.id = cu.posted_by
             WHERE cu.campaign_id = ?
             ORDER BY cu.created_at DESC',
            [$campaignId]
        );
    }

    public function allWithCampaign(): array
    {
        return $this->fetchAll(
            'SELECT cu.*, c.title AS campaign_title, u.name AS poster_name
             FROM campaign_updates cu
             LEFT JOIN campaigns c ON c.id = cu.campaign_id
             LEFT JOIN users u ON u.id = cu.posted_by
             ORDER BY cu.created_at DESC'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM campaign_updates WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO campaign_updates (campaign_id, title, content, image, posted_by, created_at)
                VALUES (:campaign_id, :title, :content, :image, :posted_by, NOW())';
        $this->query($sql, [
            ':campaign_id' => (int) $data['campaign_id'],
            ':title'       => $data['title'],
            ':content'     => $data['content'],
            ':image'       => $data['image'] ?? null,
            ':posted_by'   => (int) $data['posted_by'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function delete(int $id): int
    {
        return $this->query('DELETE FROM campaign_updates WHERE id = ?', [$id])->rowCount();
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM campaign_updates');
    }
}
