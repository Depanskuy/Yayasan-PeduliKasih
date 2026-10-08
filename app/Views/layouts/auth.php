<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Autentikasi - Yayasan Peduli Kasih Sesama') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body style="background:linear-gradient(135deg, #f0fdf4 0%, #e2e8f0 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px 15px;">

  <div style="width:100%;max-width:440px;">
    <div style="text-align:center;margin-bottom:25px;">
      <a href="<?= url('/') ?>" style="display:inline-flex;align-items:center;gap:12px;text-decoration:none;">
        <div style="width:48px;height:48px;background:var(--primary);color:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;box-shadow:var(--shadow);">
          <i class="fa-solid fa-hand-holding-heart"></i>
        </div>
        <div style="text-align:left;">
          <h2 style="font-size:1.35rem;font-weight:800;color:var(--dark);line-height:1.1;">Peduli Kasih</h2>
          <span style="font-size:0.75rem;font-weight:700;color:var(--primary);letter-spacing:0.5px;">YAYASAN SOSIAL SESAMA</span>
        </div>
      </a>
    </div>

    <!-- Flash Messages -->
    <?php if ($msg = flash_get('success')): ?>
      <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = flash_get('error')): ?>
      <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= e($msg) ?></div>
    <?php endif; ?>

    <div class="card" style="box-shadow:var(--shadow-lg);border-radius:var(--radius-lg);">
      <div class="card-body" style="padding:35px 30px;">
        <?= $content ?? '' ?>
      </div>
    </div>

    <div style="text-align:center;margin-top:20px;font-size:0.85rem;color:var(--dark-muted);">
      <a href="<?= url('/') ?>" style="color:var(--dark-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Utama</a>
    </div>
  </div>

</body>
</html>
