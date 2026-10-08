<div class="card" style="max-width:850px;margin:0 auto;">
  <div class="card-header" style="background:#f8fafc;">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-file-invoice" style="color:var(--primary);margin-right:8px;"></i> Berita Acara Penyaluran Bantuan</h3>
      <div style="font-size:0.85rem;color:var(--dark-muted);">Nomor Registrasi: <strong><?= e($disbursement['disbursement_code']) ?></strong></div>
    </div>
    <button onclick="window.print()" class="btn btn-sm btn-outline">
      <i class="fa-solid fa-print"></i> Cetak Dokumen
    </button>
  </div>
  <div class="card-body" style="padding:30px;">
    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:25px;">
      <div>
        <div style="font-size:0.85rem;color:var(--dark-muted);">Program Pengalokasian Dana:</div>
        <div style="font-size:1.1rem;font-weight:700;color:var(--primary);"><?= e($disbursement['campaign_title']) ?></div>
      </div>
      <div>
        <div style="font-size:0.85rem;color:var(--dark-muted);">Total Dana Terealisasi:</div>
        <div style="font-size:1.4rem;font-weight:800;color:#dc2626;"><?= format_rupiah($disbursement['amount']) ?></div>
      </div>
    </div>

    <table style="width:100%;font-size:0.92rem;line-height:2.2;margin-bottom:25px;border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:15px 0;">
      <tr>
        <td style="width:220px;color:var(--dark-muted);">Uraian Penyaluran:</td>
        <td><strong><?= e($disbursement['title']) ?></strong></td>
      </tr>
      <tr>
        <td style="color:var(--dark-muted);">Jenis Bantuan Sosial:</td>
        <td><span class="badge badge-warning"><?= ucfirst(e($disbursement['assistance_type'])) ?></span></td>
      </tr>
      <tr>
        <td style="color:var(--dark-muted);">Penerima Manfaat / Mustahik:</td>
        <td><?= e($disbursement['beneficiary_name'] ?? 'Penerima Umum / Komunal') ?></td>
      </tr>
      <tr>
        <td style="color:var(--dark-muted);">Jumlah Jiwa Penerima:</td>
        <td><?= (int)$disbursement['recipient_count'] ?> Orang</td>
      </tr>
      <tr>
        <td style="color:var(--dark-muted);">Tanggal Eksekusi Lapangan:</td>
        <td><?= format_date($disbursement['disbursement_date']) ?></td>
      </tr>
      <tr>
        <td style="color:var(--dark-muted);">Petugas Penyalur:</td>
        <td><?= e($disbursement['recorded_by_name']) ?></td>
      </tr>
    </table>

    <?php if (!empty($disbursement['notes'])): ?>
      <div style="margin-bottom:25px;">
        <h5 style="margin-bottom:6px;">Keterangan & Catatan Pengeluaran:</h5>
        <div style="background:#f8fafc;padding:15px;border-radius:6px;border:1px solid var(--border);font-size:0.9rem;line-height:1.6;">
          <?= nl2br(e($disbursement['notes'])) ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($disbursement['documentation_image'])): ?>
      <div>
        <h5 style="margin-bottom:10px;">Dokumentasi Penyerahan Bantuan:</h5>
        <img src="<?= asset('uploads/' . $disbursement['documentation_image']) ?>" alt="Dokumentasi Penyaluran" style="max-height:350px;border-radius:8px;border:1px solid var(--border);">
      </div>
    <?php endif; ?>

  </div>
</div>
