<?php
// public/index.php — Front Controller
declare(strict_types=1);

use App\Core\Router;

ini_set('display_errors', '1');
error_reporting(E_ALL);

/* ------------------------------------------------------------------
 | Autoloader (Composer, dengan fallback PSR-4 bila vendor belum ada)
 * ------------------------------------------------------------------ */
$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require $vendorAutoload;
}
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = $baseDir . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

/* ------------------------------------------------------------------
 | Konfigurasi umum & base path
 * ------------------------------------------------------------------ */
$config = require __DIR__ . '/../config/config.php';
date_default_timezone_set($config['timezone'] ?? 'Asia/Jakarta');

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath === '.' || $basePath === '/') {
    $basePath = '';
}

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
$scheme = $isHttps ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

define('BASE_PATH', $basePath);
define('BASE_URL', $scheme . '://' . $host . $basePath);
define('APP_CONFIG', $config);

// Flash / session
\App\Core\Helper::ensureSession();

/* ------------------------------------------------------------------
 | Parse request path (buang base path)
 * ------------------------------------------------------------------ */
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($basePath !== '' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$path = trim($uri, '/');

/* ------------------------------------------------------------------
 | Definisi rute
 * ------------------------------------------------------------------ */
$router = new Router();

/* --- Publik --- */
$router->get('', [\App\Controllers\HomeController::class, 'index']);
$router->get('tentang', [\App\Controllers\HomeController::class, 'about']);
$router->get('kontak', [\App\Controllers\HomeController::class, 'contact']);
$router->get('artikel', [\App\Controllers\ArticleController::class, 'index']);
$router->get('artikel/{slug}', [\App\Controllers\ArticleController::class, 'show']);
$router->get('galeri', [\App\Controllers\GalleryController::class, 'index']);

/* --- Auth --- */
$router->get('login', [\App\Controllers\AuthController::class, 'showLogin'], 'guest');
$router->post('login', [\App\Controllers\AuthController::class, 'login'], 'guest');
$router->get('register', [\App\Controllers\AuthController::class, 'showRegister'], 'guest');
$router->post('register', [\App\Controllers\AuthController::class, 'register'], 'guest');
$router->get('logout', [\App\Controllers\AuthController::class, 'logout']);

/* --- Kampanye & donasi publik --- */
$router->get('campaigns', [\App\Controllers\CampaignController::class, 'index']);
$router->get('campaigns/{id}', [\App\Controllers\CampaignController::class, 'show']);
$router->post('campaigns/{id}/donate', [\App\Controllers\DonationController::class, 'store']);

/* --- Relawan --- */
$router->get('volunteer/events', [\App\Controllers\VolunteerController::class, 'listEvents']);
$router->get('volunteer/events/{id}/register', [\App\Controllers\VolunteerController::class, 'showRegister'], 'auth');
$router->post('volunteer/events/{id}/register', [\App\Controllers\VolunteerController::class, 'register'], 'auth');
$router->get('volunteer/reports', [\App\Controllers\VolunteerController::class, 'myReports'], 'auth');
$router->get('volunteer/events/{id}/report', [\App\Controllers\VolunteerController::class, 'showReport'], 'auth');
$router->post('volunteer/events/{id}/report', [\App\Controllers\VolunteerController::class, 'storeReport'], 'auth');

/* --- Dashboard & area admin --- */
$router->get('admin', [\App\Controllers\AdminController::class, 'dashboard'], 'admin');
$router->get('admin/dashboard', [\App\Controllers\AdminController::class, 'dashboard'], 'admin');

$router->get('admin/users', [\App\Controllers\AdminController::class, 'users'], 'admin');
$router->post('admin/users/{id}/status', [\App\Controllers\AdminController::class, 'updateUserStatus'], 'admin');

$router->get('admin/campaigns', [\App\Controllers\AdminController::class, 'campaigns'], 'admin');
$router->get('admin/campaigns/create', [\App\Controllers\AdminController::class, 'createCampaign'], 'admin');
$router->post('admin/campaigns', [\App\Controllers\AdminController::class, 'storeCampaign'], 'admin');
$router->get('admin/campaigns/{id}/edit', [\App\Controllers\AdminController::class, 'editCampaign'], 'admin');
$router->post('admin/campaigns/{id}', [\App\Controllers\AdminController::class, 'updateCampaign'], 'admin');
$router->post('admin/campaigns/{id}/delete', [\App\Controllers\AdminController::class, 'deleteCampaign'], 'admin');

$router->get('admin/donations', [\App\Controllers\AdminController::class, 'donations'], 'admin');
$router->post('admin/donations/{id}/verify', [\App\Controllers\AdminController::class, 'verifyDonation'], 'admin');
$router->post('admin/donations/{id}/reject', [\App\Controllers\AdminController::class, 'rejectDonation'], 'admin');

$router->get('admin/disbursements', [\App\Controllers\AdminController::class, 'disbursements'], 'admin');
$router->get('admin/disbursements/create', [\App\Controllers\AdminController::class, 'createDisbursement'], 'admin');
$router->post('admin/disbursements', [\App\Controllers\AdminController::class, 'storeDisbursement'], 'admin');

$router->get('admin/beneficiaries', [\App\Controllers\AdminController::class, 'beneficiaries'], 'admin');
$router->get('admin/beneficiaries/create', [\App\Controllers\AdminController::class, 'createBeneficiary'], 'admin');
$router->post('admin/beneficiaries', [\App\Controllers\AdminController::class, 'storeBeneficiary'], 'admin');

$router->get('admin/volunteers', [\App\Controllers\AdminController::class, 'volunteers'], 'admin');
$router->post('admin/volunteers/{id}/status', [\App\Controllers\AdminController::class, 'updateVolunteerStatus'], 'admin');

/* --- Konten: artikel, galeri, kabar kampanye --- */
$router->get('admin/articles', [\App\Controllers\AdminArticleController::class, 'index'], 'admin');
$router->get('admin/articles/create', [\App\Controllers\AdminArticleController::class, 'create'], 'admin');
$router->post('admin/articles', [\App\Controllers\AdminArticleController::class, 'store'], 'admin');
$router->get('admin/articles/{id}/edit', [\App\Controllers\AdminArticleController::class, 'edit'], 'admin');
$router->post('admin/articles/{id}', [\App\Controllers\AdminArticleController::class, 'update'], 'admin');
$router->post('admin/articles/{id}/delete', [\App\Controllers\AdminArticleController::class, 'destroy'], 'admin');

$router->get('admin/gallery', [\App\Controllers\AdminGalleryController::class, 'index'], 'admin');
$router->post('admin/gallery', [\App\Controllers\AdminGalleryController::class, 'store'], 'admin');
$router->post('admin/gallery/{id}/delete', [\App\Controllers\AdminGalleryController::class, 'destroy'], 'admin');

$router->get('admin/campaign-updates', [\App\Controllers\AdminCampaignUpdateController::class, 'index'], 'admin');
$router->post('admin/campaign-updates', [\App\Controllers\AdminCampaignUpdateController::class, 'store'], 'admin');
$router->post('admin/campaign-updates/{id}/delete', [\App\Controllers\AdminCampaignUpdateController::class, 'destroy'], 'admin');

/* --- Laporan relawan & audit log --- */
$router->get('admin/field-reports', [\App\Controllers\AdminFieldReportController::class, 'index'], 'admin');
$router->post('admin/field-reports/{id}/status', [\App\Controllers\AdminFieldReportController::class, 'updateStatus'], 'admin');
$router->get('admin/audit-logs', [\App\Controllers\AdminAuditLogController::class, 'index'], 'admin');

$router->get('admin/reports', [\App\Controllers\ReportController::class, 'financial'], 'admin');
$router->get('admin/reports/download', [\App\Controllers\ReportController::class, 'exportCsv'], 'admin');

/* --- API --- */
$router->get('api/donations/monthly', [\App\Controllers\DonationController::class, 'monthly']);

/* ------------------------------------------------------------------
 | Dispatch
 * ------------------------------------------------------------------ */
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path, $basePath);
