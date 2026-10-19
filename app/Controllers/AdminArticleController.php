<?php
// app/Controllers/AdminArticleController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helper;
use App\Models\Article;
use App\Models\AuditLog;

class AdminArticleController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $model = new Article();
        $this->render('admin/articles', [
            'title'        => 'Artikel & Berita',
            'articles'     => $model->all(),
            'statusCounts' => $model->countByStatus(),
            'categories'   => Article::categoryLabels(),
        ]);
    }

    public function create(): void
    {
        $this->render('admin/article_form', [
            'title'      => 'Tambah Artikel',
            'article'    => null,
            'categories' => Article::categoryLabels(),
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrfOrFail('admin/articles/create');
        $data = $this->payload();

        if ($error = $this->validate($data)) {
            Helper::setFlash('error', $error);
            Helper::setOld($data);
            $this->redirect('admin/articles/create');
        }

        $model = new Article();
        $data['slug'] = $model->uniqueSlug($data['title']);
        $data['author_id'] = (int) $this->currentUser()['id'];

        $image = Helper::upload($_FILES['featured_image'] ?? null, 'articles');
        if ($image) {
            $data['featured_image'] = $image;
        }

        $id = $model->create($data);
        AuditLog::record($data['author_id'], 'ARTICLE_CREATE', "Menambah artikel #{$id}: {$data['title']}");

        Helper::clearOld();
        Helper::setFlash('success', 'Artikel berhasil ditambahkan.');
        $this->redirect('admin/articles');
    }

    public function edit(string $id): void
    {
        $article = (new Article())->find((int) $id);
        if (!$article) {
            Helper::setFlash('error', 'Artikel tidak ditemukan.');
            $this->redirect('admin/articles');
        }
        $this->render('admin/article_form', [
            'title'      => 'Edit Artikel',
            'article'    => $article,
            'categories' => Article::categoryLabels(),
        ]);
    }

    public function update(string $id): void
    {
        $this->verifyCsrfOrFail('admin/articles/' . $id . '/edit');
        $model = new Article();
        $article = $model->find((int) $id);
        if (!$article) {
            Helper::setFlash('error', 'Artikel tidak ditemukan.');
            $this->redirect('admin/articles');
        }

        $data = $this->payload();
        if ($error = $this->validate($data)) {
            Helper::setFlash('error', $error);
            Helper::setOld($data);
            $this->redirect('admin/articles/' . $id . '/edit');
        }

        $data['slug'] = $model->uniqueSlug($data['title'], (int) $id);
        $image = Helper::upload($_FILES['featured_image'] ?? null, 'articles');
        if ($image) {
            $data['featured_image'] = $image;
        }

        $model->update((int) $id, $data);
        AuditLog::record((int) $this->currentUser()['id'], 'ARTICLE_UPDATE', "Memperbarui artikel #{$id}: {$data['title']}");

        Helper::clearOld();
        Helper::setFlash('success', 'Artikel berhasil diperbarui.');
        $this->redirect('admin/articles');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrfOrFail('admin/articles');
        $model = new Article();
        $article = $model->find((int) $id);
        if (!$article) {
            Helper::setFlash('error', 'Artikel tidak ditemukan.');
            $this->redirect('admin/articles');
        }

        $model->delete((int) $id);
        AuditLog::record((int) $this->currentUser()['id'], 'ARTICLE_DELETE', "Menghapus artikel #{$id}: {$article['title']}");

        Helper::setFlash('success', 'Artikel berhasil dihapus.');
        $this->redirect('admin/articles');
    }

    private function payload(): array
    {
        return [
            'title'          => (string) $this->input('title'),
            'excerpt'        => (string) $this->input('excerpt'),
            'content'        => (string) $this->input('content'),
            'category'       => (string) ($this->input('category') ?: 'kegiatan'),
            'status'         => (string) ($this->input('status') ?: 'published'),
            'featured_image' => null,
        ];
    }

    private function validate(array $data): ?string
    {
        if (mb_strlen($data['title']) < 5) return 'Judul minimal 5 karakter.';
        if (mb_strlen($data['excerpt']) < 10) return 'Ringkasan minimal 10 karakter.';
        if (mb_strlen($data['content']) < 20) return 'Isi artikel minimal 20 karakter.';
        if (!array_key_exists($data['category'], Article::categoryLabels())) return 'Kategori tidak valid.';
        if (!in_array($data['status'], ['published', 'draft'], true)) return 'Status tidak valid.';
        return null;
    }
}
