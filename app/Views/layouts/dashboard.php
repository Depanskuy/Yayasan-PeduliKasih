<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Dashboard - Yayasan Peduli Kasih Sesama') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="dashboard-wrapper">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <div style="width:36px;height:36px;background:var(--primary);color:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
        <i class="fa-solid fa-hand-holding-heart"></i>
      </div>
      <a href="<?= url('/') ?>" class="sidebar-brand">Peduli Kasih</a>
    </div>

    <?php $u = auth_user(); $role = $u['role'] ?? 'donatur'; ?>

    <ul class="sidebar-menu">
      <?php if (in_array($role, ['superadmin', 'staff'])): ?>
        <li class="sidebar-heading">UTAMA</li>
        <li><a href="<?= url('admin/dashboard') ?>" class="sidebar-link"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>

        <li class="sidebar-heading">PENGGALANGAN DANA</li>
        <li><a href="<?= url('admin/campaigns') ?>" class="sidebar-link"><i class="fa-solid fa-bullhorn"></i> Program Campaign</a></li>
        <li><a href="<?= url('admin/donations') ?>" class="sidebar-link"><i class="fa-solid fa-receipt"></i> Donasi & Verifikasi</a></li>
        <li><a href="<?= url('admin/donations/offline') ?>" class="sidebar-link"><i class="fa-solid fa-cash-register"></i> Input Kasir / Offline</a></li>

        <li class="sidebar-heading">PENYALURAN SOSIAL</li>
        <li><a href="<?= url('admin/beneficiaries') ?>" class="sidebar-link"><i class="fa-solid fa-people-roof"></i> Data Mustahik</a></li>
        <li><a href="<?= url('admin/distributions') ?>" class="sidebar-link"><i class="fa-solid fa-box-open"></i> Penyaluran Bantuan</a></li>

        <li class="sidebar-heading">KERELAWANAN</li>
        <li><a href="<?= url('admin/volunteers/events') ?>" class="sidebar-link"><i class="fa-solid fa-calendar-check"></i> Event Relawan</a></li>
        <li><a href="<?= url('admin/volunteers/reports') ?>" class="sidebar-link"><i class="fa-solid fa-clipboard-list"></i> Laporan Lapangan</a></li>

        <li class="sidebar-heading">KONTEN & DOKUMENTASI</li>
        <li><a href="<?= url('admin/articles') ?>" class="sidebar-link"><i class="fa-solid fa-newspaper"></i> Berita & Artikel</a></li>
        <li><a href="<?= url('admin/gallery') ?>" class="sidebar-link"><i class="fa-solid fa-images"></i> Galeri Dokumentasi</a></li>

        <?php if ($role === 'superadmin'): ?>
          <li class="sidebar-heading">SISTEM & AUDIT</li>
          <li><a href="<?= url('admin/audit-logs') ?>" class="sidebar-link"><i class="fa-solid fa-fingerprint"></i> Audit Log</a></li>
          <li><a href="<?= url('admin/users') ?>" class="sidebar-link"><i class="fa-solid fa-users-gear"></i> Manajemen Staf/User</a></li>
          <li><a href="<?= url('admin/settings') ?>" class="sidebar-link"><i class="fa-solid fa-sliders"></i> Pengaturan Yayasan</a></li>
        <?php endif; ?>

      <?php elseif ($role === 'volunteer'): ?>
        <li class="sidebar-heading">PORTAL RELAWAN</li>
        <li><a href="<?= url('volunteer/dashboard') ?>" class="sidebar-link"><i class="fa-solid fa-gauge-high"></i> Aktivitas Saya</a></li>
        <li><a href="<?= url('volunteer/events') ?>" class="sidebar-link"><i class="fa-solid fa-calendar-days"></i> Cari Kegiatan</a></li>
        <li><a href="<?= url('profile') ?>" class="sidebar-link"><i class="fa-solid fa-id-card"></i> Profil Relawan</a></li>

      <?php else: ?>
        <li class="sidebar-heading">RUANG DONATUR</li>
        <li><a href="<?= url('donor/dashboard') ?>" class="sidebar-link"><i class="fa-solid fa-gauge-high"></i> Riwayat Donasi</a></li>
        <li><a href="<?= url('campaigns') ?>" class="sidebar-link"><i class="fa-solid fa-heart"></i> Program Donasi</a></li>
        <li><a href="<?= url('transparency') ?>" class="sidebar-link"><i class="fa-solid fa-scale-balanced"></i> Transparansi Kas</a></li>
        <li><a href="<?= url('profile') ?>" class="sidebar-link"><i class="fa-solid fa-id-card"></i> Profil Saya</a></li>
      <?php endif; ?>

      <li class="sidebar-heading">AKUN</li>
      <li><a href="<?= url('profile') ?>" class="sidebar-link"><i class="fa-solid fa-user-pen"></i> Ubah Profil</a></li>
      <li><a href="<?= url('/') ?>" class="sidebar-link"><i class="fa-solid fa-arrow-left"></i> Ke Web Publik</a></li>
      <li><a href="<?= url('logout') ?>" class="sidebar-link" style="color:#ef4444;"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
    </ul>

    <div class="sidebar-footer">
      <div style="font-size:0.75rem;color:#64748b;line-height:1.4;">
        Login sebagai: <strong style="color:#f1f5f9;"><?= ucfirst($role) ?></strong><br>
        Yayasan Peduli Kasih Sesama
      </div>
    </div>
  </aside>

  <!-- Main View Area -->
  <div class="dashboard-main">
    <header class="dashboard-topbar">
      <div>
        <h3 style="font-size:1.15rem;margin:0;"><?= e($title ?? 'Panel Yayasan') ?></h3>
      </div>
      <div style="display:flex;align-items:center;gap:18px;">
        <span class="badge badge-primary"><?= ucfirst($role) ?></span>
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:38px;height:38px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;color:var(--dark);font-weight:700;">
            <?= strtoupper(substr($u['name'] ?? 'U', 0, 1)) ?>
          </div>
          <div>
            <div style="font-weight:700;font-size:0.9rem;line-height:1.2;"><?= e($u['name'] ?? 'User') ?></div>
            <div style="font-size:0.75rem;color:var(--dark-muted);"><?= e($u['email'] ?? '') ?></div>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-content">
      <!-- Flash Messages -->
      <?php if ($msg = flash_get('success')): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= e($msg) ?></div>
      <?php endif; ?>
      <?php if ($msg = flash_get('error')): ?>
        <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= e($msg) ?></div>
      <?php endif; ?>
      <?php if ($msg = flash_get('warning')): ?>
        <div class="alert alert-warning"><i class="fa-solid fa-triangle-exclamation"></i> <?= e($msg) ?></div>
      <?php endif; ?>
      <?php if ($msg = flash_get('info')): ?>
        <div class="alert alert-info"><i class="fa-solid fa-circle-info"></i> <?= e($msg) ?></div>
      <?php endif; ?>

      <?= $content ?? '' ?>
    </div>
  </div>
</div>

</body>
</html>
