<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:25px;align-items:start;">
  
  <!-- Gallery Grid Table -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="fa-solid fa-images" style="color:var(--primary);margin-right:8px;"></i> Galeri Dokumentasi Aksi Sosial</h3>
    </div>
    <div class="card-body" style="padding:20px;">
      <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:15px;">
        <?php foreach ($galleries as $g): ?>
          <div style="border:1px solid var(--border);border-radius:6px;overflow:hidden;background:#fff;">
            <img src="<?= asset('uploads/' . $g['media_url']) ?>" alt="<?= e($g['title']) ?>" style="width:100%;height:120px;object-fit:cover;">
            <div style="padding:10px;">
              <strong style="font-size:0.85rem;display:block;margin-bottom:2px;"><?= e($g['title']) ?></strong>
              <small class="text-muted"><?= format_date($g['created_at']) ?></small>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Upload Form -->
  <div class="card">
    <div class="card-header" style="background:#f0fdf4;">
      <h3 class="card-title" style="color:var(--primary-dark);font-size:1.05rem;">
        <i class="fa-solid fa-cloud-arrow-up"></i> Unggah Foto Dokumentasi Baru
      </h3>
    </div>
    <div class="card-body">
      <form action="<?= url('admin/gallery/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
          <label class="form-label">Judul Foto Dokumentasi</label>
          <input type="text" name="title" class="form-control" placeholder="Contoh: Penyerahan Paket Sembako Lansia" required autofocus>
        </div>

        <div class="form-group">
          <label class="form-label">Program Campaign Terkait (Opsional)</label>
          <select name="campaign_id" class="form-control">
            <option value="">-- Dokumentasi Umum --</option>
            <?php foreach ($campaigns as $c): ?>
              <option value="<?= $c['id'] ?>"><?= e($c['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Keterangan Singkat</label>
          <textarea name="description" class="form-control" rows="2" placeholder="Keterangan singkat tentang aksi sosial di foto..."></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Pilih Berkas Foto (JPG/PNG/WEBP)</label>
          <input type="file" name="media_file" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
          <i class="fa-solid fa-upload"></i> Unggah ke Galeri
        </button>
      </form>
    </div>
  </div>

</div>
