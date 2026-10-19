<?php
// app/Models/Campaign.php
namespace App\Models;

use App\Core\Model;
use App\Core\Helper;

class Campaign extends Model
{
    public function all(array $filters = []): array
    {
        $sql = "SELECT c.*, cc.name AS category_name, u.name AS creator_name,
                       (SELECT COUNT(*) FROM donations d WHERE d.campaign_id = c.id AND d.payment_status = 'verified') AS donation_count
                FROM campaigns c
                LEFT JOIN campaign_categories cc ON cc.id = c.category_id
                LEFT JOIN users u ON u.id = c.created_by
                WHERE c.deleted_at IS NULL";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= ' AND c.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['category_id'])) {
            $sql .= ' AND c.category_id = ?';
            $params[] = (int) $filters['category_id'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (c.title LIKE ? OR c.short_description LIKE ?)';
            $like = '%' . $filters['search'] . '%';
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= ' ORDER BY c.is_featured DESC, c.created_at DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ' . (int) $filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= ' OFFSET ' . (int) $filters['offset'];
            }
        }

        return $this->fetchAll($sql, $params);
    }

    public function featured(int $limit = 3): array
    {
        return $this->fetchAll(
            "SELECT c.*, cc.name AS category_name
             FROM campaigns c
             LEFT JOIN campaign_categories cc ON cc.id = c.category_id
             WHERE c.deleted_at IS NULL AND c.status = 'active'
             ORDER BY c.is_featured DESC, c.collected_amount DESC
             LIMIT {$limit}"
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne(
            "SELECT c.*, cc.name AS category_name, cc.slug AS category_slug
             FROM campaigns c
             LEFT JOIN campaign_categories cc ON cc.id = c.category_id
             WHERE c.id = ? AND c.deleted_at IS NULL",
            [$id]
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM campaigns WHERE slug = ? AND deleted_at IS NULL',
            [$slug]
        );
    }

    public function categories(): array
    {
        return $this->fetchAll('SELECT * FROM campaign_categories ORDER BY name ASC');
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO campaigns
                    (category_id, title, slug, short_description, story, target_amount, collected_amount,
                     start_date, end_date, banner_image, status, is_featured, created_by, created_at)
                VALUES
                    (:category_id, :title, :slug, :short_description, :story, :target_amount, 0,
                     :start_date, :end_date, :banner_image, :status, :is_featured, :created_by, NOW())';
        $this->query($sql, [
            ':category_id'       => (int) $data['category_id'],
            ':title'             => $data['title'],
            ':slug'              => $data['slug'] ?? Helper::slugify($data['title']),
            ':short_description' => $data['short_description'],
            ':story'             => $data['story'],
            ':target_amount'     => (float) $data['target_amount'],
            ':start_date'        => $data['start_date'],
            ':end_date'          => $data['end_date'],
            ':banner_image'      => $data['banner_image'] ?? null,
            ':status'            => $data['status'] ?? 'active',
            ':is_featured'       => !empty($data['is_featured']) ? 1 : 0,
            ':created_by'        => (int) $data['created_by'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $sql = 'UPDATE campaigns SET
                    category_id = :category_id,
                    title = :title,
                    slug = :slug,
                    short_description = :short_description,
                    story = :story,
                    target_amount = :target_amount,
                    start_date = :start_date,
                    end_date = :end_date,
                    status = :status,
                    is_featured = :is_featured,
                    updated_at = NOW()
                WHERE id = :id';
        return $this->query($sql, [
            ':category_id'       => (int) $data['category_id'],
            ':title'             => $data['title'],
            ':slug'              => $data['slug'] ?? Helper::slugify($data['title']),
            ':short_description' => $data['short_description'],
            ':story'             => $data['story'],
            ':target_amount'     => (float) $data['target_amount'],
            ':start_date'        => $data['start_date'],
            ':end_date'          => $data['end_date'],
            ':status'            => $data['status'] ?? 'active',
            ':is_featured'       => !empty($data['is_featured']) ? 1 : 0,
            ':id'                => $id,
        ])->rowCount();
    }

    public function delete(int $id): int
    {
        return $this->query('UPDATE campaigns SET deleted_at = NOW() WHERE id = ?', [$id])->rowCount();
    }

    public function countByStatus(): array
    {
        $rows = $this->fetchAll(
            'SELECT status, COUNT(*) AS total FROM campaigns WHERE deleted_at IS NULL GROUP BY status'
        );
        $out = ['active' => 0, 'completed' => 0, 'pending' => 0, 'cancelled' => 0];
        foreach ($rows as $row) {
            $out[$row['status']] = (int) $row['total'];
        }
        return $out;
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM campaigns WHERE deleted_at IS NULL');
    }
}
