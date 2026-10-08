<div class="card" style="max-width:750px;">
  <div class="card-header" style="background:#f0fdf4;">
    <h3 class="card-title" style="color:var(--primary-dark);">
      <i class="fa-solid fa-cash-register"></i> Input Penerimaan Donasi Tunai / Offline
    </h3>
  </div>
  <div class="card-body">
    <p style="font-size:0.88rem;color:var(--dark-muted);margin-bottom:20px;">
      Digunakan oleh staf keuangan / kasir sekretariat saat menerima infaq, shadaqah, atau donasi tunai langsung dari donatur. Donasi yang dicatat otomatis berstatus <strong>Verified</strong> dan langsung menambah kas program.
    </p>

    <form action="<?= url('admin/donations/offline/store') ?>" method="POST">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Program Campaign Tujuan</label>
        <select name="campaign_id" class="form-control" required>
          <?php foreach ($campaigns as $c): ?>
            <option value="<?= $c['id'] ?>"><?= e($c['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Nominal Donasi Tunai (Rp)</label>
        <input type="number" name="amount" class="form-control" placeholder="100000" min="5000" required autofocus>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Nama Donatur</label>
          <input type="text" name="donor_name" class="form-control" placeholder="Nama Donatur / Muhsinin" required>
        </div>
        <div class="form-group">
          <label class="form-label">Nomor Kontak / WhatsApp (Opsional)</label>
          <input type="text" name="donor_phone" class="form-control" placeholder="081234567890">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Email (Opsional)</label>
        <input type="email" name="donor_email" class="form-control" placeholder="email@donatur.id">
      </div>

      <div class="form-group">
        <label class="form-label">Catatan / Niat / Titipan Doa</label>
        <textarea name="doa_message" class="form-control" rows="2" placeholder="Contoh: Titip sedekah jumat berkah untuk anak yatim..."></textarea>
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
          <input type="checkbox" name="is_anonymous" value="1" style="transform:scale(1.2);accent-color:var(--primary);">
          <span>Donasi sebagai Hamba Allah (Anonim)</span>
        </label>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;">
        <a href="<?= url('admin/donations') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-receipt"></i> Simpan & Terbitkan Kwitansi
        </button>
      </div>
    </form>
  </div>
</div>
