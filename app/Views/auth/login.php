<?php
// app/Views/auth/login.php
use App\Core\Helper;
?>
<section class="min-h-[calc(100vh-4rem)] py-12">
    <div class="container-page">
        <div class="mx-auto grid max-w-4xl overflow-hidden rounded-xl border border-line bg-card shadow-soft lg:grid-cols-2">

            <!-- Branding -->
            <div class="relative hidden flex-col justify-between bg-gradient-to-br from-[#D1FAE5] to-[#F0FDF9] p-8 lg:flex">
                <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-accent/10 blur-2xl"></div>
                <div class="relative">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-accent text-white">
                        <i data-lucide="heart-handshake" class="h-5 w-5"></i>
                    </span>
                    <h2 class="mt-6 text-2xl font-semibold leading-snug text-ink">
                        Selamat datang kembali
                    </h2>
                    <p class="mt-3 text-sm leading-relaxed text-ink-soft">
                        Masuk untuk mengelola donasi, kampanye, dan kegiatan relawan
                        Yayasan Peduli Kasih Sesama.
                    </p>
                </div>
                <ul class="relative mt-8 space-y-3 text-sm text-ink-soft">
                    <?php foreach (['Transparansi penyaluran dana', 'Kelola kampanye & donasi', 'Pantau kegiatan relawan'] as $item): ?>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="h-4 w-4 text-success"></i> <?= $item ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Form -->
            <div class="p-6 sm:p-8">
                <h1 class="text-2xl font-semibold text-ink">Masuk</h1>
                <p class="mt-1 text-sm text-ink-soft">Gunakan email dan password akun Anda.</p>

                <form action="<?= Helper::url('login') ?>" method="post" class="mt-6 space-y-4">
                    <?= Helper::csrfField() ?>

                    <div>
                        <label class="label" for="email">Email</label>
                        <input id="email" name="email" type="email" class="input" required autofocus
                               placeholder="nama@email.com" value="<?= Helper::e(Helper::old('email')) ?>">
                    </div>

                    <div>
                        <label class="label" for="password">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" class="input pr-11" required placeholder="••••••••">
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-soft hover:text-ink"
                                    data-toggle-password="#password" aria-label="Tampilkan password">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        <i data-lucide="log-in" class="h-4 w-4"></i> Masuk
                    </button>
                </form>

                <p class="mt-5 text-center text-sm text-ink-soft">
                    Belum punya akun?
                    <a href="<?= Helper::url('register') ?>" class="btn-link">Daftar sekarang</a>
                </p>

                <div class="mt-6 rounded-lg border border-line bg-surface p-3 text-xs text-ink-soft">
                    <p class="font-medium text-ink">Akun demo</p>
                    <p class="mt-1">Admin: <span class="text-accent">admin@pedulikasih.org</span> / <span class="text-accent">password123</span></p>
                    <p>Donatur: <span class="text-accent">budi@gmail.com</span> / <span class="text-accent">password123</span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.querySelector(btn.getAttribute('data-toggle-password'));
            if (input) input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
});
</script>
