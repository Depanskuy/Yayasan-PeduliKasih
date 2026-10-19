<?php
// app/Views/partials/campaign_card.php
use App\Core\Helper;

$campaign = $campaign ?? [];
$banner   = Helper::media($campaign['banner_image'] ?? null, 'campaigns');
$percent  = Helper::percent($campaign['collected_amount'] ?? 0, $campaign['target_amount'] ?? 0);
?>
<a href="<?= Helper::url('campaigns/' . ($campaign['id'] ?? '')) ?>" class="card card-hover group block overflow-hidden">
    <div class="relative h-44 overflow-hidden bg-surface">
        <?php if ($banner): ?>
            <img src="<?= Helper::e($banner) ?>" alt="<?= Helper::e($campaign['title'] ?? '') ?>"
                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        <?php else: ?>
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#ECFDF5] via-[#D1FAE5] to-[#F0FDF9]">
                <i data-lucide="heart-handshake" class="h-12 w-12 text-accent/50"></i>
            </div>
        <?php endif; ?>
        <span class="badge-accent absolute left-3 top-3 shadow">
            <?= Helper::e($campaign['category_name'] ?? 'Sosial') ?>
        </span>
        <?php if (!empty($campaign['is_featured'])): ?>
            <span class="badge-warning absolute right-3 top-3 shadow">
                <i data-lucide="star" class="h-3 w-3"></i> Unggulan
            </span>
        <?php endif; ?>
    </div>

    <div class="p-5">
        <h3 class="line-clamp-2 text-base font-semibold leading-snug text-ink group-hover:text-accent">
            <?= Helper::e($campaign['title'] ?? '') ?>
        </h3>
        <p class="mt-2 line-clamp-2 text-sm text-ink-soft">
            <?= Helper::e($campaign['short_description'] ?? '') ?>
        </p>

        <div class="mt-4">
            <div class="flex items-center justify-between text-xs text-ink-soft">
                <span class="font-medium text-accent"><?= Helper::rupiah($campaign['collected_amount'] ?? 0, true) ?></span>
                <span><?= $percent ?>%</span>
            </div>
            <div class="progress mt-1.5">
                <div class="progress-bar" style="width: <?= $percent ?>%"></div>
            </div>
            <div class="mt-2 flex items-center justify-between text-xs text-ink-soft">
                <span>Terkumpul</span>
                <span>Target <?= Helper::rupiah($campaign['target_amount'] ?? 0, true) ?></span>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between border-t border-line pt-3 text-xs text-ink-soft">
            <span class="inline-flex items-center gap-1.5">
                <i data-lucide="calendar-clock" class="h-3.5 w-3.5"></i>
                <?= Helper::tanggal($campaign['end_date'] ?? null) ?>
            </span>
            <span class="inline-flex items-center gap-1 text-accent">
                Donasi <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
            </span>
        </div>
    </div>
</a>
