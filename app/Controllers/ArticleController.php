<?php
// app/Controllers/ArticleController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Article;

class ArticleController extends Controller {
    protected Article $articleModel;

    public function __construct() {
        parent::__construct();
        $this->articleModel = new Article();
    }

    public function index(): void {
        $category = $_GET['category'] ?? null;
        $articles = $this->articleModel->getAllPublished($category, 12);

        $this->render('articles/index', [
            'title' => 'Kabar & Berita Kegiatan - Yayasan Peduli Kasih Sesama',
            'articles' => $articles,
            'currentCategory' => $category
        ]);
    }

    public function show(string $slug): void {
        $article = $this->articleModel->findBySlug($slug);
        if (!$article) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Artikel Tidak Ditemukan']);
            return;
        }

        $recentArticles = $this->articleModel->getAllPublished(null, 4);

        $this->render('articles/show', [
            'title' => $article['title'] . ' - Yayasan Peduli Kasih Sesama',
            'article' => $article,
            'recentArticles' => $recentArticles
        ]);
    }

    public function adminIndex(): void {
        $articles = $this->articleModel->getAllForAdmin();
        $this->render('articles/admin_index', [
            'title' => 'Manajemen Artikel & Berita - Panel Admin',
            'articles' => $articles
        ], 'dashboard');
    }

    public function create(): void {
        $this->render('articles/create', [
            'title' => 'Tulis Artikel / Berita Baru'
        ], 'dashboard');
    }

    public function store(): void {
        $data = $this->validate($_POST, [
            'title' => 'required|min:5',
            'category' => 'required',
            'excerpt' => 'required',
            'content' => 'required',
        ]);

        $featured = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $check = Security::validateUpload($_FILES['featured_image'], ['image/jpeg', 'image/png', 'image/webp']);
            if ($check['valid']) {
                $featured = 'art_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
                $dest = dirname(__DIR__, 2) . '/public/uploads/' . $featured;
                move_uploaded_file($_FILES['featured_image']['tmp_name'], $dest);
            }
        }

        $slug = slugify($data['title']);
        $existing = $this->articleModel->findBySlug($slug);
        if ($existing) {
            $slug .= '-' . time();
        }

        $user = auth_user();
        $this->articleModel->createArticle([
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'category' => $data['category'],
            'featured_image' => $featured,
            'author_id' => (int)$user['id'],
            'status' => $_POST['status'] ?? 'published'
        ]);

        $this->audit('ARTICLE_CREATED', "Menerbitkan artikel baru: '{$data['title']}'");

        $this->redirect(url('admin/articles'), ['success' => 'Artikel berhasil diterbitkan!']);
    }

    public function edit(int $id): void {
        $article = $this->articleModel->findById($id);
        if (!$article) {
            $this->redirect(url('admin/articles'), ['error' => 'Artikel tidak ditemukan.']);
        }

        $this->render('articles/edit', [
            'title' => 'Edit Artikel: ' . $article['title'],
            'article' => $article
        ], 'dashboard');
    }

    public function update(int $id): void {
        $article = $this->articleModel->findById($id);
        if (!$article) {
            $this->redirect(url('admin/articles'), ['error' => 'Artikel tidak ditemukan.']);
        }

        $data = $this->validate($_POST, [
            'title' => 'required|min:5',
            'category' => 'required',
            'excerpt' => 'required',
            'content' => 'required',
        ]);

        $updateData = [
            'title' => $data['title'],
            'category' => $data['category'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'status' => $_POST['status'] ?? 'published'
        ];

        if (!empty($_FILES['featured_image']['name'])) {
            $check = Security::validateUpload($_FILES['featured_image'], ['image/jpeg', 'image/png', 'image/webp']);
            if ($check['valid']) {
                $featured = 'art_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $check['extension'];
                $dest = dirname(__DIR__, 2) . '/public/uploads/' . $featured;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $dest)) {
                    $updateData['featured_image'] = $featured;
                }
            }
        }

        $this->articleModel->updateArticle($id, $updateData);
        $this->audit('ARTICLE_UPDATED', "Memperbarui artikel: '{$data['title']}'");

        $this->redirect(url('admin/articles'), ['success' => 'Artikel berhasil diperbarui!']);
    }
}
