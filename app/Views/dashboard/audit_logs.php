<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-fingerprint" style="color:var(--primary);margin-right:8px;"></i> Rekam Jejak Aktivitas Sistem (Audit Log)</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Mencatat aktivitas otentikasi, verifikasi donasi, pengeluaran kas, dan manipulasi data untuk akuntabilitas internal.</p>
    </div>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>Waktu (WIB)</th>
          <th>Pengguna</th>
          <th>Role</th>
          <th>Aksi</th>
          <th>Deskripsi Kejadian</th>
          <th>Alamat IP</th>
          <th>Perangkat (Agent)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $log): ?>
          <tr>
            <td><small><?= format_date($log['created_at'], true) ?></small></td>
            <td><strong><?= e($log['user_name'] ?? 'Sistem / Anonim') ?></strong></td>
            <td><span class="badge badge-primary"><?= ucfirst(e($log['user_role'] ?? 'system')) ?></span></td>
            <td><span class="badge badge-warning"><?= e($log['action']) ?></span></td>
            <td><?= e($log['description']) ?></td>
            <td><code><?= e($log['ip_address'] ?? '127.0.0.1') ?></code></td>
            <td><small class="text-muted" title="<?= e($log['user_agent'] ?? '') ?>"><?= e(substr($log['user_agent'] ?? '-', 0, 30)) ?>...</small></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
