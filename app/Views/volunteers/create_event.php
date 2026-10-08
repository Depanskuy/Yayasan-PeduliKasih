<div class="card" style="max-width:850px;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-plus-circle" style="color:var(--primary);margin-right:8px;"></i> Buat Kegiatan Relawan Baru</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/volunteers/events/store') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Nama Kegiatan Relawan</label>
        <input type="text" name="title" class="form-control" placeholder="Contoh: Aksi Tanggap Bencana: Distribusi Makanan & Trauma Healing" required autofocus>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Program Campaign Terkait (Opsional)</label>
          <select name="campaign_id" class="form-control">
            <option value="">-- Kegiatan Rutin / Umum --</option>
            <?php foreach ($campaigns as $c): ?>
              <option value="<?= $c['id'] ?>"><?= e($c['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kuota Relawan Dibutuhkan</label>
          <input type="number" name="quota" class="form-control" value="20" min="1" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Tanggal Pelaksanaan</label>
          <input type="date" name="event_date" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Jam Pelaksanaan</label>
          <input type="text" name="event_time" class="form-control" value="08:00 - 15:00 WIB" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Lokasi Kegiatan</label>
          <input type="text" name="location" class="form-control" placeholder="Nama posko, gedung, atau desa" required>
        </div>
        <div class="form-group">
          <label class="form-label">Batas Akhir Pendaftaran</label>
          <input type="date" name="registration_deadline" class="form-control" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi Lengkap & Persyaratan Relawan</label>
        <textarea name="description" class="form-control" rows="5" placeholder="Tuliskan tugas lapangan, perlengkapan yang perlu dibawa, serta kriteria relawan..." required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Foto Sampul / Banner Kegiatan</label>
        <input type="file" name="banner_image" class="form-control" accept="image/*">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
        <a href="<?= url('admin/volunteers/events') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Terbitkan Kegiatan Relawan
        </button>
      </div>
    </form>
  </div>
</div>
