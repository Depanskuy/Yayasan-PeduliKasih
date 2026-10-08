<div class="card" style="max-width:900px;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-sliders" style="color:var(--primary);margin-right:8px;"></i> Pengaturan Yayasan & Nomor Rekening Donasi</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/settings/update') ?>" method="POST">
      <?= csrf_field() ?>

      <h4 style="font-size:1.05rem;color:var(--primary);margin-bottom:15px;border-bottom:1px solid var(--border);padding-bottom:6px;">
        1. Identitas & Legalitas Yayasan
      </h4>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Nama Lembaga / Yayasan</label>
          <input type="text" name="org_name" class="form-control" value="<?= e($settings['org_name'] ?? 'Yayasan Peduli Kasih Sesama') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Tagline Lembaga</label>
          <input type="text" name="org_tagline" class="form-control" value="<?= e($settings['org_tagline'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Nomor SK Kemenkumham & Izin Kemensos</label>
        <input type="text" name="org_legal" class="form-control" value="<?= e($settings['org_legal'] ?? '') ?>">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Email Sekretariat</label>
          <input type="email" name="org_email" class="form-control" value="<?= e($settings['org_email'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Telepon / WhatsApp</label>
          <input type="text" name="org_phone" class="form-control" value="<?= e($settings['org_phone'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Lengkap Kantor</label>
        <textarea name="org_address" class="form-control" rows="2"><?= e($settings['org_address'] ?? '') ?></textarea>
      </div>

      <h4 style="font-size:1.05rem;color:var(--primary);margin:25px 0 15px;border-bottom:1px solid var(--border);padding-bottom:6px;">
        2. Rekening Bank & QRIS Resmi Donasi
      </h4>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Nomor Rekening BCA</label>
          <input type="text" name="bank_bca" class="form-control" value="<?= e($settings['bank_bca'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Nomor Rekening Mandiri</label>
          <input type="text" name="bank_mandiri" class="form-control" value="<?= e($settings['bank_mandiri'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Nomor Rekening BRI</label>
          <input type="text" name="bank_bri" class="form-control" value="<?= e($settings['bank_bri'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">NMID QRIS Nasional</label>
          <input type="text" name="qris_nmid" class="form-control" value="<?= e($settings['qris_nmid'] ?? '') ?>">
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top:20px;">
        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Pengaturan
      </button>
    </form>
  </div>
</div>
