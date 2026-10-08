<div class="card" style="max-width:800px;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-pen-to-square" style="color:var(--primary);margin-right:8px;"></i> Edit Data Mustahik: <?= e($beneficiary['name']) ?></h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/beneficiaries/update/' . $beneficiary['id']) ?>" method="POST">
      <?= csrf_field() ?>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Nomor Induk Kependudukan (NIK)</label>
          <input type="text" name="nik" class="form-control" value="<?= e($beneficiary['nik']) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Nama Lengkap Mustahik</label>
          <input type="text" name="name" class="form-control" value="<?= e($beneficiary['name']) ?>" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Kategori Mustahik</label>
          <select name="category" class="form-control" required>
            <?php foreach (['fakir', 'miskin', 'yatim', 'lansia', 'difabel', 'korban_bencana', 'lainnya'] as $cat): ?>
              <option value="<?= $cat ?>" <?= $beneficiary['category'] === $cat ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $cat)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Nomor Telepon</label>
          <input type="text" name="phone" class="form-control" value="<?= e($beneficiary['phone'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Lengkap</label>
        <textarea name="address" class="form-control" rows="2" required><?= e($beneficiary['address']) ?></textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
        <div class="form-group">
          <label class="form-label">Kelurahan</label>
          <input type="text" name="village" class="form-control" value="<?= e($beneficiary['village'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Kecamatan</label>
          <input type="text" name="district" class="form-control" value="<?= e($beneficiary['district'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Kota / Kabupaten</label>
          <input type="text" name="city" class="form-control" value="<?= e($beneficiary['city']) ?>" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Status Kelayakan Bantuan</label>
        <select name="eligibility_status" class="form-control">
          <option value="verified" <?= $beneficiary['eligibility_status'] === 'verified' ? 'selected' : '' ?>>Layak & Terverifikasi (Verified)</option>
          <option value="pending" <?= $beneficiary['eligibility_status'] === 'pending' ? 'selected' : '' ?>>Menunggu Survei (Pending)</option>
          <option value="rejected" <?= $beneficiary['eligibility_status'] === 'rejected' ? 'selected' : '' ?>>Tidak Layak (Rejected)</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Catatan Verifikasi</label>
        <textarea name="notes" class="form-control" rows="3"><?= e($beneficiary['notes'] ?? '') ?></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
        <a href="<?= url('admin/beneficiaries') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
