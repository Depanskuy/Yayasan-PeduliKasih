<?php
// app/Views/volunteer/report_form.php
use App\Core\Helper;

$event = $event ?? [];
$user  = $user ?? [];
?>
<div class="border-b border-line bg-surface/40">
    <div class="container-page flex items-center gap-2 py-3 text-xs text-ink-soft">
        <a href="<?= Helper::url('volunteer/events') ?>" class="hover:text-accent">Relawan</a>
        <i data-lucide="chevron-right" class="h-3 w-3"></i>
        <a href="<?= Helper::url('volunteer/reports') ?>" class="hover:text-accent">Laporan Saya</a>
        <i data-lucide="chevron-right" class="h-3 w-3"></i>
        <span class="text-ink">Kirim Laporan</span>
    </div>
</div>

<section class="section">
    <div class="container-page mx-auto max-w-3xl space-y-6">
        <div class="card card-pad">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-accent/15 text-accent">
                    <i data-lucide="calendar-check" class="h-5 w-5"></i>
                </span>
                <div>
                    <h1 class="text-lg font-semibold text-ink"><?= Helper::e($event['title']) ?></h1>
                    <p class="mt-1 text-sm text-ink-soft">
                        <?= Helper::tanggal($event['event_date']) ?>
                        <?php if (!empty($event['event_time'])): ?> · <?= Helper::e($event['event_time']) ?><?php endif; ?>
                        · <?= Helper::e($event['location']) ?>
                    </p>
                </div>
            </div>
        </div>

        <form action="<?= Helper::url('volunteer/events/' . $event['id'] . '/report') ?>" method="post"
              enctype="multipart/form-data" class="card card-pad space-y-5">
            <?= Helper::csrfField() ?>

            <div>
                <label class="label" for="report_title">Judul Laporan</label>
                <input id="report_title" name="report_title" class="input" required minlength="5"
                       placeholder="Contoh: Dokumentasi pembagian sembako"
                       value="<?= Helper::e(Helper::old('title')) ?>">
            </div>

            <div>
                <label class="label" for="activity_summary">Ringkasan Kegiatan</label>
                <textarea id="activity_summary" name="activity_summary" rows="6" class="textarea" required minlength="20"
                          placeholder="Ceritakan aktivitas, jumlah penerima manfaat, kendala, dan hal menarik selama kegiatan..."><?= Helper::e(Helper::old('summary')) ?></textarea>
                <p class="mt-1 text-[11px] text-ink-soft">Minimal 20 karakter.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="label" for="hours_spent">Durasi Kegiatan (jam)</label>
                    <input id="hours_spent" name="hours_spent" type="number" step="0.5" min="0.5" max="24" class="input"
                           required placeholder="4" value="<?= Helper::e(Helper::old('hours')) ?>">
                </div>
                <div>
                    <label class="label" for="documentation_image">Dokumentasi (opsional)</label>
                    <input id="documentation_image" name="documentation_image" type="file" accept="image/*"
                           class="input file:mr-3 file:rounded-md file:border-0 file:bg-accent/15 file:px-3 file:py-1 file:text-xs file:text-accent">
                    <p class="mt-1 text-[11px] text-ink-soft">JPG, PNG, atau WEBP. Maks 5 MB.</p>
                </div>
            </div>

            <div class="rounded-lg border border-line bg-surface p-3 text-xs text-ink-soft">
                Laporan akan direview admin. Anda dapat memantau statusnya pada halaman Laporan Saya.
            </div>

            <div class="flex justify-end gap-3 border-t border-line pt-5">
                <a href="<?= Helper::url('volunteer/reports') ?>" class="btn-ghost">Batal</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="send" class="h-4 w-4"></i> Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</section>
