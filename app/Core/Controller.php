<?php
// app/Core/Controller.php
namespace App\Core;

class Controller
{
    protected View $view;
    protected string $layout = 'public';

    public function __construct()
    {
        $this->view = new View();
    }

    /** Render view dengan layout default controller. */
    protected function render(string $viewPath, array $data = [], ?string $layout = null): void
    {
        echo $this->view->render($viewPath, $data, $layout ?? $this->layout);
    }

    /** Output JSON (untuk endpoint API). */
    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . (preg_match('#^https?://#', $url) ? $url : Helper::url($url)));
        exit;
    }

    /** Ambil input POST/GET dengan default + trimming. */
    protected function input(string $key, $default = null)
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    protected function only(array $keys): array
    {
        $out = [];
        foreach ($keys as $key) {
            $out[$key] = $this->input($key);
        }
        return $out;
    }

    protected function session(string $key, $value = null)
    {
        Helper::ensureSession();
        if (func_num_args() === 2) {
            $_SESSION[$key] = $value;
        }
        return $_SESSION[$key] ?? null;
    }

    protected function currentUser(): ?array
    {
        Helper::ensureSession();
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? '',
        ];
    }

    /** Pastikan request mengirim CSRF valid, jika tidak kembali ke halaman asal. */
    protected function verifyCsrfOrFail(string $redirectTo): void
    {
        if (!Helper::verifyCsrf($_POST['_csrf'] ?? null)) {
            Helper::setFlash('error', 'Sesi keamanan tidak valid. Silakan coba lagi.');
            $this->redirect($redirectTo);
        }
    }
}
