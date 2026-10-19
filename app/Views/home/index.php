<?php
// app/Views/home/index.php
use App\Core\Helper;

$settings = $settings ?? [];
$featured = $featured ?? [];
$recent   = $recent ?? [];
$stats    = $stats ?? ['donations' => 0, 'campaigns' => 0, 'beneficiaries' => 0, 'disbursed' => 0];
?>
<!-- Hero -->
<section class="relative overflow-hidden border-b border-line">
    <div class="uv-pattern uv-pattern--hero pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-accent/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-40 -left-24 h-80 w-80 rounded-full bg-accent/5 blur-3xl"></div>

    <div class="container-page relative grid gap-12 py-16 lg:grid-cols-2 lg:items-center lg:py-24">
        <div>
            <span class="badge-accent">
                <i data-lucide="sparkles" class="h-3.5 w-3.5"></i>
                Bersama, kita bisa lebih
            </span>
            <h1 class="mt-5 text-3xl font-semibold leading-tight tracking-tight text-ink sm:text-4xl lg:text-5xl">
                Menyalurkan <span class="text-accent">Amanah</span>,<br class="hidden sm:block">
                Merajut Kasih untuk Sesama
            </h1>
            <p class="mt-5 max-w-xl text-base leading-relaxed text-ink-soft">
                <?= Helper::e($settings['org_tagline'] ?? 'Menyalurkan amanah, merajut kasih, mencerahkan masa depan.') ?>
                Setiap donasi Anda disalurkan secara transparan kepada mereka yang membutuhkan.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="<?= Helper::url('campaigns') ?>" class="uv-btn uv-btn--lg">
                    Donasi Sekarang <i data-lucide="arrow-right" class="uv-btn__icon h-4 w-4"></i>
                </a>
                <a href="<?= Helper::url('volunteer/events') ?>" class="btn-outline btn-lg">
                    <i data-lucide="users" class="h-4 w-4"></i> Jadi Relawan
                </a>
            </div>

            <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <?php
                $quick = [
                    ['label' => 'Donasi Tersalur', 'value' => Helper::rupiah($stats['donations'], true), 'icon' => 'wallet'],
                    ['label' => 'Kampanye', 'value' => $stats['campaigns'], 'icon' => 'megaphone'],
                    ['label' => 'Penerima', 'value' => $stats['beneficiaries'], 'icon' => 'heart-handshake'],
                    ['label' => 'Tersalurkan', 'value' => Helper::rupiah($stats['disbursed'], true), 'icon' => 'send'],
                ];
                foreach ($quick as $item): ?>
                    <div class="rounded-xl border border-line bg-card/60 p-3.5">
                        <i data-lucide="<?= $item['icon'] ?>" class="h-4 w-4 text-accent"></i>
                        <p class="mt-2 text-lg font-semibold text-ink"><?= Helper::e($item['value']) ?></p>
                        <p class="text-[11px] text-ink-soft"><?= $item['label'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="relative">
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-line px-5 py-4">
                    <div class="flex items-center gap-2 text-sm font-medium text-ink">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-success/15 text-success">
                            <i data-lucide="shield-check" class="h-4 w-4"></i>
                        </span>
                        Transparansi Penyaluran
                    </div>
                    <span class="badge-success">Aktif</span>
                </div>
                <div class="space-y-4 p-5">
                    <?php if ($featured): ?>
                        <?php $top = $featured[0]; $pct = Helper::percent($top['collected_amount'], $top['target_amount']); ?>
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <p class="text-sm font-medium text-ink"><?= Helper::e(Helper::excerpt($top['title'], 60)) ?></p>
                                <span class="text-xs font-semibold text-accent"><?= $pct ?>%</span>
                            </div>
                            <div class="progress mt-2">
                                <div class="progress-bar" style="width: <?= $pct ?>%"></div>
                            </div>
                            <div class="mt-1.5 flex justify-between text-xs text-ink-soft">
                                <span><?= Helper::rupiah($top['collected_amount'], true) ?> terkumpul</span>
                                <span>dari <?= Helper::rupiah($top['target_amount'], true) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="rounded-lg border border-line bg-surface p-3">
                        <p class="text-xs text-ink-soft">Donasi terbaru</p>
                        <?php if ($recent): ?>
                            <?php $r = $recent[0]; ?>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="flex items-center gap-2 text-sm text-ink">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-accent/15 text-[10px] font-semibold text-accent">
                                        <?= Helper::e(Helper::initials($r['donor_name'])) ?>
                                    </span>
                                    <?= Helper::e(Helper::excerpt($r['donor_name'], 22)) ?>
                                </span>
                                <span class="text-sm font-semibold text-success"><?= Helper::rupiah($r['amount'], true) ?></span>
                            </div>
                        <?php else: ?>
                            <p class="mt-2 text-sm text-ink-soft">Belum ada donasi.</p>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-2 text-xs text-ink-soft">
                        <i data-lucide="lock" class="h-3.5 w-3.5 text-success"></i>
                        Data donatur &amp; penyaluran tercatat aman dan transparan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kampanye unggulan -->
<section class="section">
    <div class="container-page">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <span class="text-xs font-semibold uppercase tracking-widest text-accent">Program Kami</span>
                <h2 class="mt-2 text-2xl font-semibold text-ink sm:text-3xl">Kampanye yang Membutuhkan Bantuan</h2>
                <p class="mt-2 max-w-2xl text-sm text-ink-soft">
                    Pilih kampanye yang Anda percaya, lalu salurkan donasi terbaik Anda.
                </p>
            </div>
            <a href="<?= Helper::url('campaigns') ?>" class="btn-outline btn-sm shrink-0">
                Lihat Semua <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </a>
        </div>

        <?php if ($featured): ?>
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($featured as $campaign): ?>
                    <?php include __DIR__ . '/../partials/campaign_card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card mt-8 p-10 text-center text-ink-soft">Belum ada kampanye aktif.</div>
        <?php endif; ?>
    </div>
</section>

<!-- Program fokus -->
<section class="border-y border-line bg-surface/50 py-14">
    <div class="container-page">
        <div class="grid gap-6 sm:grid-cols-3">
            <?php
            $programs = [
                ['icon' => 'graduation-cap', 'title' => 'Pendidikan', 'desc' => 'Beasiswa, perlengkapan sekolah, dan bantuan biaya pendidikan bagi anak dhuafa.'],
                ['icon' => 'stethoscope', 'title' => 'Kesehatan', 'desc' => 'Dukungan pengobatan, operasi, dan layanan kesehatan gratis untuk mustahik.'],
                ['icon' => 'shield-alert', 'title' => 'Tanggap Bencana', 'desc' => 'Bantuan darurat, logistik, dan pemulihan bagi korban bencana alam.'],
            ];
            foreach ($programs as $program): ?>
                <div class="card card-pad">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-accent/15 text-accent">
                        <i data-lucide="<?= $program['icon'] ?>" class="h-5 w-5"></i>
                    </span>
                    <h3 class="mt-4 text-base font-semibold text-ink"><?= $program['title'] ?></h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-soft"><?= $program['desc'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Donasi terbaru -->
<?php if ($recent): ?>
<section class="section">
    <div class="container-page grid gap-8 lg:grid-cols-[1.2fr_1fr] lg:items-start">
        <div>
            <span class="text-xs font-semibold uppercase tracking-widest text-accent">Kebaikan Terbaru</span>
            <h2 class="mt-2 text-2xl font-semibold text-ink">Terima Kasih, Para Dermawan</h2>
            <div class="mt-6 space-y-3">
                <?php foreach ($recent as $donation): ?>
                    <div class="card flex items-center gap-4 p-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent/15 text-sm font-semibold text-accent">
                            <?= Helper::e(Helper::initials($donation['donor_name'])) ?>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-ink"><?= Helper::e($donation['donor_name']) ?></p>
                            <p class="truncate text-xs text-ink-soft">
                                <?= Helper::e($donation['campaign_title'] ?? 'Donasi umum') ?> · <?= Helper::timeAgo($donation['created_at']) ?>
                            </p>
                        </div>
                        <span class="shrink-0 text-sm font-semibold text-success"><?= Helper::rupiah($donation['amount'], true) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card card-pad bg-gradient-to-br from-[#ECFDF5] to-card">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-accent text-white">
                <i data-lucide="heart" class="h-5 w-5"></i>
            </span>
            <h3 class="mt-4 text-xl font-semibold text-ink">Mulai Kebaikan Hari Ini</h3>
            <p class="mt-2 text-sm leading-relaxed text-ink-soft">
                Sekecil apa pun donasi Anda, akan berarti besar bagi mereka yang membutuhkan.
                Bergabunglah bersama para dermawan lainnya.
            </p>
            <a href="<?= Helper::url('campaigns') ?>" class="uv-btn mt-5 w-full">
                Salurkan Donasi <i data-lucide="arrow-right" class="uv-btn__icon h-4 w-4"></i>
            </a>
            <a href="<?= Helper::url('register') ?>" class="btn-ghost mt-2 w-full">Buat Akun Donatur</a>
        </div>
    </div>
</section>
<?php endif; ?>
