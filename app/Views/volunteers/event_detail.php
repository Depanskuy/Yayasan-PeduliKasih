<div style="background:#f1f5f9;padding:15px 0;font-size:0.85rem;border-bottom:1px solid var(--border);">
  <div class="container">
    <a href="<?= url('/') ?>">Beranda</a> &nbsp;/&nbsp; 
    <a href="<?= url('volunteer/events') ?>">Kegiatan Relawan</a> &nbsp;/&nbsp; 
    <span class="text-muted"><?= e($event['title']) ?></span>
  </div>
</div>

<div class="container section" style="padding-top:35px;">
  <div style="display:grid;grid-template-columns:1.3fr 0.8fr;gap:35px;align-items:start;">
    
    <div>
      <div style="border-radius:var(--radius);overflow:hidden;margin-bottom:25px;background:#cbd5e1;">
        <img src="<?= !empty($event['banner_image']) ? asset('uploads/' . $event['banner_image']) : 'https://placehold.co/800x450/0284c7/ffffff?text=Aksi+Relawan' ?>" alt="<?= e($event['title']) ?>" style="width:100%;max-height:400px;object-fit:cover;">
      </div>

      <h1 style="font-size:1.85rem;margin-bottom:20px;"><?= e($event['title']) ?></h1>

      <div class="card" style="margin-bottom:30px;">
        <div class="card-header">
          <h4 class="card-title"><i class="fa-solid fa-circle-info" style="color:var(--primary);margin-right:8px;"></i> Deskripsi & Tugas Lapangan</h4>
        </div>
        <div class="card-body" style="line-height:1.8;font-size:0.95rem;">
          <?= nl2br(e($event['description'])) ?>
        </div>
      </div>
    </div>

    <!-- Right Column: Registration Card -->
    <div>
      <div class="card" style="position:sticky;top:90px;box-shadow:var(--shadow-lg);border-radius:var(--radius-lg);">
        <div class="card-body" style="padding:30px;">
          <h4 style="margin-bottom:20px;border-bottom:1px solid var(--border);padding-bottom:10px;">Informasi Pelaksanaan</h4>

          <div style="display:flex;flex-direction:column;gap:12px;font-size:0.9rem;margin-bottom:25px;">
            <div><i class="fa-regular fa-calendar" style="color:var(--primary);width:22px;"></i> Tanggal: <strong><?= format_date($event['event_date']) ?></strong></div>
            <div><i class="fa-regular fa-clock" style="color:var(--primary);width:22px;"></i> Waktu: <strong><?= e($event['event_time']) ?></strong></div>
            <div><i class="fa-solid fa-location-dot" style="color:var(--primary);width:22px;"></i> Lokasi: <strong><?= e($event['location']) ?></strong></div>
            <div><i class="fa-solid fa-users" style="color:var(--primary);width:22px;"></i> Kuota: <strong><?= e($event['quota']) ?> Orang</strong></div>
            <div><i class="fa-solid fa-hourglass-end" style="color:#ef4444;width:22px;"></i> Batas Daftar: <strong><?= format_date($event['registration_deadline']) ?></strong></div>
          </div>

          <?php if (!empty($existingReg)): ?>
            <div class="alert alert-info">
              <i class="fa-solid fa-circle-check"></i> Anda telah mendaftar. Status: <strong><?= strtoupper(e($existingReg['status'])) ?></strong>
            </div>
            <a href="<?= url('volunteer/dashboard') ?>" class="btn btn-outline btn-block">Buka Dashboard Relawan</a>
          <?php elseif ($user): ?>
            <!-- Form Pendaftaran -->
            <form action="<?= url('volunteer/events/' . $event['slug'] . '/register') ?>" method="POST">
              <?= csrf_field() ?>
              <h5 style="margin-bottom:12px;">Formulir Pendaftaran</h5>

              <div class="form-group">
                <label class="form-label">Keahlian / Keterampilan yang Dimiliki</label>
                <input type="text" name="skills" class="form-control" placeholder="Contoh: P3K, Mengajar, Fotografi, Mengemudi">
              </div>

              <div class="form-group">
                <label class="form-label">Motivasi Bergabung</label>
                <textarea name="motivation" class="form-control" rows="3" required placeholder="Ceritakan motivasi Anda mengikuti kegiatan ini..."></textarea>
              </div>

              <button type="submit" class="btn btn-primary btn-block">
                <i class="fa-solid fa-paper-plane"></i> Kirim Pendaftaran
              </button>
            </form>
          <?php else: ?>
            <div class="alert alert-warning">
              Silakan masuk atau daftar akun terlebih dahulu untuk mendaftar sebagai relawan aksi ini.
            </div>
            <a href="<?= url('login') ?>" class="btn btn-primary btn-block">
              <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk / Daftar Akun
            </a>
          <?php endif; ?>

        </div>
      </div>
    </div>

  </div>
</div>
