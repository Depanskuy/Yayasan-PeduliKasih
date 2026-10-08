<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-clipboard-list" style="color:var(--primary);margin-right:8px;"></i> Laporan Kegiatan Lapangan Relawan</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Tinjau laporan kerja lapangan, jam kontribusi, dan foto dokumentasi yang dikirimkan oleh relawan.</p>
    </div>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <?php if (empty($reports)): ?>
      <div style="text-align:center;padding:40px;color:var(--dark-muted);">
        <i class="fa-solid fa-inbox" style="font-size:2rem;margin-bottom:8px;"></i>
        <div>Belum ada laporan kegiatan lapangan yang masuk.</div>
      </div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Tanggal Kirim</th>
            <th>Relawan</th>
            <th>Kegiatan</th>
            <th>Judul Laporan & Ringkasan</th>
            <th>Jam Kontribusi</th>
            <th>Dokumentasi</th>
            <th>Status</th>
            <th>Feedback Admin</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reports as $rep): ?>
            <tr>
              <td><small><?= format_date($rep['created_at'], true) ?></small></td>
              <td><strong><?= e($rep['volunteer_name']) ?></strong></td>
              <td><small><?= e($rep['event_title']) ?></small></td>
              <td>
                <strong><?= e($rep['report_title']) ?></strong>
                <p style="font-size:0.82rem;color:var(--dark-muted);margin-top:4px;"><?= nl2br(e($rep['activity_summary'])) ?></p>
              </td>
              <td><strong><?= $rep['hours_spent'] ?> Jam</strong></td>
              <td>
                <?php if (!empty($rep['documentation_image'])): ?>
                  <a href="<?= asset('uploads/' . $rep['documentation_image']) ?>" target="_blank" class="btn btn-sm btn-outline" style="padding:2px 8px;font-size:0.75rem;">
                    <i class="fa-solid fa-image"></i> Foto
                  </a>
                <?php else: ?>
                  <small class="text-muted">-</small>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge badge-<?= $rep['status'] === 'approved' ? 'verified' : 'pending' ?>">
                  <?= ucfirst(e($rep['status'])) ?>
                </span>
              </td>
              <td>
                <?php if (!empty($rep['admin_feedback'])): ?>
                  <small style="color:var(--success);font-style:italic;">"<?= e($rep['admin_feedback']) ?>"</small>
                <?php else: ?>
                  <form action="<?= url('admin/volunteers/reports/' . $rep['id'] . '/feedback') ?>" method="POST" style="display:flex;gap:4px;">
                    <?= csrf_field() ?>
                    <input type="text" name="admin_feedback" placeholder="Ketik feedback..." class="form-control" style="font-size:0.75rem;padding:3px 6px;" required>
                    <button type="submit" class="btn btn-sm btn-primary" style="padding:3px 8px;font-size:0.75rem;">Setujui</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
