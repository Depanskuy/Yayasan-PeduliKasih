<div class="card" style="max-width:800px;">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-user-plus" style="color:var(--primary);margin-right:8px;"></i> Tambah Data Mustahik / Penerima Manfaat</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/beneficiaries/store') ?>" method="POST">
      <?= csrf_field() ?>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Nomor Induk Kependudukan (NIK)</label>
          <input type="text" name="nik" class="form-control" placeholder="16 digit NIK" required autofocus>
        </div>
        <div class="form-group">
          <label class="form-label">Nama Lengkap Mustahik</label>
          <input type="text" name="name" class="form-control" placeholder="Nama Penerima" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Kategori Mustahik</label>
          <select name="category" class="form-control" required>
            <option value="fakir">Fakir</option>
            <option value="miskin">Miskin</option>
            <option value="yatim">Anak Yatim / Piatu</option>
            <option value="lansia">Lansia Dhuafa</option>
            <option value="difabel">Disabilitas / Difabel</option>
            <option value="korban_bencana">Korban Bencana</option>
            <option value="lainnya">Lainnya</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Nomor Telepon / Kontak</label>
          <input type="text" name="phone" class="form-control" placeholder="081234567890">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Lengkap</label>
        <textarea name="address" class="form-control" rows="2" placeholder="Nama jalan, RT/RW, nomor rumah" required></textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
        <div class="form-group">
          <label class="form-label">Kelurahan / Desa</label>
          <input type="text" name="village" class="form-control" placeholder="Kelurahan">
        </div>
        <div class="form-group">
          <label class="form-label">Kecamatan</label>
          <input type="text" name="district" class="form-control" placeholder="Kecamatan">
        </div>
        <div class="form-group">
          <label class="form-label">Kota / Kabupaten</label>
          <input type="text" name="city" class="form-control" value="Jakarta" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Catatan Kondisi Sosial & Verifikasi</label>
        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Kondisi rumah semi permanen, menanggung 3 tanggungan balita..."></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
        <a href="<?= url('admin/beneficiaries') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Data Mustahik
        </button>
      </div>
    </form>
  </div>
</div>
