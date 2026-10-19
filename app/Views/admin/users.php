<?php
// app/Views/admin/users.php
use App\Core\Helper;

$users      = $users ?? [];
$roleCounts = $roleCounts ?? [];

$roleBadge = [
    'superadmin' => 'badge-accent',
    'staff'      => 'badge-info',
    'donatur'    => 'badge-success',
    'volunteer'  => 'badge-warning',
];
?>
<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm text-ink-soft">Kelola akun dan hak akses pengguna.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php foreach (['superadmin', 'staff', 'donatur', 'volunteer'] as $role): ?>
                <span class="badge-muted"><?= ucfirst($role) ?>: <strong class="text-ink"><?= (int) ($roleCounts[$role] ?? 0) ?></strong></span>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent/15 text-xs font-semibold text-accent">
                                        <?= Helper::e(Helper::initials($user['name'])) ?>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-ink"><?= Helper::e($user['name']) ?></p>
                                        <p class="text-xs text-ink-soft"><?= Helper::e($user['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="<?= $roleBadge[$user['role']] ?? 'badge-muted' ?>"><?= ucfirst($user['role']) ?></span></td>
                            <td>
                                <span class="<?= $user['status'] === 'active' ? 'badge-success' : 'badge-muted' ?>">
                                    <?= $user['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td class="whitespace-nowrap text-xs text-ink-soft"><?= Helper::tanggal($user['created_at']) ?></td>
                            <td class="text-right">
                                <form method="post" action="<?= Helper::url('admin/users/' . $user['id'] . '/status') ?>" class="inline">
                                    <?= Helper::csrfField() ?>
                                    <input type="hidden" name="status" value="<?= $user['status'] === 'active' ? 'inactive' : 'active' ?>">
                                    <button type="submit" class="btn-outline btn-sm">
                                        <?= $user['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-ink-soft">Belum ada pengguna.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
