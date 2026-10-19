<?php
// app/Views/admin/disbursement_form.php
use App\Core\Helper;

$campaigns     = $campaigns ?? [];
$beneficiaries = $beneficiaries ?? [];

$types = [
    'tunai'           => 'Tunai',
    'sembako'         => 'Sembako',
    'pendidikan'      => 'Pendidikan',
    'kesehatan'       => 'Kesehatan',
    'renovasi'        => 'Renovasi',
    'tanggap_darurat' => 'Tanggap Darurat',
];
$val = fn (string $key, $default = '') => Helper::old($key, $default);
$amountRaw = $val('amount');
$amountDisplay = ($amountRaw === '' || $amountRaw === null) ? '' : number_format((float) $amountRaw, 0, ',', '.');
?>
<div class="mx-auto max-w-3xl space-y-6">
    <a href="<?= Helper::url('admin/disbursements') ?>" class="btn-link">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
    </a>

    <form action="<?= Helper::url('admin/disbursements') ?>" method="post" enctype="multipart/form-data" class="card card-pad space-y-5">
        <?= Helper::csrfField() ?>

        <div>
            <label class="label" for="title">Judul Penyaluran</label>
            <input id="title" name="title" class="input" required value="<?= Helper::e($val('title')) ?>"
                   placeholder="Contoh: Distribusi Sembako Lansia Dhuafa">
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="campaign_id">Kampanye</label>
                <select id="campaign_id" name="campaign_id" class="select" required>
                    <option value="">Pilih kampanye</option>
                    <?php foreach ($campaigns as $campaign): ?>
                        <option value="<?= (int) $campaign['id'] ?>" <?= (int) $val('campaign_id', 0) === (int) $campaign['id'] ? 'selected' : '' ?>>
                            <?= Helper::e($campaign['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label" for="beneficiary_id">Penerima Manfaat (opsional)</label>
                <select id="beneficiary_id" name="beneficiary_id" class="select">
                    <option value="0">— Tidak spesifik —</option>
                    <?php foreach ($beneficiaries as $beneficiary): ?>
                        <option value="<?= (int) $beneficiary['id'] ?>" <?= (int) $val('beneficiary_id', 0) === (int) $beneficiary['id'] ? 'selected' : '' ?>>
                            <?= Helper::e($beneficiary['name']) ?> — <?= Helper::e($beneficiary['city']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="label" for="assistance_type">Jenis Bantuan</label>
                <select id="assistance_type" name="assistance_type" class="select" required>
                    <?php foreach ($types as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $val('assistance_type') === $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label" for="amount">Nominal (Rp)</label>
                <input id="amount" name="amount" class="input" inputmode="numeric" data-currency required value="<?= Helper::e($amountDisplay) ?>">
            </div>
            <div>
                <label class="label" for="recipient_count">Jumlah Penerima</label>
                <input id="recipient_count" name="recipient_count" type="number" min="1" class="input" required value="<?= Helper::e($val('recipient_count', 1)) ?>">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="disbursement_date">Tanggal Penyaluran</label>
                <input id="disbursement_date" name="disbursement_date" type="date" class="input" required value="<?= Helper::e($val('disbursement_date', date('Y-m-d'))) ?>">
            </div>
            <div>
                <label class="label" for="documentation_image">Dokumentasi (opsional)</label>
                <input id="documentation_image" name="documentation_image" type="file" accept="image/*"
                       class="input file:mr-3 file:rounded-md file:border-0 file:bg-accent/15 file:px-3 file:py-1 file:text-xs file:text-accent">
            </div>
        </div>

        <div>
            <label class="label" for="notes">Catatan</label>
            <textarea id="notes" name="notes" rows="3" class="textarea" placeholder="Detail pembelian / keterangan..."><?= Helper::e($val('notes')) ?></textarea>
        </div>

        <div class="flex justify-end gap-3 border-t border-line pt-5">
            <a href="<?= Helper::url('admin/disbursements') ?>" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="h-4 w-4"></i> Simpan
            </button>
        </div>
    </form>
</div>
