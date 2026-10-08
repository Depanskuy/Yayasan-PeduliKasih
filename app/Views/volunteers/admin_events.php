<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-calendar-check" style="color:var(--primary);margin-right:8px;"></i> Manajemen Kegiatan Relawan</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Kelola agenda kegiatan sosial, kuota relawan, dan seleksi pendaftar.</p>
    </div>
    <a href="<?= url('admin/volunteers/events/create') ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-plus"></i> Buat Kegiatan Relawan Baru
    </a>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>Nama Kegiatan</th>
          <th>Tanggal & Jam</th>
          <th>Lokasi</th>
          <th>Pendaftar</th>
          <th>Disetujui</th>
          <th>Kuota</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $ev): ?>
          <tr>
            <td>
              <strong><a href="<?= url('volunteer/events/' . $ev['slug']) ?>" target="_blank"><?= e($ev['title']) ?></a></strong>
              <div style="font-size:0.75rem;color:var(--dark-muted);"><?= e($ev['campaign_title'] ?? 'Program Umum') ?></div>
            </td>
            <td><small><?= format_date($ev['event_date']) ?><br><?= e($ev['event_time']) ?></small></td>
            <td><small><?= e($ev['location']) ?></small></td>
            <td><strong><?= (int)$ev['total_applicants'] ?></strong> pelamar</td>
            <td><span class="badge badge-verified"><?= (int)$ev['approved_count'] ?></span></td>
            <td><?= (int)$ev['quota'] ?> Orang</td>
            <td><span class="badge badge-<?= $ev['status'] === 'open' ? 'verified' : 'danger' ?>"><?= strtoupper(e($ev['status'])) ?></span></td>
            <td>
              <a href="<?= url('admin/volunteers/events/' . $ev['id'] . '/applicants') ?>" class="btn btn-sm btn-outline" style="padding:4px 8px;">
                <i class="fa-solid fa-users-viewfinder"></i> Seleksi Pendaftar (<?= (int)$ev['total_applicants'] ?>)
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
