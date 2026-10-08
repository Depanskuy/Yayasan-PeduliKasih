<div style="display:grid;grid-template-columns:1.3fr 0.9fr;gap:25px;align-items:start;">
  
  <!-- Edit Main Details -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="fa-solid fa-pen-to-square" style="color:var(--primary);margin-right:8px;"></i> Edit Data Campaign</h3>
    </div>
    <div class="card-body">
      <form action="<?= url('admin/campaigns/update/' . $campaign['id']) ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
          <label class="form-label">Judul Program Campaign</label>
          <input type="text" name="title" class="form-control" value="<?= e($campaign['title']) ?>" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
          <div class="form-group">
            <label class="form-label">Kategori Program</label>
            <select name="category_id" class="form-control" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $campaign['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Target Dana (Rp)</label>
            <input type="number" name="target_amount" class="form-control" value="<?= (int)$campaign['target_amount'] ?>" required>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
          <div class="form-group">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control" value="<?= e($campaign['start_date']) ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control" value="<?= e($campaign['end_date']) ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Status Program</label>
          <select name="status" class="form-control">
            <option value="active" <?= $campaign['status'] === 'active' ? 'selected' : '' ?>>Aktif (Menerima Donasi)</option>
            <option value="completed" <?= $campaign['status'] === 'completed' ? 'selected' : '' ?>>Selesai (Target Tercapai)</option>
            <option value="cancelled" <?= $campaign['status'] === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Singkat</label>
          <textarea name="short_description" class="form-control" rows="2" required><?= e($campaign['short_description']) ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Cerita Lengkap</label>
          <textarea name="story" class="form-control" rows="5" required><?= e($campaign['story']) ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Ganti Foto Sampul (Opsional)</label>
          <?php if (!empty($campaign['banner_image'])): ?>
            <div style="margin-bottom:8px;">
              <img src="<?= asset('uploads/' . $campaign['banner_image']) ?>" alt="" style="max-height:100px;border-radius:6px;">
            </div>
          <?php endif; ?>
          <input type="file" name="banner_image" class="form-control" accept="image/*">
        </div>

        <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
          <input type="checkbox" name="is_featured" value="1" <?= $campaign['is_featured'] ? 'checked' : '' ?> style="transform:scale(1.2);accent-color:var(--primary);">
          <span style="font-weight:600;">Program Unggulan di Beranda</span>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
        </button>
      </form>
    </div>
  </div>

  <!-- Form Tambah Kabar Progres Perkembangan -->
  <div class="card">
    <div class="card-header" style="background:#f0fdf4;">
      <h3 class="card-title" style="color:var(--primary-dark);font-size:1.05rem;">
        <i class="fa-solid fa-bullhorn"></i> Publikasikan Kabar Perkembangan
      </h3>
    </div>
    <div class="card-body">
      <p style="font-size:0.85rem;color:var(--dark-muted);margin-bottom:15px;">
        Berikan update progres fisik atau penyaluran dana agar para donatur dapat melihat transparansi penggunaan uang mereka.
      </p>

      <form action="<?= url('admin/campaigns/' . $campaign['id'] . '/update-progress') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
          <label class="form-label">Judul Kabar Perkembangan</label>
          <input type="text" name="update_title" class="form-control" placeholder="Contoh: Pengecoran Lantai 2 Telah Selesai" required>
        </div>

        <div class="form-group">
          <label class="form-label">Rincian Progres</label>
          <textarea name="update_content" class="form-control" rows="4" placeholder="Ceritakan progres pengerjaan atau kondisi terkini di lapangan..." required></textarea>
        </div>

        <button type="submit" class="btn btn-cta btn-block">
          <i class="fa-solid fa-paper-plane"></i> Publikasikan Kabar Progres
        </button>
      </form>
    </div>
  </div>

</div>
