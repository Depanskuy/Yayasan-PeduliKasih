<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-bullhorn" style="color:var(--primary);margin-right:8px;"></i> Daftar Program & Campaign Penggalangan Dana</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Kelola program aktif, pantau target pencapaian dana, dan perbarui progres lapangan.</p>
    </div>
    <a href="<?= url('admin/campaigns/create') ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-plus"></i> Tambah Campaign Baru
    </a>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>Banner</th>
          <th>Judul Campaign</th>
          <th>Kategori</th>
          <th>Target Dana</th>
          <th>Terkumpul</th>
          <th>Progress</th>
          <th>Batas Waktu</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($campaigns as $c): ?>
          <?php 
            $pct = $c['target_amount'] > 0 ? min(100, round(($c['collected_amount'] / $c['target_amount']) * 100)) : 0;
            $img = !empty($c['banner_image']) ? asset('uploads/' . $c['banner_image']) : 'https://placehold.co/100x60/059669/ffffff?text=Camp';
          ?>
          <tr>
            <td>
              <img src="<?= $img ?>" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:4px;">
            </td>
            <td>
              <strong><a href="<?= url('campaign/detail/' . $c['slug']) ?>" target="_blank"><?= e($c['title']) ?></a></strong>
              <div style="font-size:0.75rem;color:var(--dark-muted);"><?= (int)$c['donor_count'] ?> donatur terverifikasi</div>
            </td>
            <td><span class="badge badge-primary"><?= e($c['category_name']) ?></span></td>
            <td><?= format_rupiah($c['target_amount']) ?></td>
            <td><strong class="text-primary"><?= format_rupiah($c['collected_amount']) ?></strong></td>
            <td>
              <div style="width:100px;">
                <div class="progress-track" style="margin-bottom:2px;height:6px;">
                  <div class="progress-bar" style="width:<?= $pct ?>%;"></div>
                </div>
                <small style="font-weight:700;"><?= $pct ?>%</small>
              </div>
            </td>
            <td><small><?= format_date($c['end_date']) ?></small></td>
            <td>
              <span class="badge badge-<?= $c['status'] === 'active' ? 'verified' : ($c['status'] === 'completed' ? 'primary' : 'warning') ?>">
                <?= ucfirst(e($c['status'])) ?>
              </span>
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="<?= url('admin/campaigns/edit/' . $c['id']) ?>" class="btn btn-sm btn-outline" style="padding:4px 8px;">
                  <i class="fa-solid fa-pen-to-square"></i> Edit / Update
                </a>
                <a href="<?= url('campaign/' . $c['slug'] . '/donate') ?>" target="_blank" class="btn btn-sm btn-cta" style="padding:4px 8px;">
                  <i class="fa-solid fa-heart"></i>
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
