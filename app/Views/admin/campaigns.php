<?php
// app/Views/admin/campaigns.php
use App\Core\Helper;

$campaigns    = $campaigns ?? [];
$statusCounts = $statusCounts ?? [];

$statusBadge = [
    'active'    => 'badge-success',
    'completed' => 'badge-info',
    'pending'   => 'badge-warning',
    'cancelled' => 'badge-danger',
];
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div class="flex flex-wrap gap-2">
            <?php foreach (['active' => 'Aktif', 'completed' => 'Selesai', 'pending' => 'Pending', 'cancelled' => 'Dibatalkan'] as $key => $label): ?>
                <span class="badge-muted"><?= $label ?>: <strong class="text-ink"><?= (int) ($statusCounts[$key] ?? 0) ?></strong></span>
            <?php endforeach; ?>
        </div>
        <a href="<?= Helper::url('admin/campaigns/create') ?>" class="btn-primary btn-sm">
            <i data-lucide="plus" class="h-4 w-4"></i> Tambah Kampanye
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Kampanye</th>
                    <th>Target</th>
                    <th>Terkumpul</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($campaigns): ?>
                    <?php foreach ($campaigns as $campaign): ?>
                        <?php $pct = Helper::percent($campaign['collected_amount'], $campaign['target_amount']); ?>
                        <tr>
                            <td>
                                <p class="text-sm font-medium text-ink"><?= Helper::e($campaign['title']) ?></p>
                                <p class="text-xs text-ink-soft">
                                    <?= Helper::e($campaign['category_name'] ?? '-') ?>
                                    <?php if (!empty($campaign['is_featured'])): ?>
                                        · <span class="text-accent">Unggulan</span>
                                    <?php endif; ?>
                                </p>
                            </td>
                            <td class="whitespace-nowrap text-sm text-ink-soft"><?= Helper::rupiah($campaign['target_amount'], true) ?></td>
                            <td class="min-w-[140px]">
                                <div class="flex items-center gap-2">
                                    <div class="progress flex-1">
                                        <div class="progress-bar" style="width: <?= $pct ?>%"></div>
                                    </div>
                                    <span class="text-xs text-ink-soft"><?= $pct ?>%</span>
                                </div>
                                <p class="mt-1 text-xs text-ink-soft"><?= Helper::rupiah($campaign['collected_amount'], true) ?></p>
                            </td>
                            <td><span class="<?= $statusBadge[$campaign['status']] ?? 'badge-muted' ?>"><?= ucfirst($campaign['status']) ?></span></td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="<?= Helper::url('campaigns/' . $campaign['id']) ?>" target="_blank" class="btn-ghost btn-sm" title="Lihat">
                                        <i data-lucide="external-link" class="h-4 w-4"></i>
                                    </a>
                                    <a href="<?= Helper::url('admin/campaigns/' . $campaign['id'] . '/edit') ?>" class="btn-outline btn-sm">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>
                                    <form method="post" action="<?= Helper::url('admin/campaigns/' . $campaign['id'] . '/delete') ?>"
                                          data-confirm="Hapus kampanye ini?" class="inline">
                                        <?= Helper::csrfField() ?>
                                        <button type="submit" class="btn-danger btn-sm" title="Hapus">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-ink-soft">Belum ada kampanye.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
