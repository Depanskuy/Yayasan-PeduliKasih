<h3 style="font-size:1.4rem;font-weight:800;margin-bottom:8px;text-align:center;">Masuk ke Akun</h3>
<p class="text-muted text-center" style="font-size:0.88rem;margin-bottom:24px;">Silakan masukkan email dan kata sandi Anda</p>

<form action="<?= url('login') ?>" method="POST">
  <?= csrf_field() ?>

  <div class="form-group">
    <label class="form-label" for="email">Alamat Email</label>
    <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="<?= old('email', $_SESSION['flash']['old_email'] ?? '') ?>" required autofocus>
  </div>

  <div class="form-group">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
      <label class="form-label" for="password" style="margin-bottom:0;">Kata Sandi</label>
    </div>
    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
  </div>

  <button type="submit" class="btn btn-primary btn-block" style="margin-top:10px;">
    <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk Sekarang
  </button>
</form>

<div style="margin-top:24px;padding-top:20px;border-top:1px dashed var(--border);">
  <div style="font-size:0.8rem;font-weight:700;color:var(--dark-muted);margin-bottom:10px;text-align:center;">
    <i class="fa-solid fa-key" style="color:var(--accent);"></i> AKUN PENGUJIAN CEPAT (KLIK UNTUK ISI):
  </div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:0.78rem;">
    <button type="button" class="btn btn-sm btn-outline" onclick="fillDummy('admin@pedulikasih.test', 'password')">
      <i class="fa-solid fa-user-shield"></i> Super Admin
    </button>
    <button type="button" class="btn btn-sm btn-outline" onclick="fillDummy('staff@pedulikasih.test', 'password')">
      <i class="fa-solid fa-user-tie"></i> Staf Keuangan
    </button>
    <button type="button" class="btn btn-sm btn-outline" onclick="fillDummy('donatur@pedulikasih.test', 'password')">
      <i class="fa-solid fa-heart"></i> Donatur
    </button>
    <button type="button" class="btn btn-sm btn-outline" onclick="fillDummy('relawan@pedulikasih.test', 'password')">
      <i class="fa-solid fa-hand-holding-heart"></i> Relawan
    </button>
  </div>
</div>

<p style="text-align:center;font-size:0.88rem;color:var(--dark-muted);margin-top:24px;">
  Belum memiliki akun? <a href="<?= url('register') ?>" style="font-weight:700;">Daftar Akun Baru</a>
</p>

<script>
function fillDummy(email, password) {
  document.getElementById('email').value = email;
  document.getElementById('password').value = password;
}
</script>
