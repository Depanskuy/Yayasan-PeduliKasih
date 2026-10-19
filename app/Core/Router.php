<?php
// app/Core/Router.php
namespace App\Core;

class Router
{
    /** @var array<int, array{method:string,pattern:string,handler:array,middleware:?string}> */
    private array $routes = [];

    public function get(string $pattern, array $handler, ?string $middleware = null): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    public function post(string $pattern, array $handler, ?string $middleware = null): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    public function put(string $pattern, array $handler, ?string $middleware = null): void
    {
        $this->add('PUT', $pattern, $handler, $middleware);
    }

    public function delete(string $pattern, array $handler, ?string $middleware = null): void
    {
        $this->add('DELETE', $pattern, $handler, $middleware);
    }

    private function add(string $method, string $pattern, array $handler, ?string $middleware): void
    {
        $this->routes[] = compact('method', 'pattern', 'handler', 'middleware');
    }

    /**
     * Konversi pola `admin/campaigns/{id}/edit` menjadi regex.
     */
    private function compile(string $pattern): string
    {
        $pattern = trim($pattern, '/');
        if ($pattern === '') {
            return '#^$#';
        }
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '([^/]+)', $pattern);
        return '#^' . $regex . '/?$#';
    }

    public function dispatch(string $method, string $path, string $basePath = ''): void
    {
        $method = strtoupper($method);
        // Dukung method spoofing (_method) dari form.
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper((string) $_POST['_method']);
        }

        $path = trim($path, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (!preg_match($this->compile($route['pattern']), $path, $matches)) {
                continue;
            }

            array_shift($matches);

            // Middleware
            if ($route['middleware'] !== null) {
                $this->runMiddleware($route['middleware']);
            }

            [$class, $action] = $route['handler'];

            if (!class_exists($class)) {
                $this->abort(500, "Controller {$class} tidak ditemukan.");
            }

            $controller = new $class();
            if (!method_exists($controller, $action)) {
                $this->abort(500, "Method {$class}::{$action} tidak ditemukan.");
            }

            call_user_func_array([$controller, $action], $matches);
            return;
        }

        $this->abort(404, 'Halaman tidak ditemukan.');
    }

    private function runMiddleware(string $name): void
    {
        switch ($name) {
            case 'auth':
                Middleware::auth();
                break;
            case 'guest':
                Middleware::guest();
                break;
            case 'admin':
                Middleware::auth();
                Middleware::role(['superadmin', 'staff']);
                break;
            default:
                if (is_callable($name)) {
                    $name();
                }
        }
    }

    private function abort(int $code, string $message): void
    {
        http_response_code($code);
        $view = new View();
        echo $view->render('errors/' . $code, [
            'title'   => $code === 404 ? 'Halaman tidak ditemukan' : 'Terjadi kesalahan',
            'code'    => $code,
            'message' => $message,
        ], 'public');
    }
}
