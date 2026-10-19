<?php
// app/Views/admin/campaign_updates.php
use App\Core\Helper;

$updates   = $updates ?? [];
$campaigns = $campaigns ?? [];
?>
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <span class="badge-accent">Total kabar: <?= count($updates) ?></span>
        <p class="text-xs text-ink-soft">Kabar yang dipublikasikan akan tampil pada halaman detail kampanye.</p>
    </div>

    <div class="grid gap-6 xl:grid-cols-[400px_1fr] xl:items-start">
        <form action="<?= Helper::url('admin/campaign-updates') ?>" method="post" enctype="multipart/form-data" class="card card-pad space-y-4">
            <?= Helper::csrfField() ?>
            <h2 class="text-base font-semibold text-ink">Tulis Kabar Baru</h2>

            <div>
                <label class="label" for="update_campaign">Kampanye</label>
                <select id="update_campaign" name="campaign_id" class="select" required>
                    <option value="">Pilih kampanye</option>
                    <?php foreach ($campaigns as $campaign): ?>
                        <option value="<?= (int) $campaign['id'] ?>"><?= Helper::e($campaign['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="label" for="update_title">Judul Kabar</label>
                <input id="update_title" name="title" class="input" required minlength="5" placeholder="Contoh: Penyaluran tahap pertama">
            </div>

            <div>
                <label class="label" for="update_content">Isi Kabar</label>
                <textarea id="update_content" name="content" rows="6" class="textarea" required minlength="20"
                          placeholder="Tulis perkembangan kampanye..."></textarea>
            </div>

            <div>
                <label class="label" for="update_image">Gambar (opsional)</label>
                <input id="update_image" name="image" type="file" accept="image/*"
                       class="input file:mr-3 file:rounded-md file:border-0 file:bg-accent/15 file:px-3 file:py-1 file:text-xs file:text-accent">
                <p class="mt-1 text-[11px] text-ink-soft">JPG, PNG, WEBP. Maks 5 MB.</p>
            </div>

            <button type="submit" class="btn-primary w-full">
                <i data-lucide="send" class="h-4 w-4"></i> Publikasikan Kabar
            </button>
        </form>

        <div class="space-y-4">
            <?php if ($updates): ?>
                <?php foreach ($updates as $update):
                    $image = Helper::media($update['image'] ?? null, 'updates');
                ?>
                    <div class="card card-pad">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium uppercase tracking-wide text-accent">
                                    <?= Helper::e($update['campaign_title'] ?? 'Kampanye dihapus') ?>
                                </p>
                                <h3 class="mt-1 text-sm font-semibold text-ink"><?= Helper::e($update['title']) ?></h3>
                                <p class="mt-1 text-[11px] text-ink-soft">
                                    <?= Helper::e($update['poster_name'] ?? 'Admin') ?>
                                    · <?= Helper::tanggal($update['created_at'], true) ?>
                                </p>
                            </div>
                            <form method="post" action="<?= Helper::url('admin/campaign-updates/' . $update['id'] . '/delete') ?>"
                                  data-confirm="Hapus kabar ini?" class="shrink-0">
                                <?= Helper::csrfField() ?>
                                <button type="submit" class="btn-danger btn-sm">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i> Hapus
                                </button>
                            </form>
                        </div>

                        <?php if ($image): ?>
                            <img src="<?= Helper::e($image) ?>" alt="" class="mt-3 h-40 w-full rounded-lg border border-line object-cover sm:w-64">
                        <?php endif; ?>

                        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-ink-soft"><?= Helper::e($update['content']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card p-12 text-center">
                    <i data-lucide="newspaper" class="mx-auto h-10 w-10 text-ink-soft"></i>
                    <p class="mt-3 font-medium text-ink">Belum ada kabar kampanye</p>
                    <p class="mt-1 text-sm text-ink-soft">Tulis kabar pertama melalui formulir di samping.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
