<div class="card" style="max-width:750px;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-user-plus" style="color:var(--primary);margin-right:8px;"></i> Tambah Pengguna / Staf Baru</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/users/store') ?>" method="POST">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required autofocus>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Alamat Email</label>
          <input type="email" name="email" class="form-control" placeholder="nama@pedulikasih.org" required>
        </div>
        <div class="form-group">
          <label class="form-label">Nomor WhatsApp / HP</label>
          <input type="text" name="phone" class="form-control" placeholder="081234567890">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Peran (Hak Akses)</label>
          <select name="role" class="form-control" required>
            <option value="staff">Staf / Admin Keuangan</option>
            <option value="superadmin">Super Admin</option>
            <option value="volunteer">Relawan</option>
            <option value="donatur">Donatur</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Status Akun</label>
          <select name="status" class="form-control">
            <option value="active">Aktif (Bisa Login)</option>
            <option value="inactive">Nonaktif</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Kata Sandi Awal</label>
        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
        <a href="<?= url('admin/users') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Buat Akun Pengguna
        </button>
      </div>
    </form>
  </div>
</div>
