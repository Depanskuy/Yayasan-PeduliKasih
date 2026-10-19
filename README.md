# Yayasan Peduli Kasih Sesama

Aplikasi web manajemen yayasan sosial berbasis **PHP MVC murni** dengan antarmuka **Tailwind CSS** (dark theme).

## Fitur

- **Publik**: beranda, daftar & detail kampanye, form donasi, kegiatan relawan, artikel, galeri, tentang, kontak.
- **Autentikasi**: login, registrasi donatur, logout, middleware role (superadmin/staff/donatur/volunteer), CSRF protection.
- **Admin**:
  - Dashboard statistik & ringkasan.
  - Manajemen user (aktif/nonaktif).
  - CRUD kampanye (tambah, edit, hapus/soft delete, unggulan).
  - Verifikasi / penolakan donasi (otomatis menyesuaikan `collected_amount`).
  - Pencatatan penyaluran dana.
  - Manajemen penerima manfaat.
  - Pendaftaran relawan & perubahan status.
  - Laporan keuangan + ekspor CSV.
  - CRUD artikel (kategori, pencarian, penghitung dibaca).
  - Manajemen galeri foto/video.
  - Update perkembangan kampanye.
  - Verifikasi laporan lapangan relawan.
  - Audit log aktivitas pengguna.
- **API**: `GET /api/donations/monthly` (JSON).

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | PHP 8+, PDO, arsitektur MVC murni |
| Database | MySQL / MariaDB (`yayasan_pedulikasih`) |
| Styling | Tailwind CSS 3 (build lokal) |
| Ikon | Lucide |
| Font | Inter |

## Persyaratan

- PHP 8.0+ dengan ekstensi `pdo_mysql`
- MySQL / MariaDB (Laragon)
- Node.js + npm (untuk build Tailwind)
- Composer (opsional; ada autoloader fallback)

## Instalasi

1. **Jalankan MySQL** dari Laragon.

2. **Impor database** (jika belum ada):
   ```bash
   mysql -u root yayasan_pedulikasih < database.sql
   ```
   Atau impor `database.sql` lewat phpMyAdmin.

3. **Autoload Composer** (opsional):
   ```bash
   composer dump-autoload
   ```

4. **Build aset Tailwind**:
   ```bash
   npm install
   npm run build
   ```

5. **Akses aplikasi**:
   - Jika document root diarahkan ke `public/` → `http://localhost/`
   - Jika lewat folder proyek → `http://localhost/Project_PGAI/public/`

> Catatan: `public/index.php` sudah menghitung base path otomatis.

## Akun Demo

Semua akun memakai password: **`password123`**

| Role | Email |
| --- | --- |
| Superadmin | `admin@pedulikasih.org` |
| Staff | `keuangan@pedulikasih.org` |
| Donatur | `budi@gmail.com` |
| Volunteer | `siti@gmail.com` |

Jika database lama masih memakai hash placeholder, jalankan:
```bash
php scripts/set_passwords.php
```

## Pengembangan

```bash
npm run dev      # Tailwind watch mode
npm run build    # build minified ke public/css/app.css
```

## Foto Demo

Gambar artikel, banner kampanye, galeri, kabar kampanye, dan dokumentasi relawan disimpan di `public/uploads/` dan diunduh dari internet (Pexels & Wikimedia Commons). Untuk mengunduh ulang:

```bash
php scripts/fetch_images.php
```

Daftar sumber, kreator, dan lisensi ada di [`public/uploads/CREDITS.md`](public/uploads/CREDITS.md).

## Struktur

```
app/
  Controllers/   Auth, Home, Campaign, Donation, Volunteer, Article, Gallery,
                 Admin*, Report
  Core/          Router, Controller, Model, View, Middleware, Helper
  Models/        User, Campaign, Donation, Disbursement, Beneficiary,
                 VolunteerEvent, Report, Setting, Article, Gallery,
                 CampaignUpdate, VolunteerFieldReport, AuditLog
  Views/
    layouts/     public.php, admin.php
    partials/    navbar, footer, flash, campaign_card
    admin/       dashboard, users, campaigns, donations, disbursements,
                 beneficiaries, volunteers, reports, articles, gallery,
                 campaign_updates, field_reports, audit_logs, *_form
    auth/        login, register
    articles/    index, show
    gallery/     index
    campaigns/   index, show
    home/        index, about, contact
    volunteer/   events, register, reports, report_form
    errors/      404, 500
config/          config.php, database.php
public/          index.php, .htaccess, css/, js/, uploads/
resources/css/   app.css (sumber Tailwind)
scripts/         check_db.php, set_passwords.php, fetch_images.php, router.php
```

## Catatan Keamanan

- Semua form POST memakai token CSRF.
- Password disimpan dengan `password_hash()` (bcrypt).
- Query memakai prepared statement PDO.
- Upload dibatasi tipe & ukuran (maks 5 MB).
