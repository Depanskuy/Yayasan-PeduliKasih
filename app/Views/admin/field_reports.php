<?php
// app/Views/admin/field_reports.php
use App\Core\Helper;

$reports = $reports ?? [];
$counts  = $counts ?? [];

$statusBadge = [
    'submitted' => 'badge-warning',
    'approved'  => 'badge-success',
];
$statusLabel = [
    'submitted' => 'Menunggu Review',
    'approved'  => 'Disetujui',
];
?>
<div class="space-y-6">
    <div class="flex flex-wrap items-center gap-2">
        <span class="badge-warning">Menunggu: <?= (int) ($counts['submitted'] ?? 0) ?></span>
        <span class="badge-success">Disetujui: <?= (int) ($counts['approved'] ?? 0) ?></span>
        <span class="badge-muted">Total: <?= count($reports) ?></span>
    </div>

    <?php if ($reports): ?>
        <div class="space-y-4">
            <?php foreach ($reports as $report):
                $image = Helper::media($report['documentation_image'] ?? null, 'volunteers');
            ?>
                <div class="card card-pad">
                    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-start">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-sm font-semibold text-ink"><?= Helper::e($report['report_title']) ?></h2>
                                <span class="<?= $statusBadge[$report['status']] ?? 'badge-muted' ?>">
                                    <?= Helper::e($statusLabel[$report['status']] ?? ucfirst($report['status'])) ?>
                                </span>
                            </div>

                            <p class="mt-1 text-xs text-ink-soft">
                                <span class="text-ink"><?= Helper::e($report['user_name']) ?></span>
                                (<?= Helper::e($report['user_email']) ?>)
                                · <?= Helper::e($report['event_title']) ?>
                                · <?= Helper::tanggal($report['created_at'], true) ?>
                                · <?= (float) $report['hours_spent'] ?> jam
                            </p>

                            <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-ink-soft"><?= Helper::e($report['activity_summary']) ?></p>

                            <?php if ($image): ?>
                                <a href="<?= Helper::e($image) ?>" target="_blank" rel="noopener" class="mt-3 inline-block">
                                    <img src="<?= Helper::e($image) ?>" alt="Dokumentasi" class="h-24 w-36 rounded-lg border border-line object-cover">
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($report['admin_feedback'])): ?>
                                <div class="mt-3 rounded-lg border border-accent/30 bg-accent/10 p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-accent">Catatan Admin Saat Ini</p>
                                    <p class="mt-1 text-sm text-ink-soft"><?= Helper::e($report['admin_feedback']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <form method="post" action="<?= Helper::url('admin/field-reports/' . $report['id'] . '/status') ?>"
                              class="w-full space-y-3 rounded-xl border border-line bg-surface p-4 lg:w-72">
                            <?= Helper::csrfField() ?>
                            <p class="text-xs font-semibold uppercase tracking-wide text-ink-soft">Review Laporan</p>

                            <div>
                                <label class="label text-xs" for="status-<?= (int) $report['id'] ?>">Status</label>
                                <select id="status-<?= (int) $report['id'] ?>" name="status" class="select">
                                    <option value="submitted" <?= $report['status'] === 'submitted' ? 'selected' : '' ?>>Menunggu Review</option>
                                    <option value="approved" <?= $report['status'] === 'approved' ? 'selected' : '' ?>>Disetujui</option>
                                </select>
                            </div>

                            <div>
                                <label class="label text-xs" for="feedback-<?= (int) $report['id'] ?>">Catatan Admin</label>
                                <textarea id="feedback-<?= (int) $report['id'] ?>" name="admin_feedback" rows="3" class="textarea"
                                          placeholder="Masukan untuk relawan..."><?= Helper::e($report['admin_feedback'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn-primary btn-sm w-full">
                                <i data-lucide="check" class="h-4 w-4"></i> Simpan Review
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card p-12 text-center">
            <i data-lucide="clipboard-list" class="mx-auto h-10 w-10 text-ink-soft"></i>
            <p class="mt-3 font-medium text-ink">Belum ada laporan relawan</p>
            <p class="mt-1 text-sm text-ink-soft">Laporan yang dikirim relawan akan tampil di sini untuk direview.</p>
        </div>
    <?php endif; ?>
</div>
