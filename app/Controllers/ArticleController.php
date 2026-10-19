<?php
// app/Controllers/ArticleController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class ArticleController extends Controller
{
    protected string $layout = 'public';

    public function index(): void
    {
        $filters = [
            'category' => (string) ($this->input('kategori') ?? ''),
            'search'   => (string) ($this->input('q') ?? ''),
        ];
        $categories = Article::categoryLabels();
        if ($filters['category'] !== '' && !array_key_exists($filters['category'], $categories)) {
            $filters['category'] = '';
        }

        $this->render('articles/index', [
            'title'      => 'Artikel & Berita',
            'articles'   => (new Article())->published(array_filter($filters)),
            'categories' => $categories,
            'filters'    => $filters,
        ]);
    }

    public function show(string $slug): void
    {
        $model   = new Article();
        $article = $model->findBySlug($slug);

        if (!$article) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Artikel tidak ditemukan']);
            return;
        }

        $model->incrementViews((int) $article['id']);

        $related = array_values(array_filter(
            $model->published(['category' => $article['category'], 'limit' => 5]),
            fn ($item) => (int) $item['id'] !== (int) $article['id']
        ));

        $this->render('articles/show', [
            'title'   => $article['title'],
            'article' => $article,
            'related' => array_slice($related, 0, 3),
        ]);
    }
}
