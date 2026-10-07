<?php
// app/Core/View.php
namespace App\Core;

class View {
    /**
     * Render a view file with optional data.
     * @param string $viewPath Path relative to app/Views, e.g. 'home/index'
     * @param array $data Variables to extract into view scope
     * @return string Rendered HTML
     */
    public function render(string $viewPath, array $data = []): string {
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            return "View $viewPath not found";
        }
        // Extract data variables
        extract($data, EXTR_SKIP);
        // Start output buffering
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        // If layout is defined inside view, use it; otherwise just return content
        if (isset($layout) && $layout) {
            $layoutFile = __DIR__ . '/../Views/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                ob_start();
                include $layoutFile; // layout expects $content variable
                return ob_get_clean();
            }
        }
        return $content;
    }
}
?>
