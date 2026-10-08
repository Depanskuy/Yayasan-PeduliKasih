<div style="border-bottom:2px solid #0f172a;padding-bottom:15px;margin-bottom:25px;display:flex;justify-content:space-between;align-items:center;">
  <div>
    <h2 style="font-size:1.35rem;font-weight:800;color:#0f172a;margin-bottom:2px;">YAYASAN PEDULI KASIH SESAMA</h2>
    <div style="font-size:0.8rem;color:#475569;">SK Kemenkumham RI: AHU-0012485.AH.01.04.Tahun 2018 | Kemensos: 542/HUK-PS/2020</div>
    <div style="font-size:0.8rem;color:#475569;">Jl. Kasih Sejahtera No. 45, Kebayoran Baru, Jakarta Selatan | Telp: (021) 7890-1234</div>
  </div>
  <div style="text-align:right;">
    <div style="font-size:1.1rem;font-weight:800;color:#059669;">LAPORAN PERTANGGUNGJAWABAN KAS</div>
    <div style="font-size:0.8rem;color:#64748b;">Periode: Per <?= date('d F Y') ?></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-bottom:25px;">
  <div style="border:1px solid #cbd5e1;padding:12px;border-radius:6px;background:#f0fdf4;">
    <div style="font-size:0.75rem;color:#166534;">TOTAL DONASI MASUK:</div>
    <div style="font-size:1.15rem;font-weight:800;color:#166534;"><?= format_rupiah($stats['totalDonation']) ?></div>
  </div>
  <div style="border:1px solid #cbd5e1;padding:12px;border-radius:6px;background:#fef2f2;">
    <div style="font-size:0.75rem;color:#991b1b;">TOTAL PENYALURAN KELUAR:</div>
    <div style="font-size:1.15rem;font-weight:800;color:#991b1b;"><?= format_rupiah($stats['totalDisbursed']) ?></div>
  </div>
  <div style="border:1px solid #cbd5e1;padding:12px;border-radius:6px;background:#fffbeb;">
    <div style="font-size:0.75rem;color:#854d0e;">SALDO KAS AMANAH:</div>
    <div style="font-size:1.15rem;font-weight:800;color:#854d0e;"><?= format_rupiah($stats['balance']) ?></div>
  </div>
</div>

<h4 style="margin:20px 0 10px;font-size:1rem;color:#0f172a;border-bottom:1px solid #e2e8f0;padding-bottom:5px;">
  1. RINCIAN PENYALURAN BANTUAN SOSIAL (PENGELUARAN KAS)
</h4>
<table style="width:100%;border-collapse:collapse;font-size:0.85rem;margin-bottom:25px;">
  <thead>
    <tr style="background:#f8fafc;border-bottom:1px solid #0f172a;">
      <th style="padding:6px;text-align:left;">No. Ref</th>
      <th style="padding:6px;text-align:left;">Tanggal</th>
      <th style="padding:6px;text-align:left;">Uraian Penyaluran</th>
      <th style="padding:6px;text-align:left;">Penerima / Sasaran</th>
      <th style="padding:6px;text-align:right;">Nominal (Rp)</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($disbursements as $disb): ?>
      <tr style="border-bottom:1px solid #e2e8f0;">
        <td style="padding:6px;"><?= e($disb['disbursement_code']) ?></td>
        <td style="padding:6px;"><?= format_date($disb['disbursement_date']) ?></td>
        <td style="padding:6px;"><?= e($disb['title']) ?></td>
        <td style="padding:6px;"><?= e($disb['beneficiary_name'] ?? 'Penerima Umum') ?></td>
        <td style="padding:6px;text-align:right;font-weight:700;"><?= format_rupiah($disb['amount'], false) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h4 style="margin:20px 0 10px;font-size:1rem;color:#0f172a;border-bottom:1px solid #e2e8f0;padding-bottom:5px;">
  2. RINCIAN DONASI TERVERIFIKASI TERAKHIR (PEMASUKAN KAS)
</h4>
<table style="width:100%;border-collapse:collapse;font-size:0.85rem;margin-bottom:30px;">
  <thead>
    <tr style="background:#f8fafc;border-bottom:1px solid #0f172a;">
      <th style="padding:6px;text-align:left;">No. Transaksi</th>
      <th style="padding:6px;text-align:left;">Donatur</th>
      <th style="padding:6px;text-align:left;">Program Campaign</th>
      <th style="padding:6px;text-align:left;">Metode</th>
      <th style="padding:6px;text-align:right;">Nominal (Rp)</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($donations as $d): ?>
      <tr style="border-bottom:1px solid #e2e8f0;">
        <td style="padding:6px;"><?= e($d['donation_code']) ?></td>
        <td style="padding:6px;"><?= $d['is_anonymous'] ? 'Hamba Allah' : e($d['donor_name']) ?></td>
        <td style="padding:6px;"><?= e($d['campaign_title']) ?></td>
        <td style="padding:6px;"><?= strtoupper(e($d['payment_method'])) ?></td>
        <td style="padding:6px;text-align:right;font-weight:700;"><?= format_rupiah($d['amount'], false) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div style="display:flex;justify-content:space-between;margin-top:40px;align-items:flex-end;">
  <div style="font-size:0.8rem;color:#64748b;">
    Dokumen ini dicetak otomatis dari sistem audit keuangan terbuka Yayasan Peduli Kasih Sesama.
  </div>
  <div style="text-align:center;">
    <div style="font-size:0.85rem;color:#64748b;margin-bottom:50px;">Jakarta, <?= date('d F Y') ?><br>Mengetahui & Mengesahkan,</div>
    <div style="font-weight:700;text-decoration:underline;">Drs. H. Mulyono Santoso</div>
    <div style="font-size:0.8rem;color:#64748b;">Ketua Pembina Yayasan</div>
  </div>
</div>
