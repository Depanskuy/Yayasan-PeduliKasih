<?php
// app/Views/admin/disbursements.php
use App\Core\Helper;

$disbursements = $disbursements ?? [];
$total         = $total ?? 0;
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm text-ink-soft">Total penyaluran tercatat:
                <strong class="text-warning"><?= Helper::rupiah($total) ?></strong>
            </p>
        </div>
        <a href="<?= Helper::url('admin/disbursements/create') ?>" class="btn-primary btn-sm">
            <i data-lucide="plus" class="h-4 w-4"></i> Tambah Penyaluran
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode / Program</th>
                    <th>Kampanye</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th>Penerima</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($disbursements): ?>
                    <?php foreach ($disbursements as $d): ?>
                        <tr>
                            <td>
                                <p class="text-xs font-medium text-accent"><?= Helper::e($d['disbursement_code']) ?></p>
                                <p class="text-sm text-ink"><?= Helper::e(Helper::excerpt($d['title'], 40)) ?></p>
                            </td>
                            <td class="max-w-[200px]">
                                <p class="line-clamp-2 text-sm text-ink-soft"><?= Helper::e($d['campaign_title'] ?? '-') ?></p>
                            </td>
                            <td><span class="badge-muted"><?= Helper::e(str_replace('_', ' ', ucfirst($d['assistance_type']))) ?></span></td>
                            <td class="whitespace-nowrap text-sm font-semibold text-warning"><?= Helper::rupiah($d['amount']) ?></td>
                            <td class="whitespace-nowrap text-sm text-ink-soft">
                                <?= (int) $d['recipient_count'] ?> orang
                                <?php if (!empty($d['beneficiary_name'])): ?>
                                    <p class="text-[11px] text-ink-soft"><?= Helper::e($d['beneficiary_name']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap text-xs text-ink-soft"><?= Helper::tanggal($d['disbursement_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-ink-soft">Belum ada data penyaluran.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
