<div class="card" style="max-width:850px;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-pen-to-square" style="color:var(--primary);margin-right:8px;"></i> Edit Artikel: <?= e($article['title']) ?></h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/articles/update/' . $article['id']) ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Judul Artikel</label>
        <input type="text" name="title" class="form-control" value="<?= e($article['title']) ?>" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select name="category" class="form-control" required>
            <?php foreach (['berita', 'inspirasi', 'kegiatan', 'edukasi'] as $cat): ?>
              <option value="<?= $cat ?>" <?= $article['category'] === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Status Terbit</label>
          <select name="status" class="form-control">
            <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Dipublikasikan (Published)</option>
            <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Draf (Draft)</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Ringkasan Cuplikan (Excerpt)</label>
        <textarea name="excerpt" class="form-control" rows="2" required><?= e($article['excerpt']) ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Isi Lengkap Konten Artikel</label>
        <textarea name="content" class="form-control" rows="8" required><?= e($article['content']) ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Ganti Foto Sampul / Thumbnail (Opsional)</label>
        <?php if (!empty($article['featured_image'])): ?>
          <div style="margin-bottom:8px;">
            <img src="<?= asset('uploads/' . $article['featured_image']) ?>" alt="" style="max-height:100px;border-radius:6px;">
          </div>
        <?php endif; ?>
        <input type="file" name="featured_image" class="form-control" accept="image/*">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
        <a href="<?= url('admin/articles') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Artikel
        </button>
      </div>
    </form>
  </div>
</div>
