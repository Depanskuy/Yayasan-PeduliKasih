<?php
// app/Views/articles/show.php
use App\Core\Helper;

$article = $article ?? [];
$related = $related ?? [];
$image   = Helper::media($article['featured_image'] ?? null, 'articles');
$categories = \App\Models\Article::categoryLabels();
?>
<div class="border-b border-line bg-surface/40">
    <div class="container-page flex items-center gap-2 py-3 text-xs text-ink-soft">
        <a href="<?= Helper::url('') ?>" class="hover:text-accent">Beranda</a>
        <i data-lucide="chevron-right" class="h-3 w-3"></i>
        <a href="<?= Helper::url('artikel') ?>" class="hover:text-accent">Artikel</a>
        <i data-lucide="chevron-right" class="h-3 w-3"></i>
        <span class="truncate text-ink"><?= Helper::e($article['title'] ?? '') ?></span>
    </div>
</div>

<section class="py-8 sm:py-10">
    <div class="container-page grid gap-8 lg:grid-cols-[1.5fr_1fr] lg:items-start">
        <article class="card overflow-hidden">
            <?php if ($image): ?>
                <img src="<?= Helper::e($image) ?>" alt="<?= Helper::e($article['title'] ?? '') ?>" class="h-64 w-full object-cover sm:h-80">
            <?php endif; ?>

            <div class="p-5 sm:p-8">
                <span class="badge-accent">
                    <?= Helper::e($categories[$article['category'] ?? ''] ?? ucfirst($article['category'] ?? 'Artikel')) ?>
                </span>

                <h1 class="mt-3 text-2xl font-semibold leading-snug text-ink sm:text-3xl">
                    <?= Helper::e($article['title'] ?? '') ?>
                </h1>

                <div class="mt-4 flex flex-wrap items-center gap-4 border-b border-line pb-4 text-xs text-ink-soft">
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="user" class="h-3.5 w-3.5 text-accent"></i>
                        <?= Helper::e($article['author_name'] ?? 'Redaksi') ?>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="calendar" class="h-3.5 w-3.5 text-accent"></i>
                        <?= Helper::tanggal($article['created_at'] ?? null) ?>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="eye" class="h-3.5 w-3.5 text-accent"></i>
                        <?= (int) ($article['views_count'] ?? 0) ?>x dibaca
                    </span>
                </div>

                <p class="mt-5 text-sm font-medium leading-relaxed text-ink">
                    <?= Helper::e($article['excerpt'] ?? '') ?>
                </p>

                <div class="prose mt-5 max-w-none space-y-4 text-sm leading-relaxed text-ink-soft">
                    <?= Helper::safeHtml($article['content'] ?? '') ?>
                </div>

                <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-line pt-5">
                    <a href="<?= Helper::url('artikel') ?>" class="btn-ghost btn-sm">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i> Semua Artikel
                    </a>
                    <a href="<?= Helper::url('campaigns') ?>" class="btn-primary btn-sm">
                        <i data-lucide="heart" class="h-4 w-4"></i> Dukung Kampanye
                    </a>
                </div>
            </div>
        </article>

        <aside class="space-y-6 lg:sticky lg:top-20">
            <div class="card card-pad">
                <h2 class="text-sm font-semibold text-ink">Artikel Terkait</h2>
                <?php if ($related): ?>
                    <ul class="mt-4 divide-y divide-line">
                        <?php foreach ($related as $item): ?>
                            <li class="py-3 first:pt-0 last:pb-0">
                                <a href="<?= Helper::url('artikel/' . $item['slug']) ?>" class="group block">
                                    <p class="line-clamp-2 text-sm text-ink group-hover:text-accent"><?= Helper::e($item['title']) ?></p>
                                    <p class="mt-1 text-[11px] text-ink-soft"><?= Helper::tanggal($item['created_at']) ?></p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="mt-3 text-sm text-ink-soft">Belum ada artikel terkait.</p>
                <?php endif; ?>
            </div>

            <div class="card card-pad bg-gradient-to-br from-card to-surface">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-accent/15 text-accent">
                    <i data-lucide="hand-heart" class="h-5 w-5"></i>
                </span>
                <h3 class="mt-3 text-base font-semibold text-ink">Ingin ikut berbuat baik?</h3>
                <p class="mt-1 text-sm text-ink-soft">Salurkan donasi atau bergabung sebagai relawan kegiatan sosial kami.</p>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="<?= Helper::url('campaigns') ?>" class="btn-primary btn-sm">Lihat Kampanye</a>
                    <a href="<?= Helper::url('volunteer/events') ?>" class="btn-outline btn-sm">Jadi Relawan</a>
                </div>
            </div>
        </aside>
    </div>
</section>
