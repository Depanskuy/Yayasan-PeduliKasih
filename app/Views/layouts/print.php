<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= e($title ?? 'Dokumen Resmi - Yayasan Peduli Kasih Sesama') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #0f172a; padding: 30px; }
    .print-container { max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    .print-actions { max-width: 800px; margin: 0 auto 20px; display: flex; justify-content: space-between; align-items: center; }
    .btn-print { background: #059669; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-print:hover { background: #047857; }
    .btn-back { color: #64748b; text-decoration: none; font-weight: 600; }
    @media print {
      body { background: #fff; padding: 0; }
      .print-container { box-shadow: none; padding: 20px; max-width: 100%; border: none; }
      .print-actions { display: none; }
    }
  </style>
</head>
<body>

  <div class="print-actions">
    <a href="javascript:history.back()" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    <button onclick="window.print()" class="btn-print"><i class="fa-solid fa-print"></i> Cetak / Simpan PDF</button>
  </div>

  <div class="print-container">
    <?= $content ?? '' ?>
  </div>

</body>
</html>
