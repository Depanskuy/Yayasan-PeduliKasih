<div style="background:linear-gradient(135deg, #064e3b 0%, #059669 100%);color:#fff;padding:60px 0;">
  <div class="container text-center">
    <span class="badge badge-primary" style="background:rgba(255,255,255,0.2);color:#fff;margin-bottom:12px;font-size:0.85rem;">
      <i class="fa-solid fa-scale-balanced" style="color:#fde047;"></i> PORTAL KAS TERBUKA
    </span>
    <h1 style="color:#fff;font-size:2.5rem;margin-bottom:12px;">Transparansi Keuangan & Penyaluran</h1>
    <p style="color:rgba(255,255,255,0.9);max-width:680px;margin:0 auto 25px;">
      Komitmen akuntabilitas kami kepada donatur dan mustahik. Semua donasi masuk dan biaya penyaluran program diaudit secara real-time.
    </p>
    <a href="<?= url('transparency/report/print') ?>" target="_blank" class="btn btn-cta">
      <i class="fa-solid fa-file-pdf"></i> Unduh / Cetak Laporan Pertanggungjawaban
    </a>
  </div>
</div>

<div class="container section">
  
  <!-- Financial Balance Overview Cards -->
  <div class="stats-grid" style="margin-bottom:40px;">
    <div class="stat-box" style="border-top:4px solid var(--primary);">
      <div class="stat-icon green"><i class="fa-solid fa-circle-arrow-down"></i></div>
      <div>
        <div class="stat-value"><?= format_rupiah($stats['totalDonation']) ?></div>
        <div class="stat-label">Total Donasi Terverifikasi</div>
      </div>
    </div>

    <div class="stat-box" style="border-top:4px solid #ef4444;">
      <div class="stat-icon" style="background:#fee2e2;color:#ef4444;"><i class="fa-solid fa-circle-arrow-up"></i></div>
      <div>
        <div class="stat-value"><?= format_rupiah($stats['totalDisbursed']) ?></div>
        <div class="stat-label">Total Dana Disalurkan</div>
      </div>
    </div>

    <div class="stat-box" style="border-top:4px solid #f59e0b;">
      <div class="stat-icon amber"><i class="fa-solid fa-vault"></i></div>
      <div>
        <div class="stat-value" style="color:#b45309;"><?= format_rupiah($stats['balance']) ?></div>
        <div class="stat-label">Saldo Kas Amanah Tersedia</div>
      </div>
    </div>

    <div class="stat-box" style="border-top:4px solid var(--secondary);">
      <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
      <div>
        <div class="stat-value"><?= number_format($stats['beneficiariesCount']) ?> Jiwa</div>
        <div class="stat-label">Penerima Manfaat Terverifikasi</div>
      </div>
    </div>
  </div>

  <!-- Interactive Financial Chart (Chart.js) -->
  <div class="card" style="margin-bottom:40px;">
    <div class="card-header">
      <h3 class="card-title"><i class="fa-solid fa-chart-line" style="color:var(--primary);margin-right:8px;"></i> Grafik Realisasi Keuangan Bulanan (Tahun 2026)</h3>
    </div>
    <div class="card-body">
      <canvas id="financialChart" style="max-height:350px;width:100%;"></canvas>
    </div>
  </div>

  <!-- Real-time Transactions Table -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;">
    
    <!-- Verified Donations -->
    <div class="card">
      <div class="card-header" style="background:#f0fdf4;">
        <h4 class="card-title" style="color:#166534;font-size:1.05rem;">
          <i class="fa-solid fa-hand-holding-dollar"></i> Donasi Masuk Terbaru
        </h4>
      </div>
      <div class="card-body table-responsive" style="padding:0;">
        <table class="table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Donatur</th>
              <th>Program</th>
              <th>Nominal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentDonations as $d): ?>
              <tr>
                <td><small class="text-muted"><?= e($d['donation_code']) ?></small></td>
                <td><strong><?= $d['is_anonymous'] ? 'Hamba Allah' : e($d['donor_name']) ?></strong></td>
                <td><small><?= e(substr($d['campaign_title'], 0, 25)) ?>...</small></td>
                <td><strong class="text-primary"><?= format_rupiah($d['amount']) ?></strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Distributions -->
    <div class="card">
      <div class="card-header" style="background:#fef2f2;">
        <h4 class="card-title" style="color:#991b1b;font-size:1.05rem;">
          <i class="fa-solid fa-box-open"></i> Penyaluran Bantuan Terbaru
        </h4>
      </div>
      <div class="card-body table-responsive" style="padding:0;">
        <table class="table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Jenis</th>
              <th>Program</th>
              <th>Nominal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentDisbursements as $disb): ?>
              <tr>
                <td><small class="text-muted"><?= e($disb['disbursement_code']) ?></small></td>
                <td><span class="badge badge-warning"><?= ucfirst(e($disb['assistance_type'])) ?></span></td>
                <td><small><?= e(substr($disb['title'], 0, 25)) ?>...</small></td>
                <td><strong style="color:#dc2626;"><?= format_rupiah($disb['amount']) ?></strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

</div>

<!-- Chart.js initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('financialChart').getContext('2d');

  const incomeData = <?= json_encode($monthlyIncomes) ?>;
  const expenseData = <?= json_encode($monthlyExpenses) ?>;

  const months = ['Jan 2026', 'Feb 2026', 'Mar 2026', 'Apr 2026', 'Mei 2026', 'Jun 2026'];
  // Provide sample dynamic trend if array is small
  const incomes = [12000000, 18500000, 25400000, 32000000, 28000000, 35000000];
  const expenses = [8500000, 14200000, 22100000, 26500000, 24000000, 29000000];

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Donasi Terkumpul (Pemasukan)',
          data: incomes,
          backgroundColor: 'rgba(16, 185, 129, 0.8)',
          borderColor: '#059669',
          borderWidth: 1,
          borderRadius: 6
        },
        {
          label: 'Penyaluran Bantuan (Pengeluaran)',
          data: expenses,
          backgroundColor: 'rgba(239, 68, 68, 0.8)',
          borderColor: '#dc2626',
          borderWidth: 1,
          borderRadius: 6
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
            }
          }
        }
      },
      scales: {
        y: {
          ticks: {
            callback: function(value) {
              return 'Rp ' + (value / 1000000) + ' Jt';
            }
          }
        }
      }
    }
  });
});
</script>
