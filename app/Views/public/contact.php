<div style="background:linear-gradient(135deg, #064e3b 0%, #059669 100%);color:#fff;padding:60px 0;">
  <div class="container text-center">
    <h1 style="color:#fff;font-size:2.5rem;margin-bottom:10px;">Hubungi Kami</h1>
    <p style="color:rgba(255,255,255,0.9);max-width:600px;margin:0 auto;">Pintu sekretariat kami selalu terbuka untuk silaturahmi, konsultasi program, dan konfirmasi penyaluran bantuan.</p>
  </div>
</div>

<div class="container section">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;">
    <div>
      <h3 style="margin-bottom:20px;">Sekretariat Yayasan</h3>
      <p style="color:var(--dark-muted);margin-bottom:25px;line-height:1.7;">
        Silakan berkunjung langsung ke kantor operasional kami atau hubungi saluran resmi berikut untuk informasi program kerjasama, CSR perusahaan, atau pendaftaran relawan.
      </p>

      <div style="display:flex;flex-direction:column;gap:20px;">
        <div style="display:flex;gap:16px;">
          <div style="width:46px;height:46px;border-radius:10px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <div>
            <div style="font-weight:700;">Alamat Kantor</div>
            <div style="color:var(--dark-muted);font-size:0.92rem;"><?= e($settings['org_address'] ?? 'Jl. Kasih Sejahtera No. 45, Kebayoran Baru, Jakarta Selatan 12180') ?></div>
          </div>
        </div>

        <div style="display:flex;gap:16px;">
          <div style="width:46px;height:46px;border-radius:10px;background:#e0f2fe;color:var(--secondary);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
            <i class="fa-solid fa-phone"></i>
          </div>
          <div>
            <div style="font-weight:700;">Telepon / WhatsApp</div>
            <div style="color:var(--dark-muted);font-size:0.92rem;"><?= e($settings['org_phone'] ?? '(021) 7890-1234 / 0812-3456-7890') ?></div>
          </div>
        </div>

        <div style="display:flex;gap:16px;">
          <div style="width:46px;height:46px;border-radius:10px;background:var(--accent-light);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
            <i class="fa-solid fa-envelope"></i>
          </div>
          <div>
            <div style="font-weight:700;">Email Resmi</div>
            <div style="color:var(--dark-muted);font-size:0.92rem;"><?= e($settings['org_email'] ?? 'sekretariat@pedulikasih.org') ?></div>
          </div>
        </div>
      </div>
    </div>

    <div class="card" style="padding:35px;border-radius:var(--radius-lg);">
      <h3 style="margin-bottom:15px;">Kirim Pesan / Pertanyaan</h3>
      <form onsubmit="alert('Pesan Anda telah terkirim ke sekretariat. Terima kasih!'); return false;">
        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" required placeholder="Nama Anda">
        </div>
        <div class="form-group">
          <label class="form-label">Email / WhatsApp</label>
          <input type="text" class="form-control" required placeholder="Kontak yang dapat dihubungi">
        </div>
        <div class="form-group">
          <label class="form-label">Pesan / Niat Kerjasama</label>
          <textarea class="form-control" rows="4" required placeholder="Tuliskan pesan Anda..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">
          <i class="fa-solid fa-paper-plane"></i> Kirim Pesan
        </button>
      </form>
    </div>
  </div>
</div>
