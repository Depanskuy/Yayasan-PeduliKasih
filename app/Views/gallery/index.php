<div style="background:linear-gradient(135deg, #064e3b 0%, #059669 100%);color:#fff;padding:60px 0;">
  <div class="container text-center">
    <span class="badge badge-primary" style="background:rgba(255,255,255,0.2);color:#fff;margin-bottom:12px;font-size:0.85rem;">
      <i class="fa-solid fa-camera-retro" style="color:#fde047;"></i> DOKUMENTASI NYATA
    </span>
    <h1 style="color:#fff;font-size:2.5rem;margin-bottom:12px;">Galeri Foto Aksi Kemanusiaan</h1>
    <p style="color:rgba(255,255,255,0.9);max-width:650px;margin:0 auto;">
      Bukti visual setiap rupiah yang Anda titipkan telah berubah menjadi senyuman dan asa baru bagi mustahik di lapangan.
    </p>
  </div>
</div>

<div class="container section">
  <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:24px;">
    <?php foreach ($galleries as $g): ?>
      <div class="card" style="overflow:hidden;border-radius:var(--radius);">
        <div style="height:220px;overflow:hidden;background:#cbd5e1;">
          <img src="<?= asset('uploads/' . $g['media_url']) ?>" alt="<?= e($g['title']) ?>" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        </div>
        <div style="padding:16px;">
          <h4 style="font-size:1rem;margin-bottom:6px;"><?= e($g['title']) ?></h4>
          <?php if (!empty($g['description'])): ?>
            <p style="font-size:0.85rem;color:var(--dark-muted);margin-bottom:8px;"><?= e($g['description']) ?></p>
          <?php endif; ?>
          <div style="font-size:0.75rem;color:#94a3b8;"><i class="fa-regular fa-clock"></i> <?= format_date($g['created_at']) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
