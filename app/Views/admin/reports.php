<?php
// app/Views/admin/reports.php
use App\Core\Helper;

$summary  = $summary ?? ['income' => 0, 'expense' => 0, 'balance' => 0, 'pending' => 0];
$donations = $donations ?? [];
$disburse  = $disburse ?? [];

$cards = [
    ['label' => 'Total Pemasukan', 'value' => $summary['income'], 'icon' => 'arrow-down-left', 'tone' => 'text-success', 'bg' => 'bg-success/15'],
    ['label' => 'Total Penyaluran', 'value' => $summary['expense'], 'icon' => 'arrow-up-right', 'tone' => 'text-warning', 'bg' => 'bg-warning/15'],
    ['label' => 'Saldo', 'value' => $summary['balance'], 'icon' => 'wallet', 'tone' => 'text-accent', 'bg' => 'bg-accent/15'],
    ['label' => 'Donasi Pending', 'value' => $summary['pending'], 'icon' => 'clock', 'tone' => 'text-danger', 'bg' => 'bg-danger/15'],
];
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <p class="text-sm text-ink-soft">Ringkasan arus kas yayasan dari donasi terverifikasi dan penyaluran dana.</p>
        <a href="<?= Helper::url('admin/reports/download') ?>" class="btn-outline btn-sm">
            <i data-lucide="download" class="h-4 w-4"></i> Ekspor CSV
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <?php foreach ($cards as $card): ?>
            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <span class="stat-label"><?= $card['label'] ?></span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg <?= $card['bg'] ?> <?= $card['tone'] ?>">
                        <i data-lucide="<?= $card['icon'] ?>" class="h-4 w-4"></i>
                    </span>
                </div>
                <p class="stat-value mt-3 <?= $card['tone'] ?>"><?= Helper::rupiah($card['value']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="card">
            <div class="border-b border-line px-5 py-4">
                <h2 class="text-sm font-semibold text-ink">Donasi per Bulan</h2>
            </div>
            <div class="table-wrap border-0">
                <table class="table">
                    <thead>
                        <tr><th>Bulan</th><th>Transaksi</th><th class="text-right">Total</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($donations): ?>
                            <?php foreach ($donations as $row): ?>
                                <tr>
                                    <td class="text-sm text-ink"><?= Helper::e($row['month']) ?></td>
                                    <td class="text-sm text-ink-soft"><?= (int) $row['count'] ?></td>
                                    <td class="text-right text-sm font-semibold text-success"><?= Helper::rupiah($row['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-ink-soft">Belum ada donasi terverifikasi.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="border-b border-line px-5 py-4">
                <h2 class="text-sm font-semibold text-ink">Penyaluran per Kampanye</h2>
            </div>
            <div class="table-wrap border-0">
                <table class="table">
                    <thead>
                        <tr><th>Kampanye</th><th>Penyaluran</th><th class="text-right">Total</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($disburse): ?>
                            <?php foreach ($disburse as $row): ?>
                                <tr>
                                    <td class="max-w-[240px]"><p class="line-clamp-2 text-sm text-ink"><?= Helper::e($row['title']) ?></p></td>
                                    <td class="text-sm text-ink-soft"><?= (int) $row['count'] ?>x</td>
                                    <td class="text-right text-sm font-semibold text-warning"><?= Helper::rupiah($row['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-ink-soft">Belum ada penyaluran.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-pad">
        <div class="flex items-center gap-2 text-sm text-ink-soft">
            <i data-lucide="info" class="h-4 w-4 text-accent"></i>
            Saldo dihitung dari total donasi terverifikasi dikurangi total penyaluran yang tercatat.
        </div>
    </div>
</div>
