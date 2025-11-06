# Invpusda - Sistem Inventory Management

![Invpusda Banner](docs/banner.png)

<div align="center">

[![Laravel Version](https://img.shields.io/badge/Laravel-5.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-7.0+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**Sistem Manajemen Inventory berbasis web menggunakan Laravel 5**

[Fitur](#fitur) • [Instalasi](#instalasi) • [Dokumentasi](#dokumentasi) • [Screenshot](#screenshot)

</div>

---

## 📋 Tentang Project

**Invpusda** adalah aplikasi manajemen inventory (inventaris) yang dibangun menggunakan framework Laravel 5. Aplikasi ini dirancang untuk memudahkan pengelolaan data barang, stok, transaksi masuk/keluar, dan pelaporan inventory secara real-time.

## ✨ Fitur

- 📦 **Manajemen Barang**
  - CRUD data barang
  - Kategori barang
  - Upload foto barang
  - Barcode/QR Code generation

- 📊 **Manajemen Stok**
  - Monitoring stok real-time
  - Notifikasi stok minimum
  - History pergerakan stok
  - Adjustment stok

- 📥 **Transaksi Masuk**
  - Pencatatan barang masuk
  - Supplier management
  - Print bukti transaksi

- 📤 **Transaksi Keluar**
  - Pencatatan barang keluar
  - Permintaan barang
  - Approval workflow

- 📈 **Laporan**
  - Laporan stok barang
  - Laporan transaksi masuk/keluar
  - Laporan per periode
  - Export Excel

- 👥 **Manajemen User**
  - Multi-level user (Admin, Staff, Viewer)
  - Role & Permission management
  - User activity log

- 🎨 **Dashboard**
  - Dashboard interaktif
  - Grafik & Statistik
  - Quick access menu

## 🛠️ Teknologi yang Digunakan

- **Backend:** Laravel 5.x
- **Frontend:** Bootstrap, jQuery, DataTables
- **Database:** MySQL
- **Server:** Apache/Nginx
- **PHP Version:** 7.0+

## 📦 Requirement

Pastikan sistem Anda memenuhi requirement berikut:

- PHP >= 7.0
- MySQL >= 5.7
- Composer
- Apache/Nginx Web Server
- PHP Extensions:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - GD Library

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/MfuatH/Invpusda-Laravel-5.git
cd Invpusda-Laravel-5
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database dan Token Wa-Blast:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invpusda
DB_USERNAME=root
DB_PASSWORD=

FONTTE_API_KEY=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Jalankan Migration & Seeder

```bash
php artisan migrate
php artisan db:seed
```

### 6. Create Storage Link

```bash
php artisan storage:link
```

### 7. Set Permission (Linux/Mac)

```bash
chmod -R 777 storage bootstrap/cache
```

### 8. Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🔐 Default Login

Setelah instalasi, gunakan kredensial berikut untuk login:

**Admin:**
- Email: `admin@invpusda.com`
- Password: `password`

**Staff:**
- Email: `sekretariat@invpusda.com`
- Password: `password`

> ⚠️ **Penting:** Segera ubah password default setelah login pertama kali!

## 📸 Screenshot

### Dashboard
![Dashboard](/docs/Dashboard_admin.png)
*Dashboard utama *

### Data Barang
![Data Barang](/docs/Halaman_manajemen_barang.png)
*Halaman manajemen data barang*

### Transaksi Masuk
![Transaksi Masuk](/docs/Form_req_barang.png)
*Form pencatatan barang masuk*

### Transaksi Keluar
![Transaksi Keluar](/docs/Halaman_riwayat%20transaksi.png)
*Form pencatatan barang keluar*

### Request Zoom Masuk
![Request Zoom](/docs/Form_req_link_zoom.png)
*Halaman Pencatatan Request zoom*

## 📖 Dokumentasi

### Struktur Folder

```
Invpusda-Laravel-5/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   └── Models/
├── config/
├── database/
│   ├── migrations/
│   └── seeds/
├── docs/              
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── resources/
│   └── views/
├── routes/
│   └── web.php
└── storage/
```


## 🎯 Penggunaan

### Menambah Barang Baru

1. Login ke sistem
2. Navigasi ke menu **Barang** > **Tambah Barang**
3. Isi form data barang
4. Upload foto (opsional)
5. Klik **Simpan**

### Transaksi Barang Masuk

1. Menu **Transaksi** > **Barang Masuk**
2. Pilih supplier
3. Tambah item barang dan jumlah
4. Klik **Proses Transaksi**
5. Print bukti transaksi

### Transaksi Barang Keluar

1. Menu **Transaksi** > **Barang Keluar**
2. Pilih tujuan/pemohon
3. Tambah item barang dan jumlah
4. Klik **Proses Transaksi**
5. Print bukti pengeluaran

### Generate Laporan

1. Menu **Laporan**
2. Pilih jenis laporan
3. Set periode tanggal
4. Klik **Tampilkan**
5. Export Excel

## 🔧 Troubleshooting

### Error "Class not found"

```bash
composer dump-autoload
```

### Error Permission Denied

```bash
sudo chmod -R 777 storage bootstrap/cache
```

### Error Database Connection

- Pastikan MySQL service running
- Cek kredensial database di file `.env`
- Pastikan database sudah dibuat

### Cache Issue

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🤝 Kontribusi

Kontribusi selalu diterima! Berikut cara berkontribusi:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -am 'Menambah fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## 📝 Changelog

### Version 1.0.0 (Current)
- ✅ CRUD Barang
- ✅ Transaksi Masuk/Keluar
- ✅ Laporan Excel
- ✅ Multi-user management
- ✅ Dashboard analytics



## 👨‍💻 Author

**Mfuat H**

- GitHub: [@MfuatH](https://github.com/MfuatH)
- Email: [h4asanfu4at@gmail.com](mailto:h4asanfu4at@gmail.com)

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap
- DataTables
- Chart.js
- Dan semua open source libraries yang digunakan

## 📞 Support

Jika Anda memiliki pertanyaan atau membutuhkan bantuan:

- 🐛 [Report Bug](https://github.com/MfuatH/Invpusda-Laravel-5/issues)
- 💡 [Request Feature](https://github.com/MfuatH/Invpusda-Laravel-5/issues)
- 📧 Email: h4asanfu4at@gmail.com

---
