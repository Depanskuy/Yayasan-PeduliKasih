<h3 style="font-size:1.4rem;font-weight:800;margin-bottom:8px;text-align:center;">Daftar Akun Baru</h3>
<p class="text-muted text-center" style="font-size:0.88rem;margin-bottom:24px;">Bergabung bersama ribuan donatur & relawan peduli sesama</p>

<form action="<?= url('register') ?>" method="POST">
  <?= csrf_field() ?>

  <div class="form-group">
    <label class="form-label" for="name">Nama Lengkap</label>
    <input type="text" id="name" name="name" class="form-control" placeholder="Nama Anda" value="<?= old('name') ?>" required autofocus>
  </div>

  <div class="form-group">
    <label class="form-label" for="email">Alamat Email</label>
    <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="<?= old('email') ?>" required>
  </div>

  <div class="form-group">
    <label class="form-label" for="phone">Nomor WhatsApp / HP</label>
    <input type="text" id="phone" name="phone" class="form-control" placeholder="081234567890" value="<?= old('phone') ?>" required>
  </div>

  <div class="form-group">
    <label class="form-label" for="role">Daftar Sebagai</label>
    <select id="role" name="role" class="form-control" required>
      <option value="donatur">Donatur (Penyalur Kebaikan)</option>
      <option value="volunteer">Relawan (Aksi Lapangan)</option>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="password">Kata Sandi</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
  </div>

  <button type="submit" class="btn btn-primary btn-block" style="margin-top:10px;">
    <i class="fa-solid fa-user-plus"></i> Selesaikan Pendaftaran
  </button>
</form>

<p style="text-align:center;font-size:0.88rem;color:var(--dark-muted);margin-top:24px;">
  Sudah memiliki akun? <a href="<?= url('login') ?>" style="font-weight:700;">Masuk di Sini</a>
</p>
