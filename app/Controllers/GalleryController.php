<?php
// app/Controllers/GalleryController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Gallery;

class GalleryController extends Controller
{
    protected string $layout = 'public';

    public function index(): void
    {
        $this->render('gallery/index', [
            'title' => 'Galeri Kegiatan',
            'items' => (new Gallery())->all(),
        ]);
    }
}
