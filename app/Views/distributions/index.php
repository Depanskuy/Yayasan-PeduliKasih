<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-box-open" style="color:#ef4444;margin-right:8px;"></i> Rekam Jejak Penyaluran Bantuan Sosial</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Total realisasi dana bantuan yang telah disalurkan: <strong style="color:#ef4444;"><?= format_rupiah($totalAmount) ?></strong></p>
    </div>
    <a href="<?= url('admin/distributions/create') ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-plus"></i> Catat Penyaluran Baru
    </a>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>Kode Penyaluran</th>
          <th>Tanggal</th>
          <th>Judul Penyaluran</th>
          <th>Program Campaign</th>
          <th>Penerima (Mustahik)</th>
          <th>Jenis</th>
          <th>Nominal / Biaya</th>
          <th>Petugas</th>
          <th>Dokumen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($disbursements as $d): ?>
          <tr>
            <td><strong><?= e($d['disbursement_code']) ?></strong></td>
            <td><small><?= format_date($d['disbursement_date']) ?></small></td>
            <td>
              <strong><a href="<?= url('admin/distributions/' . $d['id']) ?>"><?= e($d['title']) ?></a></strong>
              <div style="font-size:0.75rem;color:var(--dark-muted);"><?= (int)$d['recipient_count'] ?> penerima manfaat</div>
            </td>
            <td><small><?= e($d['campaign_title']) ?></small></td>
            <td><?= e($d['beneficiary_name'] ?? 'Penyaluran Umum/Komunal') ?></td>
            <td><span class="badge badge-warning"><?= ucfirst(e($d['assistance_type'])) ?></span></td>
            <td><strong style="color:#dc2626;"><?= format_rupiah($d['amount']) ?></strong></td>
            <td><small><?= e($d['recorded_by_name']) ?></small></td>
            <td>
              <a href="<?= url('admin/distributions/' . $d['id']) ?>" class="btn btn-sm btn-outline" style="padding:3px 8px;font-size:0.75rem;">
                <i class="fa-solid fa-file-invoice"></i> Berita Acara
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
