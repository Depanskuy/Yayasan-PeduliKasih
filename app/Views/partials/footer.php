<?php
// app/Views/partials/footer.php
use App\Core\Helper;

$settings = $settings ?? [];
$orgName  = $settings['org_name'] ?? 'Yayasan Peduli Kasih Sesama';
?>
<footer class="mt-16 border-t border-line bg-surface">
    <div class="container-page grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-2">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-accent/15 text-accent">
                    <i data-lucide="heart-handshake" class="h-5 w-5"></i>
                </span>
                <div>
                    <p class="font-semibold text-ink"><?= Helper::e($orgName) ?></p>
                    <p class="text-xs text-ink-soft"><?= Helper::e($settings['org_tagline'] ?? '') ?></p>
                </div>
            </div>
            <p class="mt-4 max-w-md text-sm leading-relaxed text-ink-soft">
                <?= Helper::e($settings['org_address'] ?? '') ?>
            </p>
            <p class="mt-3 text-xs text-ink-soft"><?= Helper::e($settings['org_legal'] ?? '') ?></p>
        </div>

        <div>
            <p class="mb-3 text-sm font-semibold text-ink">Navigasi</p>
            <ul class="space-y-2 text-sm text-ink-soft">
                <li><a class="hover:text-accent" href="<?= Helper::url('') ?>">Beranda</a></li>
                <li><a class="hover:text-accent" href="<?= Helper::url('campaigns') ?>">Kampanye</a></li>
                <li><a class="hover:text-accent" href="<?= Helper::url('volunteer/events') ?>">Relawan</a></li>
                <li><a class="hover:text-accent" href="<?= Helper::url('artikel') ?>">Artikel</a></li>
                <li><a class="hover:text-accent" href="<?= Helper::url('galeri') ?>">Galeri</a></li>
                <li><a class="hover:text-accent" href="<?= Helper::url('tentang') ?>">Tentang Kami</a></li>
                <li><a class="hover:text-accent" href="<?= Helper::url('kontak') ?>">Kontak</a></li>
            </ul>
        </div>

        <div>
            <p class="mb-3 text-sm font-semibold text-ink">Hubungi Kami</p>
            <ul class="space-y-2 text-sm text-ink-soft">
                <li class="flex items-start gap-2">
                    <i data-lucide="phone" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                    <?= Helper::e($settings['org_phone'] ?? '-') ?>
                </li>
                <li class="flex items-start gap-2">
                    <i data-lucide="mail" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                    <?= Helper::e($settings['org_email'] ?? '-') ?>
                </li>
                <li class="flex items-start gap-2">
                    <i data-lucide="instagram" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                    <?= Helper::e($settings['social_instagram'] ?? '-') ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-line">
        <div class="container-page flex flex-col items-center justify-between gap-2 py-5 text-xs text-ink-soft sm:flex-row">
            <p>&copy; <?= date('Y') ?> <?= Helper::e($orgName) ?>. Hak cipta dilindungi.</p>
            <p>Dibangun dengan Tailwind CSS · PHP MVC</p>
        </div>
    </div>
</footer>
