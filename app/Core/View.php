<?php
// app/Core/View.php
namespace App\Core;

class View
{
    /**
     * Render view + layout.
     *
     * @param string      $viewPath Path relatif terhadap app/Views, mis. 'home/index'
     * @param array       $data     Variabel untuk view
     * @param string|null $layout   Nama layout di app/Views/layouts (tanpa .php), null = tanpa layout
     */
    public function render(string $viewPath, array $data = [], ?string $layout = 'public'): string
    {
        $viewFile = __DIR__ . '/../Views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            return "<div style=\"padding:2rem;font-family:sans-serif;color:#B91C1C;background:#F8FBF9\">View <strong>{$viewPath}</strong> tidak ditemukan.</div>";
        }

        // Sedikan $layout untuk view (agar bisa override) lalu render.
        $layoutName = $data['layout'] ?? $layout;

        extract($data, EXTR_SKIP);
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        if ($layoutName) {
            $layoutFile = __DIR__ . '/../Views/layouts/' . $layoutName . '.php';
            if (file_exists($layoutFile)) {
                // Variabel yang tersedia di layout: semua $data + $content
                extract($data, EXTR_OVERWRITE);
                ob_start();
                include $layoutFile;
                return ob_get_clean();
            }
        }

        return $content;
    }
}
