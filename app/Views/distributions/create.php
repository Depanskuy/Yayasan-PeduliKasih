<div class="card" style="max-width:850px;">
  <div class="card-header" style="background:#fef2f2;">
    <h3 class="card-title" style="color:#991b1b;">
      <i class="fa-solid fa-hand-holding-dollar"></i> Catat Penyaluran Bantuan & Pengeluaran Kas
    </h3>
  </div>
  <div class="card-body">
    <form action="<?= url('admin/distributions/store') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Judul / Kegiatan Penyaluran</label>
        <input type="text" name="title" class="form-control" placeholder="Contoh: Distribusi Paket Sembako & Santunan Tunai Lansia" required autofocus>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="form-group">
          <label class="form-label">Sumber Dana (Program Campaign)</label>
          <select name="campaign_id" class="form-control" required>
            <?php foreach ($campaigns as $c): ?>
              <option value="<?= $c['id'] ?>"><?= e($c['title']) ?> (Saldo: <?= format_rupiah($c['collected_amount']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Penerima Manfaat / Mustahik (Opsional)</label>
          <select name="beneficiary_id" class="form-control">
            <option value="">-- Penyaluran Umum / Kelompok Massal --</option>
            <?php foreach ($beneficiaries as $b): ?>
              <option value="<?= $b['id'] ?>"><?= e($b['name']) ?> (<?= e($b['nik']) ?> - <?= e($b['category']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
        <div class="form-group">
          <label class="form-label">Jenis Bantuan</label>
          <select name="assistance_type" class="form-control" required>
            <option value="sembako">Sembako / Pangan</option>
            <option value="tunai">Santunan Tunai</option>
            <option value="pendidikan">Pendidikan / SPP</option>
            <option value="kesehatan">Kesehatan / Medis</option>
            <option value="renovasi">Renovasi Bangunan</option>
            <option value="tanggap_darurat">Tanggap Darurat Bencana</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Nominal Realisasi (Rp)</label>
          <input type="number" name="amount" class="form-control" placeholder="1000000" min="1000" required>
        </div>

        <div class="form-group">
          <label class="form-label">Jumlah Jiwa Penerima</label>
          <input type="number" name="recipient_count" class="form-control" value="1" min="1" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Tanggal Penyaluran Dilaksanakan</label>
        <input type="date" name="disbursement_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Catatan Lapangan / Rincian Barang Bantuan</label>
        <textarea name="notes" class="form-control" rows="3" placeholder="Rincian pembelian barang, faktur belanja, atau keterangan situasi serah terima..."></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Unggah Foto Dokumentasi Serah Terima Bantuan</label>
        <input type="file" name="documentation_image" class="form-control" accept="image/*">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
        <a href="<?= url('admin/distributions') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary" style="background:#dc2626;">
          <i class="fa-solid fa-floppy-disk"></i> Catat Penyaluran & Potong Kas
        </button>
      </div>
    </form>
  </div>
</div>
