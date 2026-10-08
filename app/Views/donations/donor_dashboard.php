<div class="stats-grid" style="margin-bottom:30px;">
  <div class="stat-box">
    <div class="stat-icon green"><i class="fa-solid fa-heart"></i></div>
    <div>
      <div class="stat-value"><?= format_rupiah($totalContributed) ?></div>
      <div class="stat-label">Total Kebaikan Tersalurkan</div>
    </div>
  </div>

  <div class="stat-box">
    <div class="stat-icon blue"><i class="fa-solid fa-receipt"></i></div>
    <div>
      <div class="stat-value"><?= count($donations) ?> Kali</div>
      <div class="stat-label">Riwayat Transaksi Donasi</div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--primary);margin-right:8px;"></i> Riwayat Donasi Saya</h3>
    <a href="<?= url('campaigns') ?>" class="btn btn-cta btn-sm">
      <i class="fa-solid fa-heart"></i> Donasi Lagi
    </a>
  </div>
  <div class="card-body table-responsive" style="padding:0;">
    <?php if (empty($donations)): ?>
      <div style="text-align:center;padding:40px;color:var(--dark-muted);">
        <i class="fa-regular fa-folder-open" style="font-size:2.5rem;margin-bottom:10px;"></i>
        <div>Anda belum memiliki riwayat donasi.</div>
        <a href="<?= url('campaigns') ?>" class="btn btn-primary" style="margin-top:15px;">Mulai Salurkan Donasi</a>
      </div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Kode Donasi</th>
            <th>Tanggal</th>
            <th>Program Campaign</th>
            <th>Nominal</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Dokumen Resmi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($donations as $d): ?>
            <tr>
              <td><strong><?= e($d['donation_code']) ?></strong></td>
              <td><small><?= format_date($d['created_at'], true) ?></small></td>
              <td><a href="<?= url('campaign/detail/' . $d['campaign_slug']) ?>" target="_blank"><?= e($d['campaign_title']) ?></a></td>
              <td><strong class="text-primary"><?= format_rupiah($d['amount']) ?></strong></td>
              <td><span class="badge badge-primary"><?= strtoupper(e($d['payment_method'])) ?></span></td>
              <td>
                <span class="badge badge-<?= $d['payment_status'] === 'verified' ? 'verified' : ($d['payment_status'] === 'rejected' ? 'rejected' : 'pending') ?>">
                  <?= ucfirst(e($d['payment_status'])) ?>
                </span>
              </td>
              <td>
                <div style="display:flex;gap:6px;">
                  <?php if ($d['payment_status'] === 'verified'): ?>
                    <a href="<?= url('donation/receipt/' . $d['donation_code']) ?>" target="_blank" class="btn btn-sm btn-outline" style="padding:3px 8px;font-size:0.75rem;">
                      <i class="fa-solid fa-print"></i> Kwitansi
                    </a>
                    <a href="<?= url('donation/certificate/' . $d['donation_code']) ?>" target="_blank" class="btn btn-sm btn-cta" style="padding:3px 8px;font-size:0.75rem;">
                      <i class="fa-solid fa-award"></i> Sertifikat
                    </a>
                  <?php else: ?>
                    <a href="<?= url('donation/payment/' . $d['donation_code']) ?>" class="btn btn-sm btn-primary" style="padding:3px 8px;font-size:0.75rem;">
                      <i class="fa-solid fa-upload"></i> Bayar/Bukti
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
