<?php
// app/Views/campaigns/index.php
use App\Core\Helper;

$campaigns  = $campaigns ?? [];
$categories = $categories ?? [];
$filters    = $filters ?? [];
?>
<section class="border-b border-line bg-surface/40 py-12">
    <div class="container-page">
        <span class="text-xs font-semibold uppercase tracking-widest text-accent">Kampanye</span>
        <h1 class="mt-2 text-3xl font-semibold text-ink sm:text-4xl">Salurkan Kebaikan Anda</h1>
        <p class="mt-3 max-w-2xl text-sm text-ink-soft">
            Temukan kampanye yang ingin Anda dukung. Setiap donasi akan disalurkan secara transparan dan tepat sasaran.
        </p>

        <form method="get" action="<?= Helper::url('campaigns') ?>" class="mt-6 flex flex-col gap-3 sm:flex-row">
            <div class="relative flex-1">
                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft"></i>
                <input type="search" name="q" class="input pl-10" placeholder="Cari kampanye..."
                       value="<?= Helper::e($filters['search'] ?? '') ?>">
            </div>
            <select name="kategori" class="select sm:w-56">
                <option value="0">Semua Kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>"
                        <?= (int) ($filters['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>>
                        <?= Helper::e($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-primary sm:px-6">
                <i data-lucide="filter" class="h-4 w-4"></i> Filter
            </button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container-page">
        <div class="mb-6 flex items-center justify-between">
            <p class="text-sm text-ink-soft">
                Menampilkan <span class="font-semibold text-ink"><?= count($campaigns) ?></span> kampanye
            </p>
            <?php if (!empty($filters['search']) || !empty($filters['category_id'])): ?>
                <a href="<?= Helper::url('campaigns') ?>" class="btn-ghost btn-sm">Reset filter</a>
            <?php endif; ?>
        </div>

        <?php if ($campaigns): ?>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($campaigns as $campaign): ?>
                    <?php include __DIR__ . '/../partials/campaign_card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card p-12 text-center">
                <i data-lucide="search-x" class="mx-auto h-10 w-10 text-ink-soft"></i>
                <p class="mt-3 font-medium text-ink">Kampanye tidak ditemukan</p>
                <p class="mt-1 text-sm text-ink-soft">Coba ubah kata kunci atau filter kategori.</p>
                <a href="<?= Helper::url('campaigns') ?>" class="btn-outline btn-sm mt-4">Lihat semua kampanye</a>
            </div>
        <?php endif; ?>
    </div>
</section>
