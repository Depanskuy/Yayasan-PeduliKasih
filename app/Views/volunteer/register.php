<?php
// app/Views/volunteer/register.php
use App\Core\Helper;

$event   = $event ?? [];
$already = $already ?? false;
$user    = $user ?? null;
?>
<section class="py-10">
    <div class="container-page max-w-3xl">
        <a href="<?= Helper::url('volunteer/events') ?>" class="btn-link mb-6">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke kegiatan
        </a>

        <div class="card overflow-hidden">
            <div class="border-b border-line bg-surface/60 p-6">
                <span class="badge-accent">Pendaftaran Relawan</span>
                <h1 class="mt-3 text-2xl font-semibold text-ink"><?= Helper::e($event['title'] ?? '') ?></h1>
                <div class="mt-3 flex flex-wrap gap-4 text-sm text-ink-soft">
                    <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="h-4 w-4 text-accent"></i> <?= Helper::tanggal($event['event_date'] ?? null) ?></span>
                    <span class="flex items-center gap-1.5"><i data-lucide="clock" class="h-4 w-4 text-accent"></i> <?= Helper::e($event['event_time'] ?? '') ?></span>
                    <span class="flex items-center gap-1.5"><i data-lucide="map-pin" class="h-4 w-4 text-accent"></i> <?= Helper::e($event['location'] ?? '') ?></span>
                </div>
            </div>

            <div class="p-6">
                <?php if ($already): ?>
                    <div class="alert-success">
                        <i data-lucide="check-circle-2" class="h-5 w-5"></i>
                        <div>Anda sudah terdaftar pada kegiatan ini. Admin akan meninjau pendaftaran Anda.</div>
                    </div>
                    <a href="<?= Helper::url('volunteer/events') ?>" class="btn-outline mt-6">Kembali</a>
                <?php else: ?>
                    <form action="<?= Helper::url('volunteer/events/' . ($event['id'] ?? '') . '/register') ?>" method="post" class="space-y-4">
                        <?= Helper::csrfField() ?>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="label">Nama</label>
                                <input class="input" value="<?= Helper::e($user['name'] ?? '') ?>" disabled>
                            </div>
                            <div>
                                <label class="label">Email</label>
                                <input class="input" value="<?= Helper::e($user['email'] ?? '') ?>" disabled>
                            </div>
                        </div>

                        <div>
                            <label class="label" for="skills">Keahlian / Keterampilan (opsional)</label>
                            <input id="skills" name="skills" class="input" placeholder="Contoh: P3K, memasak, dokumentasi"
                                   value="<?= Helper::e(Helper::old('skills')) ?>">
                        </div>

                        <div>
                            <label class="label" for="motivation">Motivasi Bergabung</label>
                            <textarea id="motivation" name="motivation" rows="4" class="textarea" required
                                      placeholder="Ceritakan alasan Anda ingin bergabung..."><?= Helper::e(Helper::old('motivation')) ?></textarea>
                            <p class="mt-1 text-[11px] text-ink-soft">Minimal 10 karakter.</p>
                        </div>

                        <button type="submit" class="btn-primary w-full">
                            <i data-lucide="send" class="h-4 w-4"></i> Kirim Pendaftaran
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
