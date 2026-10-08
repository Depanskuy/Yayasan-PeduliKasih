<?php
// app/Core/Controller.php
namespace App\Core;

class Controller {
    protected View $view;

    public function __construct() {
        Security::startSession();
        $this->view = new View();
    }

    protected function render(string $viewPath, array $data = [], ?string $layout = 'main'): void {
        echo $this->view->render($viewPath, $data, $layout);
    }

    protected function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url, array $flash = []): void {
        foreach ($flash as $key => $message) {
            $_SESSION['flash'][$key] = $message;
        }
        header('Location: ' . $url);
        exit;
    }

    protected function back(array $flash = []): void {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        $this->redirect($referer, $flash);
    }

    protected function session(string $key, mixed $value = null): mixed {
        if (func_num_args() === 2) {
            $_SESSION[$key] = $value;
        }
        return $_SESSION[$key] ?? null;
    }

    protected function validate(array $data, array $rules): array {
        $errors = [];
        $sanitized = [];

        foreach ($rules as $field => $fieldRules) {
            $value = trim($data[$field] ?? '');
            $sanitized[$field] = $value;
            $ruleList = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && $value === '') {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi.';
                    break;
                }
                if ($rule === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Format email tidak valid.';
                    break;
                }
                if ($rule === 'numeric' && $value !== '' && !is_numeric($value)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' harus berupa angka.';
                    break;
                }
                if (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " minimal {$min} karakter.";
                        break;
                    }
                }
                if (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " maksimal {$max} karakter.";
                        break;
                    }
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            $_SESSION['flash']['error'] = reset($errors);
            $this->back();
        }

        // Clear previous input if successful
        unset($_SESSION['old_input'], $_SESSION['errors']);
        return $sanitized;
    }

    protected function audit(string $action, string $description): void {
        AuditLogger::log($action, $description);
    }

    protected function user(): ?array {
        return auth_user();
    }
}
