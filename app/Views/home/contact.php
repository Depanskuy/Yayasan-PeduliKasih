<?php
// app/Views/home/contact.php
use App\Core\Helper;
$settings = $settings ?? [];
?>
<section class="border-b border-line bg-surface/40 py-14">
    <div class="container-page">
        <span class="text-xs font-semibold uppercase tracking-widest text-accent">Kontak</span>
        <h1 class="mt-2 text-3xl font-semibold text-ink sm:text-4xl">Hubungi Kami</h1>
        <p class="mt-3 max-w-2xl text-sm text-ink-soft">
            Punya pertanyaan, ingin menjadi relawan, atau ingin berkolaborasi? Tim kami siap membantu.
        </p>
    </div>
</section>

<section class="section">
    <div class="container-page grid gap-8 lg:grid-cols-2">
        <div class="space-y-4">
            <?php
            $contacts = [
                ['icon' => 'map-pin', 'label' => 'Alamat', 'value' => $settings['org_address'] ?? '-'],
                ['icon' => 'phone', 'label' => 'Telepon', 'value' => $settings['org_phone'] ?? '-'],
                ['icon' => 'mail', 'label' => 'Email', 'value' => $settings['org_email'] ?? '-'],
                ['icon' => 'instagram', 'label' => 'Instagram', 'value' => $settings['social_instagram'] ?? '-'],
                ['icon' => 'facebook', 'label' => 'Facebook', 'value' => $settings['social_facebook'] ?? '-'],
                ['icon' => 'youtube', 'label' => 'YouTube', 'value' => $settings['social_youtube'] ?? '-'],
            ];
            foreach ($contacts as $c): ?>
                <div class="card flex items-start gap-4 p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-accent/15 text-accent">
                        <i data-lucide="<?= $c['icon'] ?>" class="h-4 w-4"></i>
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-ink-soft"><?= $c['label'] ?></p>
                        <p class="mt-0.5 text-sm text-ink"><?= Helper::e($c['value']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="card card-pad">
            <h2 class="text-lg font-semibold text-ink">Kirim Pesan</h2>
            <p class="mt-1 text-sm text-ink-soft">Formulir ini hanya contoh tampilan. Hubungi kami via email/telepon untuk respon tercepat.</p>
            <form class="mt-5 space-y-4" onsubmit="event.preventDefault(); alert('Terima kasih! Pesan Anda telah dicatat (demo).');">
                <div>
                    <label class="label" for="c-name">Nama</label>
                    <input id="c-name" class="input" placeholder="Nama lengkap" required>
                </div>
                <div>
                    <label class="label" for="c-email">Email</label>
                    <input id="c-email" type="email" class="input" placeholder="nama@email.com" required>
                </div>
                <div>
                    <label class="label" for="c-message">Pesan</label>
                    <textarea id="c-message" rows="4" class="textarea" placeholder="Tulis pesan Anda..." required></textarea>
                </div>
                <button type="submit" class="btn-primary w-full">
                    <i data-lucide="send" class="h-4 w-4"></i> Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</section>
