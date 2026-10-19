<?php
// app/Views/volunteer/reports.php
use App\Core\Helper;

$reports    = $reports ?? [];
$reportable = $reportable ?? [];

$statusBadge = [
    'submitted' => 'badge-warning',
    'approved'  => 'badge-success',
];
$statusLabel = [
    'submitted' => 'Menunggu Review',
    'approved'  => 'Disetujui',
];
?>
<section class="border-b border-line bg-surface/40 py-12">
    <div class="container-page">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <span class="text-xs font-semibold uppercase tracking-widest text-accent">Relawan</span>
                <h1 class="mt-2 text-3xl font-semibold text-ink sm:text-4xl">Laporan Saya</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-ink-soft">
                    Kirim dokumentasi kegiatan yang Anda ikuti dan pantau status review dari admin.
                </p>
            </div>
            <a href="<?= Helper::url('volunteer/events') ?>" class="btn-outline btn-sm">
                <i data-lucide="calendar-days" class="h-4 w-4"></i> Kegiatan Relawan
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-page space-y-8">
        <div>
            <h2 class="text-lg font-semibold text-ink">Kegiatan Siap Dilaporkan</h2>
            <p class="mt-1 text-sm text-ink-soft">Kegiatan yang pendaftarannya telah disetujui admin.</p>

            <?php if ($reportable): ?>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <?php foreach ($reportable as $row):
                        $event = $row['event'];
                        $hasReport = !empty($row['has_report']);
                    ?>
                        <div class="card card-pad flex flex-col">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-ink"><?= Helper::e($event['title']) ?></h3>
                                    <p class="mt-1 text-xs text-ink-soft">
                                        <?= Helper::tanggal($event['event_date']) ?>
                                        <?php if (!empty($event['event_time'])): ?> · <?= Helper::e($event['event_time']) ?><?php endif; ?>
                                    </p>
                                </div>
                                <?php if ($hasReport): ?>
                                    <span class="badge-success shrink-0"><i data-lucide="check" class="h-3 w-3"></i> Terkirim</span>
                                <?php else: ?>
                                    <span class="badge-accent shrink-0">Perlu laporan</span>
                                <?php endif; ?>
                            </div>

                            <p class="mt-3 flex items-start gap-2 text-sm text-ink-soft">
                                <i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                                <?= Helper::e($event['location']) ?>
                            </p>

                            <div class="mt-4 border-t border-line pt-3">
                                <?php if ($hasReport): ?>
                                    <button type="button" class="btn-ghost btn-sm w-full" disabled>Laporan sudah dikirim</button>
                                <?php else: ?>
                                    <a href="<?= Helper::url('volunteer/events/' . $event['id'] . '/report') ?>" class="btn-primary btn-sm w-full">
                                        <i data-lucide="file-plus" class="h-4 w-4"></i> Kirim Laporan
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card mt-4 p-8 text-center">
                    <i data-lucide="clipboard-x" class="mx-auto h-10 w-10 text-ink-soft"></i>
                    <p class="mt-3 text-sm font-medium text-ink">Belum ada kegiatan yang bisa dilaporkan</p>
                    <p class="mt-1 text-sm text-ink-soft">Daftar kegiatan relawan terlebih dahulu, lalu tunggu persetujuan admin.</p>
                    <a href="<?= Helper::url('volunteer/events') ?>" class="btn-outline btn-sm mt-4">Lihat Kegiatan</a>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-ink">Riwayat Laporan</h2>

            <?php if ($reports): ?>
                <div class="mt-4 space-y-4">
                    <?php foreach ($reports as $report):
                        $image = Helper::media($report['documentation_image'] ?? null, 'volunteers');
                    ?>
                        <div class="card card-pad">
                            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-sm font-semibold text-ink"><?= Helper::e($report['report_title']) ?></h3>
                                        <span class="<?= $statusBadge[$report['status']] ?? 'badge-muted' ?>">
                                            <?= Helper::e($statusLabel[$report['status']] ?? ucfirst($report['status'])) ?>
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs text-ink-soft">
                                        <?= Helper::e($report['event_title']) ?>
                                        · <?= Helper::tanggal($report['created_at'], true) ?>
                                        · <?= (float) $report['hours_spent'] ?> jam
                                    </p>
                                </div>
                                <?php if ($image): ?>
                                    <a href="<?= Helper::e($image) ?>" target="_blank" rel="noopener" class="shrink-0">
                                        <img src="<?= Helper::e($image) ?>" alt="Dokumentasi" class="h-16 w-24 rounded-lg border border-line object-cover">
                                    </a>
                                <?php endif; ?>
                            </div>

                            <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-ink-soft"><?= Helper::e($report['activity_summary']) ?></p>

                            <?php if (!empty($report['admin_feedback'])): ?>
                                <div class="mt-3 rounded-lg border border-accent/30 bg-accent/10 p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-accent">Catatan Admin</p>
                                    <p class="mt-1 text-sm text-ink-soft"><?= Helper::e($report['admin_feedback']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card mt-4 p-8 text-center">
                    <i data-lucide="file-text" class="mx-auto h-10 w-10 text-ink-soft"></i>
                    <p class="mt-3 text-sm font-medium text-ink">Belum ada laporan</p>
                    <p class="mt-1 text-sm text-ink-soft">Laporan yang Anda kirim akan tampil di sini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
