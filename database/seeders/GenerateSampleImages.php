<?php
// database/seeders/GenerateSampleImages.php
$uploadsDir = dirname(__DIR__, 2) . '/public/uploads/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

$images = [
    'campaign-1.jpg' => ['Asrama Yatim Nurul Barokah', 0x10, 0xb9, 0x81],
    'campaign-2.jpg' => ['Bantuan Banjir Bandang', 0x02, 0x84, 0xc7],
    'campaign-3.jpg' => ['Sembako Berkah Lansia', 0xf5, 0x9e, 0x0b],
    'campaign-4.jpg' => ['Beasiswa Pelajar Dhuafa', 0x8b, 0x5c, 0xf6],
    'campaign-5.jpg' => ['Bantuan Medis Anak', 0xef, 0x44, 0x44],
    'article-1.jpg' => ['Senyum Kakek Sanusi', 0x10, 0xb9, 0x81],
    'article-2.jpg' => ['Transparansi Banjir Tahap I', 0x05, 0x96, 0x69],
    'article-3.jpg' => ['5 Cara Membantu Sesama', 0x0d, 0x94, 0x88],
    'gallery-1.jpg' => ['Penyaluran Sembako', 0x10, 0xb9, 0x81],
    'gallery-2.jpg' => ['Pemeriksaan Kesehatan', 0x02, 0x84, 0xc7],
    'gallery-3.jpg' => ['Pengecoran Lantai 2', 0x64, 0x74, 0x8b],
    'gallery-4.jpg' => ['Trauma Healing Anak', 0xf5, 0x9e, 0x0b],
    'gallery-5.jpg' => ['Pembagian Beasiswa', 0x8b, 0x5c, 0xf6],
    'vol-event-1.jpg' => ['Aksi Tanggap Bencana', 0x02, 0x84, 0xc7],
    'vol-event-2.jpg' => ['Ekspedisi Kasih Lansia', 0x05, 0x96, 0x69],
    'vol-event-3.jpg' => ['Bakti Sosial Pengecatan', 0xd9, 0x77, 0x06],
    'proof-1.jpg' => ['Bukti Transfer BCA', 0x3b, 0x82, 0xf6],
    'proof-2.jpg' => ['Bukti Transfer Mandiri', 0x1d, 0x4e, 0xd8],
    'proof-3.jpg' => ['Bukti Transfer GoPay', 0x00, 0xaa, 0x13],
    'proof-4.jpg' => ['Bukti Transfer BRI', 0x02, 0x84, 0xc7],
    'proof-7.jpg' => ['Bukti Transfer BCA Rp 750k', 0x3b, 0x82, 0xf6],
    'proof-qris.jpg' => ['Bukti Pembayaran QRIS', 0x99, 0x1b, 0x1b],
    'disb-1.jpg' => ['Logistik Banjir Pesisir', 0x03, 0x69, 0xa1],
    'disb-2.jpg' => ['Material Semen & Pasir', 0x47, 0x55, 0x69],
    'disb-3.jpg' => ['Paket Beras & Sembako', 0xd9, 0x77, 0x06],
    'disb-4.jpg' => ['Kwitansi SPP & Perlengkapan', 0x7c, 0x3a, 0xed],
    'report-vol-1.jpg' => ['Dokumentasi Relawan', 0x05, 0x96, 0x69],
];

foreach ($images as $file => $info) {
    [$title, $r, $g, $b] = $info;
    $targetPath = $uploadsDir . $file;
    if (file_exists($targetPath)) {
        continue;
    }
    $img = imagecreatetruecolor(800, 500);
    $bg = imagecolorallocate($img, $r, $g, $b);
    imagefill($img, 0, 0, $bg);
    $white = imagecolorallocate($img, 255, 255, 255);
    $yellow = imagecolorallocate($img, 254, 240, 138);

    imagestring($img, 5, 50, 200, "YAYASAN PEDULI KASIH SESAMA", $yellow);
    imagestring($img, 5, 50, 240, $title, $white);
    imagestring($img, 3, 50, 280, "Amanah - Transparan - Profesional", $white);

    imagejpeg($img, $targetPath, 90);
    imagedestroy($img);
}
echo "Sample images generated successfully.\n";
