<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-people-roof" style="color:var(--primary);margin-right:8px;"></i> Data Penerima Manfaat (Mustahik)</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Basis data calon penerima dan penerima bantuan sosial, status verifikasi lapangan, dan rekam jejak santunan.</p>
    </div>
    <a href="<?= url('admin/beneficiaries/create') ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-user-plus"></i> Tambah Mustahik Baru
    </a>
  </div>

  <div style="padding:15px 24px;background:#f8fafc;border-bottom:1px solid var(--border);display:flex;gap:10px;flex-wrap:wrap;">
    <a href="<?= url('admin/beneficiaries') ?>" class="btn btn-sm <?= empty($currentCategory) ? 'btn-primary' : 'btn-outline' ?>">Semua Kategori</a>
    <a href="<?= url('admin/beneficiaries?category=yatim') ?>" class="btn btn-sm <?= ($currentCategory === 'yatim') ? 'btn-primary' : 'btn-outline' ?>">Anak Yatim</a>
    <a href="<?= url('admin/beneficiaries?category=lansia') ?>" class="btn btn-sm <?= ($currentCategory === 'lansia') ? 'btn-primary' : 'btn-outline' ?>">Lansia Dhuafa</a>
    <a href="<?= url('admin/beneficiaries?category=miskin') ?>" class="btn btn-sm <?= ($currentCategory === 'miskin') ? 'btn-primary' : 'btn-outline' ?>">Keluarga Miskin</a>
    <a href="<?= url('admin/beneficiaries?category=korban_bencana') ?>" class="btn btn-sm <?= ($currentCategory === 'korban_bencana') ? 'btn-primary' : 'btn-outline' ?>">Korban Bencana</a>
    <a href="<?= url('admin/beneficiaries?category=difabel') ?>" class="btn btn-sm <?= ($currentCategory === 'difabel') ? 'btn-primary' : 'btn-outline' ?>">Difabel</a>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>NIK</th>
          <th>Nama Penerima</th>
          <th>Kategori</th>
          <th>Kontak</th>
          <th>Alamat Domisili</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($beneficiaries as $b): ?>
          <tr>
            <td><code><?= e($b['nik']) ?></code></td>
            <td><strong><?= e($b['name']) ?></strong></td>
            <td><span class="badge badge-primary"><?= ucfirst(str_replace('_', ' ', e($b['category']))) ?></span></td>
            <td><small><?= e($b['phone'] ?? '-') ?></small></td>
            <td><small><?= e($b['address']) ?>, <?= e($b['city']) ?></small></td>
            <td>
              <span class="badge badge-<?= $b['eligibility_status'] === 'verified' ? 'verified' : ($b['eligibility_status'] === 'rejected' ? 'rejected' : 'pending') ?>">
                <?= ucfirst(e($b['eligibility_status'])) ?>
              </span>
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="<?= url('admin/beneficiaries/' . $b['id']) ?>" class="btn btn-sm btn-outline" style="padding:4px 8px;">
                  <i class="fa-solid fa-eye"></i> Riwayat
                </a>
                <a href="<?= url('admin/beneficiaries/edit/' . $b['id']) ?>" class="btn btn-sm btn-outline" style="padding:4px 8px;">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
