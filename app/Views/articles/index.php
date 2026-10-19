<?php
// app/Views/articles/index.php
use App\Core\Helper;

$articles   = $articles ?? [];
$categories = $categories ?? [];
$filters    = $filters ?? [];
$activeCat  = $filters['category'] ?? '';
$search     = $filters['search'] ?? '';

$catUrl = static function (string $key) use ($search): string {
    $query = array_filter(['kategori' => $key, 'q' => $search], static fn ($v) => $v !== '');
    return Helper::url('artikel') . ($query ? '?' . http_build_query($query) : '');
};
?>
<section class="border-b border-line bg-surface/40 py-12">
    <div class="container-page">
        <span class="text-xs font-semibold uppercase tracking-widest text-accent">Artikel & Berita</span>
        <h1 class="mt-2 text-3xl font-semibold text-ink sm:text-4xl">Kabar Kebaikan Terbaru</h1>
        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-ink-soft">
            Ikuti cerita, inspirasi, dan laporan kegiatan Yayasan Peduli Kasih Sesama.
        </p>

        <form method="get" action="<?= Helper::url('artikel') ?>" class="mt-6 flex max-w-md gap-2">
            <?php if ($activeCat !== ''): ?>
                <input type="hidden" name="kategori" value="<?= Helper::e($activeCat) ?>">
            <?php endif; ?>
            <div class="relative flex-1">
                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft"></i>
                <input type="search" name="q" class="input pl-9" placeholder="Cari artikel..." value="<?= Helper::e($search) ?>">
            </div>
            <button type="submit" class="btn-outline btn-sm">Cari</button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container-page">
        <div class="flex flex-wrap gap-2">
            <a href="<?= $catUrl('') ?>" class="<?= $activeCat === '' ? 'badge-accent' : 'badge-muted' ?>">
                Semua
            </a>
            <?php foreach ($categories as $key => $label): ?>
                <a href="<?= $catUrl($key) ?>" class="<?= $activeCat === $key ? 'badge-accent' : 'badge-muted' ?>">
                    <?= Helper::e($label) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($articles): ?>
            <div class="mt-7 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($articles as $article):
                    $image = Helper::media($article['featured_image'] ?? null, 'articles');
                ?>
                    <article class="card card-hover flex flex-col overflow-hidden">
                        <a href="<?= Helper::url('artikel/' . $article['slug']) ?>" class="block">
                            <div class="relative h-44 overflow-hidden bg-surface">
                                <?php if ($image): ?>
                                    <img src="<?= Helper::e($image) ?>" alt="<?= Helper::e($article['title']) ?>"
                                         class="h-full w-full object-cover transition duration-300 hover:scale-105">
                                <?php else: ?>
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#ECFDF5] via-[#D1FAE5] to-[#F0FDF9]">
                                        <i data-lucide="newspaper" class="h-12 w-12 text-accent/40"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="badge-accent absolute left-3 top-3 shadow">
                                    <?= Helper::e($categories[$article['category']] ?? ucfirst($article['category'])) ?>
                                </span>
                            </div>
                        </a>

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center gap-3 text-[11px] text-ink-soft">
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="calendar" class="h-3 w-3"></i>
                                    <?= Helper::tanggal($article['created_at']) ?>
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="eye" class="h-3 w-3"></i>
                                    <?= (int) $article['views_count'] ?>x dibaca
                                </span>
                            </div>

                            <h2 class="mt-2 line-clamp-2 text-base font-semibold leading-snug text-ink">
                                <a href="<?= Helper::url('artikel/' . $article['slug']) ?>" class="hover:text-accent">
                                    <?= Helper::e($article['title']) ?>
                                </a>
                            </h2>
                            <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-ink-soft">
                                <?= Helper::e($article['excerpt']) ?>
                            </p>

                            <div class="mt-4 flex items-center justify-between border-t border-line pt-3">
                                <span class="text-[11px] text-ink-soft">
                                    <?= Helper::e($article['author_name'] ?? 'Redaksi') ?>
                                </span>
                                <a href="<?= Helper::url('artikel/' . $article['slug']) ?>" class="btn-link text-xs">
                                    Baca <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card mt-7 p-12 text-center">
                <i data-lucide="file-search" class="mx-auto h-10 w-10 text-ink-soft"></i>
                <p class="mt-3 font-medium text-ink">Belum ada artikel</p>
                <p class="mt-1 text-sm text-ink-soft">Coba ubah kata kunci atau kategori pencarian.</p>
                <a href="<?= Helper::url('artikel') ?>" class="btn-outline btn-sm mt-4">Reset filter</a>
            </div>
        <?php endif; ?>
    </div>
</section>
