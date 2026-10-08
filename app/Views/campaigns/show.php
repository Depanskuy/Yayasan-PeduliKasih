<?php
  $pct = $campaign['target_amount'] > 0 ? min(100, round(($campaign['collected_amount'] / $campaign['target_amount']) * 100)) : 0;
  $daysLeft = max(0, ceil((strtotime($campaign['end_date']) - time()) / 86400));
  $imgUrl = !empty($campaign['banner_image']) ? asset('uploads/' . $campaign['banner_image']) : 'https://placehold.co/800x450/059669/ffffff?text=Peduli+Kasih';
?>

<div style="background:#f1f5f9;padding:15px 0;font-size:0.85rem;border-bottom:1px solid var(--border);">
  <div class="container">
    <a href="<?= url('/') ?>">Beranda</a> &nbsp;/&nbsp; 
    <a href="<?= url('campaigns') ?>">Program Donasi</a> &nbsp;/&nbsp; 
    <span class="text-muted"><?= e($campaign['title']) ?></span>
  </div>
</div>

<div class="container section" style="padding-top:35px;">
  <div style="display:grid;grid-template-columns:1.35fr 0.8fr;gap:35px;align-items:start;">
    
    <!-- Left Column: Story & Details -->
    <div>
      <div style="border-radius:var(--radius);overflow:hidden;margin-bottom:25px;box-shadow:var(--shadow-sm);background:#cbd5e1;">
        <img src="<?= $imgUrl ?>" alt="<?= e($campaign['title']) ?>" style="width:100%;max-height:420px;object-fit:cover;">
      </div>

      <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
        <span class="badge badge-primary"><i class="fa-solid <?= e($campaign['category_icon'] ?? 'fa-tag') ?>"></i> <?= e($campaign['category_name']) ?></span>
        <span class="text-muted" style="font-size:0.85rem;"><i class="fa-regular fa-calendar"></i> Berakhir: <?= format_date($campaign['end_date']) ?></span>
      </div>

      <h1 style="font-size:1.85rem;margin-bottom:20px;line-height:1.3;"><?= e($campaign['title']) ?></h1>

      <div class="card" style="margin-bottom:30px;">
        <div class="card-header">
          <h4 class="card-title"><i class="fa-solid fa-book-open" style="color:var(--primary);margin-right:8px;"></i> Cerita & Latar Belakang</h4>
        </div>
        <div class="card-body" style="line-height:1.8;font-size:0.98rem;color:#334155;">
          <?= nl2br(e($campaign['story'])) ?>
        </div>
      </div>

      <!-- Kabar Perkembangan Section -->
      <div class="card" style="margin-bottom:30px;" id="updates">
        <div class="card-header">
          <h4 class="card-title"><i class="fa-solid fa-bullhorn" style="color:var(--accent);margin-right:8px;"></i> Kabar Perkembangan & Update Lapangan (<?= count($updates) ?>)</h4>
        </div>
        <div class="card-body">
          <?php if (empty($updates)): ?>
            <p class="text-muted" style="text-align:center;padding:20px;">Belum ada kabar perkembangan terbaru untuk campaign ini.</p>
          <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:20px;">
              <?php foreach ($updates as $up): ?>
                <div style="border-left:3px solid var(--primary);padding-left:18px;margin-bottom:10px;">
                  <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px;">
                    <h4 style="font-size:1.05rem;"><?= e($up['title']) ?></h4>
                    <span style="font-size:0.75rem;color:var(--dark-muted);"><i class="fa-regular fa-clock"></i> <?= format_date($up['created_at'], true) ?></span>
                  </div>
                  <p style="font-size:0.92rem;line-height:1.6;color:var(--dark-muted);"><?= nl2br(e($up['content'])) ?></p>
                  <div style="font-size:0.78rem;color:var(--primary);margin-top:6px;font-weight:600;">Ditulis oleh: <?= e($up['author_name']) ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Donatur & Pesan Doa -->
      <div class="card">
        <div class="card-header">
          <h4 class="card-title"><i class="fa-solid fa-praying-hands" style="color:var(--secondary);margin-right:8px;"></i> Doa & Dukungan Para Donatur (<?= count($donors) ?>)</h4>
        </div>
        <div class="card-body">
          <?php if (empty($donors)): ?>
            <p class="text-muted" style="text-align:center;padding:20px;">Jadilah orang pertama yang mengalirkan kebaikan untuk campaign ini.</p>
          <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:16px;">
              <?php foreach ($donors as $d): ?>
                <div style="padding:14px;background:#f8fafc;border-radius:var(--radius-sm);border:1px solid var(--border);">
                  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <strong style="color:var(--dark);"><?= $d['is_anonymous'] ? 'Hamba Allah' : e($d['donor_name']) ?></strong>
                    <span style="font-weight:700;color:var(--primary);"><?= format_rupiah($d['amount']) ?></span>
                  </div>
                  <?php if (!empty($d['doa_message'])): ?>
                    <p style="font-size:0.88rem;color:var(--dark-muted);font-style:italic;">"<?= e($d['doa_message']) ?>"</p>
                  <?php endif; ?>
                  <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px;"><?= format_date($d['created_at']) ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- Right Column: Sticky Donation Card -->
    <div>
      <div class="card" style="position:sticky;top:90px;box-shadow:var(--shadow-lg);border-radius:var(--radius-lg);">
        <div class="card-body" style="padding:30px;">
          <div style="font-size:0.85rem;color:var(--dark-muted);margin-bottom:4px;">Dana Terkumpul</div>
          <div style="font-size:1.85rem;font-weight:800;color:var(--primary);margin-bottom:12px;line-height:1.2;">
            <?= format_rupiah($campaign['collected_amount']) ?>
          </div>

          <div class="progress-track" style="height:10px;margin-bottom:12px;">
            <div class="progress-bar" style="width: <?= $pct ?>%;"></div>
          </div>

          <div style="display:flex;justify-content:space-between;font-size:0.88rem;margin-bottom:24px;color:var(--dark-muted);">
            <span>Target: <strong><?= format_rupiah($campaign['target_amount']) ?></strong></span>
            <span><strong><?= $pct ?>%</strong> tercapai</span>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:14px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin-bottom:24px;text-align:center;">
            <div>
              <div style="font-size:1.15rem;font-weight:800;color:var(--dark);"><?= count($donors) ?></div>
              <div style="font-size:0.75rem;color:var(--dark-muted);">Donatur</div>
            </div>
            <div>
              <div style="font-size:1.15rem;font-weight:800;color:var(--dark);"><?= $daysLeft ?></div>
              <div style="font-size:0.75rem;color:var(--dark-muted);">Hari Tersisa</div>
            </div>
          </div>

          <a href="<?= url('campaign/' . $campaign['slug'] . '/donate') ?>" class="btn btn-cta btn-block" style="font-size:1.1rem;padding:14px 20px;">
            <i class="fa-solid fa-heart"></i> DONASI SEKARANG
          </a>

          <div style="margin-top:20px;padding:15px;background:#f8fafc;border-radius:var(--radius-sm);font-size:0.8rem;color:var(--dark-muted);line-height:1.5;">
            <i class="fa-solid fa-shield-halved" style="color:var(--primary);margin-right:6px;"></i> 
            Donasi Anda 100% aman, tercatat resmi, dan berhak mendapatkan e-sertifikat serta kwitansi bukti donasi.
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
