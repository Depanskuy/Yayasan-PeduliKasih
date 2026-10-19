<?php
// app/Views/campaigns/show.php
use App\Core\Helper;

$campaign  = $campaign ?? [];
$donations = $donations ?? [];
$updates   = $updates ?? [];
$others    = $others ?? [];
$banner    = Helper::media($campaign['banner_image'] ?? null, 'campaigns');
$percent   = Helper::percent($campaign['collected_amount'] ?? 0, $campaign['target_amount'] ?? 0);
$userName  = $_SESSION['user_name'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';
$donorCount = count($donations);

$methods = [
    'bca'          => 'Transfer Bank BCA',
    'mandiri'      => 'Transfer Bank Mandiri',
    'bri'          => 'Transfer Bank BRI',
    'gopay'        => 'GoPay',
    'ovo'          => 'OVO',
    'dana'         => 'DANA',
    'qris'         => 'QRIS',
    'cash_offline' => 'Tunai / Offline',
];
?>
<!-- Breadcrumb -->
<div class="border-b border-line bg-surface/40">
    <div class="container-page flex items-center gap-2 py-3 text-xs text-ink-soft">
        <a href="<?= Helper::url('') ?>" class="hover:text-accent">Beranda</a>
        <i data-lucide="chevron-right" class="h-3 w-3"></i>
        <a href="<?= Helper::url('campaigns') ?>" class="hover:text-accent">Kampanye</a>
        <i data-lucide="chevron-right" class="h-3 w-3"></i>
        <span class="truncate text-ink"><?= Helper::e($campaign['title'] ?? '') ?></span>
    </div>
</div>

<section class="py-8 sm:py-10">
    <div class="container-page grid gap-8 lg:grid-cols-[1.6fr_1fr] lg:items-start">

        <!-- Konten utama -->
        <div class="space-y-6">
            <div class="card overflow-hidden">
                <div class="relative h-56 sm:h-72">
                    <?php if ($banner): ?>
                        <img src="<?= Helper::e($banner) ?>" alt="<?= Helper::e($campaign['title'] ?? '') ?>" class="h-full w-full object-cover">
                    <?php else: ?>
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#ECFDF5] via-[#D1FAE5] to-[#F0FDF9]">
                            <i data-lucide="heart-handshake" class="h-16 w-16 text-accent/40"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute left-4 top-4 flex gap-2">
                        <span class="badge-accent shadow"><?= Helper::e($campaign['category_name'] ?? 'Sosial') ?></span>
                        <span class="badge-success shadow">
                            <i data-lucide="circle" class="h-2 w-2 fill-current"></i>
                            <?= Helper::e(ucfirst($campaign['status'] ?? 'active')) ?>
                        </span>
                    </div>
                </div>

                <div class="p-5 sm:p-7">
                    <h1 class="text-2xl font-semibold leading-snug text-ink sm:text-3xl">
                        <?= Helper::e($campaign['title'] ?? '') ?>
                    </h1>
                    <p class="mt-3 text-sm leading-relaxed text-ink-soft">
                        <?= Helper::e($campaign['short_description'] ?? '') ?>
                    </p>

                    <!-- Progress -->
                    <div class="mt-6 rounded-xl border border-line bg-surface p-4">
                        <div class="flex flex-wrap items-end justify-between gap-3">
                            <div>
                                <p class="text-xs text-ink-soft">Terkumpul</p>
                                <p class="text-2xl font-semibold text-accent"><?= Helper::rupiah($campaign['collected_amount'] ?? 0) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-ink-soft">Target</p>
                                <p class="text-lg font-semibold text-ink"><?= Helper::rupiah($campaign['target_amount'] ?? 0) ?></p>
                            </div>
                        </div>
                        <div class="progress mt-3 h-2.5">
                            <div class="progress-bar" style="width: <?= $percent ?>%"></div>
                        </div>
                        <div class="mt-2 flex items-center justify-between text-xs text-ink-soft">
                            <span><?= $percent ?>% tercapai</span>
                            <span><?= $donorCount ?> donatur terbaru</span>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <div class="rounded-lg border border-line bg-surface p-3">
                            <p class="text-[11px] uppercase tracking-wide text-ink-soft">Mulai</p>
                            <p class="mt-1 text-sm text-ink"><?= Helper::tanggal($campaign['start_date'] ?? null) ?></p>
                        </div>
                        <div class="rounded-lg border border-line bg-surface p-3">
                            <p class="text-[11px] uppercase tracking-wide text-ink-soft">Berakhir</p>
                            <p class="mt-1 text-sm text-ink"><?= Helper::tanggal($campaign['end_date'] ?? null) ?></p>
                        </div>
                        <div class="col-span-2 rounded-lg border border-line bg-surface p-3 sm:col-span-1">
                            <p class="text-[11px] uppercase tracking-wide text-ink-soft">Kategori</p>
                            <p class="mt-1 text-sm text-ink"><?= Helper::e($campaign['category_name'] ?? '-') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cerita -->
            <div class="card card-pad">
                <h2 class="text-lg font-semibold text-ink">Cerita Kampanye</h2>
                <div class="prose mt-4 max-w-none text-sm leading-relaxed text-ink-soft">
                    <?= nl2br(Helper::e($campaign['story'] ?? '')) ?>
                </div>
            </div>

            <!-- Kabar Kampanye -->
            <?php if ($updates): ?>
                <div class="card card-pad">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-ink">Kabar Terbaru</h2>
                        <span class="badge-accent"><?= count($updates) ?> kabar</span>
                    </div>

                    <div class="mt-4 space-y-4">
                        <?php foreach ($updates as $update):
                            $updateImage = Helper::media($update['image'] ?? null, 'updates');
                        ?>
                            <div class="rounded-xl border border-line bg-surface p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h3 class="text-sm font-semibold text-ink"><?= Helper::e($update['title']) ?></h3>
                                    <span class="text-[11px] text-ink-soft"><?= Helper::tanggal($update['created_at'], true) ?></span>
                                </div>
                                <?php if ($updateImage): ?>
                                    <img src="<?= Helper::e($updateImage) ?>" alt="<?= Helper::e($update['title']) ?>"
                                         class="mt-3 h-48 w-full rounded-lg border border-line object-cover">
                                <?php endif; ?>
                                <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-ink-soft"><?= Helper::e($update['content']) ?></p>
                                <p class="mt-2 text-[11px] text-ink-soft">Oleh <?= Helper::e($update['poster_name'] ?? 'Admin') ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Donatur -->
            <div class="card card-pad">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-ink">Donatur Terbaru</h2>
                    <span class="badge-muted"><?= $donorCount ?> terbaru</span>
                </div>

                <?php if ($donations): ?>
                    <ul class="mt-4 divide-y divide-line">
                        <?php foreach ($donations as $donation): ?>
                            <li class="flex items-start gap-3 py-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-accent/15 text-xs font-semibold text-accent">
                                    <?= Helper::e(Helper::initials($donation['donor_name'])) ?>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <p class="text-sm font-medium text-ink">
                                            <?= Helper::e($donation['donor_name']) ?>
                                            <?php if (!empty($donation['is_anonymous'])): ?>
                                                <span class="badge-muted ml-1">Anonim</span>
                                            <?php endif; ?>
                                        </p>
                                        <span class="text-sm font-semibold text-success"><?= Helper::rupiah($donation['amount']) ?></span>
                                    </div>
                                    <?php if (!empty($donation['doa_message'])): ?>
                                        <p class="mt-1 text-xs italic text-ink-soft">“<?= Helper::e($donation['doa_message']) ?>”</p>
                                    <?php endif; ?>
                                    <p class="mt-0.5 text-[11px] text-ink-soft"><?= Helper::timeAgo($donation['created_at']) ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="mt-4 text-sm text-ink-soft">Jadilah donatur pertama untuk kampanye ini.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar donasi -->
        <aside class="lg:sticky lg:top-20">
            <div class="card card-pad">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent/15 text-accent">
                        <i data-lucide="hand-heart" class="h-4 w-4"></i>
                    </span>
                    <h2 class="text-lg font-semibold text-ink">Donasi Sekarang</h2>
                </div>

                <form action="<?= Helper::url('campaigns/' . ($campaign['id'] ?? '') . '/donate') ?>" method="post"
                      enctype="multipart/form-data" class="mt-5 space-y-4" data-loading>
                    <?= Helper::csrfField() ?>

                    <div>
                        <label class="label" for="amount">Nominal Donasi</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-ink-soft">Rp</span>
                            <input id="amount" name="amount" class="input pl-10" inputmode="numeric" data-currency
                                   placeholder="10.000" required value="<?= Helper::e(Helper::old('amount')) ?>">
                        </div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <?php foreach ([50000, 100000, 250000, 500000] as $preset): ?>
                                <button type="button" class="btn-outline btn-sm" data-donation-amount="<?= $preset ?>">
                                    <?= Helper::rupiah($preset, true) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <label class="label" for="donor_name">Nama Donatur</label>
                        <input id="donor_name" name="donor_name" class="input" required
                               placeholder="Nama lengkap" value="<?= Helper::e(Helper::old('donor_name', $userName)) ?>">
                    </div>

                    <div>
                        <label class="label" for="donor_email">Email</label>
                        <input id="donor_email" name="donor_email" type="email" class="input" required
                               placeholder="nama@email.com" value="<?= Helper::e(Helper::old('donor_email', $userEmail)) ?>">
                    </div>

                    <div>
                        <label class="label" for="donor_phone">No. HP (opsional)</label>
                        <input id="donor_phone" name="donor_phone" class="input" placeholder="08xxxxxxxxxx"
                               value="<?= Helper::e(Helper::old('donor_phone')) ?>">
                    </div>

                    <div>
                        <label class="label" for="payment_method">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" class="select" required>
                            <?php foreach ($methods as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="label" for="doa_message">Doa / Pesan (opsional)</label>
                        <textarea id="doa_message" name="doa_message" rows="3" class="textarea"
                                  placeholder="Tulis doa atau pesan kebaikan..."><?= Helper::e(Helper::old('doa_message')) ?></textarea>
                    </div>

                    <div>
                        <label class="label" for="payment_proof">Bukti Transfer (opsional)</label>
                        <input id="payment_proof" name="payment_proof" type="file" accept="image/*,.pdf"
                               class="input file:mr-3 file:rounded-md file:border-0 file:bg-accent/15 file:px-3 file:py-1 file:text-xs file:text-accent">
                        <p class="mt-1 text-[11px] text-ink-soft">JPG, PNG, WEBP, atau PDF. Maks 5 MB.</p>
                    </div>

                    <label class="uv-check">
                        <input type="checkbox" name="is_anonymous" value="1">
                        <span class="uv-check__box" aria-hidden="true">
                            <svg viewBox="0 0 16 16">
                                <path d="M3 8.5l3.2 3.2L13 5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <span>Sembunyikan nama saya (anonim)</span>
                    </label>

                    <button type="submit" class="uv-btn w-full" data-loading-button>
                        <span class="uv-loader hidden" aria-hidden="true">
                            <span class="uv-loader__cell"></span>
                            <span class="uv-loader__cell"></span>
                            <span class="uv-loader__cell"></span>
                            <span class="uv-loader__cell"></span>
                            <span class="uv-loader__cell"></span>
                            <span class="uv-loader__cell"></span>
                            <span class="uv-loader__cell"></span>
                            <span class="uv-loader__cell"></span>
                            <span class="uv-loader__cell"></span>
                        </span>
                        <i data-lucide="heart" class="h-4 w-4" data-loading-hide></i>
                        <span data-loading-label>Salurkan Donasi</span>
                    </button>
                    <p class="text-center text-[11px] text-ink-soft">
                        Donasi akan diverifikasi oleh admin sebelum ditampilkan.
                    </p>
                </form>
            </div>

            <!-- Kampanye lain -->
            <?php if ($others): ?>
                <div class="card mt-6 p-5">
                    <h3 class="text-sm font-semibold text-ink">Kampanye Lainnya</h3>
                    <ul class="mt-3 space-y-3">
                        <?php foreach ($others as $other): ?>
                            <?php $op = Helper::percent($other['collected_amount'], $other['target_amount']); ?>
                            <li>
                                <a href="<?= Helper::url('campaigns/' . $other['id']) ?>" class="group block">
                                    <p class="line-clamp-2 text-sm text-ink group-hover:text-accent"><?= Helper::e($other['title']) ?></p>
                                    <div class="progress mt-1.5 h-1.5">
                                        <div class="progress-bar" style="width: <?= $op ?>%"></div>
                                    </div>
                                    <p class="mt-1 text-[11px] text-ink-soft"><?= $op ?>% · <?= Helper::rupiah($other['collected_amount'], true) ?></p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var amount = document.getElementById('amount');
    document.querySelectorAll('[data-donation-amount]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!amount) return;
            var value = parseInt(btn.getAttribute('data-donation-amount'), 10);
            amount.value = new Intl.NumberFormat('id-ID').format(value);
        });
    });
});
</script>
