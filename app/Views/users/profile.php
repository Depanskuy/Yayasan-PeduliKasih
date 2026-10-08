<div class="card" style="max-width:800px;margin:0 auto;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-id-badge" style="color:var(--primary);margin-right:8px;"></i> Pengaturan Profil Pengguna</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('profile') ?>" method="POST">
      <?= csrf_field() ?>

      <div style="display:flex;align-items:center;gap:15px;margin-bottom:25px;padding-bottom:15px;border-bottom:1px solid var(--border);">
        <div style="width:64px;height:64px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:700;">
          <?= strtoupper(substr($user['name'], 0, 1)) ?>
        </div>
        <div>
          <h4 style="margin-bottom:2px;"><?= e($user['name']) ?></h4>
          <span class="badge badge-primary"><?= ucfirst(e($user['role'])) ?></span>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Alamat Email</label>
          <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Nomor WhatsApp / Telepon</label>
        <input type="text" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Lengkap Domisili</label>
        <textarea name="address" class="form-control" rows="2"><?= e($user['address'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Bio / Keterangan Singkat</label>
        <textarea name="bio" class="form-control" rows="2"><?= e($user['bio'] ?? '') ?></textarea>
      </div>

      <h4 style="font-size:1.05rem;color:var(--primary);margin:25px 0 15px;border-top:1px solid var(--border);padding-top:15px;">
        Ganti Kata Sandi (Opsional)
      </h4>
      <div class="form-group">
        <label class="form-label">Kata Sandi Baru (Kosongkan jika tidak ingin mengubah)</label>
        <input type="password" name="new_password" class="form-control" placeholder="Minimal 6 karakter" minlength="6">
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top:15px;">
        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
      </button>
    </form>
  </div>
</div>
