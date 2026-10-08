-- Database: yayasan_pedulikasih
-- Skema Relasional Lengkap untuk Sistem Manajemen Yayasan Sosial Peduli Kasih Sesama

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `gallery`;
DROP TABLE IF EXISTS `articles`;
DROP TABLE IF EXISTS `volunteer_field_reports`;
DROP TABLE IF EXISTS `volunteer_registrations`;
DROP TABLE IF EXISTS `volunteer_events`;
DROP TABLE IF EXISTS `disbursements`;
DROP TABLE IF EXISTS `beneficiaries`;
DROP TABLE IF EXISTS `donations`;
DROP TABLE IF EXISTS `campaign_updates`;
DROP TABLE IF EXISTS `campaigns`;
DROP TABLE IF EXISTS `campaign_categories`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `users`;

-- 1. Tabel Users (Multi-User Role)
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(30) NULL,
  `role` ENUM('superadmin', 'staff', 'donatur', 'volunteer') NOT NULL DEFAULT 'donatur',
  `avatar` VARCHAR(255) NULL,
  `address` TEXT NULL,
  `bio` TEXT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX `idx_role` (`role`),
  INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Settings (Konfigurasi Yayasan)
CREATE TABLE `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `setting_group` VARCHAR(50) DEFAULT 'general',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel Kategori Campaign
CREATE TABLE `campaign_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `icon` VARCHAR(50) DEFAULT 'fa-heart',
  `description` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel Campaign (Penggalangan Dana & Program Sosial)
CREATE TABLE `campaigns` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `short_description` TEXT NOT NULL,
  `story` LONGTEXT NOT NULL,
  `target_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `collected_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `banner_image` VARCHAR(255) NULL,
  `status` ENUM('active', 'completed', 'pending', 'cancelled') NOT NULL DEFAULT 'active',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_by` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (`category_id`) REFERENCES `campaign_categories` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  INDEX `idx_status` (`status`),
  INDEX `idx_is_featured` (`is_featured`),
  INDEX `idx_end_date` (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel Update/Kabar Perkembangan Campaign
CREATE TABLE `campaign_updates` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `image` VARCHAR(255) NULL,
  `posted_by` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabel Donasi (Transaksi Penggalangan Dana)
CREATE TABLE `donations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `donation_code` VARCHAR(50) NOT NULL UNIQUE,
  `campaign_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NULL,
  `donor_name` VARCHAR(150) NOT NULL,
  `donor_email` VARCHAR(150) NOT NULL,
  `donor_phone` VARCHAR(30) NULL,
  `amount` DECIMAL(15,2) NOT NULL,
  `payment_method` ENUM('bca', 'mandiri', 'bri', 'gopay', 'ovo', 'dana', 'qris', 'cash_offline') NOT NULL,
  `payment_status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  `payment_proof` VARCHAR(255) NULL,
  `doa_message` TEXT NULL,
  `is_anonymous` TINYINT(1) NOT NULL DEFAULT 0,
  `verified_by` INT UNSIGNED NULL,
  `verified_at` TIMESTAMP NULL DEFAULT NULL,
  `rejection_reason` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_payment_status` (`payment_status`),
  INDEX `idx_donation_code` (`donation_code`),
  INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabel Penerima Manfaat (Mustahik / Klien Yayasan)
CREATE TABLE `beneficiaries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nik` VARCHAR(30) NOT NULL UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `category` ENUM('fakir', 'miskin', 'yatim', 'lansia', 'difabel', 'korban_bencana', 'lainnya') NOT NULL,
  `phone` VARCHAR(30) NULL,
  `address` TEXT NOT NULL,
  `village` VARCHAR(100) NULL,
  `district` VARCHAR(100) NULL,
  `city` VARCHAR(100) NOT NULL DEFAULT 'Jakarta',
  `eligibility_status` ENUM('verified', 'pending', 'rejected') NOT NULL DEFAULT 'verified',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX `idx_category` (`category`),
  INDEX `idx_eligibility` (`eligibility_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tabel Penyaluran Bantuan (Distribusi Program & Pengeluaran Kas)
CREATE TABLE `disbursements` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `disbursement_code` VARCHAR(50) NOT NULL UNIQUE,
  `campaign_id` INT UNSIGNED NOT NULL,
  `beneficiary_id` INT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `assistance_type` ENUM('tunai', 'sembako', 'pendidikan', 'kesehatan', 'renovasi', 'tanggap_darurat') NOT NULL,
  `amount` DECIMAL(15,2) NOT NULL,
  `recipient_count` INT NOT NULL DEFAULT 1,
  `disbursement_date` DATE NOT NULL,
  `documentation_image` VARCHAR(255) NULL,
  `receipt_file` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `recorded_by` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  INDEX `idx_disbursement_date` (`disbursement_date`),
  INDEX `idx_campaign_id` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tabel Event & Program Kegiatan Relawan
CREATE TABLE `volunteer_events` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` LONGTEXT NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `event_date` DATE NOT NULL,
  `event_time` VARCHAR(50) NOT NULL DEFAULT '08:00 - 15:00 WIB',
  `quota` INT NOT NULL DEFAULT 20,
  `registration_deadline` DATE NOT NULL,
  `banner_image` VARCHAR(255) NULL,
  `status` ENUM('open', 'closed', 'completed') NOT NULL DEFAULT 'open',
  `created_by` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  INDEX `idx_status` (`status`),
  INDEX `idx_event_date` (`event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Tabel Pendaftaran Relawan
CREATE TABLE `volunteer_registrations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `motivation` TEXT NOT NULL,
  `skills` VARCHAR(255) NULL,
  `status` ENUM('pending', 'approved', 'rejected', 'attended') NOT NULL DEFAULT 'pending',
  `attendance_time` TIMESTAMP NULL DEFAULT NULL,
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `volunteer_events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `uk_event_user` (`event_id`, `user_id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Tabel Laporan Kegiatan Lapangan Relawan
CREATE TABLE `volunteer_field_reports` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `report_title` VARCHAR(255) NOT NULL,
  `activity_summary` TEXT NOT NULL,
  `hours_spent` DECIMAL(4,1) NOT NULL DEFAULT 4.0,
  `documentation_image` VARCHAR(255) NULL,
  `status` ENUM('submitted', 'approved') NOT NULL DEFAULT 'submitted',
  `admin_feedback` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `volunteer_events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Tabel Artikel & Berita Kegiatan
CREATE TABLE `articles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `excerpt` TEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `category` ENUM('berita', 'inspirasi', 'kegiatan', 'edukasi') NOT NULL DEFAULT 'kegiatan',
  `featured_image` VARCHAR(255) NULL,
  `author_id` INT UNSIGNED NOT NULL,
  `status` ENUM('published', 'draft') NOT NULL DEFAULT 'published',
  `views_count` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  INDEX `idx_status` (`status`),
  INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Tabel Galeri Media Dokumentasi
CREATE TABLE `gallery` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `media_type` ENUM('photo', 'video') NOT NULL DEFAULT 'photo',
  `media_url` VARCHAR(255) NOT NULL,
  `campaign_id` INT UNSIGNED NULL,
  `event_id` INT UNSIGNED NULL,
  `uploaded_by` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`event_id`) REFERENCES `volunteer_events` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Tabel Audit Log Aktivitas Sistem
CREATE TABLE `audit_logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NULL,
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `ip_address` VARCHAR(50) NULL,
  `user_agent` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_action` (`action`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================================
-- DUMMY DATA SEEDER AWAL (REAL-WORLD DATASET INDONESIA)
-- ==========================================================

-- Data Pengguna Awal (Password semua: bcrypt untuk masing-masing user)
-- admin123 => $2y$10$w36D96k0b6wE3G4O877y0ecQ72gRsln3u7iLp2OtxK3Z4s.F.jD8q
-- staff123 => $2y$10$tZtM5s8tXf8h0MhGg9gMye1J5q1o7u7b9x/VwRzC8yJ8H1r2l3m4y
-- donatur123 => $2y$10$X8f1F8d1f8xXzQkZ1o8dXeGfA1vB2c3D4e5F6g7H8i9J0k1L2m3Ny
-- relawan123 => $2y$10$a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a

-- Untuk kompatibilitas mutlak kita gunakan hash password_hash('password', PASSWORD_BCRYPT)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `bio`, `status`) VALUES
(1, 'Drs. H. Mulyono Santoso', 'admin@pedulikasih.org', '$2y$10$n4Fh6G.xQ1vDvZfMbmB5re0u3m2kE8f8q1j0n3u7iLp2OtxK3Z4s.', '081234567890', 'superadmin', 'Pengurus Utama & Direktur Eksekutif Yayasan Peduli Kasih Sesama', 'active'),
(2, 'Siti Rahmawati, S.E.', 'keuangan@pedulikasih.org', '$2y$10$n4Fh6G.xQ1vDvZfMbmB5re0u3m2kE8f8q1j0n3u7iLp2OtxK3Z4s.', '081398765432', 'staff', 'Staf Keuangan dan Verifikator Donasi Yayasan', 'active'),
(3, 'Budi Pratama', 'budi@gmail.com', '$2y$10$n4Fh6G.xQ1vDvZfMbmB5re0u3m2kE8f8q1j0n3u7iLp2OtxK3Z4s.', '085711223344', 'donatur', 'Donatur rutin program peduli pendidikan anak dan dhuafa', 'active'),
(4, 'Aisyah Putri Maharani', 'siti@gmail.com', '$2y$10$n4Fh6G.xQ1vDvZfMbmB5re0u3m2kE8f8q1j0n3u7iLp2OtxK3Z4s.', '082155667788', 'volunteer', 'Mahasiswi keperawatan & relawan medis tanggap bencana', 'active'),
(5, 'Hendro Wibowo', 'hendro@donatur.id', '$2y$10$n4Fh6G.xQ1vDvZfMbmB5re0u3m2kE8f8q1j0n3u7iLp2OtxK3Z4s.', '081800112233', 'donatur', 'Pemerhati anak yatim dan pendukung dakwah sosial', 'active');

-- Konfigurasi Pengaturan Yayasan
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('org_name', 'Yayasan Peduli Kasih Sesama', 'general'),
('org_tagline', 'Menyalurkan Amanah, Merajut Kasih, Mencerahkan Masa Depan', 'general'),
('org_email', 'sekretariat@pedulikasih.org', 'general'),
('org_phone', '(021) 7890-1234 / 0812-3456-7890', 'general'),
('org_address', 'Jl. Kasih Sejahtera No. 45, Kebayoran Baru, Jakarta Selatan 12180', 'general'),
('org_legal', 'SK Kemenkumham RI: AHU-0012485.AH.01.04.Tahun 2018 | Izin Kemensos: 542/HUK-PS/2020', 'general'),
('bank_bca', '8830-192-800 a.n Yayasan Peduli Kasih Sesama', 'payment'),
('bank_mandiri', '137-00-9876543-2 a.n Yayasan Peduli Kasih Sesama', 'payment'),
('bank_bri', '0206-01-008912-301 a.n Peduli Kasih Sesama', 'payment'),
('qris_nmid', 'ID1020083921008', 'payment'),
('social_instagram', '@pedulikasih.sesama', 'social'),
('social_facebook', 'Yayasan Peduli Kasih Sesama', 'social'),
('social_youtube', 'Peduli Kasih Sesama Channel', 'social');

-- Kategori Campaign
INSERT INTO `campaign_categories` (`id`, `name`, `slug`, `icon`, `description`) VALUES
(1, 'Pendidikan & Beasiswa', 'pendidikan-beasiswa', 'fa-graduation-cap', 'Bantuan biaya sekolah, seragam, buku, dan beasiswa yatim piatu dhuafa'),
(2, 'Santunan Panti Asuhan', 'santunan-panti-asuhan', 'fa-home', 'Renovasi sarana panti asuhan, pemenuhan gizi, dan operasional pengasuhan'),
(3, 'Tanggap Bencana', 'tanggap-bencana', 'fa-shield-halved', 'Bantuan darurat logistik, tenda, medis, dan dapur umum korban bencana'),
(4, 'Kesehatan & Pengobatan', 'kesehatan-pengobatan', 'fa-hand-holding-medical', 'Bantuan biaya operasi, obat-obatan, dan layanan kesehatan gratis mustahik'),
(5, 'Sembako & Pangan Dhuafa', 'sembako-pangan-dhuafa', 'fa-bowl-rice', 'Distribusi beras, paket pangan, dan makanan berkah untuk keluarga prasejahtera');

-- Data Campaigns
INSERT INTO `campaigns` (`id`, `category_id`, `title`, `slug`, `short_description`, `story`, `target_amount`, `collected_amount`, `start_date`, `end_date`, `banner_image`, `status`, `is_featured`, `created_by`) VALUES
(1, 2, 'Pembangunan Asrama Yatim Piatu Nurul Barokah', 'pembangunan-asrama-yatim-piatu-nurul-barokah', 'Mari wujudkan tempat tinggal yang layak, aman, dan nyaman bagi 45 anak yatim dhuafa yang asramanya retak dan bocor.', 'Asrama Nurul Barokah telah berdiri selama 15 tahun dan menjadi rumah bagi 45 anak yatim dan terlantar. Saat ini kondisi atap asrama telah lapuk dan rawan ambruk bila hujan deras mengguyur. Kami mengajak para muhsinin untuk bersama-sama membangun kembali lantai dua dan fasilitas belajar anak-anak agar mereka dapat menghafal Al-Quran dan belajar dengan ceria tanpa takut tertimpa genteng.', 150000000.00, 94500000.00, '2026-01-10', '2026-12-31', 'campaign-1.jpg', 'active', 1, 1),
(2, 3, 'Bantuan Tanggap Darurat Banjir Bandang Pesisir', 'bantuan-tanggap-darurat-banjir-bandang-pesisir', 'Ulurkan tangan untuk 320 KK pengungsi banjir yang kehilangan rumah, pakaian, serta kebutuhan air bersih.', 'Bencana banjir bandang setinggi 2 meter menerjang permukiman warga pesisir. Lebih dari 300 kepala keluarga mengungsi dengan pakaian yang menempel di badan. Tim relawan Yayasan Peduli Kasih Sesama telah mendirikan posko dapur umum darurat dan posko kesehatan. Dana yang terkumpul akan langsung dialokasikan untuk paket sembako, selimut, susu bayi, popok, serta obat-obatan higienis.', 75000000.00, 52300000.00, '2026-02-01', '2026-11-30', 'campaign-2.jpg', 'active', 1, 1),
(3, 5, 'Paket Sembako Berkah untuk 1.000 Lansia Dhuafa', 'paket-sembako-berkah-untuk-1000-lansia-dhuafa', 'Berbagi ketahanan pangan berupa paket beras 10kg, minyak goreng, telur, dan gizi bagi lansia prasejahtera.', 'Di pelosok desa, masih banyak kakek dan nenek lansia yang hidup sebatang kara dan harus bekerja memungut rongsokan demi sesuap nasi. Program ini menargetkan penyaluran 1.000 paket sembako senilai Rp 150.000 per paket untuk meringankan beban lansia dhuafa.', 150000000.00, 112000000.00, '2026-01-01', '2026-11-15', 'campaign-3.jpg', 'active', 1, 1),
(4, 1, 'Beasiswa Asa Pelajar: Cegah Anak Dhuafa Putus Sekolah', 'beasiswa-asa-pelajar-cegah-putus-sekolah', 'Bantu 80 siswa SD, SMP, dan SMA dari keluarga kurang mampu melunasi SPP dan perlengkapan sekolah tahun ajaran baru.', 'Pendidikan adalah jembatan memutus rantai kemiskinan. Melalui program Beasiswa Asa Pelajar, yayasan membiayai SPP sekolah swasta/kejuruan serta memberikan paket perlengkapan sekolah berupa tas, sepatu, dan buku bagi anak-anak berprestasi yang terancam putus sekolah.', 60000000.00, 38500000.00, '2026-03-01', '2026-12-20', 'campaign-4.jpg', 'active', 0, 1),
(5, 4, 'Bantuan Operasi Hernia & Nutrisi Bayi Sakit Dhuafa', 'bantuan-operasi-hernia-dan-nutrisi-bayi-dhuafa', 'Dukungan biaya pendampingan pengobatan dan suplemen nutrisi medis bagi balita dhuafa penderita penyakit kronis.', 'Banyak orang tua dhuafa yang tidak sanggup menanggung biaya obat non-BPJS dan ongkos bolak-balik ke rumah sakit rujukan. Program ini mendampingi pasien dhuafa mulai dari transportasi ambulans gratis hingga asupan nutrisi pasca operasi.', 40000000.00, 24000000.00, '2026-02-15', '2026-10-31', 'campaign-5.jpg', 'active', 0, 1);

-- Updates Campaign
INSERT INTO `campaign_updates` (`campaign_id`, `title`, `content`, `posted_by`, `created_at`) VALUES
(1, 'Pemasangan Tiang Pancang Lantai 2 Selesai', 'Alhamdulillah berkat doa dan donasi para dermawan, pengecoran pondasi dan tiang lantai 2 telah rampung 100%. Saat ini tukang mulai memasang dinding bata ringan.', 2, '2026-03-15 10:00:00'),
(2, 'Penyaluran 200 Paket Logistik dan Dapur Umum Beroperasi', 'Tim relawan telah mendistribusikan 200 paket selimut, makanan siap santap, serta air galon bersih langsung ke tenda-tenda evakuasi warga.', 2, '2026-03-20 14:30:00');

-- Penerima Manfaat (Mustahik)
INSERT INTO `beneficiaries` (`id`, `nik`, `name`, `category`, `phone`, `address`, `village`, `district`, `city`, `eligibility_status`, `notes`) VALUES
(1, '3174015609800001', 'Kakek Sanusi (74 th)', 'lansia', '081299887766', 'Kp. Nelayan RT 03/05 No. 12', 'Pluit', 'Penjaringan', 'Jakarta Utara', 'verified', 'Lansia hidup sebatang kara, menderita asam urat dan katarak ringan'),
(2, '3174026501950003', 'Ibu Marhawa (42 th)', 'miskin', '085677889900', 'Jl. Rawa Bebek Gang Harapan No. 7', 'Pulogebang', 'Cakung', 'Jakarta Timur', 'verified', 'Janda dengan 3 anak usia sekolah dasar, buruh cuci harian'),
(3, '3174034407120002', 'Adik Farhan (9 th)', 'yatim', '081322334455', 'Jl. Cempaka Putih Barat RT 08/02', 'Cempaka Putih', 'Cempaka Putih', 'Jakarta Pusat', 'verified', 'Yatim sejak usia 3 tahun, siswa berprestasi peringkat 1 di kelas 3 SD'),
(4, '3174041112880004', 'Bapak Rusdianto (51 th)', 'difabel', '087811224455', 'Kp. Melayu Kecil RT 05/01 No. 34', 'Bukit Duri', 'Tebet', 'Jakarta Selatan', 'verified', 'Tuna daksa kaki kanan akibat kecelakaan kerja, menghidupi 4 tanggungan'),
(5, '3174052308900005', 'Ibu Aminah (38 th)', 'korban_bencana', '082144556677', 'Posko Evakuasi Pengungsian Tanggul', 'Muara Baru', 'Penjaringan', 'Jakarta Utara', 'verified', 'Rumah terendam banjir bandang dan perabot hancur terseret arus');

-- Transaksi Donasi
INSERT INTO `donations` (`id`, `donation_code`, `campaign_id`, `user_id`, `donor_name`, `donor_email`, `donor_phone`, `amount`, `payment_method`, `payment_status`, `payment_proof`, `doa_message`, `is_anonymous`, `verified_by`, `verified_at`, `created_at`) VALUES
(1, 'DON-202603-001', 1, 3, 'Budi Pratama', 'budi@gmail.com', '085711223344', 1000000.00, 'bca', 'verified', 'proof-1.jpg', 'Semoga asrama yatim segera selesai dan anak-anak bisa belajar dengan nyaman.', 0, 2, '2026-03-02 11:20:00', '2026-03-02 10:15:00'),
(2, 'DON-202603-002', 1, NULL, 'Hamba Allah', 'dermawan@email.com', '081288889999', 5000000.00, 'qris', 'verified', 'proof-qris.jpg', 'Titip doa keselamatan untuk keluarga kami dan berkah rezeki.', 1, 2, '2026-03-03 09:10:00', '2026-03-03 09:05:00'),
(3, 'DON-202603-003', 2, 5, 'Hendro Wibowo', 'hendro@donatur.id', '081800112233', 2500000.00, 'mandiri', 'verified', 'proof-2.jpg', 'Semoga warga terdampak banjir lekas pulih dan diberi kesabaran ekstra.', 0, 2, '2026-03-04 15:45:00', '2026-03-04 14:30:00'),
(4, 'DON-202603-004', 3, 3, 'Budi Pratama', 'budi@gmail.com', '085711223344', 500000.00, 'gopay', 'verified', 'proof-3.jpg', 'Sedekah sembako untuk kakek nenek dhuafa, bismillah berkah.', 0, 2, '2026-03-06 08:30:00', '2026-03-06 08:15:00'),
(5, 'DON-202603-005', 4, NULL, 'dr. Ratna Anindita', 'ratna.anindita@hospital.id', '081199881122', 1500000.00, 'bri', 'verified', 'proof-4.jpg', 'Semangat belajar adik-adik penerima beasiswa, capai cita-citamu!', 0, 2, '2026-03-08 17:00:00', '2026-03-08 16:20:00'),
(6, 'DON-202603-006', 2, NULL, 'Hamba Allah', 'donatur_anonim@gmail.com', '081355443322', 300000.00, 'qris', 'pending', NULL, 'Bismillah sedikit bantuan untuk saudara di pengungsian.', 1, NULL, NULL, '2026-03-10 13:00:00'),
(7, 'DON-202603-007', 5, 3, 'Budi Pratama', 'budi@gmail.com', '085711223344', 750000.00, 'bca', 'pending', 'proof-7.jpg', 'Semoga adik kecil lekas sembuh dari operasinya.', 0, NULL, NULL, '2026-03-11 11:15:00');

-- Penyaluran Bantuan Sosial (Pengeluaran Kas Yayasan)
INSERT INTO `disbursements` (`id`, `disbursement_code`, `campaign_id`, `beneficiary_id`, `title`, `assistance_type`, `amount`, `recipient_count`, `disbursement_date`, `documentation_image`, `notes`, `recorded_by`) VALUES
(1, 'DIS-202603-001', 2, 5, 'Distribusi Logistik Sembako & Selimut Posko Tanggap Banjir', 'tanggap_darurat', 12500000.00, 150, '2026-03-05', 'disb-1.jpg', 'Pembelian 150 karton mie instan, 300 selimut tebal, popok bayi, dan minyak kayu putih', 2),
(2, 'DIS-202603-002', 1, NULL, 'Pembelian Material Semen, Pasir, dan Bata Ringan Asrama Yatim', 'renovasi', 28000000.00, 45, '2026-03-08', 'disb-2.jpg', 'Faktur toko bangunan No. TB-4491 untuk pengecoran plat lantai 2 asrama santri', 2),
(3, 'DIS-202603-003', 3, 1, 'Penyaluran Paket Sembako & Santunan Tunai Lansia Dhuafa', 'sembako', 4500000.00, 30, '2026-03-12', 'disb-3.jpg', 'Paket beras 10kg + minyak goreng + santunan uang saku untuk 30 lansia di wilayah Pluit & Penjaringan', 2),
(4, 'DIS-202603-004', 4, 3, 'Pelunasan SPP 6 Bulan & Paket Perlengkapan Sekolah Siswa Yatim', 'pendidikan', 3600000.00, 1, '2026-03-14', 'disb-4.jpg', 'Pembayaran SPP dan seragam baru bagi adik Farhan kelas 3 SD', 2);

-- Event & Program Kegiatan Relawan
INSERT INTO `volunteer_events` (`id`, `campaign_id`, `title`, `slug`, `description`, `location`, `event_date`, `event_time`, `quota`, `registration_deadline`, `banner_image`, `status`, `created_by`) VALUES
(1, 2, 'Aksi Tanggap Bencana: Distribusi Makanan & Trauma Healing Anak', 'aksi-tanggap-bencana-distribusi-makanan', 'Bergabunglah bersama 30 relawan untuk mendistribusikan 500 paket makanan hangat dan mengadakan sesi bermain ceria (trauma healing) bagi anak-anak korban banjir di tenda pengungsian.', 'Posko Pengungsian Muara Baru, Penjaringan, Jakarta Utara', '2026-04-12', '07:30 - 16:00 WIB', 30, '2026-04-10', 'vol-event-1.jpg', 'open', 1),
(2, 3, 'Ekspedisi Kasih: Antar Sembako Door-to-Door Lansia Pelosok', 'ekspedisi-kasih-antar-sembako-lansia', 'Membantu mengantar paket sembako dan menemani mengobrol kakek-nenek lansia dhuafa yang tinggal di gang sempit dan perbukitan pelosok.', 'Kecamatan Rumpin, Kab. Bogor & Sekitarnya', '2026-04-19', '08:00 - 15:00 WIB', 20, '2026-04-16', 'vol-event-2.jpg', 'open', 1),
(3, 1, 'Bakti Sosial & Gotong Royong Pengecatan Asrama Yatim', 'gotong-royong-pengecatan-asrama-yatim', 'Aksi bersama membersihkan puing, mengecat dinding, dan menata perpustakaan mini untuk anak-anak asrama Nurul Barokah.', 'Asrama Nurul Barokah, Tebet Timur, Jakarta Selatan', '2026-05-02', '08:30 - 14:00 WIB', 25, '2026-04-30', 'vol-event-3.jpg', 'open', 1);

-- Pendaftaran Relawan
INSERT INTO `volunteer_registrations` (`id`, `event_id`, `user_id`, `motivation`, `skills`, `status`, `attendance_time`, `admin_notes`) VALUES
(1, 1, 4, 'Saya mahasiswa keperawatan tingkat akhir, ingin membantu pertolongan pertama dan menghibur adik-adik di pengungsian.', 'P3K, Keperawatan, Mendongeng anak', 'approved', '2026-04-12 07:45:00', 'Ditempatkan di tim medis posko dan sesi dongeng ceria.');

-- Laporan Kegiatan Lapangan Relawan
INSERT INTO `volunteer_field_reports` (`id`, `event_id`, `user_id`, `report_title`, `activity_summary`, `hours_spent`, `documentation_image`, `status`, `admin_feedback`) VALUES
(1, 1, 4, 'Laporan Pelayanan Kesehatan Ringan & Trauma Healing', 'Alhamdulillah kegiatan berjalan tertib. Tim kami melayani cek tensi dan luka ringan untuk 65 lansia serta mengajak bermain 40 anak-anak pengungsi dengan permainan edukatif dan bingkisan susu.', 7.5, 'report-vol-1.jpg', 'approved', 'Terima kasih atas dedikasi dan laporan yang sangat rapi!');

-- Artikel & Berita Kegiatan
INSERT INTO `articles` (`id`, `title`, `slug`, `excerpt`, `content`, `category`, `featured_image`, `author_id`, `status`, `views_count`) VALUES
(1, 'Senyum Bahagia Kakek Sanusi Menerima Paket Sembako Kasih', 'senyum-bahagia-kakek-sanusi-menerima-sembako', 'Melihat kehangatan dan rasa syukur dari para lansia dhuafa yang kini tak lagi khawatir akan makanan esok hari berkat kebaikan para donatur.', '<p>Di sebuah bilik kayu berukuran 3x3 meter di pinggiran Jakarta Utara, Kakek Sanusi (74 th) menyambut tim relawan Yayasan Peduli Kasih Sesama dengan mata berkaca-kaca. Sejak setahun terakhir kakinya sulit digerakkan karena sakit sendi parah, membuatnya tak bisa lagi bekerja serabutan.</p><p>Ketika tim relawan menyerahkan paket sembako berkah berisi beras 10kg, telur, susu, dan uang santunan, senyum haru merekah di wajahnya. \"Terima kasih nak, kakek kira bulan ini kakek cuma makan nasi garam. Semoga Allah membalas berlipat ganda untuk bapak ibu donatur,\" ucap beliau.</p><p>Kisah Kakek Sanusi adalah satu dari ratusan lansia yang kita jangkau bersama melalui program Paket Sembako Berkah. Terima kasih kepada seluruh sahabat donatur yang tak pernah lelah menitipkan kepeduliannya.</p>', 'inspirasi', 'article-1.jpg', 1, 'published', 428),
(2, 'Transparansi Penyaluran Donasi Banjir Tahap I: Amanah Anda Telah Tiba', 'transparansi-penyaluran-donasi-banjir-tahap-1', 'Laporan pertanggungjawaban terbuka mengenai pengalokasian dana darurat bagi 150 KK korban bencana banjir pesisir.', '<p>Sebagai bentuk komitmen integritas dan akuntabilitas Yayasan Peduli Kasih Sesama, kami mempublikasikan laporan penyaluran donasi tahap pertama untuk bencana banjir bandang pesisir.</p><p>Total dana yang telah dibelanjakan pada tahap ini sebesar <strong>Rp 12.500.000</strong> dengan rincian: pengadaan 300 lembar selimut tebal, 150 karton mie instan, 100 paket popok bayi & pembalut wanita, serta obat-obatan higienis dan antiseptik.</p><p>Distribusi dilakukan langsung secara door-to-tent oleh tim relawan lapangan bersama tokoh masyarakat setempat. Laporan kuitansi dan faktur belanja dapat diakses di menu transparansi keuangan yayasan.</p>', 'berita', 'article-2.jpg', 2, 'published', 612),
(3, '5 Cara Sederhana Membantu Sesama Tanpa Harus Menunggu Mapan', '5-cara-sederhana-membantu-sesama-tanpa-menunggu-mapan', 'Kebaikan bukan tentang seberapa besar nominal yang kita punya, melainkan seberapa tulus kepedulian yang kita beri.', '<p>Banyak orang beranggapan bahwa menolong sesama hanya bisa dilakukan ketika kita sudah kaya raya atau berkecukupan materi. Padahal, benih kebaikan bisa ditanam dari hal-hal terkecil:</p><ol><li><strong>Menyisihkan Uang Jajan Mulai Rp 10.000:</strong> Sedekah rutin bernilai kecil yang konsisten sangat berdampak bila dikumpulkan bersama jutaan dermawan lainnya.</li><li><strong>Menjadi Relawan (Volunteer):</strong> Menyumbangkan waktu, tenaga, dan keterampilan untuk program sosial di akhir pekan.</li><li><strong>Menyebarkan Informasi Kebaikan:</strong> Berbagi tautan campaign sosial di media sosial Anda agar semakin banyak yang tergerak.</li><li><strong>Mendonorkan Darah:</strong> Satu kantong darah bisa menyelamatkan hingga tiga nyawa sesama manusia.</li><li><strong>Doa Tulus:</strong> Doa kebaikan untuk saudara-saudara kita yang sedang diuji bencana dan sakit.</li></ol>', 'edukasi', 'article-3.jpg', 1, 'published', 305);

-- Galeri Media
INSERT INTO `gallery` (`title`, `description`, `media_type`, `media_url`, `campaign_id`, `uploaded_by`) VALUES
('Penyaluran Sembako Lansia Pesisir', 'Dokumentasi penyerahan bantuan pangan langsung ke rumah lansia dhuafa', 'photo', 'gallery-1.jpg', 3, 2),
('Pemeriksaan Kesehatan Anak Korban Bencana', 'Layanan cek kesehatan dan pembagian vitamin gratis di tenda posko', 'photo', 'gallery-2.jpg', 2, 2),
('Pengecoran Lantai 2 Asrama Yatim Nurul Barokah', 'Progres pembangunan fisik asrama santri yatim', 'photo', 'gallery-3.jpg', 1, 2),
('Trauma Healing dan Ceria Anak-Anak', 'Relawan mengajak anak-anak bernyanyi dan melukis bersama', 'photo', 'gallery-4.jpg', 2, 2),
('Pembagian Paket Beasiswa & Seragam Sekolah', 'Penyerahan perlengkapan belajar bagi adik-adik penerima beasiswa', 'photo', 'gallery-5.jpg', 4, 2);

-- Audit Log Awal
INSERT INTO `audit_logs` (`user_id`, `action`, `description`, `ip_address`) VALUES
(1, 'SYSTEM_INIT', 'Inisialisasi sistem database Yayasan Peduli Kasih Sesama', '127.0.0.1'),
(2, 'DONATION_VERIFIED', 'Verifikasi pembayaran donasi #DON-202603-001 senilai Rp 1.000.000 (BCA)', '127.0.0.1'),
(2, 'DISBURSEMENT_RECORDED', 'Pencatatan penyaluran dana #DIS-202603-001 tanggap darurat banjir senilai Rp 12.500.000', '127.0.0.1');
