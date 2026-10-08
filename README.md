# Yayasan Peduli Kasih Sesama

Aplikasi web manajemen yayasan sosial berbasis **PHP Native MVC** yang dirancang untuk mengelola operasional yayasan, campaign penggalangan dana, donasi, program sosial, penerima manfaat, penyaluran bantuan, transparansi keuangan, artikel, galeri, dan relawan.

## 1. Tujuan Sistem

Sistem ini dibuat untuk membantu Yayasan Peduli Kasih Sesama dalam:

- Mengelola akun dan hak akses pengguna.
- Mengelola campaign penggalangan dana.
- Mencatat dan memverifikasi donasi.
- Mengelola program sosial dan penerima manfaat.
- Mencatat penyaluran bantuan.
- Menyediakan laporan dan transparansi keuangan.
- Mengelola kegiatan dan relawan.
- Mengelola artikel, berita, dan dokumentasi kegiatan.
- Menyediakan audit log untuk aktivitas penting sistem.

## 2. Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | PHP Native 8.x |
| Arsitektur | MVC |
| Database | MySQL |
| Frontend | HTML5, CSS3 |
| JavaScript | Vanilla JavaScript |
| Chart | Chart.js |
| PDF | Dompdf |
| Excel | PhpSpreadsheet |
| Server lokal | Laragon / XAMPP |
| Authentication | PHP Session |
| Password | `password_hash()` / `password_verify()` |

Framework backend seperti Laravel atau CodeIgniter **tidak digunakan**.

## 3. Role Pengguna

### Super Admin
Memiliki akses penuh terhadap sistem, termasuk:

- Dashboard.
- Manajemen user/staff.
- Campaign.
- Donasi.
- Program sosial.
- Penerima manfaat.
- Penyaluran bantuan.
- Laporan keuangan.
- Artikel dan galeri.
- Relawan.
- Audit log.
- Konfigurasi sistem.

### Staff / Admin Keuangan

- Input donasi offline.
- Verifikasi donasi online.
- Mengelola transaksi keuangan.
- Mencatat penyaluran bantuan.
- Membuat laporan keuangan.

### Donatur

- Melihat campaign.
- Melakukan donasi.
- Melihat riwayat donasi.
- Melihat status donasi.
- Mengunduh bukti donasi.
- Mengunduh e-sertifikat jika tersedia.
- Melihat transparansi program.

### Relawan

- Melihat kegiatan sosial.
- Mendaftar kegiatan.
- Melihat penugasan.
- Melakukan absensi.
- Mengirim laporan kegiatan.
- Mengunggah dokumentasi.

## 4. Fitur Utama

### Authentication

- Login.
- Register.
- Logout.
- Forgot password.
- Reset password.
- Role-based redirect.
- Profile.
- Change password.
- Activity history.

### Campaign & Donasi

- Daftar campaign.
- Detail campaign.
- Target dan progress dana.
- Batas waktu campaign.
- Campaign update.
- Galeri campaign.
- Form donasi.
- Donasi anonim.
- Transfer bank.
- E-wallet.
- QRIS.
- Status pending/verified/rejected/cancelled.
- Verifikasi donasi.
- Bukti donasi.
- E-sertifikat.

### Program Sosial

Campaign dan program sosial dipisahkan agar alur penggalangan dana dan penggunaan dana dapat dikelola secara jelas.

Contoh:

```text
Program:
Bantuan Pendidikan Anak Dhuafa

Campaign:
100 Beasiswa untuk Anak Lampung
```

### Penerima Manfaat

- Data penerima.
- Kategori penerima.
- Data kontak.
- Status penerima.
- Riwayat bantuan.

### Penyaluran Bantuan

- Dana.
- Sembako.
- Layanan kesehatan.
- Bantuan pendidikan.
- Program sosial lainnya.
- Relasi ke program/campaign.
- Bukti penyaluran.

### Transparansi Keuangan

- Total pemasukan.
- Total pengeluaran.
- Saldo.
- Statistik campaign.
- Grafik pemasukan/pengeluaran.
- Daftar transaksi.
- Export PDF.
- Export Excel.

### Artikel & Galeri

- Artikel.
- Berita kegiatan.
- Artikel inspiratif.
- Galeri foto.
- Dokumentasi kegiatan.

### Relawan

- Event/kegiatan sosial.
- Pendaftaran relawan.
- Penugasan.
- Absensi.
- Laporan kegiatan.
- Dokumentasi.

### Audit Log

Aktivitas penting dicatat, misalnya:

```text
08-10-2026 07:30
Admin
Menambahkan campaign "Bantuan Bencana Lampung"
```

## 5. Struktur Folder

```text
yayasan-peduli-kasih/
│
├── app/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   │   ├── layouts/
│   │   ├── components/
│   │   ├── auth/
│   │   ├── public/
│   │   ├── dashboard/
│   │   ├── users/
│   │   ├── campaigns/
│   │   ├── donations/
│   │   ├── beneficiaries/
│   │   ├── distributions/
│   │   ├── financial/
│   │   ├── articles/
│   │   ├── gallery/
│   │   ├── volunteers/
│   │   ├── volunteer-events/
│   │   └── attendance/
│   ├── core/
│   └── helpers/
│
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── index.php
│   ├── assets/
│   └── uploads/
├── routes/
├── storage/
├── vendor/
├── .env
├── .gitignore
├── composer.json
└── README.md
```

## 6. Prinsip MVC

### Model
Bertanggung jawab terhadap:

- Query database.
- Relasi data.
- Operasi CRUD.
- Akses data.

### View
Bertanggung jawab terhadap:

- HTML.
- Tampilan data.
- Form.
- Komponen UI.

View tidak boleh menjalankan query database secara langsung.

### Controller
Bertanggung jawab terhadap:

- Menerima request.
- Validasi input.
- Memanggil model.
- Menentukan view.
- Redirect.
- Menjalankan alur aplikasi.

## 7. Alur Donasi

```text
Public
  ↓
Campaign
  ↓
Detail Campaign
  ↓
Donasi
  ↓
Pilih Metode Pembayaran
  ↓
Konfirmasi
  ↓
Pending
  ↓
Pembayaran
  ↓
Verifikasi Staff
  ↓
Verified
  ↓
Update Campaign
  ↓
Update Laporan Keuangan
  ↓
Bukti Donasi / Sertifikat
```

## 8. Keamanan

Sistem harus menerapkan:

- Prepared statement / PDO.
- Password hashing.
- CSRF protection.
- XSS escaping.
- Server-side validation.
- Client-side validation.
- Session security.
- Role-based authorization.
- Upload validation.
- File type validation.
- File size validation.
- Audit log.
- Proteksi halaman berdasarkan role.

Output HTML harus menggunakan escaping seperti:

```php
htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
```

Query database harus menggunakan prepared statement.

## 9. Database Utama

Tabel utama:

```text
roles
users
password_resets
audit_logs

campaigns
campaign_updates
campaign_galleries

donations
donation_items
payments
certificates

programs
beneficiaries
distributions
financial_transactions

articles
galleries

volunteer_events
volunteer_registrations
volunteer_assignments
attendance
volunteer_reports
```

Semua tabel yang membutuhkan relasi harus menggunakan Foreign Key dan Index yang sesuai.

## 10. Status Donasi

Gunakan status:

```text
pending
verified
rejected
cancelled
```

Donasi hanya masuk ke laporan pemasukan resmi setelah berstatus `verified`.

## 11. Nomor Transaksi

Gunakan nomor transaksi yang mudah dibaca, misalnya:

```text
DON-20261008-0001
DON-20261008-0002

DIST-20261008-0001
DIST-20261008-0002
```

ID database tetap digunakan sebagai primary key.

## 12. Tahapan Pengembangan

### Fase 1
Core MVC:

- Router.
- Database.
- Controller.
- Model.
- View.
- Session.
- Middleware.
- CSRF.
- Validator.

### Fase 2
Authentication dan User Management.

### Fase 3
Campaign dan Program Sosial.

### Fase 4
Donasi dan Verifikasi.

### Fase 5
Penerima Manfaat dan Penyaluran.

### Fase 6
Laporan dan Transparansi.

### Fase 7
Relawan.

### Fase 8
Artikel dan Galeri.

### Fase 9
Audit Log dan Security Hardening.

### Fase 10
UI/UX dan Responsive Design.

## 13. Instalasi

### Clone / Copy Project

Letakkan project pada:

```text
C:\laragon\www\yayasan-peduli-kasih
```

atau folder web server yang digunakan.

### Install Dependency

```bash
composer install
```

### Konfigurasi Database

Buat database:

```sql
CREATE DATABASE yayasan_peduli_kasih;
```

Atur `.env`:

```env
APP_NAME="Yayasan Peduli Kasih Sesama"
APP_URL=http://localhost/yayasan-peduli-kasih

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yayasan_peduli_kasih
DB_USERNAME=root
DB_PASSWORD=
```

### Import Database

Import:

```text
database/migrations/database.sql
```

Kemudian jalankan seeder:

```text
database/seeders/DatabaseSeeder.php
```

## 14. Dummy Account

Seeder dapat menyediakan akun pengujian:

```text
Super Admin
Email    : admin@pedulikasih.test
Password : password

Staff
Email    : staff@pedulikasih.test
Password : password

Donatur
Email    : donatur@pedulikasih.test
Password : password

Relawan
Email    : relawan@pedulikasih.test
Password : password
```

Untuk deployment nyata, semua password dummy wajib diganti.

## 15. Prinsip UI/UX

Konsep visual:

- Profesional.
- Bersih.
- Hangat.
- Humanis.
- Trustworthy.
- Mobile-first.

Palet:

- Emerald/Sage sebagai warna utama.
- Putih dan abu-abu terang sebagai background.
- Oranye hangat sebagai CTA.
- Merah untuk error.
- Hijau untuk status berhasil.

Public website:

- Hero section.
- Campaign cards.
- Progress bar.
- Statistik.
- Program sosial.
- Testimoni.
- Galeri.
- Artikel.
- Transparansi.
- CTA donasi.

Dashboard:

- Sidebar.
- Navbar.
- Cards statistik.
- Data table.
- Search.
- Filter.
- Pagination.
- Modal.
- Toast.
- Chart.
- Empty state.
- Loading state.
- Error state.

## 16. Prinsip Pengembangan

Jangan membuat seluruh aplikasi dalam satu file.

Hindari:

```text
index.php
    ├── SQL
    ├── HTML
    ├── authentication
    ├── business logic
    └── CSS
```

Gunakan:

```text
Request
   ↓
Router
   ↓
Controller
   ↓
Model
   ↓
Database
   ↓
Controller
   ↓
View
```

Business logic, query database, dan tampilan harus dipisahkan.

## 17. Target Akhir

Aplikasi akhir harus mampu menghubungkan:

```text
Campaign
   ↓
Donasi
   ↓
Verifikasi
   ↓
Dana Terkumpul
   ↓
Program Sosial
   ↓
Penerima Manfaat
   ↓
Penyaluran Bantuan
   ↓
Laporan Keuangan
   ↓
Transparansi Publik
```

Serta:

```text
Relawan
   ↓
Kegiatan
   ↓
Pendaftaran
   ↓
Penugasan
   ↓
Absensi
   ↓
Laporan
   ↓
Dokumentasi
```

## 18. Status Proyek

Project masih dalam tahap pengembangan bertahap.

Prioritas pertama adalah membangun **Core MVC + Database + Authentication + Role Management** sebelum mengembangkan modul bisnis lainnya.
