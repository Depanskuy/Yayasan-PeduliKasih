<?php
// app/Views/gallery/index.php
use App\Core\Helper;

$items = $items ?? [];
$mediaUrl = static function (array $item): ?string {
    $url = $item['media_url'] ?? '';
    if ($url === '') {
        return null;
    }
    if (preg_match('~^https?://~i', $url)) {
        return $url;
    }
    return Helper::media($url, 'gallery');
};
?>
<section class="border-b border-line bg-surface/40 py-12">
    <div class="container-page">
        <span class="text-xs font-semibold uppercase tracking-widest text-accent">Galeri</span>
        <h1 class="mt-2 text-3xl font-semibold text-ink sm:text-4xl">Momen & Dokumentasi Kegiatan</h1>
        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-ink-soft">
            Kumpulan foto dan video kegiatan sosial yang telah kami lakukan bersama para relawan dan donatur.
        </p>
    </div>
</section>

<section class="section">
    <div class="container-page">
        <div class="flex flex-wrap gap-2" data-gallery-filter>
            <button type="button" class="badge-accent" data-filter="all">Semua</button>
            <button type="button" class="badge-muted" data-filter="photo">Foto</button>
            <button type="button" class="badge-muted" data-filter="video">Video</button>
        </div>

        <?php if ($items): ?>
            <div class="mt-7 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-gallery-grid>
                <?php foreach ($items as $item):
                    $src = $mediaUrl($item);
                    $isVideo = ($item['media_type'] ?? 'photo') === 'video';
                ?>
                    <figure class="card card-hover group overflow-hidden" data-media="<?= Helper::e($item['media_type']) ?>">
                        <div class="relative h-52 overflow-hidden bg-surface">
                            <?php if ($isVideo): ?>
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#ECFDF5] via-[#D1FAE5] to-[#F0FDF9]">
                                    <i data-lucide="video" class="h-12 w-12 text-accent/40"></i>
                                </div>
                                <?php if ($src): ?>
                                    <a href="<?= Helper::e($src) ?>" target="_blank" rel="noopener"
                                       class="absolute inset-0 flex items-center justify-center bg-black/30 transition group-hover:bg-black/50">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-accent text-white">
                                            <i data-lucide="play" class="h-5 w-5 fill-current"></i>
                                        </span>
                                    </a>
                                <?php endif; ?>
                                <span class="badge-muted absolute left-3 top-3">Video</span>
                            <?php elseif ($src): ?>
                                <img src="<?= Helper::e($src) ?>" alt="<?= Helper::e($item['title']) ?>"
                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                                <span class="badge-muted absolute left-3 top-3">Foto</span>
                            <?php else: ?>
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#ECFDF5] via-[#D1FAE5] to-[#F0FDF9]">
                                    <i data-lucide="image" class="h-12 w-12 text-accent/40"></i>
                                </div>
                                <span class="badge-muted absolute left-3 top-3">Foto</span>
                            <?php endif; ?>
                        </div>

                        <figcaption class="p-4">
                            <h2 class="line-clamp-1 text-sm font-semibold text-ink"><?= Helper::e($item['title']) ?></h2>
                            <?php if (!empty($item['description'])): ?>
                                <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-ink-soft"><?= Helper::e($item['description']) ?></p>
                            <?php endif; ?>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px] text-ink-soft">
                                <?php if (!empty($item['campaign_title'])): ?>
                                    <span class="inline-flex items-center gap-1"><i data-lucide="megaphone" class="h-3 w-3"></i><?= Helper::e($item['campaign_title']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($item['event_title'])): ?>
                                    <span class="inline-flex items-center gap-1"><i data-lucide="calendar" class="h-3 w-3"></i><?= Helper::e($item['event_title']) ?></span>
                                <?php endif; ?>
                                <span class="inline-flex items-center gap-1"><i data-lucide="clock" class="h-3 w-3"></i><?= Helper::tanggal($item['created_at']) ?></span>
                            </div>
                        </figcaption>
                    </figure>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card mt-7 p-12 text-center">
                <i data-lucide="images" class="mx-auto h-10 w-10 text-ink-soft"></i>
                <p class="mt-3 font-medium text-ink">Galeri masih kosong</p>
                <p class="mt-1 text-sm text-ink-soft">Dokumentasi kegiatan akan tampil di sini.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var wrap = document.querySelector('[data-gallery-filter]');
    if (!wrap) return;
    var items = document.querySelectorAll('[data-gallery-grid] [data-media]');
    wrap.querySelectorAll('[data-filter]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = btn.getAttribute('data-filter');
            wrap.querySelectorAll('[data-filter]').forEach(function (b) {
                b.className = b === btn ? 'badge-accent' : 'badge-muted';
            });
            items.forEach(function (item) {
                item.style.display = (type === 'all' || item.getAttribute('data-media') === type) ? '' : 'none';
            });
        });
    });
});
</script>
