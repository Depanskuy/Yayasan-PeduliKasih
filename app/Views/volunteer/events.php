<?php
// app/Views/volunteer/events.php
use App\Core\Helper;

$events     = $events ?? [];
$registered = $registered ?? [];
$isLoggedIn = !empty($_SESSION['user_id']);
?>
<section class="border-b border-line bg-surface/40 py-12">
    <div class="container-page">
        <span class="text-xs font-semibold uppercase tracking-widest text-accent">Relawan</span>
        <h1 class="mt-2 text-3xl font-semibold text-ink sm:text-4xl">Jadilah Bagian dari Perubahan</h1>
        <p class="mt-3 max-w-2xl text-sm text-ink-soft">
            Bergabunglah dalam kegiatan sosial kami. Sumbangkan waktu dan tenaga Anda untuk sesama.
        </p>
    </div>
</section>

<section class="section">
    <div class="container-page">
        <?php if ($events): ?>
            <div class="grid gap-6 md:grid-cols-2">
                <?php foreach ($events as $event):
                    $registeredCount = (int) ($event['registered_count'] ?? 0);
                    $quota = (int) ($event['quota'] ?? 0);
                    $isFull = $quota > 0 && $registeredCount >= $quota;
                    $isClosed = ($event['status'] ?? 'open') !== 'open';
                    $already = !empty($registered[(int) $event['id']]);
                    $deadlinePassed = strtotime($event['registration_deadline']) < strtotime(date('Y-m-d'));
                ?>
                    <article class="card card-pad flex flex-col">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl border border-line bg-surface">
                                    <span class="text-lg font-semibold leading-none text-accent"><?= date('d', strtotime($event['event_date'])) ?></span>
                                    <span class="mt-0.5 text-[10px] uppercase text-ink-soft"><?= date('M Y', strtotime($event['event_date'])) ?></span>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold leading-snug text-ink"><?= Helper::e($event['title']) ?></h2>
                                    <p class="mt-1 text-xs text-ink-soft"><?= Helper::e($event['event_time'] ?? '') ?></p>
                                </div>
                            </div>
                            <?php if ($already): ?>
                                <span class="badge-success shrink-0"><i data-lucide="check" class="h-3 w-3"></i> Terdaftar</span>
                            <?php elseif ($isClosed || $isFull || $deadlinePassed): ?>
                                <span class="badge-muted shrink-0">Ditutup</span>
                            <?php else: ?>
                                <span class="badge-success shrink-0">Aktif</span>
                            <?php endif; ?>
                        </div>

                        <p class="mt-4 line-clamp-3 text-sm leading-relaxed text-ink-soft">
                            <?= Helper::e($event['description']) ?>
                        </p>

                        <div class="mt-4 space-y-2 text-sm text-ink-soft">
                            <p class="flex items-start gap-2">
                                <i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                                <?= Helper::e($event['location']) ?>
                            </p>
                            <p class="flex items-center gap-2">
                                <i data-lucide="users" class="h-4 w-4 shrink-0 text-accent"></i>
                                <?= $registeredCount ?> / <?= $quota ?> relawan
                            </p>
                            <p class="flex items-center gap-2">
                                <i data-lucide="calendar-clock" class="h-4 w-4 shrink-0 text-accent"></i>
                                Batas daftar: <?= Helper::tanggal($event['registration_deadline']) ?>
                            </p>
                        </div>

                        <div class="mt-5 border-t border-line pt-4">
                            <?php if ($already): ?>
                                <a href="<?= Helper::url('volunteer/events/' . $event['id'] . '/register') ?>" class="btn-outline w-full btn-sm">Lihat Pendaftaran</a>
                            <?php elseif ($isClosed || $isFull || $deadlinePassed): ?>
                                <button type="button" class="btn-ghost w-full btn-sm" disabled>Pendaftaran ditutup</button>
                            <?php elseif ($isLoggedIn): ?>
                                <a href="<?= Helper::url('volunteer/events/' . $event['id'] . '/register') ?>" class="btn-primary w-full btn-sm">
                                    Daftar Relawan <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= Helper::url('login') ?>" class="btn-primary w-full btn-sm">Masuk untuk Mendaftar</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card p-12 text-center">
                <i data-lucide="calendar-x" class="mx-auto h-10 w-10 text-ink-soft"></i>
                <p class="mt-3 font-medium text-ink">Belum ada kegiatan relawan</p>
                <p class="mt-1 text-sm text-ink-soft">Silakan cek kembali nanti.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
