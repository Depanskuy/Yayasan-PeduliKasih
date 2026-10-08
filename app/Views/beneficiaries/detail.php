<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:25px;align-items:start;">
  
  <!-- Profile Mustahik Card -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="fa-solid fa-id-card" style="color:var(--primary);margin-right:8px;"></i> Profil Mustahik</h3>
    </div>
    <div class="card-body">
      <div style="text-align:center;margin-bottom:20px;">
        <div style="width:70px;height:70px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:2rem;">
          <i class="fa-solid fa-person"></i>
        </div>
        <h3 style="font-size:1.25rem;margin-bottom:4px;"><?= e($beneficiary['name']) ?></h3>
        <span class="badge badge-primary"><?= ucfirst(str_replace('_', ' ', e($beneficiary['category']))) ?></span>
      </div>

      <table style="width:100%;font-size:0.9rem;line-height:2;">
        <tr>
          <td style="color:var(--dark-muted);width:100px;">NIK:</td>
          <td><strong><?= e($beneficiary['nik']) ?></strong></td>
        </tr>
        <tr>
          <td style="color:var(--dark-muted);">Telepon:</td>
          <td><?= e($beneficiary['phone'] ?? '-') ?></td>
        </tr>
        <tr>
          <td style="color:var(--dark-muted);">Alamat:</td>
          <td><?= e($beneficiary['address']) ?>, <?= e($beneficiary['city']) ?></td>
        </tr>
        <tr>
          <td style="color:var(--dark-muted);">Status:</td>
          <td><span class="badge badge-verified"><?= ucfirst(e($beneficiary['eligibility_status'])) ?></span></td>
        </tr>
      </table>

      <?php if (!empty($beneficiary['notes'])): ?>
        <div style="margin-top:15px;padding:12px;background:#f8fafc;border-radius:6px;border:1px solid var(--border);font-size:0.85rem;">
          <strong>Catatan Survei Lapangan:</strong><br>
          <?= nl2br(e($beneficiary['notes'])) ?>
        </div>
      <?php endif; ?>

      <div style="margin-top:20px;">
        <a href="<?= url('admin/beneficiaries/edit/' . $beneficiary['id']) ?>" class="btn btn-outline btn-block btn-sm">
          <i class="fa-solid fa-pen-to-square"></i> Edit Data Mustahik
        </a>
      </div>
    </div>
  </div>

  <!-- Riwayat Penyaluran Bantuan -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--accent);margin-right:8px;"></i> Riwayat Bantuan yang Telah Diterima</h3>
      <a href="<?= url('admin/distributions/create') ?>" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-plus"></i> Salurkan Bantuan Baru
      </a>
    </div>
    <div class="card-body table-responsive" style="padding:0;">
      <?php if (empty($history)): ?>
        <div style="text-align:center;padding:40px;color:var(--dark-muted);">
          <i class="fa-solid fa-box-open" style="font-size:2rem;margin-bottom:8px;"></i>
          <div>Belum ada catatan penyaluran bantuan untuk mustahik ini.</div>
        </div>
      <?php else: ?>
        <table class="table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Tanggal</th>
              <th>Program</th>
              <th>Jenis Bantuan</th>
              <th>Nominal</th>
              <th>Petugas</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($history as $h): ?>
              <tr>
                <td><strong><?= e($h['disbursement_code']) ?></strong></td>
                <td><small><?= format_date($h['disbursement_date']) ?></small></td>
                <td><small><?= e($h['campaign_title']) ?></small></td>
                <td><span class="badge badge-warning"><?= ucfirst(e($h['assistance_type'])) ?></span></td>
                <td><strong class="text-primary"><?= format_rupiah($h['amount']) ?></strong></td>
                <td><small><?= e($h['recorded_by_name']) ?></small></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

</div>
