<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-users-viewfinder" style="color:var(--primary);margin-right:8px;"></i> Seleksi Relawan: <?= e($event['title']) ?></h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Konfirmasi atau tolak calon relawan yang mendaftar pada kegiatan ini.</p>
    </div>
    <a href="<?= url('admin/volunteers/events') ?>" class="btn btn-outline btn-sm">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kegiatan
    </a>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <?php if (empty($applicants)): ?>
      <div style="text-align:center;padding:40px;color:var(--dark-muted);">
        <i class="fa-solid fa-users-slash" style="font-size:2rem;margin-bottom:8px;"></i>
        <div>Belum ada relawan yang mendaftar pada kegiatan ini.</div>
      </div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Nama Relawan</th>
            <th>Kontak</th>
            <th>Keahlian</th>
            <th>Motivasi Bergabung</th>
            <th>Status Seleksi</th>
            <th>Absensi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applicants as $app): ?>
            <tr>
              <td><strong><?= e($app['volunteer_name']) ?></strong></td>
              <td>
                <small><?= e($app['volunteer_phone'] ?? '-') ?><br><?= e($app['volunteer_email']) ?></small>
              </td>
              <td><span class="badge badge-primary"><?= e($app['skills'] ?? 'Umum') ?></span></td>
              <td><small style="max-width:250px;display:block;"><?= nl2br(e($app['motivation'])) ?></small></td>
              <td>
                <span class="badge badge-<?= $app['status'] === 'approved' ? 'verified' : ($app['status'] === 'attended' ? 'primary' : ($app['status'] === 'rejected' ? 'rejected' : 'pending')) ?>">
                  <?= ucfirst(e($app['status'])) ?>
                </span>
              </td>
              <td>
                <?php if ($app['status'] === 'attended'): ?>
                  <span style="font-size:0.75rem;color:var(--success);font-weight:700;"><i class="fa-solid fa-check-double"></i> Hadir Lapangan</span>
                <?php else: ?>
                  <span style="font-size:0.75rem;color:#94a3b8;">Belum Hadir</span>
                <?php endif; ?>
              </td>
              <td>
                <form action="<?= url('admin/volunteers/applicant/' . $app['id'] . '/status') ?>" method="POST" style="display:flex;gap:4px;">
                  <?= csrf_field() ?>
                  <input type="hidden" name="event_id" value="<?= e($event['id']) ?>">
                  <?php if ($app['status'] !== 'approved'): ?>
                    <button type="submit" name="status" value="approved" class="btn btn-sm btn-primary" style="padding:3px 8px;font-size:0.75rem;" title="Terima Relawan">
                      <i class="fa-solid fa-check"></i> Terima
                    </button>
                  <?php endif; ?>
                  <?php if ($app['status'] !== 'rejected'): ?>
                    <button type="submit" name="status" value="rejected" class="btn btn-sm" style="background:#fee2e2;color:#ef4444;padding:3px 8px;font-size:0.75rem;" title="Tolak Pendaftaran">
                      <i class="fa-solid fa-xmark"></i> Tolak
                    </button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
