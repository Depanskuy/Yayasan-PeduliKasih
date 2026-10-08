<!-- Hero Section -->
<section class="hero-section">
  <div class="container hero-grid">
    <div>
      <div class="hero-tag">
        <i class="fa-solid fa-heart" style="color:#f87171;"></i> Amanah, Terpercaya, & Berkelanjutan
      </div>
      <h1 class="hero-title">
        Menyalurkan Amanah, <span>Merajut Kasih</span> Bagi Sesama
      </h1>
      <p class="hero-desc">
        Bersama Yayasan Peduli Kasih Sesama, ulurkan tangan Anda untuk membantu ribuan anak yatim, lansia prasejahtera, beasiswa pelajar dhuafa, dan korban bencana di pelosok negeri.
      </p>
      <div class="hero-buttons">
        <a href="<?= url('campaigns') ?>" class="btn btn-cta">
          <i class="fa-solid fa-hand-holding-heart"></i> DONASI SEKARANG
        </a>
        <a href="<?= url('transparency') ?>" class="btn btn-outline-white">
          <i class="fa-solid fa-scale-balanced"></i> Transparansi Kas
        </a>
        <a href="<?= url('volunteer/events') ?>" class="btn btn-outline-white">
          <i class="fa-solid fa-users"></i> Gabung Relawan
        </a>
      </div>
    </div>

    <div>
      <div class="hero-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;">
          <span style="font-size:0.8rem;font-weight:700;color:var(--primary);text-transform:uppercase;">
            <i class="fa-solid fa-fire"></i> Campaign Mendesak
          </span>
          <span class="badge badge-verified">Terverifikasi</span>
        </div>
        <?php if (!empty($featuredCampaigns[0])): $feat = $featuredCampaigns[0]; ?>
          <h3 style="font-size:1.2rem;margin-bottom:12px;line-height:1.3;"><?= e($feat['title']) ?></h3>
          <p style="font-size:0.88rem;color:var(--dark-muted);margin-bottom:18px;line-height:1.5;">
            <?= e(substr($feat['short_description'], 0, 110)) ?>...
          </p>
          <?php 
            $pct = $feat['target_amount'] > 0 ? min(100, round(($feat['collected_amount'] / $feat['target_amount']) * 100)) : 0;
          ?>
          <div class="progress-container">
            <div class="progress-track">
              <div class="progress-bar" style="width: <?= $pct ?>%;"></div>
            </div>
            <div class="progress-info">
              <div>
                <div style="font-size:0.75rem;color:var(--dark-muted);">Terkumpul:</div>
                <div class="progress-collected"><?= format_rupiah($feat['collected_amount']) ?></div>
              </div>
              <div style="text-align:right;">
                <div style="font-size:0.75rem;color:var(--dark-muted);">Target Dana:</div>
                <div class="progress-target"><?= format_rupiah($feat['target_amount']) ?></div>
              </div>
            </div>
          </div>
          <a href="<?= url('campaign/' . $feat['slug'] . '/donate') ?>" class="btn btn-cta btn-block" style="margin-top:14px;">
            <i class="fa-solid fa-heart"></i> DONASI SEKARANG
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Stats Counter -->
<div class="container stats-section">
  <div class="stats-grid">
    <div class="stat-box">
      <div class="stat-icon green"><i class="fa-solid fa-hand-holding-dollar"></i></div>
      <div>
        <div class="stat-value"><?= format_rupiah($stats['totalDonations']) ?></div>
        <div class="stat-label">Total Donasi Terhimpun</div>
      </div>
    </div>

    <div class="stat-box">
      <div class="stat-icon amber"><i class="fa-solid fa-box-open"></i></div>
      <div>
        <div class="stat-value"><?= format_rupiah($stats['totalDisbursed']) ?></div>
        <div class="stat-label">Total Bantuan Disalurkan</div>
      </div>
    </div>

    <div class="stat-box">
      <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
      <div>
        <div class="stat-value"><?= number_format($stats['beneficiariesCount']) ?> Jiwa</div>
        <div class="stat-label">Penerima Manfaat / Mustahik</div>
      </div>
    </div>

    <div class="stat-box">
      <div class="stat-icon purple"><i class="fa-solid fa-heart"></i></div>
      <div>
        <div class="stat-value"><?= number_format($stats['donorCount']) ?> Kali</div>
        <div class="stat-label">Transaksi Kebaikan</div>
      </div>
    </div>
  </div>
</div>

<!-- Featured Campaigns Section -->
<section class="section" style="padding-top:20px;">
  <div class="container">
    <div class="section-header text-center">
      <div class="section-tag">PROGRAM UNGGULAN</div>
      <h2 class="section-title">Mari Bergandengan Tangan Membantu Mereka</h2>
      <p class="section-subtitle">Setiap rupiah donasi Anda disalurkan secara amanah, diaudit berkala, dan dipublikasikan terbuka.</p>
    </div>

    <div class="campaign-grid">
      <?php foreach ($recentCampaigns as $c): ?>
        <?php 
          $pct = $c['target_amount'] > 0 ? min(100, round(($c['collected_amount'] / $c['target_amount']) * 100)) : 0;
          $imgUrl = !empty($c['banner_image']) ? asset('uploads/' . $c['banner_image']) : 'https://placehold.co/600x400/059669/ffffff?text=Peduli+Kasih';
        ?>
        <div class="campaign-card">
          <div class="campaign-thumb-wrap">
            <img src="<?= $imgUrl ?>" alt="<?= e($c['title']) ?>" class="campaign-thumb">
            <span class="campaign-badge"><i class="fa-solid fa-tag"></i> <?= e($c['category_name']) ?></span>
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

    <div class="text-center" style="margin-top:40px;">
      <a href="<?= url('campaigns') ?>" class="btn btn-primary">
        <i class="fa-solid fa-arrow-right"></i> Lihat Seluruh Program Donasi
      </a>
    </div>
  </div>
</section>

<!-- Program Sosial (Pemisahan Program vs Campaign) -->
<section class="section" style="background:#f1f5f9;">
  <div class="container">
    <div class="section-header text-center">
      <div class="section-tag">PILAR UTAMA</div>
      <h2 class="section-title">5 Program Sosial Berkelanjutan Yayasan</h2>
      <p class="section-subtitle">Penggalangan dana dialirkan ke program kerja nyata untuk memutus mata rantai kesulitan mustahik.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:24px;">
      <div class="card" style="padding:24px;text-align:center;border-radius:var(--radius);">
        <div style="width:60px;height:60px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.6rem;">
          <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <h4 style="margin-bottom:10px;">Pendidikan & Beasiswa</h4>
        <p style="font-size:0.88rem;color:var(--dark-muted);">Beasiswa SPP, perlengkapan sekolah, dan bimbingan belajar gratis untuk anak yatim piatu dhuafa.</p>
      </div>

      <div class="card" style="padding:24px;text-align:center;border-radius:var(--radius);">
        <div style="width:60px;height:60px;border-radius:50%;background:#e0f2fe;color:var(--secondary);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.6rem;">
          <i class="fa-solid fa-home"></i>
        </div>
        <h4 style="margin-bottom:10px;">Santunan Panti Asuhan</h4>
        <p style="font-size:0.88rem;color:var(--dark-muted);">Renovasi sarana tempat tinggal, gizi sehat, dan kebutuhan pengasuhan harian anak asuh.</p>
      </div>

      <div class="card" style="padding:24px;text-align:center;border-radius:var(--radius);">
        <div style="width:60px;height:60px;border-radius:50%;background:var(--accent-light);color:var(--accent);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.6rem;">
          <i class="fa-solid fa-bowl-rice"></i>
        </div>
        <h4 style="margin-bottom:10px;">Pangan & Sembako</h4>
        <p style="font-size:0.88rem;color:var(--dark-muted);">Beras berkah, minyak, telur, dan lauk nutrisi untuk keluarga prasejahtera dan janda lansia.</p>
      </div>

      <div class="card" style="padding:24px;text-align:center;border-radius:var(--radius);">
        <div style="width:60px;height:60px;border-radius:50%;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.6rem;">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h4 style="margin-bottom:10px;">Tanggap Bencana</h4>
        <p style="font-size:0.88rem;color:var(--dark-muted);">Respon cepat logistik darurat, dapur umum, selimut, dan trauma healing korban musibah.</p>
      </div>

      <div class="card" style="padding:24px;text-align:center;border-radius:var(--radius);">
        <div style="width:60px;height:60px;border-radius:50%;background:#f3e8ff;color:#9333ea;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.6rem;">
          <i class="fa-solid fa-hand-holding-medical"></i>
        </div>
        <h4 style="margin-bottom:10px;">Kesehatan & Medis</h4>
        <p style="font-size:0.88rem;color:var(--dark-muted);">Bantuan pendampingan operasi pasien dhuafa, ambulans gratis, dan suplemen bayi.</p>
      </div>
    </div>
  </div>
</section>

<!-- Transparansi Preview -->
<section class="section">
  <div class="container">
    <div style="background:linear-gradient(135deg, #064e3b 0%, #059669 100%);color:#fff;border-radius:var(--radius-lg);padding:50px 40px;display:grid;grid-template-columns:1.2fr 0.8fr;gap:40px;align-items:center;">
      <div>
        <span style="background:rgba(255,255,255,0.2);padding:6px 14px;border-radius:50px;font-size:0.8rem;font-weight:700;display:inline-block;margin-bottom:15px;">
          <i class="fa-solid fa-certificate" style="color:#fde047;"></i> PRINSIP AKUNTABILITAS 100%
        </span>
        <h2 style="color:#fff;font-size:2.2rem;margin-bottom:16px;">Setiap Rupiah Amanah Tercatat & Terbuka</h2>
        <p style="color:rgba(255,255,255,0.9);margin-bottom:24px;line-height:1.6;">
          Kami menjamin seluruh donasi tercatat secara digital, diverifikasi staf berintegritas, dan penyaluran dapat diaudit langsung oleh publik melalui portal transparansi kas.
        </p>
        <div style="display:flex;gap:15px;flex-wrap:wrap;">
          <a href="<?= url('transparency') ?>" class="btn btn-cta">
            <i class="fa-solid fa-chart-pie"></i> Buka Transparansi Keuangan
          </a>
          <a href="<?= url('transparency/report/print') ?>" target="_blank" class="btn btn-outline-white">
            <i class="fa-solid fa-print"></i> Cetak Laporan Resmi
          </a>
        </div>
      </div>

      <div style="background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.2);padding:30px;border-radius:var(--radius);">
        <h4 style="color:#fff;margin-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.2);padding-bottom:10px;">Ringkasan Kas Yayasan</h4>
        <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
          <span>Donasi Terverifikasi:</span>
          <strong><?= format_rupiah($stats['totalDonations']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
          <span>Penyaluran Bantuan:</span>
          <strong style="color:#fecaca;">- <?= format_rupiah($stats['totalDisbursed']) ?></strong>
        </div>
        <div style="border-top:1px dashed rgba(255,255,255,0.3);padding-top:14px;display:flex;justify-content:space-between;font-size:1.15rem;">
          <span>Saldo Kas Amanah:</span>
          <strong style="color:#fde047;"><?= format_rupiah($stats['balance']) ?></strong>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Kabar Berita & Artikel Terbaru -->
<section class="section" style="background:#f8fafc;">
  <div class="container">
    <div class="section-header text-center">
      <div class="section-tag">DOKUMENTASI & KABAR</div>
      <h2 class="section-title">Cerita dari Lapangan</h2>
      <p class="section-subtitle">Kisah inspiratif para penerima manfaat dan dokumentasi aksi relawan di berbagai daerah.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:28px;">
      <?php foreach ($recentArticles as $art): ?>
        <div class="card" style="overflow:hidden;display:flex;flex-direction:column;">
          <div style="height:190px;overflow:hidden;background:#cbd5e1;">
            <img src="<?= !empty($art['featured_image']) ? asset('uploads/' . $art['featured_image']) : 'https://placehold.co/600x400/0284c7/ffffff?text=Peduli+Kasih' ?>" alt="<?= e($art['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
          </div>
          <div style="padding:22px;display:flex;flex-direction:column;flex-grow:1;">
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;color:var(--dark-muted);margin-bottom:10px;">
              <span class="badge badge-primary"><?= ucfirst(e($art['category'])) ?></span>
              <span><i class="fa-regular fa-calendar"></i> <?= format_date($art['created_at']) ?></span>
            </div>
            <h4 style="font-size:1.15rem;margin-bottom:10px;line-height:1.4;">
              <a href="<?= url('article/' . $art['slug']) ?>" style="color:var(--dark);"><?= e($art['title']) ?></a>
            </h4>
            <p style="font-size:0.88rem;color:var(--dark-muted);margin-bottom:18px;flex-grow:1;"><?= e(substr($art['excerpt'], 0, 110)) ?>...</p>
            <a href="<?= url('article/' . $art['slug']) ?>" style="font-weight:700;font-size:0.9rem;display:inline-flex;align-items:center;gap:6px;">
              Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Call to Action Footer -->
<section style="background:linear-gradient(135deg, #10b981 0%, #047857 100%);color:#fff;padding:60px 0;text-align:center;">
  <div class="container">
    <h2 style="color:#fff;font-size:2.4rem;margin-bottom:15px;">Satu Kebaikan Anda, Sejuta Senyuman Mereka</h2>
    <p style="max-width:620px;margin:0 auto 30px;font-size:1.1rem;color:rgba(255,255,255,0.9);">
      Mari wujudkan kepedulian nyata hari ini. Donasi Anda langsung disalurkan ke mustahik yang membutuhkan.
    </p>
    <a href="<?= url('campaigns') ?>" class="btn btn-cta" style="font-size:1.15rem;padding:16px 36px;">
      <i class="fa-solid fa-heart"></i> DONASI SEKARANG
    </a>
  </div>
</section>
