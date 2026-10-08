<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-newspaper" style="color:var(--primary);margin-right:8px;"></i> Manajemen Artikel & Kabar Berita</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Tulis publikasi berita aksi lapangan, artikel inspirasi, dan edukasi sosial.</p>
    </div>
    <a href="<?= url('admin/articles/create') ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-plus"></i> Tulis Artikel Baru
    </a>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>Thumbnail</th>
          <th>Judul Artikel</th>
          <th>Kategori</th>
          <th>Penulis</th>
          <th>Views</th>
          <th>Tanggal</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($articles as $art): ?>
          <?php 
            $img = !empty($art['featured_image']) ? asset('uploads/' . $art['featured_image']) : 'https://placehold.co/100x60/0284c7/ffffff?text=Berita';
          ?>
          <tr>
            <td>
              <img src="<?= $img ?>" alt="" style="width:60px;height:40px;object-fit:cover;border-radius:4px;">
            </td>
            <td>
              <strong><a href="<?= url('article/' . $art['slug']) ?>" target="_blank"><?= e($art['title']) ?></a></strong>
            </td>
            <td><span class="badge badge-primary"><?= ucfirst(e($art['category'])) ?></span></td>
            <td><small><?= e($art['author_name']) ?></small></td>
            <td><?= number_format($art['views_count']) ?></td>
            <td><small><?= format_date($art['created_at']) ?></small></td>
            <td>
              <span class="badge badge-<?= $art['status'] === 'published' ? 'verified' : 'draft' ?>">
                <?= ucfirst(e($art['status'])) ?>
              </span>
            </td>
            <td>
              <a href="<?= url('admin/articles/edit/' . $art['id']) ?>" class="btn btn-sm btn-outline" style="padding:4px 8px;">
                <i class="fa-solid fa-pen-to-square"></i> Edit
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
