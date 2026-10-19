<?php
// app/Views/admin/donations.php
use App\Core\Helper;

$donations    = $donations ?? [];
$statusCounts = $statusCounts ?? [];
$filters      = $filters ?? [];

$statusBadge = [
    'pending'  => 'badge-warning',
    'verified' => 'badge-success',
    'rejected' => 'badge-danger',
];
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
        <div class="flex flex-wrap gap-2">
            <span class="badge-warning">Pending: <?= (int) ($statusCounts['pending'] ?? 0) ?></span>
            <span class="badge-success">Terverifikasi: <?= (int) ($statusCounts['verified'] ?? 0) ?></span>
            <span class="badge-danger">Ditolak: <?= (int) ($statusCounts['rejected'] ?? 0) ?></span>
        </div>

        <form method="get" action="<?= Helper::url('admin/donations') ?>" class="flex flex-col gap-2 sm:flex-row">
            <div class="relative">
                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft"></i>
                <input type="search" name="q" class="input pl-9 sm:w-56" placeholder="Cari donatur / kode..."
                       value="<?= Helper::e($filters['search'] ?? '') ?>">
            </div>
            <select name="status" class="select sm:w-40">
                <option value="">Semua status</option>
                <?php foreach (['pending' => 'Pending', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'] as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($filters['status'] ?? '') === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn-outline btn-sm" type="submit">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode / Donatur</th>
                    <th>Kampanye</th>
                    <th>Nominal</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($donations): ?>
                    <?php foreach ($donations as $donation): ?>
                        <tr>
                            <td>
                                <p class="text-xs font-medium text-accent"><?= Helper::e($donation['donation_code']) ?></p>
                                <p class="text-sm text-ink"><?= Helper::e($donation['donor_name']) ?></p>
                                <p class="text-xs text-ink-soft"><?= Helper::tanggal($donation['created_at'], true) ?></p>
                            </td>
                            <td class="max-w-[220px]">
                                <p class="line-clamp-2 text-sm text-ink"><?= Helper::e($donation['campaign_title'] ?? '-') ?></p>
                            </td>
                            <td class="whitespace-nowrap text-sm font-semibold text-success"><?= Helper::rupiah($donation['amount']) ?></td>
                            <td class="whitespace-nowrap text-xs uppercase text-ink-soft"><?= Helper::e($donation['payment_method']) ?></td>
                            <td>
                                <span class="<?= $statusBadge[$donation['payment_status']] ?? 'badge-muted' ?>">
                                    <?= ucfirst($donation['payment_status']) ?>
                                </span>
                                <?php if (!empty($donation['rejection_reason'])): ?>
                                    <p class="mt-1 max-w-[160px] text-[11px] text-danger"><?= Helper::e($donation['rejection_reason']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <?php if ($donation['payment_status'] !== 'verified'): ?>
                                        <form method="post" action="<?= Helper::url('admin/donations/' . $donation['id'] . '/verify') ?>"
                                              data-confirm="Verifikasi donasi ini?" class="inline">
                                            <?= Helper::csrfField() ?>
                                            <button type="submit" class="btn-success btn-sm">
                                                <i data-lucide="check" class="h-4 w-4"></i> Verifikasi
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($donation['payment_status'] !== 'rejected'): ?>
                                        <form method="post" action="<?= Helper::url('admin/donations/' . $donation['id'] . '/reject') ?>"
                                              class="inline"
                                              onsubmit="var r = window.prompt('Alasan penolakan:'); if (!r) { return false; } this.rejection_reason.value = r;">
                                            <?= Helper::csrfField() ?>
                                            <input type="hidden" name="rejection_reason" value="">
                                            <button type="submit" class="btn-danger btn-sm">
                                                <i data-lucide="x" class="h-4 w-4"></i> Tolak
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-ink-soft">Tidak ada donasi pada filter ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
