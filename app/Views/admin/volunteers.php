<?php
// app/Views/admin/volunteers.php
use App\Core\Helper;

$events        = $events ?? [];
$registrations = $registrations ?? [];

$statusBadge = [
    'pending'  => 'badge-warning',
    'approved' => 'badge-success',
    'rejected' => 'badge-danger',
    'attended' => 'badge-info',
];
?>
<div class="space-y-6">
    <!-- Kegiatan -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <?php if ($events): ?>
            <?php foreach ($events as $event): ?>
                <div class="card p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl border border-line bg-surface">
                            <span class="text-base font-semibold leading-none text-accent"><?= date('d', strtotime($event['event_date'])) ?></span>
                            <span class="mt-0.5 text-[10px] uppercase text-ink-soft"><?= date('M', strtotime($event['event_date'])) ?></span>
                        </div>
                        <span class="<?= $event['status'] === 'open' ? 'badge-success' : 'badge-muted' ?>"><?= ucfirst($event['status']) ?></span>
                    </div>
                    <p class="mt-3 line-clamp-2 text-sm font-medium text-ink"><?= Helper::e($event['title']) ?></p>
                    <p class="mt-1 text-xs text-ink-soft"><?= Helper::e($event['location']) ?></p>
                    <div class="mt-3 flex items-center justify-between text-xs text-ink-soft">
                        <span><?= (int) ($event['registered_count'] ?? 0) ?> / <?= (int) $event['quota'] ?> relawan</span>
                        <span><?= Helper::tanggal($event['event_date']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card p-6 text-sm text-ink-soft sm:col-span-2 xl:col-span-3">Belum ada kegiatan relawan.</div>
        <?php endif; ?>
    </div>

    <!-- Pendaftaran -->
    <div class="card">
        <div class="border-b border-line px-5 py-4">
            <h2 class="text-sm font-semibold text-ink">Pendaftaran Relawan</h2>
        </div>
        <div class="table-wrap border-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Relawan</th>
                        <th>Kegiatan</th>
                        <th>Motivasi</th>
                        <th>Status</th>
                        <th class="text-right">Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($registrations): ?>
                        <?php foreach ($registrations as $r): ?>
                            <tr>
                                <td>
                                    <p class="text-sm font-medium text-ink"><?= Helper::e($r['user_name']) ?></p>
                                    <p class="text-xs text-ink-soft"><?= Helper::e($r['user_email']) ?></p>
                                </td>
                                <td class="max-w-[200px]">
                                    <p class="line-clamp-2 text-sm text-ink-soft"><?= Helper::e($r['event_title']) ?></p>
                                </td>
                                <td class="max-w-[240px]">
                                    <p class="line-clamp-2 text-xs text-ink-soft"><?= Helper::e($r['motivation']) ?></p>
                                    <?php if (!empty($r['skills'])): ?>
                                        <p class="mt-1 text-[11px] text-accent"><?= Helper::e($r['skills']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="<?= $statusBadge[$r['status']] ?? 'badge-muted' ?>"><?= ucfirst($r['status']) ?></span>
                                </td>
                                <td>
                                    <form method="post" action="<?= Helper::url('admin/volunteers/' . $r['id'] . '/status') ?>"
                                          class="flex justify-end gap-2">
                                        <?= Helper::csrfField() ?>
                                        <select name="status" class="select w-32 py-1.5 text-xs">
                                            <?php foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'attended' => 'Attended'] as $key => $label): ?>
                                                <option value="<?= $key ?>" <?= $r['status'] === $key ? 'selected' : '' ?>><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn-outline btn-sm">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-ink-soft">Belum ada pendaftaran relawan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
