<?php
// app/Models/Article.php
namespace App\Models;

use App\Core\Model;

class Article extends Model {
    protected string $table = 'articles';

    public function getAllPublished(?string $category = null, int $limit = 10): array {
        $sql = "SELECT a.*, u.name AS author_name 
                FROM articles a
                JOIN users u ON a.author_id = u.id
                WHERE a.status = 'published'";
        $params = [];
        if ($category) {
            $sql .= " AND a.category = :cat";
            $params[':cat'] = $category;
        }
        $sql .= " ORDER BY a.created_at DESC LIMIT " . (int)$limit;
        return $this->fetchAll($sql, $params);
    }

    public function findBySlug(string $slug): ?array {
        $sql = "SELECT a.*, u.name AS author_name 
                FROM articles a
                JOIN users u ON a.author_id = u.id
                WHERE a.slug = :slug LIMIT 1";
        $article = $this->fetch($sql, [':slug' => $slug]);
        if ($article) {
            // Increment views count
            $this->query("UPDATE articles SET views_count = views_count + 1 WHERE id = :id", [':id' => $article['id']]);
        }
        return $article;
    }

    public function findById(int $id): ?array {
        return $this->fetch("SELECT * FROM articles WHERE id = :id LIMIT 1", [':id' => $id]);
    }

    public function getAllForAdmin(): array {
        $sql = "SELECT a.*, u.name AS author_name 
                FROM articles a
                JOIN users u ON a.author_id = u.id
                ORDER BY a.id DESC";
        return $this->fetchAll($sql);
    }

    public function createArticle(array $data): int {
        return $this->insert($this->table, [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'category' => $data['category'] ?? 'kegiatan',
            'featured_image' => $data['featured_image'] ?? null,
            'author_id' => $data['author_id'],
            'status' => $data['status'] ?? 'published',
        ]);
    }

    public function updateArticle(int $id, array $data): bool {
        return $this->update($this->table, $id, $data);
    }
}
