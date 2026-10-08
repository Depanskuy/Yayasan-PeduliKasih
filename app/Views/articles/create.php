<div class="card" style="max-width:850px;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-pen-nib" style="color:var(--primary);margin-right:8px;"></i> Tulis Artikel / Berita Baru</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/articles/store') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Judul Artikel</label>
        <input type="text" name="title" class="form-control" placeholder="Judul artikel atau berita kegiatan" required autofocus>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select name="category" class="form-control" required>
            <option value="berita">Berita Kegiatan</option>
            <option value="inspirasi">Kisah Inspiratif</option>
            <option value="kegiatan">Aksi Lapangan</option>
            <option value="edukasi">Edukasi Kemanusiaan</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Status Terbit</label>
          <select name="status" class="form-control">
            <option value="published">Publikasikan Sekarang</option>
            <option value="draft">Simpan Sebagai Draf</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Ringkasan Cuplikan (Excerpt)</label>
        <textarea name="excerpt" class="form-control" rows="2" placeholder="Ringkasan singkat untuk tampilan kartu preview..." required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Isi Lengkap Konten Artikel (Mendukung HTML & Paragraf)</label>
        <textarea name="content" class="form-control" rows="8" placeholder="Tuliskan isi artikel lengkap di sini..." required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Foto Utama / Thumbnail Artikel (JPG/PNG/WEBP)</label>
        <input type="file" name="featured_image" class="form-control" accept="image/*">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
        <a href="<?= url('admin/articles') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Simpan & Terbitkan Artikel
        </button>
      </div>
    </form>
  </div>
</div>
