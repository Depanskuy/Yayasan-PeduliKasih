<div class="container section" style="max-width:760px;">
  <div style="text-align:center;margin-bottom:25px;">
    <?php if ($donation['payment_status'] === 'verified'): ?>
      <div style="width:64px;height:64px;background:var(--success-light);color:var(--success);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;font-size:2rem;">
        <i class="fa-solid fa-check"></i>
      </div>
      <h1 style="font-size:2rem;color:var(--success);margin-bottom:6px;">Donasi Terverifikasi!</h1>
      <p class="text-muted">Terima kasih atas kebaikan Anda. Dana telah dialokasikan ke program.</p>
    <?php else: ?>
      <span class="badge badge-warning" style="margin-bottom:10px;font-size:0.85rem;padding:6px 14px;">
        <i class="fa-solid fa-clock"></i> MENUNGGU PEMBAYARAN / VERIFIKASI
      </span>
      <h1 style="font-size:1.85rem;margin-bottom:6px;">Instruksi Pembayaran Donasi</h1>
      <p class="text-muted">Kode Donasi: <strong><?= e($donation['donation_code']) ?></strong></p>
    <?php endif; ?>
  </div>

  <div class="card" style="box-shadow:var(--shadow-lg);border-radius:var(--radius-lg);margin-bottom:25px;">
    <div class="card-body" style="padding:30px;">
      
      <div style="background:#f8fafc;border-radius:var(--radius);padding:20px;border:1px solid var(--border);margin-bottom:25px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:0.9rem;">
          <span class="text-muted">Program Kebaikan:</span>
          <strong><?= e($donation['campaign_title']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:0.9rem;">
          <span class="text-muted">Nama Donatur:</span>
          <strong><?= $donation['is_anonymous'] ? 'Hamba Allah (Anonim)' : e($donation['donor_name']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:0.9rem;">
          <span class="text-muted">Metode Pembayaran:</span>
          <span class="badge badge-primary"><?= strtoupper(e($donation['payment_method'])) ?></span>
        </div>
        <div style="border-top:1px dashed var(--border);padding-top:12px;margin-top:12px;display:flex;justify-content:space-between;align-items:center;">
          <span style="font-weight:700;">Total Donasi:</span>
          <div style="font-size:1.4rem;font-weight:800;color:var(--primary);">
            <?= format_rupiah($donation['amount']) ?>
          </div>
        </div>
      </div>

      <!-- Detail Rekening / QRIS -->
      <?php if ($donation['payment_status'] !== 'verified'): ?>
        <div style="margin-bottom:30px;padding:24px;border:1px solid #bbf7d0;background:#f0fdf4;border-radius:var(--radius);">
          <h4 style="margin-bottom:15px;color:var(--primary-dark);"><i class="fa-solid fa-wallet"></i> Silakan Transfer Ke Rekening Resmi Yayasan:</h4>

          <?php if ($donation['payment_method'] === 'bca'): ?>
            <div style="font-size:1.1rem;margin-bottom:6px;"><strong>Bank Central Asia (BCA)</strong></div>
            <div style="font-size:1.5rem;font-weight:800;color:var(--dark);letter-spacing:1px;">8830-192-800</div>
            <div style="color:var(--dark-muted);font-size:0.85rem;">a.n Yayasan Peduli Kasih Sesama</div>

          <?php elseif ($donation['payment_method'] === 'mandiri'): ?>
            <div style="font-size:1.1rem;margin-bottom:6px;"><strong>Bank Mandiri</strong></div>
            <div style="font-size:1.5rem;font-weight:800;color:var(--dark);letter-spacing:1px;">137-00-9876543-2</div>
            <div style="color:var(--dark-muted);font-size:0.85rem;">a.n Yayasan Peduli Kasih Sesama</div>

          <?php elseif ($donation['payment_method'] === 'bri'): ?>
            <div style="font-size:1.1rem;margin-bottom:6px;"><strong>Bank Rakyat Indonesia (BRI)</strong></div>
            <div style="font-size:1.5rem;font-weight:800;color:var(--dark);letter-spacing:1px;">0206-01-008912-301</div>
            <div style="color:var(--dark-muted);font-size:0.85rem;">a.n Peduli Kasih Sesama</div>

          <?php elseif ($donation['payment_method'] === 'qris'): ?>
            <div class="text-center">
              <div style="font-size:1.1rem;font-weight:700;margin-bottom:10px;">Scan QRIS Nasional</div>
              <div style="display:inline-block;padding:15px;background:#fff;border-radius:12px;box-shadow:var(--shadow-sm);border:1px solid var(--border);">
                <!-- QR Code SVG representation -->
                <svg width="200" height="200" viewBox="0 0 100 100">
                  <rect width="100" height="100" fill="#ffffff"/>
                  <rect x="10" y="10" width="25" height="25" fill="#000000"/>
                  <rect x="15" y="15" width="15" height="15" fill="#ffffff"/>
                  <rect x="18" y="18" width="9" height="9" fill="#000000"/>
                  <rect x="65" y="10" width="25" height="25" fill="#000000"/>
                  <rect x="70" y="15" width="15" height="15" fill="#ffffff"/>
                  <rect x="73" y="18" width="9" height="9" fill="#000000"/>
                  <rect x="10" y="65" width="25" height="25" fill="#000000"/>
                  <rect x="15" y="70" width="15" height="15" fill="#ffffff"/>
                  <rect x="18" y="73" width="9" height="9" fill="#000000"/>
                  <rect x="45" y="15" width="10" height="10" fill="#000000"/>
                  <rect x="40" y="35" width="20" height="20" fill="#059669"/>
                  <rect x="45" y="65" width="15" height="20" fill="#000000"/>
                  <rect x="70" y="45" width="20" height="15" fill="#000000"/>
                  <rect x="65" y="70" width="25" height="20" fill="#000000"/>
                </svg>
              </div>
              <div style="font-size:0.85rem;color:var(--dark-muted);margin-top:10px;">NMID: ID1020083921008</div>
              <div style="font-size:0.8rem;color:var(--dark-muted);">Bisa di-scan dari aplikasi BCA Mobile, GoPay, OVO, Dana, ShopeePay, dsb.</div>
            </div>

          <?php else: ?>
            <div style="font-size:1.1rem;margin-bottom:6px;"><strong>E-Wallet <?= strtoupper(e($donation['payment_method'])) ?></strong></div>
            <div style="font-size:1.5rem;font-weight:800;color:var(--dark);">0812-3456-7890</div>
            <div style="color:var(--dark-muted);font-size:0.85rem;">a.n Yayasan Peduli Kasih Sesama</div>
          <?php endif; ?>
        </div>

        <!-- FORM UPLOAD BUKTI TRANSFER -->
        <div style="border-top:1px solid var(--border);padding-top:24px;">
          <h4 style="margin-bottom:12px;"><i class="fa-solid fa-file-arrow-up"></i> Konfirmasi Bukti Pembayaran</h4>
          <?php if (!empty($donation['payment_proof'])): ?>
            <div class="alert alert-info">
              <i class="fa-solid fa-circle-check"></i> Bukti transfer telah Anda unggah. Menunggu proses verifikasi staf keuangan.
            </div>
            <div style="margin-bottom:15px;text-align:center;">
              <img src="<?= asset('uploads/' . $donation['payment_proof']) ?>" alt="Bukti Transfer" style="max-height:220px;border-radius:8px;margin:0 auto;border:1px solid var(--border);">
            </div>
          <?php endif; ?>

          <form action="<?= url('donation/payment/' . $donation['donation_code'] . '/upload') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
              <label class="form-label"><?= !empty($donation['payment_proof']) ? 'Ganti Berkas Bukti Transfer (JPG/PNG/WEBP):' : 'Unggah Foto Bukti Transfer (JPG/PNG/WEBP):' ?></label>
              <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fa-solid fa-upload"></i> Unggah Bukti Pembayaran
            </button>
          </form>
        </div>
      <?php else: ?>
        <!-- Tombol Kwitansi & Sertifikat jika verified -->
        <div style="display:flex;gap:15px;margin-top:20px;flex-wrap:wrap;">
          <a href="<?= url('donation/receipt/' . $donation['donation_code']) ?>" target="_blank" class="btn btn-primary" style="flex:1;">
            <i class="fa-solid fa-print"></i> Cetak Kwitansi Resmi
          </a>
          <a href="<?= url('donation/certificate/' . $donation['donation_code']) ?>" target="_blank" class="btn btn-cta" style="flex:1;">
            <i class="fa-solid fa-award"></i> Unduh E-Sertifikat
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <div class="text-center">
    <a href="<?= url('campaigns') ?>" class="text-muted"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Program</a>
  </div>
</div>
