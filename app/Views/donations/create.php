<div class="container section" style="max-width:760px;">
  <div style="text-align:center;margin-bottom:30px;">
    <span class="badge badge-primary" style="margin-bottom:10px;">FORMULIR DONASI ONLINE</span>
    <h1 style="font-size:2rem;margin-bottom:10px;">Salurkan Bantuan Terbaik Anda</h1>
    <p class="text-muted">Untuk program: <strong><?= e($campaign['title']) ?></strong></p>
  </div>

  <div class="card" style="box-shadow:var(--shadow-lg);border-radius:var(--radius-lg);">
    <div class="card-body" style="padding:35px 30px;">
      <form action="<?= url('donation/store') ?>" method="POST" id="donationForm">
        <?= csrf_field() ?>
        <input type="hidden" name="campaign_id" value="<?= e($campaign['id']) ?>">

        <!-- 1. PILIH NOMINAL CEPAT -->
        <div class="form-group">
          <label class="form-label" style="font-size:1rem;margin-bottom:12px;">1. Pilih Nominal Donasi</label>
          <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:10px;margin-bottom:15px;">
            <button type="button" class="btn btn-outline nominal-btn" onclick="setNominal(25000, this)">Rp 25.000</button>
            <button type="button" class="btn btn-outline nominal-btn" onclick="setNominal(50000, this)">Rp 50.000</button>
            <button type="button" class="btn btn-outline nominal-btn active" onclick="setNominal(100000, this)">Rp 100.000</button>
            <button type="button" class="btn btn-outline nominal-btn" onclick="setNominal(250000, this)">Rp 250.000</button>
            <button type="button" class="btn btn-outline nominal-btn" onclick="setNominal(500000, this)">Rp 500.000</button>
            <button type="button" class="btn btn-outline nominal-btn" onclick="setNominal(1000000, this)">Rp 1.000.000</button>
          </div>

          <label class="form-label" style="font-size:0.85rem;color:var(--dark-muted);">Atau masukkan nominal lainnya (Rp):</label>
          <div style="position:relative;">
            <span style="position:absolute;left:14px;top:10px;font-weight:700;color:var(--dark-muted);">Rp</span>
            <input type="number" id="customAmount" name="amount" class="form-control" style="padding-left:45px;font-size:1.1rem;font-weight:700;" value="100000" min="10000" required>
          </div>
        </div>

        <!-- 2. METODE PEMBAYARAN -->
        <div class="form-group" style="margin-top:30px;">
          <label class="form-label" style="font-size:1rem;margin-bottom:12px;">2. Pilih Metode Pembayaran</label>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            
            <label style="display:flex;align-items:center;gap:12px;padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;background:#fff;">
              <input type="radio" name="payment_method" value="qris" checked style="accent-color:var(--primary);transform:scale(1.2);">
              <div>
                <strong>QRIS (Semua E-Wallet)</strong>
                <div style="font-size:0.75rem;color:var(--dark-muted);">GoPay, OVO, ShopeePay, Dana, LinkAja</div>
              </div>
            </label>

            <label style="display:flex;align-items:center;gap:12px;padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;background:#fff;">
              <input type="radio" name="payment_method" value="bca" style="accent-color:var(--primary);transform:scale(1.2);">
              <div>
                <strong>Transfer Bank BCA</strong>
                <div style="font-size:0.75rem;color:var(--dark-muted);"><?= e($settings['bank_bca'] ?? '8830-192-800') ?></div>
              </div>
            </label>

            <label style="display:flex;align-items:center;gap:12px;padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;background:#fff;">
              <input type="radio" name="payment_method" value="mandiri" style="accent-color:var(--primary);transform:scale(1.2);">
              <div>
                <strong>Transfer Bank Mandiri</strong>
                <div style="font-size:0.75rem;color:var(--dark-muted);"><?= e($settings['bank_mandiri'] ?? '137-00-9876543-2') ?></div>
              </div>
            </label>

            <label style="display:flex;align-items:center;gap:12px;padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;background:#fff;">
              <input type="radio" name="payment_method" value="bri" style="accent-color:var(--primary);transform:scale(1.2);">
              <div>
                <strong>Transfer Bank BRI</strong>
                <div style="font-size:0.75rem;color:var(--dark-muted);"><?= e($settings['bank_bri'] ?? '0206-01-008912-301') ?></div>
              </div>
            </label>

            <label style="display:flex;align-items:center;gap:12px;padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;background:#fff;">
              <input type="radio" name="payment_method" value="gopay" style="accent-color:var(--primary);transform:scale(1.2);">
              <div>
                <strong>GoPay E-Wallet</strong>
                <div style="font-size:0.75rem;color:var(--dark-muted);">Instan via aplikasi</div>
              </div>
            </label>

            <label style="display:flex;align-items:center;gap:12px;padding:14px;border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;background:#fff;">
              <input type="radio" name="payment_method" value="dana" style="accent-color:var(--primary);transform:scale(1.2);">
              <div>
                <strong>DANA E-Wallet</strong>
                <div style="font-size:0.75rem;color:var(--dark-muted);">Instan via aplikasi</div>
              </div>
            </label>

          </div>
        </div>

        <!-- 3. DATA DONATUR -->
        <div class="form-group" style="margin-top:30px;">
          <label class="form-label" style="font-size:1rem;margin-bottom:12px;">3. Informasi Donatur</label>

          <div style="margin-bottom:14px;">
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;font-size:0.9rem;cursor:pointer;">
              <input type="checkbox" name="is_anonymous" value="1" id="anonCheck" onchange="toggleAnon(this)" style="accent-color:var(--primary);transform:scale(1.2);">
              <span>Sembunyikan nama saya (Donasi sebagai <strong>Hamba Allah</strong>)</span>
            </label>
          </div>

          <div id="donorDetails">
            <div class="form-group">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" id="donorName" name="donor_name" class="form-control" placeholder="Nama Anda" value="<?= e($user['name'] ?? old('donor_name', '')) ?>" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
              <div class="form-group">
                <label class="form-label">Alamat Email (untuk pengiriman bukti/sertifikat)</label>
                <input type="email" name="donor_email" class="form-control" placeholder="nama@email.com" value="<?= e($user['email'] ?? old('donor_email', '')) ?>" required>
              </div>
              <div class="form-group">
                <label class="form-label">Nomor WhatsApp / HP</label>
                <input type="text" name="donor_phone" class="form-control" placeholder="081234567890" value="<?= e($user['phone'] ?? old('donor_phone', '')) ?>">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Doa / Pesan Dukungan (Opsional)</label>
            <textarea name="doa_message" class="form-control" rows="2" placeholder="Tuliskan doa atau pesan kebaikan untuk penerima manfaat..."><?= old('doa_message') ?></textarea>
          </div>
        </div>

        <button type="submit" class="btn btn-cta btn-block" style="padding:16px;font-size:1.15rem;margin-top:20px;">
          <i class="fa-solid fa-heart"></i> Lanjutkan Pembayaran Donasi
        </button>
      </form>
    </div>
  </div>
</div>

<script>
function setNominal(amount, btn) {
  document.getElementById('customAmount').value = amount;
  document.querySelectorAll('.nominal-btn').forEach(b => b.classList.remove('active', 'btn-primary'));
  btn.classList.add('btn-primary');
}

function toggleAnon(checkbox) {
  const nameInput = document.getElementById('donorName');
  if (checkbox.checked) {
    nameInput.dataset.original = nameInput.value;
    nameInput.value = 'Hamba Allah';
  } else {
    nameInput.value = nameInput.dataset.original || '';
  }
}
</script>
<style>
.nominal-btn.btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
</style>
