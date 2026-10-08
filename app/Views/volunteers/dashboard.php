<div style="display:grid;grid-template-columns:1.3fr 1fr;gap:25px;align-items:start;">
  
  <!-- My Registered Events & Attendance -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="fa-solid fa-calendar-check" style="color:var(--primary);margin-right:8px;"></i> Kegiatan Relawan Saya</h3>
      <a href="<?= url('volunteer/events') ?>" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-magnifying-glass"></i> Cari Event Baru
      </a>
    </div>
    <div class="card-body table-responsive" style="padding:0;">
      <?php if (empty($registrations)): ?>
        <div style="text-align:center;padding:35px;color:var(--dark-muted);">
          <i class="fa-solid fa-calendar-xmark" style="font-size:2rem;margin-bottom:8px;"></i>
          <div>Anda belum terdaftar pada kegiatan relawan manapun.</div>
          <a href="<?= url('volunteer/events') ?>" class="btn btn-outline btn-sm" style="margin-top:12px;">Lihat Kegiatan Terbuka</a>
        </div>
      <?php else: ?>
        <table class="table">
          <thead>
            <tr>
              <th>Kegiatan</th>
              <th>Jadwal</th>
              <th>Status</th>
              <th>Absensi Hadir</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($registrations as $r): ?>
              <tr>
                <td>
                  <strong><a href="<?= url('volunteer/events/' . $r['event_slug']) ?>" target="_blank"><?= e($r['event_title']) ?></a></strong>
                  <div style="font-size:0.75rem;color:var(--dark-muted);"><i class="fa-solid fa-location-dot"></i> <?= e($r['location']) ?></div>
                </td>
                <td><small><?= format_date($r['event_date']) ?><br><?= e($r['event_time']) ?></small></td>
                <td>
                  <span class="badge badge-<?= $r['status'] === 'approved' ? 'verified' : ($r['status'] === 'attended' ? 'primary' : ($r['status'] === 'rejected' ? 'rejected' : 'pending')) ?>">
                    <?= ucfirst(e($r['status'])) ?>
                  </span>
                </td>
                <td>
                  <?php if ($r['status'] === 'approved'): ?>
                    <form action="<?= url('volunteer/attendance/' . $r['id']) ?>" method="POST" onsubmit="return confirm('Konfirmasi kehadiran Anda di lokasi?')">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-sm btn-primary" style="padding:4px 8px;font-size:0.75rem;">
                        <i class="fa-solid fa-location-crosshairs"></i> Absen Hadir
                      </button>
                    </form>
                  <?php elseif ($r['status'] === 'attended'): ?>
                    <span style="font-size:0.75rem;color:var(--primary);font-weight:700;">
                      <i class="fa-solid fa-circle-check"></i> Telah Hadir
                    </span>
                  <?php else: ?>
                    <small class="text-muted">Menunggu Seleksi</small>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- Form Kirim Laporan Lapangan Relawan -->
  <div class="card">
    <div class="card-header" style="background:#f0fdf4;">
      <h3 class="card-title" style="color:var(--primary-dark);font-size:1.05rem;">
        <i class="fa-solid fa-clipboard-check"></i> Kirim Laporan Kegiatan Lapangan
      </h3>
    </div>
    <div class="card-body">
      <form action="<?= url('volunteer/report/submit') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
          <label class="form-label">Pilih Kegiatan yang Telah Diikuti</label>
          <select name="event_id" class="form-control" required>
            <?php foreach ($registrations as $r): ?>
              <?php if (in_array($r['status'], ['approved', 'attended'])): ?>
                <option value="<?= $r['event_id'] ?>"><?= e($r['event_title']) ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Judul Laporan</label>
          <input type="text" name="report_title" class="form-control" placeholder="Contoh: Laporan Pendampingan Posko Trauma Healing" required>
        </div>

        <div class="form-group">
          <label class="form-label">Ringkasan Aktivitas & Capaian di Lapangan</label>
          <textarea name="activity_summary" class="form-control" rows="3" placeholder="Ceritakan jumlah mustahik yang dibantu dan tugas yang Anda selesaikan..." required></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Estimasi Jam Kerja Relawan (Hours Spent)</label>
          <input type="number" step="0.5" name="hours_spent" class="form-control" value="4.0" required>
        </div>

        <div class="form-group">
          <label class="form-label">Unggah Foto Dokumentasi Kegiatan Anda</label>
          <input type="file" name="documentation_image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-cta btn-block">
          <i class="fa-solid fa-paper-plane"></i> Kirim Laporan Lapangan
        </button>
      </form>
    </div>
  </div>

</div>
