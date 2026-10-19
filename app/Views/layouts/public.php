<?php
// app/Views/layouts/public.php
use App\Core\Helper;

$settings    = $settings ?? (new \App\Models\Setting())->all();
$orgName     = $settings['org_name'] ?? 'Yayasan Peduli Kasih Sesama';
$currentUser = [
    'id'    => $_SESSION['user_id'] ?? null,
    'name'  => $_SESSION['user_name'] ?? '',
    'role'  => $_SESSION['user_role'] ?? '',
];
$isAdminRole = in_array($currentUser['role'], ['superadmin', 'staff'], true);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= Helper::e($settings['org_tagline'] ?? 'Yayasan sosial untuk sesama') ?>">
    <title><?= Helper::e($title ?? 'Beranda') ?> — <?= Helper::e($orgName) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= Helper::asset('css/app.css') ?>">
</head>
<body class="min-h-screen bg-base text-ink">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <main>
        <?php include __DIR__ . '/../partials/flash.php'; ?>
        <?= $content ?? '' ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="<?= Helper::asset('js/app.js') ?>"></script>
</body>
</html>
