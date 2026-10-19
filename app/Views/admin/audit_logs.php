<?php
// app/Views/admin/audit_logs.php
use App\Core\Helper;

$logs    = $logs ?? [];
$actions = $actions ?? [];
$filters = $filters ?? [];
$total   = $total ?? 0;

$badgeFor = static function (string $action): string {
    if (str_contains($action, 'DELETE') || str_contains($action, 'REJECT')) return 'badge-danger';
    if (str_contains($action, 'CREATE') || str_contains($action, 'REGISTER')) return 'badge-success';
    if (str_contains($action, 'UPDATE') || str_contains($action, 'VERIFY') || str_contains($action, 'STATUS')) return 'badge-info';
    return 'badge-muted';
};
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
        <div class="flex flex-wrap items-center gap-2">
            <span class="badge-accent">Total aktivitas: <?= (int) $total ?></span>
            <span class="badge-muted">Ditampilkan: <?= count($logs) ?></span>
        </div>

        <form method="get" action="<?= Helper::url('admin/audit-logs') ?>" class="flex flex-col gap-2 sm:flex-row">
            <div class="relative">
                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft"></i>
                <input type="search" name="q" class="input pl-9 sm:w-56" placeholder="Cari deskripsi / user..."
                       value="<?= Helper::e($filters['search'] ?? '') ?>">
            </div>
            <select name="action" class="select sm:w-52">
                <option value="">Semua aksi</option>
                <?php foreach ($actions as $row): ?>
                    <option value="<?= Helper::e($row['action']) ?>" <?= ($filters['action'] ?? '') === $row['action'] ? 'selected' : '' ?>>
                        <?= Helper::e($row['action']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn-outline btn-sm" type="submit">Filter</button>
            <?php if (!empty($filters['search']) || !empty($filters['action'])): ?>
                <a href="<?= Helper::url('admin/audit-logs') ?>" class="btn-ghost btn-sm">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($logs): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="whitespace-nowrap text-xs text-ink-soft"><?= Helper::tanggal($log['created_at'], true) ?></td>
                            <td>
                                <p class="text-sm text-ink"><?= Helper::e($log['user_name'] ?? 'Sistem / Tamu') ?></p>
                                <?php if (!empty($log['user_email'])): ?>
                                    <p class="text-xs text-ink-soft"><?= Helper::e($log['user_email']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td><span class="<?= $badgeFor($log['action']) ?>"><?= Helper::e($log['action']) ?></span></td>
                            <td class="max-w-[420px] text-sm text-ink-soft"><?= Helper::e($log['description']) ?></td>
                            <td class="whitespace-nowrap text-xs text-ink-soft"><?= Helper::e($log['ip_address'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-ink-soft">Tidak ada aktivitas pada filter ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
