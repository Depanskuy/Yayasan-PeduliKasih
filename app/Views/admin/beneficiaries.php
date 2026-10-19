<?php
// app/Views/admin/beneficiaries.php
use App\Core\Helper;

$beneficiaries = $beneficiaries ?? [];
$byCategory    = $byCategory ?? [];
$categories    = $categories ?? [];
$filters       = $filters ?? [];
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
        <div class="flex flex-wrap gap-2">
            <?php foreach ($categories as $key => $label): ?>
                <span class="badge-muted"><?= $label ?>: <strong class="text-ink"><?= (int) ($byCategory[$key] ?? 0) ?></strong></span>
            <?php endforeach; ?>
        </div>
        <a href="<?= Helper::url('admin/beneficiaries/create') ?>" class="btn-primary btn-sm">
            <i data-lucide="plus" class="h-4 w-4"></i> Tambah Penerima
        </a>
    </div>

    <form method="get" action="<?= Helper::url('admin/beneficiaries') ?>" class="flex flex-col gap-2 sm:flex-row">
        <div class="relative flex-1">
            <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft"></i>
            <input type="search" name="q" class="input pl-9" placeholder="Cari nama / NIK / kota..."
                   value="<?= Helper::e($filters['search'] ?? '') ?>">
        </div>
        <select name="kategori" class="select sm:w-48">
            <option value="">Semua kategori</option>
            <?php foreach ($categories as $key => $label): ?>
                <option value="<?= $key ?>" <?= ($filters['category'] ?? '') === $key ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn-outline btn-sm" type="submit">Filter</button>
    </form>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama / NIK</th>
                    <th>Kategori</th>
                    <th>Domisili</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($beneficiaries): ?>
                    <?php foreach ($beneficiaries as $b): ?>
                        <tr>
                            <td>
                                <p class="text-sm font-medium text-ink"><?= Helper::e($b['name']) ?></p>
                                <p class="text-xs text-ink-soft">NIK: <?= Helper::e($b['nik']) ?></p>
                            </td>
                            <td><span class="badge-accent"><?= Helper::e($categories[$b['category']] ?? $b['category']) ?></span></td>
                            <td class="text-sm text-ink-soft">
                                <?= Helper::e($b['city']) ?>
                                <?php if (!empty($b['district'])): ?>
                                    <p class="text-xs text-ink-soft"><?= Helper::e($b['district']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="<?= $b['eligibility_status'] === 'verified' ? 'badge-success' : ($b['eligibility_status'] === 'pending' ? 'badge-warning' : 'badge-danger') ?>">
                                    <?= ucfirst($b['eligibility_status']) ?>
                                </span>
                            </td>
                            <td class="whitespace-nowrap text-xs text-ink-soft"><?= Helper::tanggal($b['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-ink-soft">Belum ada penerima manfaat.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
