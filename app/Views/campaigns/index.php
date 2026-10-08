<div style="background:linear-gradient(135deg, #064e3b 0%, #059669 100%);color:#fff;padding:60px 0;">
  <div class="container text-center">
    <h1 style="color:#fff;font-size:2.5rem;margin-bottom:12px;">Program Donasi & Bantuan Sosial</h1>
    <p style="color:rgba(255,255,255,0.9);max-width:650px;margin:0 auto 30px;">
      Pilih program kebaikan yang ingin Anda dukung. Seluruh donasi yang terkumpul disalurkan 100% transparan dan terdokumentasi.
    </p>

    <!-- Search Form -->
    <form action="<?= url('campaigns') ?>" method="GET" style="max-width:560px;margin:0 auto;display:flex;gap:10px;">
      <input type="text" name="q" class="form-control" style="background:#fff;border-radius:50px;padding:12px 24px;" placeholder="Cari program donasi..." value="<?= e($searchQuery ?? '') ?>">
      <?php if (!empty($selectedCategory)): ?>
        <input type="hidden" name="category" value="<?= e($selectedCategory) ?>">
      <?php endif; ?>
      <button type="submit" class="btn btn-cta" style="border-radius:50px;padding:12px 24px;">
        <i class="fa-solid fa-magnifying-glass"></i> Cari
      </button>
    </form>
  </div>
</div>

<div class="container section">
  <!-- Category Filter Pills -->
  <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:35px;justify-content:center;">
    <a href="<?= url('campaigns') ?>" class="btn btn-sm <?= empty($selectedCategory) ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:50px;">
      Semua Kategori
    </a>
    <?php foreach ($categories as $cat): ?>
      <a href="<?= url('campaigns?category=' . $cat['id']) ?>" class="btn btn-sm <?= ($selectedCategory == $cat['id']) ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:50px;">
        <i class="fa-solid <?= e($cat['icon']) ?>"></i> <?= e($cat['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if (empty($campaigns)): ?>
    <div class="card text-center" style="padding:60px 20px;">
      <div style="font-size:3rem;color:#94a3b8;margin-bottom:15px;"><i class="fa-solid fa-heart-crack"></i></div>
      <h3>Tidak Ada Campaign Ditemukan</h3>
      <p class="text-muted" style="margin-bottom:20px;">Coba gunakan kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
      <a href="<?= url('campaigns') ?>" class="btn btn-outline" style="display:inline-flex;margin:0 auto;">Tampilkan Semua Program</a>
    </div>
  <?php else: ?>
    <div class="campaign-grid">
      <?php foreach ($campaigns as $c): ?>
        <?php 
          $pct = $c['target_amount'] > 0 ? min(100, round(($c['collected_amount'] / $c['target_amount']) * 100)) : 0;
          $imgUrl = !empty($c['banner_image']) ? asset('uploads/' . $c['banner_image']) : 'https://placehold.co/600x400/059669/ffffff?text=Peduli+Kasih';
        ?>
        <div class="campaign-card">
          <div class="campaign-thumb-wrap">
            <img src="<?= $imgUrl ?>" alt="<?= e($c['title']) ?>" class="campaign-thumb">
            <span class="campaign-badge"><i class="fa-solid <?= e($c['category_icon'] ?? 'fa-tag') ?>"></i> <?= e($c['category_name']) ?></span>
          </div>
          <div class="campaign-body">
            <h4 class="campaign-title">
              <a href="<?= url('campaign/detail/' . $c['slug']) ?>"><?= e($c['title']) ?></a>
            </h4>
            <p class="campaign-desc"><?= e($c['short_description']) ?></p>

            <div class="progress-container">
              <div class="progress-track">
                <div class="progress-bar" style="width: <?= $pct ?>%;"></div>
              </div>
              <div class="progress-info">
                <div>
                  <div style="font-size:0.75rem;color:var(--dark-muted);">Terkumpul:</div>
                  <div class="progress-collected"><?= format_rupiah($c['collected_amount']) ?></div>
                </div>
                <div style="text-align:right;">
                  <div style="font-size:0.75rem;color:var(--dark-muted);">Target:</div>
                  <div class="progress-target"><?= format_rupiah($c['target_amount']) ?></div>
                </div>
              </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:10px;">
              <a href="<?= url('campaign/detail/' . $c['slug']) ?>" class="btn btn-outline btn-sm" style="flex:1;">
                Detail
              </a>
              <a href="<?= url('campaign/' . $c['slug'] . '/donate') ?>" class="btn btn-cta btn-sm" style="flex:1;">
                DONASI
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
