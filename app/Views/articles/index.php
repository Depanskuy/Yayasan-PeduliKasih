<div style="background:linear-gradient(135deg, #064e3b 0%, #059669 100%);color:#fff;padding:60px 0;">
  <div class="container text-center">
    <span class="badge badge-primary" style="background:rgba(255,255,255,0.2);color:#fff;margin-bottom:12px;font-size:0.85rem;">
      <i class="fa-solid fa-newspaper" style="color:#fde047;"></i> WARTA YAYASAN
    </span>
    <h1 style="color:#fff;font-size:2.5rem;margin-bottom:12px;">Kabar, Inspirasi, & Edukasi</h1>
    <p style="color:rgba(255,255,255,0.9);max-width:650px;margin:0 auto;">
      Ikuti perkembangan kabar lapangan penyaluran donasi dan cerita inspiratif mustahik di berbagai wilayah.
    </p>
  </div>
</div>

<div class="container section">
  <div style="display:flex;gap:10px;justify-content:center;margin-bottom:35px;flex-wrap:wrap;">
    <a href="<?= url('articles') ?>" class="btn btn-sm <?= empty($currentCategory) ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:50px;">Semua Kategori</a>
    <a href="<?= url('articles?category=berita') ?>" class="btn btn-sm <?= ($currentCategory === 'berita') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:50px;">Berita Penyaluran</a>
    <a href="<?= url('articles?category=inspirasi') ?>" class="btn btn-sm <?= ($currentCategory === 'inspirasi') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:50px;">Kisah Inspiratif</a>
    <a href="<?= url('articles?category=kegiatan') ?>" class="btn btn-sm <?= ($currentCategory === 'kegiatan') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:50px;">Aksi Lapangan</a>
    <a href="<?= url('articles?category=edukasi') ?>" class="btn btn-sm <?= ($currentCategory === 'edukasi') ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:50px;">Edukasi Berbagi</a>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:28px;">
    <?php foreach ($articles as $art): ?>
      <div class="card" style="overflow:hidden;display:flex;flex-direction:column;">
        <div style="height:200px;overflow:hidden;background:#cbd5e1;">
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
