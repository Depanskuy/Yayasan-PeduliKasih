<?php
// app/Core/Controller.php
namespace App\Core;

class Controller {
    protected $view;
    public function __construct() {
        $this->view = new View();
    }
    protected function render($viewPath, $data = []) {
        echo $this->view->render($viewPath, $data);
    }
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    protected function session($key, $value = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (func_num_args() == 2) {
            $_SESSION[$key] = $value;
        }
        return $_SESSION[$key] ?? null;
    }
}
?>
