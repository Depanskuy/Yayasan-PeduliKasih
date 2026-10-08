<?php
// app/Models/CampaignCategory.php
namespace App\Models;

use App\Core\Model;

class CampaignCategory extends Model {
    protected string $table = 'campaign_categories';

    public function getAll(): array {
        return $this->fetchAll("SELECT * FROM campaign_categories ORDER BY name ASC");
    }

    public function findById(int $id): ?array {
        return $this->fetch("SELECT * FROM campaign_categories WHERE id = :id LIMIT 1", [':id' => $id]);
    }

    public function findBySlug(string $slug): ?array {
        return $this->fetch("SELECT * FROM campaign_categories WHERE slug = :slug LIMIT 1", [':slug' => $slug]);
    }
}
