<?php
// app/Models/Campaign.php
namespace App\Models;

use App\Core\Model;

class Campaign extends Model {
    protected string $table = 'campaigns';

    public function getAllActive(?int $limit = null, ?int $categoryId = null, ?string $search = null): array {
        $sql = "SELECT c.*, cat.name AS category_name, cat.icon AS category_icon, u.name AS author_name 
                FROM campaigns c
                JOIN campaign_categories cat ON c.category_id = cat.id
                JOIN users u ON c.created_by = u.id
                WHERE c.status = 'active' AND c.deleted_at IS NULL";
        $params = [];

        if ($categoryId) {
            $sql .= " AND c.category_id = :cat_id";
            $params[':cat_id'] = $categoryId;
        }

        if ($search) {
            $sql .= " AND (c.title LIKE :search OR c.short_description LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        $sql .= " ORDER BY c.is_featured DESC, c.id DESC";

        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return $this->fetchAll($sql, $params);
    }

    public function getFeatured(int $limit = 3): array {
        $sql = "SELECT c.*, cat.name AS category_name, cat.icon AS category_icon 
                FROM campaigns c
                JOIN campaign_categories cat ON c.category_id = cat.id
                WHERE c.status = 'active' AND c.is_featured = 1 AND c.deleted_at IS NULL
                ORDER BY c.id DESC LIMIT " . (int)$limit;
        return $this->fetchAll($sql);
    }

    public function findBySlug(string $slug): ?array {
        $sql = "SELECT c.*, cat.name AS category_name, cat.icon AS category_icon, u.name AS author_name 
                FROM campaigns c
                JOIN campaign_categories cat ON c.category_id = cat.id
                JOIN users u ON c.created_by = u.id
                WHERE c.slug = :slug AND c.deleted_at IS NULL LIMIT 1";
        return $this->fetch($sql, [':slug' => $slug]);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT c.*, cat.name AS category_name 
                FROM campaigns c
                JOIN campaign_categories cat ON c.category_id = cat.id
                WHERE c.id = :id AND c.deleted_at IS NULL LIMIT 1";
        return $this->fetch($sql, [':id' => $id]);
    }

    public function getAllForAdmin(): array {
        $sql = "SELECT c.*, cat.name AS category_name,
                (SELECT COUNT(*) FROM donations WHERE campaign_id = c.id AND payment_status = 'verified') AS donor_count
                FROM campaigns c
                JOIN campaign_categories cat ON c.category_id = cat.id
                WHERE c.deleted_at IS NULL
                ORDER BY c.id DESC";
        return $this->fetchAll($sql);
    }

    public function createCampaign(array $data): int {
        return $this->insert($this->table, [
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_description' => $data['short_description'],
            'story' => $data['story'],
            'target_amount' => $data['target_amount'],
            'collected_amount' => 0,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'banner_image' => $data['banner_image'] ?? null,
            'status' => $data['status'] ?? 'active',
            'is_featured' => $data['is_featured'] ?? 0,
            'created_by' => $data['created_by']
        ]);
    }

    public function updateCampaign(int $id, array $data): bool {
        return $this->update($this->table, $id, $data);
    }

    public function addCollectedAmount(int $id, float $amount): bool {
        $sql = "UPDATE campaigns SET collected_amount = collected_amount + :amount WHERE id = :id";
        return $this->query($sql, [':amount' => $amount, ':id' => $id])->rowCount() > 0;
    }

    public function getUpdates(int $campaignId): array {
        $sql = "SELECT cu.*, u.name AS author_name 
                FROM campaign_updates cu
                JOIN users u ON cu.posted_by = u.id
                WHERE cu.campaign_id = :cid
                ORDER BY cu.created_at DESC";
        return $this->fetchAll($sql, [':cid' => $campaignId]);
    }

    public function addUpdate(int $campaignId, string $title, string $content, int $postedBy, ?string $image = null): int {
        return $this->insert('campaign_updates', [
            'campaign_id' => $campaignId,
            'title' => $title,
            'content' => $content,
            'image' => $image,
            'posted_by' => $postedBy,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getDonors(int $campaignId, int $limit = 20): array {
        $sql = "SELECT donor_name, amount, is_anonymous, doa_message, created_at 
                FROM donations 
                WHERE campaign_id = :cid AND payment_status = 'verified'
                ORDER BY created_at DESC LIMIT " . (int)$limit;
        return $this->fetchAll($sql, [':cid' => $campaignId]);
    }
}
