<?php
// app/Views/errors/404.php
use App\Core\Helper;
$code    = $code ?? 404;
$message = $message ?? 'Halaman yang Anda cari tidak ditemukan.';
?>
<section class="py-20">
    <div class="container-page flex min-h-[50vh] flex-col items-center justify-center text-center">
        <p class="text-7xl font-bold tracking-tight text-accent/30 sm:text-8xl"><?= (int) $code ?></p>
        <h1 class="mt-4 text-2xl font-semibold text-ink"><?= $code === 404 ? 'Halaman tidak ditemukan' : 'Terjadi kesalahan' ?></h1>
        <p class="mt-2 max-w-md text-sm text-ink-soft"><?= Helper::e($message) ?></p>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="<?= Helper::url('') ?>" class="btn-primary">
                <i data-lucide="home" class="h-4 w-4"></i> Kembali ke Beranda
            </a>
            <a href="<?= Helper::url('campaigns') ?>" class="btn-outline">Lihat Kampanye</a>
        </div>
    </div>
</section>
