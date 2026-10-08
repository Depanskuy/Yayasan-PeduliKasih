<div style="background:#f1f5f9;padding:15px 0;font-size:0.85rem;border-bottom:1px solid var(--border);">
  <div class="container">
    <a href="<?= url('/') ?>">Beranda</a> &nbsp;/&nbsp; 
    <a href="<?= url('articles') ?>">Artikel & Berita</a> &nbsp;/&nbsp; 
    <span class="text-muted"><?= e($article['title']) ?></span>
  </div>
</div>

<div class="container section" style="padding-top:35px;max-width:900px;">
  <span class="badge badge-primary" style="margin-bottom:12px;"><?= ucfirst(e($article['category'])) ?></span>
  <h1 style="font-size:2.2rem;margin-bottom:15px;line-height:1.3;"><?= e($article['title']) ?></h1>

  <div style="display:flex;align-items:center;gap:20px;font-size:0.88rem;color:var(--dark-muted);margin-bottom:25px;padding-bottom:15px;border-bottom:1px solid var(--border);">
    <span><i class="fa-solid fa-user-pen"></i> Ditulis oleh: <strong><?= e($article['author_name']) ?></strong></span>
    <span><i class="fa-regular fa-calendar"></i> <?= format_date($article['created_at']) ?></span>
    <span><i class="fa-regular fa-eye"></i> <?= number_format($article['views_count']) ?> Kali Dibaca</span>
  </div>

  <?php if (!empty($article['featured_image'])): ?>
    <div style="margin-bottom:30px;border-radius:var(--radius);overflow:hidden;background:#cbd5e1;">
      <img src="<?= asset('uploads/' . $article['featured_image']) ?>" alt="<?= e($article['title']) ?>" style="width:100%;max-height:450px;object-fit:cover;">
    </div>
  <?php endif; ?>

  <div style="line-height:1.9;font-size:1.05rem;color:#334155;margin-bottom:40px;">
    <?= $article['content'] ?>
  </div>

  <div style="padding:25px;background:#f8fafc;border-radius:var(--radius);border:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
    <div>
      <div style="font-weight:700;">Bagikan Kebaikan Ini:</div>
      <div style="font-size:0.85rem;color:var(--dark-muted);">Ajak teman dan keluarga ikut peduli sesama</div>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="https://api.whatsapp.com/send?text=<?= urlencode($article['title'] . ' ' . url('article/' . $article['slug'])) ?>" target="_blank" class="btn btn-sm" style="background:#22c55e;color:#fff;">
        <i class="fa-brands fa-whatsapp"></i> WhatsApp
      </a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(url('article/' . $article['slug'])) ?>" target="_blank" class="btn btn-sm" style="background:#1877f2;color:#fff;">
        <i class="fa-brands fa-facebook"></i> Facebook
      </a>
    </div>
  </div>
</div>
