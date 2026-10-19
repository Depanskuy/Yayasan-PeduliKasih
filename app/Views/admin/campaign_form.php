<?php
// app/Views/admin/campaign_form.php
use App\Core\Helper;

$campaign   = $campaign ?? null;
$categories = $categories ?? [];
$isEdit     = !empty($campaign);

$val = function (string $key, $default = '') use ($campaign) {
    if ($campaign && array_key_exists($key, $campaign)) {
        return $campaign[$key];
    }
    return Helper::old($key, $default);
};
$action = $isEdit
    ? Helper::url('admin/campaigns/' . $campaign['id'])
    : Helper::url('admin/campaigns');

$targetRaw = $val('target_amount');
$targetDisplay = ($targetRaw === '' || $targetRaw === null)
    ? ''
    : number_format((float) $targetRaw, 0, ',', '.');
?>
<div class="mx-auto max-w-3xl space-y-6">
    <a href="<?= Helper::url('admin/campaigns') ?>" class="btn-link">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
    </a>

    <form action="<?= $action ?>" method="post" enctype="multipart/form-data" class="card card-pad space-y-5">
        <?= Helper::csrfField() ?>

        <div>
            <label class="label" for="title">Judul Kampanye</label>
            <input id="title" name="title" class="input" required value="<?= Helper::e($val('title')) ?>">
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="category_id">Kategori</label>
                <select id="category_id" name="category_id" class="select" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id'] ?>"
                            <?= (int) $val('category_id', 0) === (int) $category['id'] ? 'selected' : '' ?>>
                            <?= Helper::e($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select">
                    <?php foreach (['active' => 'Aktif', 'pending' => 'Pending', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $val('status', 'active') === $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label class="label" for="short_description">Deskripsi Singkat</label>
            <textarea id="short_description" name="short_description" rows="2" class="textarea" required><?= Helper::e($val('short_description')) ?></textarea>
        </div>

        <div>
            <label class="label" for="story">Cerita Lengkap</label>
            <textarea id="story" name="story" rows="6" class="textarea" required><?= Helper::e($val('story')) ?></textarea>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="label" for="target_amount">Target Dana (Rp)</label>
                <input id="target_amount" name="target_amount" class="input" inputmode="numeric" data-currency required
                       value="<?= Helper::e($targetDisplay) ?>">
            </div>
            <div>
                <label class="label" for="start_date">Tanggal Mulai</label>
                <input id="start_date" name="start_date" type="date" class="input" required value="<?= Helper::e($val('start_date')) ?>">
            </div>
            <div>
                <label class="label" for="end_date">Tanggal Selesai</label>
                <input id="end_date" name="end_date" type="date" class="input" required value="<?= Helper::e($val('end_date')) ?>">
            </div>
        </div>

        <div>
            <label class="label" for="banner_image">Banner (opsional)</label>
            <input id="banner_image" name="banner_image" type="file" accept="image/*"
                   class="input file:mr-3 file:rounded-md file:border-0 file:bg-accent/15 file:px-3 file:py-1 file:text-xs file:text-accent">
            <?php if ($isEdit && !empty($campaign['banner_image'])): ?>
                <p class="mt-1 text-xs text-ink-soft">Banner saat ini: <?= Helper::e($campaign['banner_image']) ?></p>
            <?php endif; ?>
        </div>

        <label class="flex items-center gap-2 text-sm text-ink-soft">
            <input type="checkbox" name="is_featured" value="1"
                   class="h-4 w-4 rounded border-line bg-surface text-accent focus:ring-accent/40"
                <?= !empty($val('is_featured')) ? 'checked' : '' ?>>
            Tandai sebagai kampanye unggulan
        </label>

        <div class="flex justify-end gap-3 border-t border-line pt-5">
            <a href="<?= Helper::url('admin/campaigns') ?>" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="h-4 w-4"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Kampanye' ?>
            </button>
        </div>
    </form>
</div>
