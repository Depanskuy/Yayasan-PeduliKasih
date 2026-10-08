<div style="background:linear-gradient(135deg, #064e3b 0%, #059669 100%);color:#fff;padding:60px 0;">
  <div class="container text-center">
    <span class="badge badge-primary" style="background:rgba(255,255,255,0.2);color:#fff;margin-bottom:12px;font-size:0.85rem;">
      <i class="fa-solid fa-users" style="color:#fde047;"></i> RELAWAN PEDULI SESAMA
    </span>
    <h1 style="color:#fff;font-size:2.5rem;margin-bottom:12px;">Aksi Sosial & Relawan Lapangan</h1>
    <p style="color:rgba(255,255,255,0.9);max-width:650px;margin:0 auto 25px;">
      Sumbangkan waktu, tenaga, dan keahlian Anda untuk saudara kita yang membutuhkan. Bergabunglah dalam berbagai aksi kemanusiaan kami.
    </p>
    <?php if (!is_logged_in()): ?>
      <a href="<?= url('register') ?>" class="btn btn-cta">
        <i class="fa-solid fa-user-plus"></i> Daftar Sebagai Relawan
      </a>
    <?php endif; ?>
  </div>
</div>

<div class="container section">
  <div class="campaign-grid">
    <?php foreach ($events as $ev): ?>
      <?php 
        $img = !empty($ev['banner_image']) ? asset('uploads/' . $ev['banner_image']) : 'https://placehold.co/600x400/0284c7/ffffff?text=Relawan+Peduli';
      ?>
      <div class="campaign-card">
        <div class="campaign-thumb-wrap">
          <img src="<?= $img ?>" alt="<?= e($ev['title']) ?>" class="campaign-thumb">
          <span class="campaign-badge"><i class="fa-solid fa-calendar-check"></i> <?= format_date($ev['event_date']) ?></span>
        </div>
        <div class="campaign-body">
          <h4 class="campaign-title">
            <a href="<?= url('volunteer/events/' . $ev['slug']) ?>"><?= e($ev['title']) ?></a>
          </h4>
          <div style="font-size:0.85rem;color:var(--dark-muted);margin-bottom:12px;">
            <div><i class="fa-solid fa-location-dot" style="color:var(--primary);margin-right:6px;"></i> <?= e($ev['location']) ?></div>
            <div><i class="fa-regular fa-clock" style="color:var(--primary);margin-right:6px;"></i> <?= e($ev['event_time']) ?></div>
            <div><i class="fa-solid fa-users" style="color:var(--primary);margin-right:6px;"></i> Kuota: <?= (int)$ev['registered_count'] ?> / <?= (int)$ev['quota'] ?> Relawan</div>
          </div>
          <p class="campaign-desc"><?= e(substr($ev['description'], 0, 110)) ?>...</p>

          <a href="<?= url('volunteer/events/' . $ev['slug']) ?>" class="btn btn-primary btn-block" style="margin-top:auto;">
            <i class="fa-solid fa-hand-holding-hand"></i> Detail & Daftar Relawan
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
