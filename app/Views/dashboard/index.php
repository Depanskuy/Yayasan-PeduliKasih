<!-- Stat Cards -->
<div class="stats-grid" style="margin-bottom:30px;">
  <div class="stat-box" style="border-left:4px solid var(--primary);">
    <div class="stat-icon green"><i class="fa-solid fa-hand-holding-dollar"></i></div>
    <div>
      <div class="stat-value"><?= format_rupiah($stats['totalDonation']) ?></div>
      <div class="stat-label">Donasi Masuk (Verified)</div>
    </div>
  </div>

  <div class="stat-box" style="border-left:4px solid #ef4444;">
    <div class="stat-icon" style="background:#fee2e2;color:#ef4444;"><i class="fa-solid fa-box-open"></i></div>
    <div>
      <div class="stat-value"><?= format_rupiah($stats['totalDisbursed']) ?></div>
      <div class="stat-label">Penyaluran Bantuan</div>
    </div>
  </div>

  <div class="stat-box" style="border-left:4px solid #f59e0b;">
    <div class="stat-icon amber"><i class="fa-solid fa-vault"></i></div>
    <div>
      <div class="stat-value" style="color:#b45309;"><?= format_rupiah($stats['balance']) ?></div>
      <div class="stat-label">Saldo Kas Yayasan</div>
    </div>
  </div>

  <div class="stat-box" style="border-left:4px solid var(--secondary);">
    <div class="stat-icon blue"><i class="fa-solid fa-clock-rotate-left"></i></div>
    <div>
      <div class="stat-value" style="color:#0284c7;"><?= $stats['pendingCount'] ?> Donasi</div>
      <div class="stat-label">Menunggu Verifikasi</div>
    </div>
  </div>
</div>

<!-- Financial Trend Chart & Quick Summary -->
<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:24px;margin-bottom:30px;">
  
  <div class="card">
    <div class="card-header">
      <h4 class="card-title"><i class="fa-solid fa-chart-line" style="color:var(--primary);margin-right:8px;"></i> Tren Keuangan 2026</h4>
    </div>
    <div class="card-body">
      <canvas id="adminFinChart" style="max-height:280px;"></canvas>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h4 class="card-title"><i class="fa-solid fa-bolt" style="color:var(--accent);margin-right:8px;"></i> Aksi Cepat Staf</h4>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
      <a href="<?= url('admin/donations/offline') ?>" class="btn btn-outline" style="justify-content:flex-start;">
        <i class="fa-solid fa-cash-register" style="color:var(--primary);"></i> Input Donasi Tunai / Offline
      </a>
      <a href="<?= url('admin/distributions/create') ?>" class="btn btn-outline" style="justify-content:flex-start;">
        <i class="fa-solid fa-hand-holding-medical" style="color:#ef4444;"></i> Catat Penyaluran Bantuan Baru
      </a>
      <a href="<?= url('admin/campaigns/create') ?>" class="btn btn-outline" style="justify-content:flex-start;">
        <i class="fa-solid fa-plus-circle" style="color:var(--secondary);"></i> Buat Program Campaign Baru
      </a>
      <a href="<?= url('admin/beneficiaries/create') ?>" class="btn btn-outline" style="justify-content:flex-start;">
        <i class="fa-solid fa-user-plus" style="color:#8b5cf6;"></i> Daftarkan Mustahik Baru
      </a>
      <a href="<?= url('transparency/report/print') ?>" target="_blank" class="btn btn-outline" style="justify-content:flex-start;">
        <i class="fa-solid fa-print" style="color:#d97706;"></i> Cetak Laporan Keuangan Transparan
      </a>
    </div>
  </div>

</div>

<!-- Pending Donations Awaiting Verification Table -->
<div class="card" style="margin-bottom:30px;">
  <div class="card-header" style="background:#fffbeb;">
    <div style="display:flex;align-items:center;gap:10px;">
      <i class="fa-solid fa-triangle-exclamation" style="color:#d97706;font-size:1.2rem;"></i>
      <h4 class="card-title" style="color:#92400e;margin:0;">Donasi Butuh Verifikasi Segera (Pending)</h4>
    </div>
    <a href="<?= url('admin/donations?status=pending') ?>" class="btn btn-sm btn-outline">Lihat Semua</a>
  </div>
  <div class="card-body table-responsive" style="padding:0;">
    <?php if (empty($pendingDonations)): ?>
      <div style="text-align:center;padding:30px;color:var(--dark-muted);">
        <i class="fa-solid fa-circle-check" style="color:var(--success);font-size:2rem;margin-bottom:8px;"></i>
        <div>Semua donasi telah diverifikasi. Tidak ada antrean pending.</div>
      </div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>No. Donasi</th>
            <th>Donatur</th>
            <th>Program</th>
            <th>Metode</th>
            <th>Nominal</th>
            <th>Bukti</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pendingDonations as $d): ?>
            <tr>
              <td><strong><?= e($d['donation_code']) ?></strong></td>
              <td>
                <?= $d['is_anonymous'] ? '<em>Hamba Allah</em>' : e($d['donor_name']) ?>
                <div style="font-size:0.75rem;color:var(--dark-muted);"><?= e($d['donor_email']) ?></div>
              </td>
              <td><?= e(substr($d['campaign_title'], 0, 30)) ?>...</td>
              <td><span class="badge badge-warning"><?= strtoupper(e($d['payment_method'])) ?></span></td>
              <td><strong class="text-primary"><?= format_rupiah($d['amount']) ?></strong></td>
              <td>
                <?php if (!empty($d['payment_proof'])): ?>
                  <a href="<?= asset('uploads/' . $d['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-outline" style="padding:4px 8px;font-size:0.75rem;">
                    <i class="fa-solid fa-image"></i> Lihat
                  </a>
                <?php else: ?>
                  <span style="font-size:0.75rem;color:#94a3b8;">Belum Upload</span>
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;gap:6px;">
                  <form action="<?= url('admin/donations/verify/' . $d['id']) ?>" method="POST" onsubmit="return confirm('Verifikasi donasi ini?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-primary" style="padding:4px 10px;">
                      <i class="fa-solid fa-check"></i> Terima
                    </button>
                  </form>
                  <button type="button" class="btn btn-sm" style="background:#fee2e2;color:#ef4444;padding:4px 10px;" onclick="rejectModal(<?= $d['id'] ?>, '<?= e($d['donation_code']) ?>')">
                    <i class="fa-solid fa-xmark"></i> Tolak
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
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

document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('adminFinChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
      datasets: [
        {
          label: 'Donasi Masuk (Rp)',
          data: [12000000, 18500000, 25400000, 32000000, 28000000, 35000000],
          borderColor: '#059669',
          backgroundColor: 'rgba(5, 150, 105, 0.1)',
          fill: true,
          tension: 0.3
        },
        {
          label: 'Penyaluran (Rp)',
          data: [8500000, 14200000, 22100000, 26500000, 24000000, 29000000],
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239, 68, 68, 0.1)',
          fill: true,
          tension: 0.3
        }
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top' } }
    }
  });
});
</script>
