<?php
// app/Views/auth/register.php
use App\Core\Helper;
?>
<section class="py-12">
    <div class="container-page">
        <div class="mx-auto max-w-xl">
            <div class="text-center">
                <span class="badge-accent"><i data-lucide="user-plus" class="h-3.5 w-3.5"></i> Bergabung</span>
                <h1 class="mt-4 text-2xl font-semibold text-ink sm:text-3xl">Buat Akun Donatur</h1>
                <p class="mt-2 text-sm text-ink-soft">Daftar gratis dan mulai berbagi kebaikan bersama kami.</p>
            </div>

            <div class="card mt-6 p-6 sm:p-8">
                <form action="<?= Helper::url('register') ?>" method="post" class="space-y-4">
                    <?= Helper::csrfField() ?>

                    <div>
                        <label class="label" for="name">Nama Lengkap</label>
                        <input id="name" name="name" class="input" required placeholder="Nama lengkap"
                               value="<?= Helper::e(Helper::old('name')) ?>">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="email">Email</label>
                            <input id="email" name="email" type="email" class="input" required placeholder="nama@email.com"
                                   value="<?= Helper::e(Helper::old('email')) ?>">
                        </div>
                        <div>
                            <label class="label" for="phone">No. HP (opsional)</label>
                            <input id="phone" name="phone" class="input" placeholder="08xxxxxxxxxx"
                                   value="<?= Helper::e(Helper::old('phone')) ?>">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="password">Password</label>
                            <input id="password" name="password" type="password" class="input" required placeholder="Min. 6 karakter">
                        </div>
                        <div>
                            <label class="label" for="password_confirm">Konfirmasi Password</label>
                            <input id="password_confirm" name="password_confirm" type="password" class="input" required placeholder="Ulangi password">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        <i data-lucide="user-plus" class="h-4 w-4"></i> Daftar
                    </button>
                </form>

                <p class="mt-5 text-center text-sm text-ink-soft">
                    Sudah punya akun?
                    <a href="<?= Helper::url('login') ?>" class="btn-link">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</section>
