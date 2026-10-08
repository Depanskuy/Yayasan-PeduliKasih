<?php
// ─── Hero Stats ──────────────────────────────────────────────────────────────
$statusColor = '#059669';
$statusBg    = '#ecfdf5';
?>

<div style="max-width:900px;margin:0 auto;padding:0 0 40px;">

  <!-- ═══ Page Header ═══════════════════════════════════════════════════════ -->
  <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:28px;">
    <div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
        <div style="width:40px;height:40px;background:var(--primary);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
          <i class="fa-solid fa-file-invoice"></i>
        </div>
        <div>
          <h2 style="margin:0;font-size:1.4rem;font-weight:800;color:var(--dark);">Berita Acara Penyaluran</h2>
          <p style="margin:0;font-size:0.82rem;color:var(--dark-muted);">Dokumen resmi realisasi bantuan sosial</p>
        </div>
      </div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a href="<?= url('admin/distributions') ?>" class="btn btn-sm btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Kembali
      </a>
      <button onclick="window.print()" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-print"></i> Cetak Dokumen
      </button>
    </div>
  </div>

  <!-- ═══ Disbursement Code Banner ══════════════════════════════════════════ -->
  <div style="background:linear-gradient(135deg,var(--primary-dark) 0%,var(--primary) 100%);border-radius:16px;padding:22px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <div style="font-size:0.75rem;color:rgba(255,255,255,0.7);letter-spacing:1px;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Nomor Registrasi Berita Acara</div>
      <div style="font-size:1.5rem;font-weight:800;color:#fff;letter-spacing:1px;font-family:monospace;">
        <?= e($disbursement['disbursement_code']) ?>
      </div>
    </div>
    <div style="background:rgba(255,255,255,0.15);border-radius:10px;padding:12px 20px;text-align:center;">
      <div style="font-size:0.75rem;color:rgba(255,255,255,0.75);margin-bottom:2px;">Total Realisasi</div>
      <div style="font-size:1.6rem;font-weight:900;color:#fde047;">
        <?= format_rupiah($disbursement['amount']) ?>
      </div>
    </div>
  </div>

  <!-- ═══ Top Info Cards ═════════════════════════════════════════════════════ -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">

    <!-- Program -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px 20px;">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
        <div style="width:28px;height:28px;background:var(--primary-light);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:0.8rem;">
          <i class="fa-solid fa-bullhorn"></i>
        </div>
        <span style="font-size:0.75rem;color:var(--dark-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Program</span>
      </div>
      <div style="font-weight:700;color:var(--dark);font-size:0.95rem;line-height:1.4;"><?= e($disbursement['campaign_title']) ?></div>
    </div>

    <!-- Jenis Bantuan -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px 20px;">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
        <div style="width:28px;height:28px;background:#fef3c7;border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:0.8rem;">
          <i class="fa-solid fa-box-open"></i>
        </div>
        <span style="font-size:0.75rem;color:var(--dark-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Jenis Bantuan</span>
      </div>
      <div style="font-weight:700;color:var(--dark);font-size:0.95rem;"><?= ucfirst(e($disbursement['assistance_type'])) ?></div>
    </div>

    <!-- Jumlah Penerima -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px 20px;">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
        <div style="width:28px;height:28px;background:#ede9fe;border-radius:7px;display:flex;align-items:center;justify-content:center;color:#7c3aed;font-size:0.8rem;">
          <i class="fa-solid fa-users"></i>
        </div>
        <span style="font-size:0.75rem;color:var(--dark-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Penerima</span>
      </div>
      <div style="font-weight:700;color:var(--dark);font-size:1.1rem;"><?= (int)$disbursement['recipient_count'] ?> <span style="font-size:0.85rem;font-weight:500;color:var(--dark-muted);">Jiwa</span></div>
    </div>

    <!-- Tanggal -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px 20px;">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
        <div style="width:28px;height:28px;background:#e0f2fe;border-radius:7px;display:flex;align-items:center;justify-content:center;color:#0284c7;font-size:0.8rem;">
          <i class="fa-solid fa-calendar-day"></i>
        </div>
        <span style="font-size:0.75rem;color:var(--dark-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Tanggal</span>
      </div>
      <div style="font-weight:700;color:var(--dark);font-size:0.95rem;"><?= format_date($disbursement['disbursement_date']) ?></div>
    </div>

  </div>

  <!-- ═══ Detail Info Table ══════════════════════════════════════════════════ -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:24px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border);background:#f8fafc;">
      <h4 style="margin:0;font-size:1rem;font-weight:700;color:var(--dark);display:flex;align-items:center;gap:8px;">
        <i class="fa-solid fa-list-check" style="color:var(--primary);"></i>
        Rincian Penyaluran
      </h4>
    </div>

    <div style="padding:0 24px;">

      <!-- Row: Uraian -->
      <div style="display:flex;align-items:flex-start;padding:16px 0;border-bottom:1px solid #f1f5f9;gap:16px;">
        <div style="min-width:220px;font-size:0.85rem;color:var(--dark-muted);display:flex;align-items:center;gap:8px;">
          <i class="fa-solid fa-tag" style="width:14px;color:var(--primary);"></i> Uraian Penyaluran
        </div>
        <div style="font-weight:600;color:var(--dark);font-size:0.93rem;"><?= e($disbursement['title']) ?></div>
      </div>

      <!-- Row: Penerima Manfaat -->
      <div style="display:flex;align-items:flex-start;padding:16px 0;border-bottom:1px solid #f1f5f9;gap:16px;">
        <div style="min-width:220px;font-size:0.85rem;color:var(--dark-muted);display:flex;align-items:center;gap:8px;">
          <i class="fa-solid fa-person-shelter" style="width:14px;color:var(--primary);"></i> Penerima Manfaat / Mustahik
        </div>
        <div style="font-weight:600;color:var(--dark);font-size:0.93rem;">
          <?= e($disbursement['beneficiary_name'] ?? 'Penerima Umum / Komunal') ?>
        </div>
      </div>

      <!-- Row: Jenis Bantuan -->
      <div style="display:flex;align-items:flex-start;padding:16px 0;border-bottom:1px solid #f1f5f9;gap:16px;">
        <div style="min-width:220px;font-size:0.85rem;color:var(--dark-muted);display:flex;align-items:center;gap:8px;">
          <i class="fa-solid fa-cubes" style="width:14px;color:var(--primary);"></i> Jenis Bantuan Sosial
        </div>
        <div>
          <span style="display:inline-block;background:var(--accent-light);color:var(--accent-hover);font-size:0.8rem;font-weight:700;padding:3px 12px;border-radius:20px;">
            <?= ucfirst(e($disbursement['assistance_type'])) ?>
          </span>
        </div>
      </div>

      <!-- Row: Jumlah Penerima -->
      <div style="display:flex;align-items:flex-start;padding:16px 0;border-bottom:1px solid #f1f5f9;gap:16px;">
        <div style="min-width:220px;font-size:0.85rem;color:var(--dark-muted);display:flex;align-items:center;gap:8px;">
          <i class="fa-solid fa-users" style="width:14px;color:var(--primary);"></i> Jumlah Jiwa Penerima
        </div>
        <div style="font-weight:700;color:var(--dark);font-size:0.93rem;"><?= (int)$disbursement['recipient_count'] ?> Orang</div>
      </div>

      <!-- Row: Tanggal -->
      <div style="display:flex;align-items:flex-start;padding:16px 0;border-bottom:1px solid #f1f5f9;gap:16px;">
        <div style="min-width:220px;font-size:0.85rem;color:var(--dark-muted);display:flex;align-items:center;gap:8px;">
          <i class="fa-solid fa-calendar-check" style="width:14px;color:var(--primary);"></i> Tanggal Eksekusi Lapangan
        </div>
        <div style="font-weight:600;color:var(--dark);font-size:0.93rem;"><?= format_date($disbursement['disbursement_date']) ?></div>
      </div>

      <!-- Row: Petugas -->
      <div style="display:flex;align-items:flex-start;padding:16px 0;gap:16px;">
        <div style="min-width:220px;font-size:0.85rem;color:var(--dark-muted);display:flex;align-items:center;gap:8px;">
          <i class="fa-solid fa-user-tie" style="width:14px;color:var(--primary);"></i> Petugas Penyalur
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:32px;height:32px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:0.75rem;font-weight:700;">
            <?= strtoupper(substr(e($disbursement['recorded_by_name']), 0, 2)) ?>
          </div>
          <span style="font-weight:600;color:var(--dark);font-size:0.93rem;"><?= e($disbursement['recorded_by_name']) ?></span>
        </div>
      </div>

    </div>
  </div>

  <!-- ═══ Catatan ════════════════════════════════════════════════════════════ -->
  <?php if (!empty($disbursement['notes'])): ?>
  <div style="background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:24px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border);background:#f8fafc;">
      <h4 style="margin:0;font-size:1rem;font-weight:700;color:var(--dark);display:flex;align-items:center;gap:8px;">
        <i class="fa-solid fa-note-sticky" style="color:var(--accent);"></i>
        Keterangan &amp; Catatan
      </h4>
    </div>
    <div style="padding:20px 24px;">
      <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:16px 20px;font-size:0.92rem;line-height:1.75;color:var(--dark);">
        <?= nl2br(e($disbursement['notes'])) ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- ═══ Dokumentasi Foto ═══════════════════════════════════════════════════ -->
  <?php if (!empty($disbursement['documentation_image'])): ?>
  <div style="background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:24px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border);background:#f8fafc;">
      <h4 style="margin:0;font-size:1rem;font-weight:700;color:var(--dark);display:flex;align-items:center;gap:8px;">
        <i class="fa-solid fa-camera" style="color:var(--secondary);"></i>
        Dokumentasi Penyerahan Bantuan
      </h4>
    </div>
    <div style="padding:24px;text-align:center;">
      <img
        src="<?= asset('uploads/' . $disbursement['documentation_image']) ?>"
        alt="Dokumentasi Penyaluran Bantuan"
        style="max-width:100%;max-height:420px;border-radius:12px;border:1px solid var(--border);box-shadow:var(--shadow);"
      >
    </div>
  </div>
  <?php endif; ?>

  <!-- ═══ Footer Actions ════════════════════════════════════════════════════ -->
  <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:8px;">
    <a href="<?= url('admin/distributions') ?>" class="btn btn-outline">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <button onclick="window.print()" class="btn btn-primary">
      <i class="fa-solid fa-print"></i> Cetak Berita Acara
    </button>
  </div>

</div>
