<?php
// app/Core/Router.php
namespace App\Core;

class Router {
    protected static array $routes = [];
    protected static string $prefix = '';
    protected static array $groupMiddlewares = [];

    public static function get(string $path, string|array|callable $action, array $middlewares = []): void {
        self::addRoute('GET', $path, $action, $middlewares);
    }

    public static function post(string $path, string|array|callable $action, array $middlewares = []): void {
        self::addRoute('POST', $path, $action, $middlewares);
    }

    public static function group(array $attributes, callable $callback): void {
        $previousPrefix = self::$prefix;
        $previousMiddlewares = self::$groupMiddlewares;

        if (isset($attributes['prefix'])) {
            self::$prefix = rtrim(self::$prefix, '/') . '/' . trim($attributes['prefix'], '/');
        }

        if (isset($attributes['middleware'])) {
            $middlewares = (array)$attributes['middleware'];
            self::$groupMiddlewares = array_merge(self::$groupMiddlewares, $middlewares);
        }

        call_user_func($callback);

        self::$prefix = $previousPrefix;
        self::$groupMiddlewares = $previousMiddlewares;
    }

    protected static function addRoute(string $method, string $path, string|array|callable $action, array $middlewares = []): void {
        $fullPath = rtrim(self::$prefix, '/') . '/' . trim($path, '/');
        $fullPath = '/' . trim($fullPath, '/');
        if ($fullPath !== '/') {
            $fullPath = rtrim($fullPath, '/');
        }

        $allMiddlewares = array_merge(self::$groupMiddlewares, $middlewares);

        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $fullPath,
            'action' => $action,
            'middlewares' => $allMiddlewares
        ];
    }

    public static function dispatch(): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

        // Get URI and clean subfolder path
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        $scriptDir = str_replace('\\', '/', $scriptDir);

        if ($scriptDir !== '/' && $scriptDir !== '.' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }

        $uri = '/' . trim($uri, '/');
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        foreach (self::$routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Convert route {param} to regex
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match

                // Execute middlewares
                if (!self::runMiddlewares($route['middlewares'])) {
                    return;
                }

                // CSRF verification for POST/PUT/DELETE
                if (in_array($requestMethod, ['POST', 'PUT', 'DELETE'])) {
                    $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
                    if (!Security::validateCsrfToken($token)) {
                        http_response_code(419);
                        echo "<div style='font-family:sans-serif;padding:30px;text-align:center;'>
                            <h2 style='color:#dc2626;'>419 - Sesi Kedaluwarsa (CSRF Token Mismatch)</h2>
                            <p>Sesi Anda telah berakhir atau formulir tidak valid. Silakan kembali dan muat ulang halaman.</p>
                            <a href='javascript:history.back()' style='display:inline-block;padding:10px 20px;background:#059669;color:#fff;border-radius:6px;text-decoration:none;'>Kembali</a>
                        </div>";
                        return;
                    }
                }

                self::executeAction($route['action'], $matches);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        $view = new View();
        echo $view->render('errors/404', ['title' => '404 - Halaman Tidak Ditemukan']);
    }

    protected static function runMiddlewares(array $middlewares): bool {
        foreach ($middlewares as $mw) {
            if ($mw === 'auth') {
                if (!is_logged_in()) {
                    $_SESSION['flash']['error'] = 'Silakan login terlebih dahulu untuk mengakses halaman ini.';
                    header('Location: ' . url('login'));
                    exit;
                }
            } elseif ($mw === 'guest') {
                if (is_logged_in()) {
                    $user = auth_user();
                    if (in_array($user['role'], ['superadmin', 'staff'])) {
                        header('Location: ' . url('admin/dashboard'));
                    } elseif ($user['role'] === 'volunteer') {
                        header('Location: ' . url('volunteer/dashboard'));
                    } else {
                        header('Location: ' . url('donor/dashboard'));
                    }
                    exit;
                }
            } elseif (str_starts_with($mw, 'role:')) {
                $allowedRoles = explode(',', substr($mw, 5));
                if (!is_logged_in() || !has_role($allowedRoles)) {
                    http_response_code(403);
                    $view = new View();
                    echo $view->render('errors/403', ['title' => '403 - Akses Ditolak']);
                    return false;
                }
            }
        }
        return true;
    }

    protected static function executeAction(string|array|callable $action, array $params): void {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }

        if (is_string($action)) {
            [$controllerName, $method] = explode('@', $action);
            $controllerClass = "App\\Controllers\\" . $controllerName;

            if (!class_exists($controllerClass)) {
                http_response_code(500);
                die("Controller class '{$controllerClass}' tidak ditemukan.");
            }

            $controller = new $controllerClass();
            if (!method_exists($controller, $method)) {
                http_response_code(500);
                die("Method '{$method}' tidak ditemukan pada class '{$controllerClass}'.");
            }

            call_user_func_array([$controller, $method], $params);
            return;
        }

        if (is_array($action)) {
            [$controllerClass, $method] = $action;
            $controller = new $controllerClass();
            call_user_func_array([$controller, $method], $params);
        }
    }
}
