<?php
// scripts/fetch_images.php
// Mengunduh foto dari internet ke public/uploads/ sesuai nama file di database.
// Sumber: Pexels (Pexels License) dan Wikimedia Commons (CC BY-SA).
// Jalankan: php scripts/fetch_images.php

$baseDir = __DIR__ . '/../public/uploads';

$assets = [
    // ================= Artikel =================
    'articles/article-1.jpg' => [
        'url'     => 'https://images.pexels.com/photos/6646924/pexels-photo-6646924.jpeg?auto=compress&cs=tinysrgb&w=1280',
        'title'   => 'Relawan menyerahkan paket sembako kepada lansia',
        'source'  => 'https://www.pexels.com/photo/6646924/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'articles/article-2.jpg' => [
        'url'     => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Banjir_Jakarta_2013.jpeg/1280px-Banjir_Jakarta_2013.jpeg',
        'title'   => 'Banjir Jakarta 2013',
        'source'  => 'https://commons.wikimedia.org/wiki/File:Banjir_Jakarta_2013.jpeg',
        'license' => 'CC BY-SA 3.0',
        'author'  => 'Thedonz (Indonesian Wikipedia)',
    ],
    'articles/article-3.jpg' => [
        'url'     => 'https://images.pexels.com/photos/6646918/pexels-photo-6646918.jpeg?auto=compress&cs=tinysrgb&w=1280',
        'title'   => 'Relawan mengemas paket bantuan',
        'source'  => 'https://www.pexels.com/photo/6646918/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],

    // ================= Kampanye =================
    'campaigns/campaign-1.jpg' => [
        'url'     => 'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=1600',
        'title'   => 'Rumah layak huni untuk asrama yatim',
        'source'  => 'https://www.pexels.com/photo/106399/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'campaigns/campaign-2.jpg' => [
        'url'     => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/6/64/Banjir_Jakarta_Perumahan_di_Tanjung_Duren_1_Januari_2020.jpg/1280px-Banjir_Jakarta_Perumahan_di_Tanjung_Duren_1_Januari_2020.jpg',
        'title'   => 'Banjir Jakarta, perumahan di Tanjung Duren, 1 Januari 2020',
        'source'  => 'https://commons.wikimedia.org/wiki/File:Banjir_Jakarta_Perumahan_di_Tanjung_Duren_1_Januari_2020.jpg',
        'license' => 'CC BY-SA 4.0',
        'author'  => 'Cun Cun',
    ],
    'campaigns/campaign-3.jpg' => [
        'url'     => 'https://images.pexels.com/photos/6688614/pexels-photo-6688614.jpeg?auto=compress&cs=tinysrgb&w=1600&h=900&fit=crop&crop=faces',
        'title'   => 'Potret lansia tersenyum',
        'source'  => 'https://www.pexels.com/photo/6688614/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'campaigns/campaign-4.jpg' => [
        'url'     => 'https://images.pexels.com/photos/8199562/pexels-photo-8199562.jpeg?auto=compress&cs=tinysrgb&w=1600',
        'title'   => 'Pelajar bersemangat di kelas',
        'source'  => 'https://www.pexels.com/photo/8199562/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'campaigns/campaign-5.jpg' => [
        'url'     => 'https://images.pexels.com/photos/3875225/pexels-photo-3875225.jpeg?auto=compress&cs=tinysrgb&w=1600',
        'title'   => 'Ibu dan bayi menerima dukungan nutrisi',
        'source'  => 'https://www.pexels.com/photo/3875225/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],

    // ================= Galeri =================
    'gallery/gallery-1.jpg' => [
        'url'     => 'https://images.pexels.com/photos/6646916/pexels-photo-6646916.jpeg?auto=compress&cs=tinysrgb&w=1280',
        'title'   => 'Relawan mengemas dan menyalurkan paket sembako',
        'source'  => 'https://www.pexels.com/photo/6646916/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'gallery/gallery-2.jpg' => [
        'url'     => 'https://images.pexels.com/photos/5998474/pexels-photo-5998474.jpeg?auto=compress&cs=tinysrgb&w=1280&h=720&fit=crop&crop=faces',
        'title'   => 'Dokter memeriksa kesehatan anak',
        'source'  => 'https://www.pexels.com/photo/5998474/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'gallery/gallery-3.jpg' => [
        'url'     => 'https://images.pexels.com/photos/2219024/pexels-photo-2219024.jpeg?auto=compress&cs=tinysrgb&w=1280',
        'title'   => 'Pekerjaan konstruksi bangunan',
        'source'  => 'https://www.pexels.com/photo/2219024/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'gallery/gallery-4.jpg' => [
        'url'     => 'https://images.pexels.com/photos/1148998/pexels-photo-1148998.jpeg?auto=compress&cs=tinysrgb&w=1280',
        'title'   => 'Anak-anak bermain dan melukis tangan',
        'source'  => 'https://www.pexels.com/photo/1148998/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'gallery/gallery-5.jpg' => [
        'url'     => 'https://images.pexels.com/photos/1720186/pexels-photo-1720186.jpeg?auto=compress&cs=tinysrgb&w=1280',
        'title'   => 'Anak-anak belajar dan menggambar di kelas',
        'source'  => 'https://www.pexels.com/photo/1720186/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],

    // ================= Kabar Kampanye =================
    'updates/update-1.jpg' => [
        'url'     => 'https://images.pexels.com/photos/1216589/pexels-photo-1216589.jpeg?auto=compress&cs=tinysrgb&w=1280',
        'title'   => 'Pekerja konstruksi asrama',
        'source'  => 'https://www.pexels.com/photo/1216589/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
    'updates/update-2.jpg' => [
        'url'     => 'https://images.pexels.com/photos/6994993/pexels-photo-6994993.jpeg?auto=compress&cs=tinysrgb&w=1280',
        'title'   => 'Distribusi paket logistik dan dapur umum',
        'source'  => 'https://www.pexels.com/photo/6994993/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],

    // ================= Dokumentasi Relawan =================
    'volunteers/report-vol-1.jpg' => [
        'url'     => 'https://images.pexels.com/photos/8612992/pexels-photo-8612992.jpeg?auto=compress&cs=tinysrgb&w=1280&h=720&fit=crop',
        'title'   => 'Anak-anak mengikuti kegiatan trauma healing',
        'source'  => 'https://www.pexels.com/photo/8612992/',
        'license' => 'Pexels License',
        'author'  => 'Pexels contributor',
    ],
];

$context = stream_context_create([
    'http' => [
        'timeout'       => 45,
        'user_agent'    => 'PeduliKasihDemo/1.0 (image fetcher)',
        'follow_location' => 1,
        'max_redirects' => 5,
    ],
]);

$ok = 0;
$failed = 0;

foreach ($assets as $relative => $meta) {
    $target = $baseDir . '/' . $relative;
    $dir = dirname($target);
    if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
        echo "FAIL  {$relative} — tidak bisa membuat folder\n";
        $failed++;
        continue;
    }

    echo "GET   {$relative} ... ";
    $data = @file_get_contents($meta['url'], false, $context);
    if ($data === false || strlen($data) < 1000) {
        echo "GAGAL (unduhan kosong)\n";
        $failed++;
        continue;
    }

    // Validasi & konversi ke JPEG bila perlu (mis. WebP/PNG).
    $info = @getimagesizefromstring($data);
    if (!$info) {
        echo "GAGAL (bukan gambar)\n";
        $failed++;
        continue;
    }

    $isJpeg = ($info[2] ?? 0) === IMAGETYPE_JPEG;
    if (!$isJpeg && function_exists('imagecreatefromstring')) {
        $img = @imagecreatefromstring($data);
        if ($img !== false) {
            ob_start();
            imagejpeg($img, null, 88);
            $data = ob_get_clean();
            imagedestroy($img);
            $isJpeg = true;
        }
    }

    if (!$isJpeg) {
        echo "GAGAL (format tidak didukung: {$info['mime']})\n";
        $failed++;
        continue;
    }

    if (file_put_contents($target, $data) === false) {
        echo "GAGAL (tidak bisa menulis file)\n";
        $failed++;
        continue;
    }

    $saved = @getimagesize($target);
    echo sprintf(
        "OK  %dx%d  %s KB\n",
        $saved[0] ?? 0,
        $saved[1] ?? 0,
        number_format(strlen($data) / 1024, 0, ',', '.')
    );
    $ok++;
}

// Tulis kredit foto.
$credits = "# Kredit & Lisensi Foto\n\n";
$credits .= "Foto-foto berikut diunduh dari internet untuk keperluan demo aplikasi. ";
$credits .= "Semua foto digunakan sesuai lisensi masing-masing.\n\n";
$credits .= "| File | Judul | Kreator | Lisensi | Sumber |\n";
$credits .= "| --- | --- | --- | --- | --- |\n";
foreach ($assets as $relative => $meta) {
    $credits .= sprintf(
        "| `%s` | %s | %s | %s | [tautan](%s) |\n",
        $relative,
        str_replace('|', '-', $meta['title']),
        str_replace('|', '-', $meta['author']),
        $meta['license'],
        $meta['source']
    );
}
$credits .= "\n- **Pexels License**: https://www.pexels.com/license/\n";
$credits .= "- **CC BY-SA 3.0**: https://creativecommons.org/licenses/by-sa/3.0/\n";
$credits .= "- **CC BY-SA 4.0**: https://creativecommons.org/licenses/by-sa/4.0/\n";

file_put_contents($baseDir . '/CREDITS.md', $credits);

echo "\nSelesai: {$ok} berhasil, {$failed} gagal. Kredit: public/uploads/CREDITS.md\n";
exit($failed > 0 ? 1 : 0);
