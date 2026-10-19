<?php
// app/Views/admin/article_form.php
use App\Core\Helper;

$article    = $article ?? null;
$categories = $categories ?? [];
$isEdit     = !empty($article);

$val = static function (string $key, $default = '') use ($article) {
    if ($article && array_key_exists($key, $article)) {
        return $article[$key];
    }
    return Helper::old($key, $default);
};
$action = $isEdit
    ? Helper::url('admin/articles/' . $article['id'])
    : Helper::url('admin/articles');
$thumb = $isEdit ? Helper::media($article['featured_image'] ?? null, 'articles') : null;
?>
<div class="mx-auto max-w-3xl space-y-6">
    <a href="<?= Helper::url('admin/articles') ?>" class="btn-link">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
    </a>

    <form action="<?= $action ?>" method="post" enctype="multipart/form-data" class="card card-pad space-y-5">
        <?= Helper::csrfField() ?>

        <div>
            <label class="label" for="title">Judul Artikel</label>
            <input id="title" name="title" class="input" required minlength="5" value="<?= Helper::e($val('title')) ?>">
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="category">Kategori</label>
                <select id="category" name="category" class="select" required>
                    <?php foreach ($categories as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $val('category', 'kegiatan') === $key ? 'selected' : '' ?>><?= Helper::e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select">
                    <option value="published" <?= $val('status', 'published') === 'published' ? 'selected' : '' ?>>Terbit</option>
                    <option value="draft" <?= $val('status') === 'draft' ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>
        </div>

        <div>
            <label class="label" for="excerpt">Ringkasan</label>
            <textarea id="excerpt" name="excerpt" rows="2" class="textarea" required minlength="10"
                      placeholder="Ringkasan singkat yang tampil di daftar artikel..."><?= Helper::e($val('excerpt')) ?></textarea>
        </div>

        <div>
            <label class="label" for="content">Isi Artikel</label>
            <textarea id="content" name="content" rows="12" class="textarea" required minlength="20"
                      placeholder="Tulis isi artikel... Tag HTML dasar seperti <p>, <strong>, <ul>, <li> diperbolehkan."><?= Helper::e($val('content')) ?></textarea>
            <p class="mt-1 text-[11px] text-ink-soft">Tag yang diizinkan saat ditampilkan: p, br, strong, em, ul, ol, li, h2, h3, blockquote.</p>
        </div>

        <div>
            <label class="label" for="featured_image">Gambar Utama (opsional)</label>
            <input id="featured_image" name="featured_image" type="file" accept="image/*"
                   class="input file:mr-3 file:rounded-md file:border-0 file:bg-accent/15 file:px-3 file:py-1 file:text-xs file:text-accent">
            <p class="mt-1 text-[11px] text-ink-soft">JPG, PNG, atau WEBP. Maks 5 MB.</p>
            <?php if ($thumb): ?>
                <img src="<?= Helper::e($thumb) ?>" alt="Gambar saat ini" class="mt-3 h-28 w-44 rounded-lg border border-line object-cover">
            <?php endif; ?>
        </div>

        <div class="flex justify-end gap-3 border-t border-line pt-5">
            <a href="<?= Helper::url('admin/articles') ?>" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="h-4 w-4"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Artikel' ?>
            </button>
        </div>
    </form>
</div>
