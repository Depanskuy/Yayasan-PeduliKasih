<?php
// app/Core/View.php
namespace App\Core;

class View {
    public function render(string $viewPath, array $data = [], ?string $layout = 'main'): string {
        $viewFile = dirname(__DIR__) . '/Views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            return "<div style='padding:20px;background:#fee2e2;color:#991b1b;font-family:sans-serif;'>
                <h3>View Tidak Ditemukan</h3>
                <p>File view <code>{$viewPath}.php</code> tidak ada di direktori <code>app/Views/</code>.</p>
            </div>";
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // If view explicitly sets $layout = false or a custom layout, honor it
        if (isset($customLayout)) {
            $layout = $customLayout;
        }

        if ($layout) {
            $layoutFile = dirname(__DIR__) . '/Views/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                ob_start();
                include $layoutFile;
                return ob_get_clean();
            }
        }

        return $content;
    }
}
