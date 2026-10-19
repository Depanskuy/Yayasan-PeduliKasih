<?php
// app/Views/admin/articles.php
use App\Core\Helper;

$articles     = $articles ?? [];
$statusCounts = $statusCounts ?? [];
$categories   = $categories ?? [];
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div class="flex flex-wrap gap-2">
            <span class="badge-success">Terbit: <?= (int) ($statusCounts['published'] ?? 0) ?></span>
            <span class="badge-warning">Draft: <?= (int) ($statusCounts['draft'] ?? 0) ?></span>
            <a href="<?= Helper::url('artikel') ?>" target="_blank" class="badge-muted">
                <i data-lucide="external-link" class="h-3 w-3"></i> Lihat Publik
            </a>
        </div>
        <a href="<?= Helper::url('admin/articles/create') ?>" class="btn-primary btn-sm">
            <i data-lucide="plus" class="h-4 w-4"></i> Tambah Artikel
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Artikel</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Dibaca</th>
                    <th>Tanggal</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($articles): ?>
                    <?php foreach ($articles as $article):
                        $thumb = Helper::media($article['featured_image'] ?? null, 'articles');
                    ?>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <?php if ($thumb): ?>
                                        <img src="<?= Helper::e($thumb) ?>" alt="" class="h-10 w-14 rounded-md border border-line object-cover">
                                    <?php else: ?>
                                        <span class="flex h-10 w-14 items-center justify-center rounded-md border border-line bg-surface text-ink-soft">
                                            <i data-lucide="newspaper" class="h-4 w-4"></i>
                                        </span>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <p class="max-w-[260px] truncate text-sm font-medium text-ink"><?= Helper::e($article['title']) ?></p>
                                        <p class="max-w-[260px] truncate text-xs text-ink-soft">/<?= Helper::e($article['slug']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge-accent"><?= Helper::e($categories[$article['category']] ?? ucfirst($article['category'])) ?></span></td>
                            <td class="text-sm text-ink-soft"><?= Helper::e($article['author_name'] ?? '-') ?></td>
                            <td>
                                <span class="<?= $article['status'] === 'published' ? 'badge-success' : 'badge-warning' ?>">
                                    <?= $article['status'] === 'published' ? 'Terbit' : 'Draft' ?>
                                </span>
                            </td>
                            <td class="text-sm text-ink-soft"><?= (int) $article['views_count'] ?>x</td>
                            <td class="whitespace-nowrap text-xs text-ink-soft"><?= Helper::tanggal($article['created_at']) ?></td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <?php if ($article['status'] === 'published'): ?>
                                        <div class="uv-tip uv-tip--down">
                                            <a href="<?= Helper::url('artikel/' . $article['slug']) ?>" target="_blank" class="btn-ghost btn-sm" aria-label="Lihat artikel">
                                                <i data-lucide="external-link" class="h-4 w-4"></i>
                                            </a>
                                            <span class="uv-tip__bubble" role="tooltip">Lihat</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="uv-tip uv-tip--down">
                                        <a href="<?= Helper::url('admin/articles/' . $article['id'] . '/edit') ?>" class="btn-outline btn-sm" aria-label="Edit artikel">
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>
                                        <span class="uv-tip__bubble" role="tooltip">Edit</span>
                                    </div>
                                    <div class="uv-tip uv-tip--down">
                                        <form method="post" action="<?= Helper::url('admin/articles/' . $article['id'] . '/delete') ?>"
                                              data-confirm="Hapus artikel ini?" class="inline">
                                            <?= Helper::csrfField() ?>
                                            <button type="submit" class="btn-danger btn-sm" aria-label="Hapus artikel">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                        <span class="uv-tip__bubble" role="tooltip">Hapus</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-ink-soft">Belum ada artikel.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
