<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Yayasan Peduli Kasih Sesama – Platform donasi dan program sosial untuk membantu masyarakat Indonesia.">
  <meta name="keywords" content="donasi, sosial, yayasan, zakat, infaq, shadaqah, bantuan, relawan, transparansi">
  <meta property="og:title" content="Yayasan Peduli Kasih Sesama">
  <meta property="og:description" content="Bergabung dalam program donasi dan relawan kami untuk menebar kebaikan.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= url() ?>">
  <meta property="og:image" content="<?= asset('assets/images/og-image.jpg') ?>">
  <meta name="author" content="Yayasan Peduli Kasih Sesama">
  <meta name="theme-color" content="#064e3b">
  <link rel="icon" href="<?= asset('assets/images/favicon.ico') ?>" type="image/x-icon">
  <link rel="apple-touch-icon" href="<?= asset('assets/images/apple-touch-icon.png') ?>">
  <title><?= e($title ?? 'Yayasan Peduli Kasih Sesama') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <!-- Top Announcement Bar – full width, edge to edge -->
  <div class="top-announcement-bar">
    <div class="top-bar-inner">
      <span>
        <i class="fa-solid fa-certificate" style="color:#fde047;margin-right:6px;"></i>
        Terdaftar Resmi SK Kemenkumham RI: AHU-0012485.AH.01.04.Tahun 2018
      </span>
      <span class="top-bar-contacts">
        <span><i class="fa-solid fa-phone" style="margin-right:4px;"></i> (021) 7890-1234</span>
        <span style="opacity:0.4;">|</span>
        <span><i class="fa-solid fa-envelope" style="margin-right:4px;"></i> info@pedulikasih.org</span>
      </span>
    </div>
  </div>

  <!-- Navbar -->
  <header class="site-header">
    <div class="navbar-wrapper">
      <nav class="navbar">
        <a href="<?= url('/') ?>" class="navbar-brand">
          <div class="brand-icon">
            <i class="fa-solid fa-hand-holding-heart"></i>
          </div>
          <div>
            <div style="line-height:1.1;">Peduli Kasih</div>
            <div style="font-size:0.75rem;font-weight:600;color:var(--primary);letter-spacing:0.5px;">YAYASAN SOSIAL SESAMA</div>
          </div>
        </a>

        <button type="button" class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
          <i class="fa-solid fa-bars"></i>
        </button>

        <ul class="nav-menu" id="navMenu">
          <li><a href="<?= url('/') ?>" class="nav-link"><i class="fa-solid fa-house"></i> Beranda</a></li>
          <li><a href="<?= url('campaigns') ?>" class="nav-link"><i class="fa-solid fa-hand-holding-dollar"></i> Program Donasi</a></li>
          <li><a href="<?= url('transparency') ?>" class="nav-link"><i class="fa-solid fa-scale-balanced"></i> Transparansi</a></li>
          <li><a href="<?= url('volunteer/events') ?>" class="nav-link"><i class="fa-solid fa-users"></i> Relawan</a></li>
          <li><a href="<?= url('articles') ?>" class="nav-link"><i class="fa-solid fa-newspaper"></i> Kabar Berita</a></li>
          <li><a href="<?= url('gallery') ?>" class="nav-link"><i class="fa-solid fa-images"></i> Galeri</a></li>
          <li><a href="<?= url('about') ?>" class="nav-link"><i class="fa-solid fa-circle-info"></i> Tentang</a></li>
        </ul>

        <div class="nav-actions">
          <?php if (is_logged_in()): ?>
            <?php $u = auth_user(); ?>
            <a href="<?= in_array($u['role'], ['superadmin', 'staff']) ? url('admin/dashboard') : ( $u['role'] === 'volunteer' ? url('volunteer/dashboard') : url('donor/dashboard') ) ?>" class="btn btn-outline btn-sm">
              <i class="fa-solid fa-user-circle"></i> <?= e($u['name']) ?> (<?= ucfirst($u['role']) ?>)
            </a>
            <a href="<?= url('logout') ?>" class="btn btn-sm" style="color:var(--danger);background:#fee2e2;"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
          <?php else: ?>
            <a href="<?= url('login') ?>" class="btn btn-outline btn-sm"><i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk</a>
            <a href="<?= url('campaigns') ?>" class="btn btn-cta btn-sm"><i class="fa-solid fa-heart"></i> DONASI SEKARANG</a>
          <?php endif; ?>
        </div>
      </nav>
    </div>
  </header>

  <!-- Flash Messages -->
  <?php
    $flashSuccess = flash_get('success');
    $flashError   = flash_get('error');
    $flashWarning = flash_get('warning');
    $flashInfo    = flash_get('info');
    $hasFlash     = $flashSuccess || $flashError || $flashWarning || $flashInfo;
  ?>
  <?php if ($hasFlash): ?>
  <div class="container" style="padding-top:20px;padding-bottom:4px;">
    <?php if ($flashSuccess): ?>
      <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= e($flashSuccess) ?></div>
    <?php endif; ?>
    <?php if ($flashError): ?>
      <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= e($flashError) ?></div>
    <?php endif; ?>
    <?php if ($flashWarning): ?>
      <div class="alert alert-warning"><i class="fa-solid fa-triangle-exclamation"></i> <?= e($flashWarning) ?></div>
    <?php endif; ?>
    <?php if ($flashInfo): ?>
      <div class="alert alert-info"><i class="fa-solid fa-circle-info"></i> <?= e($flashInfo) ?></div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- Main Content Body -->
  <main>
    <?= $content ?? '' ?>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px;">
            <div style="width:36px;height:36px;background:var(--primary);color:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
              <i class="fa-solid fa-hand-holding-heart"></i>
            </div>
            <h4 style="color:#fff;font-weight:800;font-size:1.2rem;">Yayasan Peduli Kasih Sesama</h4>
          </div>
          <p style="margin-bottom:15px;line-height:1.6;">Lembaga nirlaba pengelola zakat, infaq, shadaqah, dan dana kemanusiaan yang berdedikasi mengentaskan kemiskinan, memajukan pendidikan yatim dhuafa, dan tanggap kebencanaan di pelosok nusantara.</p>
          <p style="font-size:0.8rem;color:#64748b;"><i class="fa-solid fa-shield-halved"></i> Izin Operasional Kemensos RI No. 542/HUK-PS/2020</p>
        </div>

        <div>
          <h5 class="footer-title">Tautan Cepat</h5>
          <ul class="footer-links">
            <li><a href="<?= url('campaigns') ?>">Program Donasi</a></li>
            <li><a href="<?= url('transparency') ?>">Transparansi Kas</a></li>
            <li><a href="<?= url('volunteer/events') ?>">Gabung Relawan</a></li>
            <li><a href="<?= url('articles') ?>">Artikel & Berita</a></li>
            <li><a href="<?= url('gallery') ?>">Dokumentasi Aksi</a></li>
          </ul>
        </div>

        <div>
          <h5 class="footer-title">Rekening Donasi</h5>
          <ul class="footer-links" style="font-size:0.88rem;">
            <li><strong>Bank BCA:</strong><br>8830-192-800</li>
            <li><strong>Bank Mandiri:</strong><br>137-00-9876543-2</li>
            <li><strong>Bank BRI:</strong><br>0206-01-008912-301</li>
            <li style="color:#fde047;">a.n Yayasan Peduli Kasih Sesama</li>
          </ul>
        </div>

        <div>
          <h5 class="footer-title">Sekretariat Pusat</h5>
          <p style="margin-bottom:12px;font-size:0.88rem;"><i class="fa-solid fa-location-dot" style="color:var(--primary);margin-right:6px;"></i> Jl. Kasih Sejahtera No. 45, Kebayoran Baru, Jakarta Selatan 12180</p>
          <p style="margin-bottom:12px;font-size:0.88rem;"><i class="fa-solid fa-phone" style="color:var(--primary);margin-right:6px;"></i> (021) 7890-1234 / 0812-3456-7890</p>
          <p style="font-size:0.88rem;"><i class="fa-solid fa-envelope" style="color:var(--primary);margin-right:6px;"></i> sekretariat@pedulikasih.org</p>
        </div>
      </div>

      <div class="footer-bottom">
        <div>&copy; 2026 Yayasan Peduli Kasih Sesama. Seluruh hak cipta dilindungi.</div>
        <div style="display:flex;gap:18px;">
          <a href="<?= url('about') ?>" style="color:#94a3b8;">Tentang Kami</a>
          <a href="<?= url('contact') ?>" style="color:#94a3b8;">Kontak</a>
          <a href="<?= url('login') ?>" style="color:#94a3b8;">Portal Staf</a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggle = document.getElementById('navToggle');
      const menu = document.getElementById('navMenu');
      if (toggle && menu) {
        toggle.addEventListener('click', function() {
          menu.classList.toggle('is-active');
        });
      }
    });
  </script>
</body>
</html>
