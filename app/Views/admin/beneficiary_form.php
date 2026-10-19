<?php
// app/Views/admin/beneficiary_form.php
use App\Core\Helper;

$categories = $categories ?? [];
$val = fn (string $key, $default = '') => Helper::old($key, $default);
?>
<div class="mx-auto max-w-3xl space-y-6">
    <a href="<?= Helper::url('admin/beneficiaries') ?>" class="btn-link">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
    </a>

    <form action="<?= Helper::url('admin/beneficiaries') ?>" method="post" class="card card-pad space-y-5">
        <?= Helper::csrfField() ?>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="nik">NIK</label>
                <input id="nik" name="nik" class="input" required value="<?= Helper::e($val('nik')) ?>" placeholder="16 digit NIK">
            </div>
            <div>
                <label class="label" for="name">Nama Lengkap</label>
                <input id="name" name="name" class="input" required value="<?= Helper::e($val('name')) ?>">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="category">Kategori</label>
                <select id="category" name="category" class="select" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach ($categories as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $val('category') === $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label" for="phone">No. HP</label>
                <input id="phone" name="phone" class="input" value="<?= Helper::e($val('phone')) ?>" placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <div>
            <label class="label" for="address">Alamat</label>
            <textarea id="address" name="address" rows="2" class="textarea" required><?= Helper::e($val('address')) ?></textarea>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="label" for="village">Kelurahan / Desa</label>
                <input id="village" name="village" class="input" value="<?= Helper::e($val('village')) ?>">
            </div>
            <div>
                <label class="label" for="district">Kecamatan</label>
                <input id="district" name="district" class="input" value="<?= Helper::e($val('district')) ?>">
            </div>
            <div>
                <label class="label" for="city">Kota</label>
                <input id="city" name="city" class="input" value="<?= Helper::e($val('city', 'Jakarta')) ?>">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="eligibility_status">Status Kelayakan</label>
                <select id="eligibility_status" name="eligibility_status" class="select">
                    <?php foreach (['verified' => 'Terverifikasi', 'pending' => 'Pending', 'rejected' => 'Ditolak'] as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $val('eligibility_status', 'verified') === $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label" for="notes">Catatan</label>
                <input id="notes" name="notes" class="input" value="<?= Helper::e($val('notes')) ?>" placeholder="Kondisi / keterangan">
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-line pt-5">
            <a href="<?= Helper::url('admin/beneficiaries') ?>" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="h-4 w-4"></i> Simpan
            </button>
        </div>
    </form>
</div>
