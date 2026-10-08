<div style="border-bottom:2px solid #0f172a;padding-bottom:15px;margin-bottom:25px;display:flex;justify-content:space-between;align-items:center;">
  <div style="display:flex;align-items:center;gap:15px;">
    <div style="width:50px;height:50px;background:#059669;color:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;">
      <i class="fa-solid fa-hand-holding-heart"></i>
    </div>
    <div>
      <h2 style="font-size:1.4rem;font-weight:800;color:#0f172a;margin-bottom:2px;">YAYASAN PEDULI KASIH SESAMA</h2>
      <div style="font-size:0.8rem;color:#475569;">SK Kemenkumham: AHU-0012485.AH.01.04.Tahun 2018 | Kemensos: 542/HUK-PS/2020</div>
      <div style="font-size:0.8rem;color:#475569;">Jl. Kasih Sejahtera No. 45, Kebayoran Baru, Jakarta Selatan | Telp: (021) 7890-1234</div>
    </div>
  </div>
  <div style="text-align:right;">
    <div style="font-size:1.2rem;font-weight:800;color:#059669;">KWITANSI DONASI</div>
    <div style="font-size:0.85rem;color:#64748b;">No: <strong><?= e($donation['donation_code']) ?></strong></div>
  </div>
</div>

<table style="width:100%;border-collapse:collapse;margin-bottom:30px;font-size:0.95rem;">
  <tr>
    <td style="padding:10px 0;width:200px;color:#64748b;">Telah Diterima Dari</td>
    <td style="padding:10px 0;font-weight:700;">: <?= $donation['is_anonymous'] ? 'Hamba Allah (Anonim)' : e($donation['donor_name']) ?></td>
  </tr>
  <tr>
    <td style="padding:10px 0;color:#64748b;">Alamat Email</td>
    <td style="padding:10px 0;">: <?= e($donation['donor_email']) ?></td>
  </tr>
  <tr>
    <td style="padding:10px 0;color:#64748b;">Uang Sejumlah</td>
    <td style="padding:10px 0;font-weight:800;color:#059669;font-size:1.15rem;">: <?= format_rupiah($donation['amount']) ?></td>
  </tr>
  <tr>
    <td style="padding:10px 0;color:#64748b;">Untuk Penyaluran Program</td>
    <td style="padding:10px 0;">: <?= e($donation['campaign_title']) ?></td>
  </tr>
  <tr>
    <td style="padding:10px 0;color:#64748b;">Metode & Status</td>
    <td style="padding:10px 0;">: <?= strtoupper(e($donation['payment_method'])) ?> &nbsp;(Status: <strong style="color:#059669;"><?= strtoupper(e($donation['payment_status'])) ?></strong>)</td>
  </tr>
  <tr>
    <td style="padding:10px 0;color:#64748b;">Tanggal Verifikasi</td>
    <td style="padding:10px 0;">: <?= format_date($donation['verified_at'] ?? $donation['created_at'], true) ?></td>
  </tr>
</table>

<div style="display:flex;justify-content:space-between;align-items:flex-end;margin-top:40px;border-top:1px dashed #cbd5e1;padding-top:25px;">
  <div style="font-size:0.8rem;color:#64748b;max-width:350px;">
    <em>"Jazakumullahu khairan katsiran. Semoga setiap rupiah yang Anda sedekahkan menjadi pemberat timbangan amal kebaikan dan pembuka pintu keberkahan."</em>
  </div>

  <div style="text-align:center;">
    <div style="font-size:0.85rem;color:#64748b;margin-bottom:60px;">Jakarta, <?= format_date($donation['verified_at'] ?? $donation['created_at']) ?><br>Pengurus & Bendahara Yayasan,</div>
    <div style="font-weight:700;text-decoration:underline;">Siti Rahmawati, S.E.</div>
    <div style="font-size:0.8rem;color:#64748b;">Divisi Keuangan & Verifikasi Donasi</div>
  </div>
</div>
