<?php
// public/index.php
// Front controller for the MVC application

// Enable error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Autoload classes via Composer
require __DIR__ . '/../vendor/autoload.php';

// Load database connection (singleton) if needed elsewhere
$pdo = require __DIR__ . '/../config/database.php';

// Simple router based on the request URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Define routes: [method, pattern, controller@action]
$routes = [
    ['GET', '', 'HomeController@index'],
    ['GET', 'login', 'AuthController@showLogin'],
    ['POST', 'login', 'AuthController@login'],
    ['GET', 'register', 'AuthController@showRegister'],
    ['POST', 'register', 'AuthController@register'],
    ['GET', 'logout', 'AuthController@logout'],
    ['GET', 'campaigns', 'CampaignController@index'],
    ['GET', 'campaigns/([0-9]+)', 'CampaignController@show'],
    ['POST', 'campaigns/([0-9]+)/donate', 'DonationController@store'],
    ['GET', 'volunteer/events', 'VolunteerController@listEvents'],
    ['GET', 'volunteer/events/([0-9]+)/register', 'VolunteerController@showRegister'],
    ['POST', 'volunteer/events/([0-9]+)/register', 'VolunteerController@register'],
    // Add more routes as needed
];

$matched = false;
foreach ($routes as $route) {
    [$method, $pattern, $handler] = $route;
    if ($_SERVER['REQUEST_METHOD'] !== $method) continue;
    $regex = '#^' . $pattern . '$#';
    if (preg_match($regex, $uri, $matches)) {
        $matched = true;
        [$controllerName, $action] = explode('@', $handler);
        $controllerClass = "App\\Controllers\\" . $controllerName;
        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "Controller $controllerClass not found";
            exit;
        }
        $controller = new $controllerClass();
        // Remove full match from $matches
        array_shift($matches);
        call_user_func_array([$controller, $action], $matches);
        break;
    }
}

if (!$matched) {
    http_response_code(404);
    echo "Page not found";
}
?>
