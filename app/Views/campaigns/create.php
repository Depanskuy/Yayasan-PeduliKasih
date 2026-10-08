<div class="card" style="max-width:850px;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-plus-circle" style="color:var(--primary);margin-right:8px;"></i> Tambah Program Campaign Baru</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/campaigns/store') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Judul Program Campaign</label>
        <input type="text" name="title" class="form-control" placeholder="Contoh: Beasiswa Pendidikan 100 Anak Yatim" required autofocus>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Kategori Program</label>
          <select name="category_id" class="form-control" required>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Target Penggalangan Dana (Rp)</label>
          <input type="number" name="target_amount" class="form-control" placeholder="50000000" min="1000000" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Tanggal Mulai</label>
          <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Selesai (Batas Waktu)</label>
          <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d', strtotime('+3 months')) ?>" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi Singkat (Ringkasan Cuplikan)</label>
        <textarea name="short_description" class="form-control" rows="2" placeholder="Ringkasan 1-2 kalimat untuk kartu beranda..." required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Cerita Lengkap & Latar Belakang Masalah</label>
        <textarea name="story" class="form-control" rows="6" placeholder="Jelaskan kondisi mustahik, tujuan penggalangan dana, serta perincian alokasi bantuan..." required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Unggah Foto Sampul / Banner (JPG/PNG/WEBP)</label>
        <input type="file" name="banner_image" class="form-control" accept="image/*">
      </div>

      <div style="display:flex;gap:20px;align-items:center;margin-top:10px;">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:600;">
          <input type="checkbox" name="is_featured" value="1" style="transform:scale(1.2);accent-color:var(--primary);">
          <span>Tampilkan sebagai Program Unggulan (Featured) di Beranda</span>
        </label>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:25px;">
        <a href="<?= url('admin/campaigns') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Simpan & Terbitkan Campaign
        </button>
      </div>
    </form>
  </div>
</div>
