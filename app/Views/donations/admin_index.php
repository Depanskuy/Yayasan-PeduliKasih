<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-receipt" style="color:var(--primary);margin-right:8px;"></i> Manajemen & Verifikasi Donasi</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Verifikasi bukti pembayaran transfer donatur online dan input transaksi donasi offline.</p>
    </div>
    <a href="<?= url('admin/donations/offline') ?>" class="btn btn-cta btn-sm">
      <i class="fa-solid fa-cash-register"></i> Input Donasi Offline / Kasir
    </a>
  </div>

  <div style="padding:15px 24px;background:#f8fafc;border-bottom:1px solid var(--border);display:flex;gap:10px;">
    <a href="<?= url('admin/donations') ?>" class="btn btn-sm <?= empty($currentStatus) ? 'btn-primary' : 'btn-outline' ?>">Semua</a>
    <a href="<?= url('admin/donations?status=pending') ?>" class="btn btn-sm <?= ($currentStatus === 'pending') ? 'btn-primary' : 'btn-outline' ?>">Pending (Menunggu)</a>
    <a href="<?= url('admin/donations?status=verified') ?>" class="btn btn-sm <?= ($currentStatus === 'verified') ? 'btn-primary' : 'btn-outline' ?>">Verified (Diterima)</a>
    <a href="<?= url('admin/donations?status=rejected') ?>" class="btn btn-sm <?= ($currentStatus === 'rejected') ? 'btn-primary' : 'btn-outline' ?>">Rejected (Ditolak)</a>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>No. Donasi</th>
          <th>Tanggal</th>
          <th>Donatur</th>
          <th>Program Campaign</th>
          <th>Metode</th>
          <th>Nominal</th>
          <th>Status</th>
          <th>Bukti Transfer</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($donations as $d): ?>
          <tr>
            <td>
              <strong><?= e($d['donation_code']) ?></strong>
              <?php if (!empty($d['doa_message'])): ?>
                <div style="font-size:0.75rem;color:var(--dark-muted);font-style:italic;" title="<?= e($d['doa_message']) ?>">"<?= e(substr($d['doa_message'], 0, 30)) ?>..."</div>
              <?php endif; ?>
            </td>
            <td><small><?= format_date($d['created_at'], true) ?></small></td>
            <td>
              <strong><?= $d['is_anonymous'] ? '<em>Hamba Allah (Anonim)</em>' : e($d['donor_name']) ?></strong>
              <div style="font-size:0.75rem;color:var(--dark-muted);"><?= e($d['donor_email']) ?></div>
            </td>
            <td><small><?= e(substr($d['campaign_title'], 0, 30)) ?>...</small></td>
            <td><span class="badge badge-primary"><?= strtoupper(e($d['payment_method'])) ?></span></td>
            <td><strong class="text-primary"><?= format_rupiah($d['amount']) ?></strong></td>
            <td>
              <span class="badge badge-<?= $d['payment_status'] === 'verified' ? 'verified' : ($d['payment_status'] === 'rejected' ? 'rejected' : 'pending') ?>">
                <?= ucfirst(e($d['payment_status'])) ?>
              </span>
            </td>
            <td>
              <?php if (!empty($d['payment_proof'])): ?>
                <a href="<?= asset('uploads/' . $d['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-outline" style="padding:3px 8px;font-size:0.75rem;">
                  <i class="fa-solid fa-image"></i> Lihat
                </a>
              <?php else: ?>
                <small class="text-muted">Tidak Ada</small>
              <?php endif; ?>
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <?php if ($d['payment_status'] === 'pending'): ?>
                  <form action="<?= url('admin/donations/verify/' . $d['id']) ?>" method="POST" onsubmit="return confirm('Verifikasi donasi ini?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-primary" style="padding:4px 8px;">
                      <i class="fa-solid fa-check"></i>
                    </button>
                  </form>
                  <button type="button" class="btn btn-sm" style="background:#fee2e2;color:#ef4444;padding:4px 8px;" onclick="rejectModal(<?= $d['id'] ?>, '<?= e($d['donation_code']) ?>')">
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                <?php else: ?>
                  <a href="<?= url('donation/receipt/' . $d['donation_code']) ?>" target="_blank" class="btn btn-sm btn-outline" style="padding:4px 8px;" title="Cetak Kwitansi">
                    <i class="fa-solid fa-print"></i>
                  </a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tolak Donasi -->
<div id="rejectModalWrapper" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius);padding:30px;max-width:440px;width:90%;">
    <h4 style="margin-bottom:12px;color:var(--danger);">Tolak Donasi: <span id="rejectModalCode"></span></h4>
    <form id="rejectModalForm" method="POST">
      <?= csrf_field() ?>
      <div class="form-group">
        <label class="form-label">Alasan Penolakan:</label>
        <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Contoh: Bukti transfer palsu atau mutasi tidak ditemukan."></textarea>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('rejectModalWrapper').style.display='none'">Batal</button>
        <button type="submit" class="btn btn-primary" style="background:var(--danger);">Tolak Donasi</button>
      </div>
    </form>
  </div>
</div>

<script>
function rejectModal(id, code) {
  document.getElementById('rejectModalCode').innerText = '#' + code;
  document.getElementById('rejectModalForm').action = '<?= url('admin/donations/reject/') ?>/' + id;
  document.getElementById('rejectModalWrapper').style.display = 'flex';
}
</script>
