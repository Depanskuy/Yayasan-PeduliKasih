<?php
// app/Models/Article.php
namespace App\Models;

use App\Core\Model;
use App\Core\Helper;

class Article extends Model
{
    public function published(array $filters = []): array
    {
        $sql = "SELECT a.*, u.name AS author_name
                FROM articles a
                LEFT JOIN users u ON u.id = a.author_id
                WHERE a.status = 'published'";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= ' AND a.category = ?';
            $params[] = $filters['category'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (a.title LIKE ? OR a.excerpt LIKE ?)';
            $like = '%' . $filters['search'] . '%';
            array_push($params, $like, $like);
        }

        $sql .= ' ORDER BY a.created_at DESC';

        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ' . (int) $filters['limit'];
        }

        return $this->fetchAll($sql, $params);
    }

    public function all(): array
    {
        return $this->fetchAll(
            'SELECT a.*, u.name AS author_name
             FROM articles a
             LEFT JOIN users u ON u.id = a.author_id
             ORDER BY a.created_at DESC'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT a.*, u.name AS author_name FROM articles a LEFT JOIN users u ON u.id = a.author_id WHERE a.id = ?',
            [$id]
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->fetchOne(
            'SELECT a.*, u.name AS author_name FROM articles a LEFT JOIN users u ON u.id = a.author_id WHERE a.slug = ?',
            [$slug]
        );
    }

    public function incrementViews(int $id): void
    {
        $this->query('UPDATE articles SET views_count = views_count + 1 WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO articles
                    (title, slug, excerpt, content, category, featured_image, author_id, status, views_count, created_at)
                VALUES
                    (:title, :slug, :excerpt, :content, :category, :featured_image, :author_id, :status, 0, NOW())';
        $this->query($sql, [
            ':title'          => $data['title'],
            ':slug'           => $data['slug'],
            ':excerpt'        => $data['excerpt'],
            ':content'        => $data['content'],
            ':category'       => $data['category'],
            ':featured_image' => $data['featured_image'] ?? null,
            ':author_id'      => (int) $data['author_id'],
            ':status'         => $data['status'] ?? 'published',
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $sql = 'UPDATE articles SET
                    title = :title,
                    slug = :slug,
                    excerpt = :excerpt,
                    content = :content,
                    category = :category,
                    featured_image = COALESCE(:featured_image, featured_image),
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id';
        return $this->query($sql, [
            ':title'          => $data['title'],
            ':slug'           => $data['slug'],
            ':excerpt'        => $data['excerpt'],
            ':content'        => $data['content'],
            ':category'       => $data['category'],
            ':featured_image' => $data['featured_image'] ?? null,
            ':status'         => $data['status'] ?? 'published',
            ':id'             => $id,
        ])->rowCount();
    }

    public function delete(int $id): int
    {
        return $this->query('DELETE FROM articles WHERE id = ?', [$id])->rowCount();
    }

    public function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Helper::slugify($base);
        $candidate = $slug;
        $i = 2;
        while (true) {
            $sql = 'SELECT COUNT(*) FROM articles WHERE slug = ?';
            $params = [$candidate];
            if ($ignoreId) {
                $sql .= ' AND id <> ?';
                $params[] = $ignoreId;
            }
            if (!$this->scalar($sql, $params)) {
                return $candidate;
            }
            $candidate = $slug . '-' . $i++;
        }
    }

    public function countByStatus(): array
    {
        $rows = $this->fetchAll('SELECT status, COUNT(*) AS total FROM articles GROUP BY status');
        $out = ['published' => 0, 'draft' => 0];
        foreach ($rows as $row) {
            $out[$row['status']] = (int) $row['total'];
        }
        return $out;
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM articles');
    }

    public static function categoryLabels(): array
    {
        return [
            'berita'    => 'Berita',
            'inspirasi' => 'Inspirasi',
            'kegiatan'  => 'Kegiatan',
            'edukasi'   => 'Edukasi',
        ];
    }
}
