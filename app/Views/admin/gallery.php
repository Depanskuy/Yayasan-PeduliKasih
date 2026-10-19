<?php
// app/Views/admin/gallery.php
use App\Core\Helper;

$items     = $items ?? [];
$counts    = $counts ?? [];
$campaigns = $campaigns ?? [];
$events    = $events ?? [];

$src = static function (array $item): ?string {
    $url = $item['media_url'] ?? '';
    if ($url === '') return null;
    if (preg_match('~^https?://~i', $url)) return $url;
    return Helper::media($url, 'gallery');
};
?>
<div class="space-y-6">
    <div class="flex flex-wrap items-center gap-2">
        <span class="badge-accent">Foto: <?= (int) ($counts['photo'] ?? 0) ?></span>
        <span class="badge-info">Video: <?= (int) ($counts['video'] ?? 0) ?></span>
        <a href="<?= Helper::url('galeri') ?>" target="_blank" class="badge-muted">
            <i data-lucide="external-link" class="h-3 w-3"></i> Lihat Publik
        </a>
    </div>

    <div class="grid gap-6 xl:grid-cols-[380px_1fr] xl:items-start">
        <!-- Form upload -->
        <form action="<?= Helper::url('admin/gallery') ?>" method="post" enctype="multipart/form-data" class="card card-pad space-y-4">
            <?= Helper::csrfField() ?>
            <h2 class="text-base font-semibold text-ink">Tambah Media</h2>

            <div>
                <label class="label" for="gallery_title">Judul</label>
                <input id="gallery_title" name="title" class="input" required minlength="3" placeholder="Judul dokumentasi">
            </div>

            <div>
                <label class="label" for="gallery_description">Deskripsi (opsional)</label>
                <textarea id="gallery_description" name="description" rows="2" class="textarea" placeholder="Keterangan singkat..."></textarea>
            </div>

            <div>
                <label class="label" for="media_type">Tipe Media</label>
                <select id="media_type" name="media_type" class="select" data-media-type>
                    <option value="photo">Foto</option>
                    <option value="video">Video</option>
                </select>
            </div>

            <div data-photo-field>
                <label class="label" for="gallery_image">File Foto</label>
                <input id="gallery_image" name="image" type="file" accept="image/*"
                       class="input file:mr-3 file:rounded-md file:border-0 file:bg-accent/15 file:px-3 file:py-1 file:text-xs file:text-accent">
                <p class="mt-1 text-[11px] text-ink-soft">JPG, PNG, WEBP. Maks 5 MB.</p>
            </div>

            <div class="hidden" data-video-field>
                <label class="label" for="gallery_media_url">URL Video</label>
                <input id="gallery_media_url" name="media_url" type="url" class="input" placeholder="https://youtube.com/watch?v=...">
                <p class="mt-1 text-[11px] text-ink-soft">Tautan YouTube / video lain.</p>
            </div>

            <div>
                <label class="label" for="gallery_campaign">Terkait Kampanye (opsional)</label>
                <select id="gallery_campaign" name="campaign_id" class="select">
                    <option value="">Tidak ada</option>
                    <?php foreach ($campaigns as $campaign): ?>
                        <option value="<?= (int) $campaign['id'] ?>"><?= Helper::e($campaign['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="label" for="gallery_event">Terkait Kegiatan Relawan (opsional)</label>
                <select id="gallery_event" name="event_id" class="select">
                    <option value="">Tidak ada</option>
                    <?php foreach ($events as $event): ?>
                        <option value="<?= (int) $event['id'] ?>"><?= Helper::e($event['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-primary w-full">
                <i data-lucide="upload" class="h-4 w-4"></i> Simpan Media
            </button>
        </form>

        <!-- Daftar media -->
        <div>
            <?php if ($items): ?>
                <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-3">
                    <?php foreach ($items as $item):
                        $media = $src($item);
                        $isVideo = $item['media_type'] === 'video';
                    ?>
                        <div class="card overflow-hidden">
                            <div class="relative h-40 bg-surface">
                                <?php if ($isVideo): ?>
                                    <div class="flex h-full items-center justify-center bg-gradient-to-br from-[#ECFDF5] to-[#F0FDF9]">
                                        <i data-lucide="video" class="h-10 w-10 text-accent/40"></i>
                                    </div>
                                    <span class="badge-info absolute left-2 top-2">Video</span>
                                <?php elseif ($media): ?>
                                    <img src="<?= Helper::e($media) ?>" alt="<?= Helper::e($item['title']) ?>" class="h-full w-full object-cover">
                                    <span class="badge-accent absolute left-2 top-2">Foto</span>
                                <?php else: ?>
                                    <div class="flex h-full items-center justify-center bg-gradient-to-br from-[#ECFDF5] to-[#F0FDF9]">
                                        <i data-lucide="image" class="h-10 w-10 text-accent/40"></i>
                                    </div>
                                    <span class="badge-accent absolute left-2 top-2">Foto</span>
                                <?php endif; ?>
                            </div>

                            <div class="p-3">
                                <p class="line-clamp-1 text-sm font-medium text-ink"><?= Helper::e($item['title']) ?></p>
                                <?php if (!empty($item['description'])): ?>
                                    <p class="mt-1 line-clamp-2 text-xs text-ink-soft"><?= Helper::e($item['description']) ?></p>
                                <?php endif; ?>
                                <p class="mt-2 text-[11px] text-ink-soft">
                                    <?= Helper::e($item['uploader_name'] ?? 'Admin') ?>
                                    · <?= Helper::tanggal($item['created_at']) ?>
                                </p>
                                <?php if (!empty($item['campaign_title'])): ?>
                                    <p class="mt-1 truncate text-[11px] text-accent"><?= Helper::e($item['campaign_title']) ?></p>
                                <?php elseif (!empty($item['event_title'])): ?>
                                    <p class="mt-1 truncate text-[11px] text-accent"><?= Helper::e($item['event_title']) ?></p>
                                <?php endif; ?>

                                <div class="mt-3 flex justify-end gap-2 border-t border-line pt-3">
                                    <?php if ($isVideo && $media): ?>
                                        <a href="<?= Helper::e($media) ?>" target="_blank" rel="noopener" class="btn-ghost btn-sm">
                                            <i data-lucide="external-link" class="h-4 w-4"></i>
                                        </a>
                                    <?php endif; ?>
                                    <form method="post" action="<?= Helper::url('admin/gallery/' . $item['id'] . '/delete') ?>"
                                          data-confirm="Hapus media ini?" class="inline">
                                        <?= Helper::csrfField() ?>
                                        <button type="submit" class="btn-danger btn-sm">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card p-12 text-center">
                    <i data-lucide="images" class="mx-auto h-10 w-10 text-ink-soft"></i>
                    <p class="mt-3 font-medium text-ink">Belum ada media galeri</p>
                    <p class="mt-1 text-sm text-ink-soft">Unggah foto atau tambahkan tautan video melalui formulir.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var select = document.querySelector('[data-media-type]');
    if (!select) return;
    var photo = document.querySelector('[data-photo-field]');
    var video = document.querySelector('[data-video-field]');
    function sync() {
        var isVideo = select.value === 'video';
        photo.classList.toggle('hidden', isVideo);
        video.classList.toggle('hidden', !isVideo);
    }
    select.addEventListener('change', sync);
    sync();
});
</script>
