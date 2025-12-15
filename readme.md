# Invpusda - Sistem Inventory Management
<!-- README updated to reflect current features (Dec 2025) -->
# Invpusda - Sistem Inventory & Request Management

![Invpusda Banner](docs/banner.png)

<div align="center">

[![Laravel Version](https://img.shields.io/badge/Laravel-5.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-7.0+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**Aplikasi manajemen inventaris dan permintaan dinas berbasis Laravel 5**

</div>

---

## 📋 Sekilas

Invpusda adalah aplikasi untuk mengelola inventaris, permintaan barang, layanan pendukung (catering, link Zoom), serta peminjaman kendaraan dinas. Aplikasi sudah dilengkapi alur permintaan — persetujuan admin — pelaporan, dan notifikasi WA (via FontteService).

## ✨ Fitur Utama (saat ini)

- **Manajemen Barang**: CRUD barang, penyesuaian stok, dan riwayat transaksi.
- **Permintaan Barang**: Form permintaan, approval oleh admin bidang, pengurangan stok otomatis saat disetujui.
- **Permintaan Catering (Konsumsi)**: Form pengajuan, upload nota, modal preview, status approval, dan fitur laporan.
- **Permintaan Link Zoom**: Form request Zoom dengan jadwal dan notifikasi ke admin bidang.
- **Peminjaman Kendaraan Dinas**: Form publik untuk meminjam kendaraan, daftar unit kendaraan, admin approve/reject/complete, cek bentrok jadwal, dan cooldown 2 hari berbasis tanggal.
- **Manajemen Kendaraan**: CRUD unit kendaraan (jenis, plat no, status).
- **Dokumen & Laporan**: Upload laporan rapat (file), preview/download, admin verification, export sederhana.
- **Notifikasi WA**: Integrasi via `FontteService` untuk notifikasi request/approval/reject ke user atau admin.
- **Role-based Access**: Perbedaan tampilan/aksi untuk `super_admin` dan `admin_barang` (dengan filter bidang).
- **UI/UX**: Dashboard statistik, responsive views, modals, and 24-hour time inputs (flatpickr) for time fields.

## 🛠️ Teknologi

- **Backend:** Laravel 5.x
- **Frontend:** Bootstrap, flatpickr (time picker), FontAwesome, jQuery
- **Database:** MySQL
- **WA Integration:** `App\Services\FontteService` (requires `FONTTE_API_KEY` in `.env`)

## 📦 Requirement

- PHP >= 7.0
- MySQL >= 5.7
- Composer
- PHP Extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, GD

## 🚀 Instalasi & Setup Singkat

1. Clone dan masuk ke repo:

```powershell
git clone https://github.com/MfuatH/Invpusda-Laravel-5.git
cd Invpusda-Laravel-5
```

2. Install dependency:

```powershell
composer install
```

3. Salin environment dan sesuaikan database serta `FONTTE_API_KEY`:

```powershell
copy .env.example .env
```

4. Generate app key:

```powershell
php artisan key:generate
```

5. Jalankan migrasi dan seeder:

```powershell
php artisan migrate
php artisan db:seed
```

6. Buat symbolic link storage (jika diperlukan):

```powershell
php artisan storage:link
```

7. Jalankan server pengembangan:

```powershell
php artisan serve
```

## 🔐 Akun Default (Jika Seeder disertakan)

- **Admin**: `admin@pusda.com` / `password`
- **Staff Sekretariat**: `sekretariat@pusda.com` / `password`

Jika kredensial tidak tersedia di seeders Anda, buat user lewat seeder atau lewat panel admin.

## ✅ Tips Pengujian Fitur Kendaraan

- Menambahkan unit kendaraan via tinker (contoh):

```powershell
php artisan tinker
App\Kendaraan::create(['jenis' => 'Innova Reborn', 'plat_no' => 'L 1234 AB', 'status' => 'available']);
exit
```

- Akses formulir publik: `/request-kendaraan` — kirim request, lalu cek admin `/dashboard/approvals/kendaraan` untuk approve/reject.

<!-- ## 📸 Screenshots (Tampilkan di README)

Untuk menampilkan tampilan aplikasi di README, letakkan file gambar di folder `docs/screenshots/`.
Contoh nama file yang sering dipakai:

- `dashboard.png` — tampilan dashboard admin
- `konsumsi_form.png` — form pemesanan catering
- `kendaraan_form.png` — form request kendaraan
- `approvals_kendaraan.png` — daftar approval kendaraan

Setelah menaruh file gambar, Anda bisa menambahkan di README seperti contoh di bawah (Markdown):

```markdown
## Screenshot

![Dashboard](/docs/screenshots/dashboard.png)
![Form Konsumsi](/docs/screenshots/konsumsi_form.png)
![Form Kendaraan](/docs/screenshots/kendaraan_form.png)
```

Rekomendasi ukuran: gunakan gambar lebar sekitar 1200px atau kurang agar tampil rapi di GitHub. Jika ingin menggunakan versi kecil, buat juga versi `-small.png`. -->

## ⚙️ Catatan Khusus & Perilaku

- Cooldown Peminjaman Kendaraan: 2 hari berdasarkan tanggal (date-only). Jika pengembalian terakhir ber-tanggal `2025-11-01`, peminjam berikutnya hanya diperbolehkan meminjam mulai `2025-11-03` — jam tidak diperhitungkan.
- Validasi bentrok jadwal mencegah overlapping peminjaman pada unit yang sama.
- Upload file laporan: disimpan di `storage/app/public/uploads/laporan_rapat` (pastikan `storage:link` sudah dijalankan).

## 🔧 Troubleshooting umum

- Jika muncul error migration "Duplicate column": periksa file migrations di `database/migrations/` dan pastikan tidak ada migration ganda menambahkan kolom yang sama.
- Jika notifikasi WA gagal, periksa `FONTTE_API_KEY` dan konfigurasi di `App\Services\FontteService`.

## 📝 Contributing

1. Fork repository
2. Buat branch fitur: `git checkout -b fitur-baru`
3. Commit & push
4. Buat Pull Request

## 👨‍💻 Author

- **Mfuat H** — h4asanfu4at@gmail.com

---

