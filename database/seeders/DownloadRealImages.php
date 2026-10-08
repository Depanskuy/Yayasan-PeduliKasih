<?php
// database/seeders/DownloadRealImages.php
$uploadsDir = dirname(__DIR__, 2) . '/public/uploads/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Curated high quality photos from Unsplash for Indonesian / charity context
$photoMap = [
    // 1. Pembangunan Asrama Yatim Nurul Barokah (Construction of orphanage/school)
    'campaign-1.jpg' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',
    
    // 2. Bantuan Banjir Bandang (Flood rescue / disaster relief)
    'campaign-2.jpg' => 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
    
    // 3. Sembako Berkah Lansia (Elderly grandmother receiving care / food package)
    'campaign-3.jpg' => 'https://images.unsplash.com/photo-1581579438747-1dc8d17bbce4?auto=format&fit=crop&w=800&q=80',
    
    // 4. Beasiswa Asa Pelajar (Asian schoolchildren smiling with books in class)
    'campaign-4.jpg' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80',
    
    // 5. Bantuan Operasi & Medis Bayi (Doctor / hospital examining sick child)
    'campaign-5.jpg' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80',

    // Articles
    // Senyum Bahagia Kakek Sanusi (Elderly grandfather smiling)
    'article-1.jpg' => 'https://images.unsplash.com/photo-1506863530036-1efeddceb993?auto=format&fit=crop&w=800&q=80',
    // Transparansi Penyaluran Donasi Banjir (Volunteers distributing relief boxes)
    'article-2.jpg' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80',
    // 5 Cara Sederhana Membantu Sesama (Volunteers smiling / community hands)
    'article-3.jpg' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=800&q=80',

    // Gallery
    // Penyaluran Sembako Lansia Pesisir
    'gallery-1.jpg' => 'https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80',
    // Pemeriksaan Kesehatan Anak Korban Bencana (Medical checkup camp)
    'gallery-2.jpg' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=800&q=80',
    // Pengecoran Lantai 2 Asrama Yatim (Construction site masonry)
    'gallery-3.jpg' => 'https://images.unsplash.com/photo-1590486803833-1c5dc8ddd4c8?auto=format&fit=crop&w=800&q=80',
    // Trauma Healing dan Ceria Anak-Anak (Children drawing and smiling)
    'gallery-4.jpg' => 'https://images.unsplash.com/photo-1485546246426-74dc88dec4d9?auto=format&fit=crop&w=800&q=80',
    // Pembagian Paket Beasiswa & Seragam Sekolah (School kids with backpack)
    'gallery-5.jpg' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80',

    // Volunteer Events
    // Aksi Tanggap Bencana Makanan Hangat (Emergency relief food prep)
    'vol-event-1.jpg' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=800&q=80',
    // Ekspedisi Kasih Antar Sembako Pelosok (Volunteers walking with aid packages)
    'vol-event-2.jpg' => 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?auto=format&fit=crop&w=800&q=80',
    // Gotong Royong Pengecatan Asrama (Painting wall with paint roller)
    'vol-event-3.jpg' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=800&q=80',

    // Disbursements Proof (Bantuan Nyata)
    // Logistik Sembako & Selimut Posko Tanggap Banjir
    'disb-1.jpg' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
    // Pembelian Material Semen, Pasir, Bata Ringan
    'disb-2.jpg' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
    // Penyaluran Paket Sembako & Santunan
    'disb-3.jpg' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80',
    // Kwitansi SPP & Perlengkapan Sekolah
    'disb-4.jpg' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=800&q=80',

    // Laporan Relawan Lapangan
    'report-vol-1.jpg' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
];

echo "Downloading contextual photos...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

foreach ($photoMap as $filename => $url) {
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode === 200 && strlen($data) > 2000) {
        file_put_contents($uploadsDir . $filename, $data);
        echo "[OK] {$filename} downloaded (" . strlen($data) . " bytes)\n";
    } else {
        echo "[FAIL] {$filename} status {$httpCode}\n";
    }
}

curl_close($ch);
echo "Completed.\n";
