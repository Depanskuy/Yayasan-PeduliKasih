<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title"><i class="fa-solid fa-users-gear" style="color:var(--primary);margin-right:8px;"></i> Manajemen Pengguna & Hak Akses</h3>
      <p class="text-muted" style="font-size:0.85rem;margin-top:4px;">Kelola akun Super Admin, Staf Keuangan, Relawan, dan Donatur terdaftar.</p>
    </div>
    <a href="<?= url('admin/users/create') ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
    </a>
  </div>

  <div style="padding:15px 24px;background:#f8fafc;border-bottom:1px solid var(--border);display:flex;gap:10px;">
    <a href="<?= url('admin/users') ?>" class="btn btn-sm <?= empty($currentRole) ? 'btn-primary' : 'btn-outline' ?>">Semua Role</a>
    <a href="<?= url('admin/users?role=superadmin') ?>" class="btn btn-sm <?= ($currentRole === 'superadmin') ? 'btn-primary' : 'btn-outline' ?>">Super Admin</a>
    <a href="<?= url('admin/users?role=staff') ?>" class="btn btn-sm <?= ($currentRole === 'staff') ? 'btn-primary' : 'btn-outline' ?>">Staf Keuangan</a>
    <a href="<?= url('admin/users?role=volunteer') ?>" class="btn btn-sm <?= ($currentRole === 'volunteer') ? 'btn-primary' : 'btn-outline' ?>">Relawan</a>
    <a href="<?= url('admin/users?role=donatur') ?>" class="btn btn-sm <?= ($currentRole === 'donatur') ? 'btn-primary' : 'btn-outline' ?>">Donatur</a>
  </div>

  <div class="card-body table-responsive" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>Nama Pengguna</th>
          <th>Email</th>
          <th>Telepon</th>
          <th>Peran (Role)</th>
          <th>Status</th>
          <th>Terdaftar</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><strong><?= e($u['name']) ?></strong></td>
            <td><?= e($u['email']) ?></td>
            <td><small><?= e($u['phone'] ?? '-') ?></small></td>
            <td>
              <span class="badge badge-<?= $u['role'] === 'superadmin' ? 'verified' : ($u['role'] === 'staff' ? 'warning' : 'primary') ?>">
                <?= ucfirst(e($u['role'])) ?>
              </span>
            </td>
            <td>
              <span class="badge badge-<?= $u['status'] === 'active' ? 'verified' : 'danger' ?>">
                <?= ucfirst(e($u['status'])) ?>
              </span>
            </td>
            <td><small><?= format_date($u['created_at']) ?></small></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
