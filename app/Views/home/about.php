<?php
// app/Views/home/about.php
use App\Core\Helper;
$settings = $settings ?? [];
?>
<section class="border-b border-line bg-surface/40 py-14">
    <div class="container-page">
        <span class="text-xs font-semibold uppercase tracking-widest text-accent">Tentang Kami</span>
        <h1 class="mt-2 text-3xl font-semibold text-ink sm:text-4xl"><?= Helper::e($settings['org_name'] ?? 'Yayasan Peduli Kasih Sesama') ?></h1>
        <p class="mt-4 max-w-3xl text-base leading-relaxed text-ink-soft">
            <?= Helper::e($settings['org_tagline'] ?? '') ?>. Kami adalah lembaga sosial yang berkomitmen
            menyalurkan amanah para donatur secara transparan, profesional, dan tepat sasaran.
        </p>
    </div>
</section>

<section class="section">
    <div class="container-page grid gap-8 lg:grid-cols-3">
        <div class="card card-pad">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-accent/15 text-accent">
                <i data-lucide="target" class="h-5 w-5"></i>
            </span>
            <h2 class="mt-4 text-lg font-semibold text-ink">Visi</h2>
            <p class="mt-2 text-sm leading-relaxed text-ink-soft">
                Menjadi lembaga sosial terpercaya yang mencerahkan masa depan masyarakat dhuafa.
            </p>
        </div>
        <div class="card card-pad lg:col-span-2">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-accent/15 text-accent">
                <i data-lucide="list-checks" class="h-5 w-5"></i>
            </span>
            <h2 class="mt-4 text-lg font-semibold text-ink">Misi</h2>
            <ul class="mt-3 space-y-2 text-sm text-ink-soft">
                <?php foreach ([
                    'Menyalurkan donasi secara transparan dan akuntabel.',
                    'Memberdayakan anak yatim, lansia, dan keluarga prasejahtera.',
                    'Membangun kolaborasi dengan relawan dan mitra kebaikan.',
                ] as $item): ?>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check-circle-2" class="mt-0.5 h-4 w-4 shrink-0 text-success"></i>
                        <?= Helper::e($item) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="container-page mt-10">
        <div class="card card-pad flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-ink">Legalitas &amp; Alamat</p>
                <p class="mt-1 text-sm text-ink-soft"><?= Helper::e($settings['org_legal'] ?? '-') ?></p>
                <p class="mt-1 text-sm text-ink-soft"><?= Helper::e($settings['org_address'] ?? '-') ?></p>
            </div>
            <a href="<?= Helper::url('kontak') ?>" class="btn-outline btn-sm shrink-0">Hubungi Kami</a>
        </div>
    </div>
</section>
