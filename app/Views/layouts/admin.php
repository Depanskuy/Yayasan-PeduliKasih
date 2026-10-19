<?php
// app/Views/layouts/admin.php
use App\Core\Helper;

$settings    = $settings ?? (new \App\Models\Setting())->all();
$orgName     = $settings['org_name'] ?? 'Yayasan Peduli Kasih Sesama';
$currentUser = [
    'id'    => $_SESSION['user_id'] ?? null,
    'name'  => $_SESSION['user_name'] ?? 'Admin',
    'email' => $_SESSION['user_email'] ?? '',
    'role'  => $_SESSION['user_role'] ?? '',
];

$nav = [
    ['label' => 'Dashboard',         'icon' => 'layout-dashboard', 'route' => 'admin/dashboard',      'match' => 'admin/dashboard'],
    ['label' => 'User',              'icon' => 'users',            'route' => 'admin/users',          'match' => 'admin/users'],
    ['label' => 'Kampanye',          'icon' => 'megaphone',        'route' => 'admin/campaigns',      'match' => 'admin/campaigns'],
    ['label' => 'Kabar Kampanye',    'icon' => 'newspaper',        'route' => 'admin/campaign-updates', 'match' => 'admin/campaign-updates'],
    ['label' => 'Donasi',            'icon' => 'hand-coins',       'route' => 'admin/donations',      'match' => 'admin/donations'],
    ['label' => 'Penyaluran',        'icon' => 'send',             'route' => 'admin/disbursements',  'match' => 'admin/disbursements'],
    ['label' => 'Penerima Manfaat',  'icon' => 'heart-handshake',  'route' => 'admin/beneficiaries',  'match' => 'admin/beneficiaries'],
    ['label' => 'Relawan',           'icon' => 'users-round',      'route' => 'admin/volunteers',     'match' => 'admin/volunteers'],
    ['label' => 'Laporan Relawan',   'icon' => 'clipboard-check',  'route' => 'admin/field-reports',  'match' => 'admin/field-reports'],
    ['label' => 'Artikel',           'icon' => 'file-text',        'route' => 'admin/articles',       'match' => 'admin/articles'],
    ['label' => 'Galeri',            'icon' => 'image',            'route' => 'admin/gallery',        'match' => 'admin/gallery'],
    ['label' => 'Audit Log',         'icon' => 'scroll-text',      'route' => 'admin/audit-logs',     'match' => 'admin/audit-logs'],
    ['label' => 'Laporan Keuangan',  'icon' => 'bar-chart-3',      'route' => 'admin/reports',        'match' => 'admin/reports'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helper::e($title ?? 'Admin') ?> — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= Helper::asset('css/app.css') ?>">
</head>
<body class="min-h-screen bg-canvas text-ink">

<!-- Overlay mobile -->
<div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black/60 lg:hidden" data-close-sidebar></div>

<!-- Sidebar -->
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-line bg-surface transition-transform duration-200 lg:translate-x-0">
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-line px-5">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent/15 text-accent">
            <i data-lucide="heart-handshake" class="h-5 w-5"></i>
        </span>
        <div class="leading-tight">
            <p class="text-sm font-semibold text-ink">Peduli Kasih</p>
            <p class="text-xs text-ink-soft">Admin Panel</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        <?php foreach ($nav as $item): ?>
            <a href="<?= Helper::url($item['route']) ?>"
               class="side-link <?= Helper::isActive($item['match']) ? 'side-link-active' : '' ?>">
                <i data-lucide="<?= $item['icon'] ?>" class="h-[18px] w-[18px]"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="shrink-0 border-t border-line p-3">
        <a href="<?= Helper::url('') ?>" class="side-link">
            <i data-lucide="globe" class="h-[18px] w-[18px]"></i>
            <span>Lihat Situs</span>
        </a>
        <a href="<?= Helper::url('logout') ?>" class="side-link">
            <i data-lucide="log-out" class="h-[18px] w-[18px]"></i>
            <span>Keluar</span>
        </a>
    </div>
</aside>

<!-- Main -->
<div class="lg:pl-64">
    <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-line bg-canvas/80 px-4 backdrop-blur sm:px-6">
        <div class="flex items-center gap-3">
            <button type="button" class="btn-ghost btn-sm lg:hidden" data-open-sidebar aria-label="Buka menu">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>
            <div>
                <h1 class="text-base font-semibold text-ink sm:text-lg"><?= Helper::e($title ?? 'Dashboard') ?></h1>
                <p class="hidden text-xs text-ink-soft sm:block"><?= Helper::e($orgName) ?></p>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            <span class="badge-accent hidden sm:inline-flex">
                <i data-lucide="shield-check" class="h-3.5 w-3.5"></i>
                <?= Helper::e(ucfirst($currentUser['role'])) ?>
            </span>
            <div class="flex items-center gap-2 rounded-lg border border-line bg-card px-2.5 py-1.5">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-accent/20 text-xs font-semibold text-accent">
                    <?= Helper::e(Helper::initials($currentUser['name'])) ?>
                </span>
                <span class="hidden text-sm text-ink md:inline"><?= Helper::e($currentUser['name']) ?></span>
            </div>
        </div>
    </header>

    <main class="p-4 sm:p-6">
        <?php include __DIR__ . '/../partials/flash.php'; ?>
        <?= $content ?? '' ?>
    </main>
</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script src="<?= Helper::asset('js/app.js') ?>"></script>
</body>
</html>
