<?php
// app/Views/admin/dashboard.php
use App\Core\Helper;

$stats           = $stats ?? [];
$campaignStatus  = $campaignStatus ?? [];
$donationStatus  = $donationStatus ?? [];
$roleCounts      = $roleCounts ?? [];
$recentDonations = $recentDonations ?? [];
$recentDisburse  = $recentDisburse ?? [];
$campaignProgress = $campaignProgress ?? [];
$volunteerStats  = $volunteerStats ?? [];

$mainCards = [
    ['label' => 'Total Donasi', 'value' => Helper::rupiah($stats['donations'] ?? 0), 'icon' => 'wallet', 'tone' => 'text-success', 'bg' => 'bg-success/15'],
    ['label' => 'Kampanye', 'value' => $stats['campaigns'] ?? 0, 'icon' => 'megaphone', 'tone' => 'text-accent', 'bg' => 'bg-accent/15'],
    ['label' => 'Penerima Manfaat', 'value' => $stats['beneficiaries'] ?? 0, 'icon' => 'heart-handshake', 'tone' => 'text-[#2563EB]', 'bg' => 'bg-[#3B82F6]/10'],
    ['label' => 'Total Penyaluran', 'value' => Helper::rupiah($stats['disbursed'] ?? 0), 'icon' => 'send', 'tone' => 'text-warning', 'bg' => 'bg-warning/15'],
];
?>
<div class="space-y-6">

    <!-- Stat cards -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <?php foreach ($mainCards as $card): ?>
            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <span class="stat-label"><?= $card['label'] ?></span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg <?= $card['bg'] ?> <?= $card['tone'] ?>">
                        <i data-lucide="<?= $card['icon'] ?>" class="h-4 w-4"></i>
                    </span>
                </div>
                <p class="stat-value mt-3"><?= Helper::e($card['value']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Alert pending -->
    <?php if (!empty($stats['pendingDonations'])): ?>
        <a href="<?= Helper::url('admin/donations?status=pending') ?>"
           class="flex items-center gap-3 rounded-xl border border-warning/30 bg-warning/10 px-4 py-3 text-sm text-warning">
            <i data-lucide="clock" class="h-5 w-5"></i>
            <span class="flex-1"><?= (int) $stats['pendingDonations'] ?> donasi menunggu verifikasi.</span>
            <span class="inline-flex items-center gap-1 font-medium">Tinjau <i data-lucide="arrow-right" class="h-4 w-4"></i></span>
        </a>
    <?php endif; ?>

    <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
        <!-- Progress kampanye -->
        <div class="card">
            <div class="flex items-center justify-between border-b border-line px-5 py-4">
                <h2 class="text-sm font-semibold text-ink">Progress Kampanye</h2>
                <a href="<?= Helper::url('admin/campaigns') ?>" class="btn-link text-xs">Lihat semua</a>
            </div>
            <div class="divide-y divide-line">
                <?php if ($campaignProgress): ?>
                    <?php foreach ($campaignProgress as $campaign): ?>
                        <?php $pct = Helper::percent($campaign['collected_amount'], $campaign['target_amount']); ?>
                        <div class="px-5 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <p class="line-clamp-1 text-sm font-medium text-ink"><?= Helper::e($campaign['title']) ?></p>
                                <span class="shrink-0 text-xs font-semibold text-accent"><?= $pct ?>%</span>
                            </div>
                            <div class="progress mt-2">
                                <div class="progress-bar" style="width: <?= $pct ?>%"></div>
                            </div>
                            <div class="mt-1.5 flex justify-between text-xs text-ink-soft">
                                <span><?= Helper::rupiah($campaign['collected_amount'], true) ?></span>
                                <span>dari <?= Helper::rupiah($campaign['target_amount'], true) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="px-5 py-8 text-center text-sm text-ink-soft">Belum ada kampanye aktif.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Ringkasan status -->
        <div class="space-y-6">
            <div class="card">
                <div class="border-b border-line px-5 py-4">
                    <h2 class="text-sm font-semibold text-ink">Status Donasi</h2>
                </div>
                <div class="grid grid-cols-3 divide-x divide-line">
                    <?php
                    $ds = [
                        ['label' => 'Pending', 'key' => 'pending', 'tone' => 'text-warning'],
                        ['label' => 'Terverifikasi', 'key' => 'verified', 'tone' => 'text-success'],
                        ['label' => 'Ditolak', 'key' => 'rejected', 'tone' => 'text-danger'],
                    ];
                    foreach ($ds as $d): ?>
                        <div class="p-4 text-center">
                            <p class="text-xl font-semibold <?= $d['tone'] ?>"><?= (int) ($donationStatus[$d['key']] ?? 0) ?></p>
                            <p class="mt-0.5 text-[11px] text-ink-soft"><?= $d['label'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card">
                <div class="border-b border-line px-5 py-4">
                    <h2 class="text-sm font-semibold text-ink">Pengguna &amp; Relawan</h2>
                </div>
                <div class="grid grid-cols-2 gap-3 p-4">
                    <div class="rounded-lg border border-line bg-surface p-3">
                        <p class="text-xs text-ink-soft">Donatur</p>
                        <p class="mt-1 text-lg font-semibold text-ink"><?= (int) ($roleCounts['donatur'] ?? 0) ?></p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-3">
                        <p class="text-xs text-ink-soft">Relawan</p>
                        <p class="mt-1 text-lg font-semibold text-ink"><?= (int) ($roleCounts['volunteer'] ?? 0) ?></p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-3">
                        <p class="text-xs text-ink-soft">Kegiatan</p>
                        <p class="mt-1 text-lg font-semibold text-ink"><?= (int) ($volunteerStats['events'] ?? 0) ?></p>
                    </div>
                    <div class="rounded-lg border border-line bg-surface p-3">
                        <p class="text-xs text-ink-soft">Pendaftaran</p>
                        <p class="mt-1 text-lg font-semibold text-ink"><?= (int) ($volunteerStats['registrations'] ?? 0) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <!-- Donasi terbaru -->
        <div class="card">
            <div class="flex items-center justify-between border-b border-line px-5 py-4">
                <h2 class="text-sm font-semibold text-ink">Donasi Terbaru</h2>
                <a href="<?= Helper::url('admin/donations') ?>" class="btn-link text-xs">Lihat semua</a>
            </div>
            <div class="table-wrap border-0">
                <table class="table">
                    <thead>
                        <tr><th>Donatur</th><th>Nominal</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($recentDonations): ?>
                            <?php foreach ($recentDonations as $d): ?>
                                <tr>
                                    <td>
                                        <p class="text-sm text-ink"><?= Helper::e(Helper::excerpt($d['donor_name'], 24)) ?></p>
                                        <p class="text-[11px] text-ink-soft"><?= Helper::timeAgo($d['created_at']) ?></p>
                                    </td>
                                    <td class="whitespace-nowrap text-sm font-medium text-success"><?= Helper::rupiah($d['amount'], true) ?></td>
                                    <td>
                                        <?php
                                        $ps = $d['payment_status'];
                                        $cls = $ps === 'verified' ? 'badge-success' : ($ps === 'pending' ? 'badge-warning' : 'badge-danger');
                                        ?>
                                        <span class="<?= $cls ?>"><?= ucfirst($ps) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-ink-soft">Belum ada donasi.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Penyaluran terbaru -->
        <div class="card">
            <div class="flex items-center justify-between border-b border-line px-5 py-4">
                <h2 class="text-sm font-semibold text-ink">Penyaluran Terbaru</h2>
                <a href="<?= Helper::url('admin/disbursements') ?>" class="btn-link text-xs">Lihat semua</a>
            </div>
            <div class="table-wrap border-0">
                <table class="table">
                    <thead>
                        <tr><th>Program</th><th>Nominal</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($recentDisburse): ?>
                            <?php foreach ($recentDisburse as $d): ?>
                                <tr>
                                    <td class="text-sm text-ink"><?= Helper::e(Helper::excerpt($d['title'], 30)) ?></td>
                                    <td class="whitespace-nowrap text-sm font-medium text-warning"><?= Helper::rupiah($d['amount'], true) ?></td>
                                    <td class="whitespace-nowrap text-xs text-ink-soft"><?= Helper::tanggal($d['disbursement_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-ink-soft">Belum ada penyaluran.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
