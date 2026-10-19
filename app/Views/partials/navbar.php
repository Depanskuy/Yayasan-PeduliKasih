<?php
// app/Views/partials/navbar.php
use App\Core\Helper;

$settings    = $settings ?? [];
$orgName     = $settings['org_name'] ?? 'Peduli Kasih Sesama';
$currentUser = [
    'id'   => $_SESSION['user_id'] ?? null,
    'name' => $_SESSION['user_name'] ?? '',
    'role' => $_SESSION['user_role'] ?? '',
];
$isAdminRole = in_array($currentUser['role'], ['superadmin', 'staff'], true);

$links = [
    ['label' => 'Beranda',  'route' => '',                  'match' => ''],
    ['label' => 'Kampanye', 'route' => 'campaigns',         'match' => 'campaigns'],
    ['label' => 'Relawan',  'route' => 'volunteer/events',  'match' => 'volunteer/events'],
    ['label' => 'Artikel',  'route' => 'artikel',           'match' => 'artikel'],
    ['label' => 'Galeri',   'route' => 'galeri',            'match' => 'galeri'],
    ['label' => 'Tentang',  'route' => 'tentang',           'match' => 'tentang'],
    ['label' => 'Kontak',   'route' => 'kontak',            'match' => 'kontak'],
];
?>
<header class="sticky top-0 z-30 border-b border-line bg-canvas/80 backdrop-blur">
    <div class="container-page flex h-16 items-center justify-between gap-4">
        <a href="<?= Helper::url('') ?>" class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent/15 text-accent">
                <i data-lucide="heart-handshake" class="h-5 w-5"></i>
            </span>
            <span class="leading-tight">
                <span class="block text-sm font-semibold text-ink">Peduli Kasih</span>
                <span class="hidden text-[11px] text-ink-soft sm:block">Sesama</span>
            </span>
        </a>

        <nav class="hidden items-center gap-1 lg:flex">
            <?php foreach ($links as $link): ?>
                <a href="<?= Helper::url($link['route']) ?>"
                   class="rounded-lg px-3 py-2 text-sm transition <?= Helper::isActive($link['match']) ? 'text-accent' : 'text-ink-soft hover:text-ink' ?>">
                    <?= $link['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="hidden items-center gap-2 lg:flex">
            <?php if ($currentUser['id']): ?>
                <?php if ($isAdminRole): ?>
                    <a href="<?= Helper::url('admin/dashboard') ?>" class="btn-outline btn-sm">
                        <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Dashboard
                    </a>
                <?php endif; ?>
                <a href="<?= Helper::url('volunteer/reports') ?>" class="btn-ghost btn-sm">Laporan</a>
                <span class="text-sm text-ink-soft">Halo, <?= Helper::e(explode(' ', $currentUser['name'])[0]) ?></span>
                <a href="<?= Helper::url('logout') ?>" class="btn-ghost btn-sm">Keluar</a>
            <?php else: ?>
                <a href="<?= Helper::url('login') ?>" class="btn-ghost btn-sm">Masuk</a>
                <a href="<?= Helper::url('register') ?>" class="btn-primary btn-sm">Daftar</a>
            <?php endif; ?>
        </div>

        <button type="button" class="btn-ghost btn-sm lg:hidden" data-toggle="#mobile-menu" aria-label="Menu">
            <i data-lucide="menu" class="h-5 w-5"></i>
        </button>
    </div>

    <div id="mobile-menu" class="hidden border-t border-line bg-surface lg:hidden">
        <nav class="container-page flex flex-col gap-1 py-3">
            <?php foreach ($links as $link): ?>
                <a href="<?= Helper::url($link['route']) ?>"
                   class="rounded-lg px-3 py-2 text-sm <?= Helper::isActive($link['match']) ? 'bg-accent/10 text-accent' : 'text-ink-soft hover:bg-black/5 hover:text-ink' ?>">
                    <?= $link['label'] ?>
                </a>
            <?php endforeach; ?>
            <div class="mt-2 flex gap-2 border-t border-line pt-3">
                <?php if ($currentUser['id']): ?>
                    <?php if ($isAdminRole): ?>
                        <a href="<?= Helper::url('admin/dashboard') ?>" class="btn-outline btn-sm flex-1">Dashboard</a>
                    <?php endif; ?>
                    <a href="<?= Helper::url('volunteer/reports') ?>" class="btn-outline btn-sm flex-1">Laporan</a>
                    <a href="<?= Helper::url('logout') ?>" class="btn-ghost btn-sm flex-1">Keluar</a>
                <?php else: ?>
                    <a href="<?= Helper::url('login') ?>" class="btn-ghost btn-sm flex-1">Masuk</a>
                    <a href="<?= Helper::url('register') ?>" class="btn-primary btn-sm flex-1">Daftar</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
