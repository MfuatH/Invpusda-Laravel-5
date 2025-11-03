<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            // Elektronik
            ['kode_barang' => 'BRG-0001', 'nama_barang' => 'Laptop Dell Inspiron', 'satuan' => 'Unit', 'jumlah' => 5, 'lokasi' => 'Gudang Utama', 'keterangan' => 'Laptop untuk keperluan kantor'],
            ['kode_barang' => 'BRG-0002', 'nama_barang' => 'Printer HP LaserJet', 'satuan' => 'Unit', 'jumlah' => 3, 'lokasi' => 'Gudang Utama', 'keterangan' => 'Printer untuk cetak dokumen'],
            ['kode_barang' => 'BRG-0003', 'nama_barang' => 'Proyektor Epson', 'satuan' => 'Unit', 'jumlah' => 2, 'lokasi' => 'Ruang Meeting', 'keterangan' => 'Proyektor untuk presentasi'],
            ['kode_barang' => 'BRG-0004', 'nama_barang' => 'Monitor LG 24 Inch', 'satuan' => 'Unit', 'jumlah' => 6, 'lokasi' => 'Gudang Utama', 'keterangan' => 'Monitor tambahan untuk komputer'],
            ['kode_barang' => 'BRG-0005', 'nama_barang' => 'Keyboard Logitech', 'satuan' => 'Unit', 'jumlah' => 10, 'lokasi' => 'Gudang Utama', 'keterangan' => 'Keyboard cadangan'],

            // Alat Tulis Kantor
            ['kode_barang' => 'BRG-0006', 'nama_barang' => 'Pulpen Biru', 'satuan' => 'Pack', 'jumlah' => 30, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Pulpen untuk staf'],
            ['kode_barang' => 'BRG-0007', 'nama_barang' => 'Pensil 2B', 'satuan' => 'Pack', 'jumlah' => 25, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Pensil umum kantor'],
            ['kode_barang' => 'BRG-0008', 'nama_barang' => 'Spidol Hitam', 'satuan' => 'Pack', 'jumlah' => 15, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Spidol papan tulis'],
            ['kode_barang' => 'BRG-0009', 'nama_barang' => 'Kertas A4', 'satuan' => 'Rim', 'jumlah' => 50, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Kertas untuk cetak dokumen'],
            ['kode_barang' => 'BRG-0010', 'nama_barang' => 'Map Folder', 'satuan' => 'Pack', 'jumlah' => 20, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Map arsip dokumen'],

            // Furniture
            ['kode_barang' => 'BRG-0011', 'nama_barang' => 'Meja Kerja', 'satuan' => 'Unit', 'jumlah' => 8, 'lokasi' => 'Gudang Furniture', 'keterangan' => 'Meja staf administrasi'],
            ['kode_barang' => 'BRG-0012', 'nama_barang' => 'Kursi Kantor', 'satuan' => 'Unit', 'jumlah' => 12, 'lokasi' => 'Gudang Furniture', 'keterangan' => 'Kursi kerja staf'],
            ['kode_barang' => 'BRG-0013', 'nama_barang' => 'Lemari Arsip', 'satuan' => 'Unit', 'jumlah' => 4, 'lokasi' => 'Gudang Furniture', 'keterangan' => 'Penyimpanan dokumen'],
            ['kode_barang' => 'BRG-0014', 'nama_barang' => 'Rak Buku', 'satuan' => 'Unit', 'jumlah' => 5, 'lokasi' => 'Ruang Pustaka', 'keterangan' => 'Rak untuk buku referensi'],
            ['kode_barang' => 'BRG-0015', 'nama_barang' => 'Kursi Tamu', 'satuan' => 'Unit', 'jumlah' => 6, 'lokasi' => 'Ruang Tamu', 'keterangan' => 'Kursi ruang tamu kantor'],

            // Kebersihan
            ['kode_barang' => 'BRG-0016', 'nama_barang' => 'Sapu Lantai', 'satuan' => 'Unit', 'jumlah' => 10, 'lokasi' => 'Gudang Kebersihan', 'keterangan' => 'Peralatan kebersihan'],
            ['kode_barang' => 'BRG-0017', 'nama_barang' => 'Pel Lantai', 'satuan' => 'Unit', 'jumlah' => 8, 'lokasi' => 'Gudang Kebersihan', 'keterangan' => 'Untuk membersihkan lantai'],
            ['kode_barang' => 'BRG-0018', 'nama_barang' => 'Tempat Sampah', 'satuan' => 'Unit', 'jumlah' => 15, 'lokasi' => 'Gudang Kebersihan', 'keterangan' => 'Tempat sampah kantor'],
            ['kode_barang' => 'BRG-0019', 'nama_barang' => 'Cairan Pembersih Lantai', 'satuan' => 'Botol', 'jumlah' => 25, 'lokasi' => 'Gudang Kebersihan', 'keterangan' => 'Pembersih area umum'],
            ['kode_barang' => 'BRG-0020', 'nama_barang' => 'Tisu Gulung', 'satuan' => 'Pack', 'jumlah' => 30, 'lokasi' => 'Gudang Kebersihan', 'keterangan' => 'Tisu serbaguna'],

            // Peralatan Dapur
            ['kode_barang' => 'BRG-0021', 'nama_barang' => 'Dispenser Air', 'satuan' => 'Unit', 'jumlah' => 3, 'lokasi' => 'Pantry', 'keterangan' => 'Dispenser untuk air minum'],
            ['kode_barang' => 'BRG-0022', 'nama_barang' => 'Gelas Kaca', 'satuan' => 'Lusin', 'jumlah' => 10, 'lokasi' => 'Pantry', 'keterangan' => 'Gelas untuk staf'],
            ['kode_barang' => 'BRG-0023', 'nama_barang' => 'Piring Makan', 'satuan' => 'Lusin', 'jumlah' => 8, 'lokasi' => 'Pantry', 'keterangan' => 'Piring umum'],
            ['kode_barang' => 'BRG-0024', 'nama_barang' => 'Sendok Stainless', 'satuan' => 'Lusin', 'jumlah' => 8, 'lokasi' => 'Pantry', 'keterangan' => 'Sendok untuk dapur'],
            ['kode_barang' => 'BRG-0025', 'nama_barang' => 'Kompor Gas', 'satuan' => 'Unit', 'jumlah' => 2, 'lokasi' => 'Pantry', 'keterangan' => 'Kompor untuk masak ringan'],

            // IT Equipment
            ['kode_barang' => 'BRG-0026', 'nama_barang' => 'Router WiFi TP-Link', 'satuan' => 'Unit', 'jumlah' => 5, 'lokasi' => 'Server Room', 'keterangan' => 'Perangkat jaringan kantor'],
            ['kode_barang' => 'BRG-0027', 'nama_barang' => 'Kabel LAN 10m', 'satuan' => 'Roll', 'jumlah' => 12, 'lokasi' => 'Server Room', 'keterangan' => 'Kabel jaringan LAN'],
            ['kode_barang' => 'BRG-0028', 'nama_barang' => 'UPS APC 650VA', 'satuan' => 'Unit', 'jumlah' => 4, 'lokasi' => 'Server Room', 'keterangan' => 'Backup daya komputer'],
            ['kode_barang' => 'BRG-0029', 'nama_barang' => 'Mouse Logitech', 'satuan' => 'Unit', 'jumlah' => 15, 'lokasi' => 'Gudang Utama', 'keterangan' => 'Mouse cadangan'],
            ['kode_barang' => 'BRG-0030', 'nama_barang' => 'Flashdisk 32GB', 'satuan' => 'Unit', 'jumlah' => 20, 'lokasi' => 'Gudang Utama', 'keterangan' => 'Penyimpanan data sementara'],

            // Kendaraan & Perawatan
            ['kode_barang' => 'BRG-0031', 'nama_barang' => 'Oli Mesin', 'satuan' => 'Botol', 'jumlah' => 10, 'lokasi' => 'Gudang Kendaraan', 'keterangan' => 'Perawatan kendaraan dinas'],
            ['kode_barang' => 'BRG-0032', 'nama_barang' => 'Ban Motor', 'satuan' => 'Unit', 'jumlah' => 6, 'lokasi' => 'Gudang Kendaraan', 'keterangan' => 'Suku cadang motor dinas'],
            ['kode_barang' => 'BRG-0033', 'nama_barang' => 'Kunci Pas Set', 'satuan' => 'Set', 'jumlah' => 4, 'lokasi' => 'Gudang Peralatan', 'keterangan' => 'Peralatan mekanik'],
            ['kode_barang' => 'BRG-0034', 'nama_barang' => 'Dongkrak Hidrolik', 'satuan' => 'Unit', 'jumlah' => 2, 'lokasi' => 'Gudang Peralatan', 'keterangan' => 'Perangkat bantu perbaikan'],
            ['kode_barang' => 'BRG-0035', 'nama_barang' => 'Lap Mikrofiber', 'satuan' => 'Pack', 'jumlah' => 10, 'lokasi' => 'Gudang Kendaraan', 'keterangan' => 'Lap serbaguna'],

            // Tambahan berbagai kategori
            ['kode_barang' => 'BRG-0036', 'nama_barang' => 'Kabel Listrik', 'satuan' => 'Roll', 'jumlah' => 8, 'lokasi' => 'Gudang Listrik', 'keterangan' => 'Kebutuhan instalasi listrik'],
            ['kode_barang' => 'BRG-0037', 'nama_barang' => 'Lampu LED 12W', 'satuan' => 'Unit', 'jumlah' => 20, 'lokasi' => 'Gudang Listrik', 'keterangan' => 'Lampu penerangan kantor'],
            ['kode_barang' => 'BRG-0038', 'nama_barang' => 'Stop Kontak', 'satuan' => 'Unit', 'jumlah' => 15, 'lokasi' => 'Gudang Listrik', 'keterangan' => 'Peralatan listrik cadangan'],
            ['kode_barang' => 'BRG-0039', 'nama_barang' => 'Obeng Set', 'satuan' => 'Set', 'jumlah' => 5, 'lokasi' => 'Gudang Peralatan', 'keterangan' => 'Peralatan umum kantor'],
            ['kode_barang' => 'BRG-0040', 'nama_barang' => 'Tang Kombinasi', 'satuan' => 'Unit', 'jumlah' => 6, 'lokasi' => 'Gudang Peralatan', 'keterangan' => 'Alat kerja teknisi'],

            // Barang Habis Pakai
            ['kode_barang' => 'BRG-0041', 'nama_barang' => 'Baterai AA', 'satuan' => 'Pack', 'jumlah' => 25, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Baterai untuk alat kantor'],
            ['kode_barang' => 'BRG-0042', 'nama_barang' => 'Baterai 9V', 'satuan' => 'Pack', 'jumlah' => 10, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Untuk alat ukur'],
            ['kode_barang' => 'BRG-0043', 'nama_barang' => 'Kabel HDMI', 'satuan' => 'Unit', 'jumlah' => 5, 'lokasi' => 'Ruang Meeting', 'keterangan' => 'Untuk proyektor dan laptop'],
            ['kode_barang' => 'BRG-0044', 'nama_barang' => 'Kipas Angin', 'satuan' => 'Unit', 'jumlah' => 4, 'lokasi' => 'Gudang Utama', 'keterangan' => 'Pendingin ruangan tambahan'],
            ['kode_barang' => 'BRG-0045', 'nama_barang' => 'Tinta Printer Canon', 'satuan' => 'Botol', 'jumlah' => 12, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Untuk printer Canon G-Series'],

            // Barang cadangan tambahan
            ['kode_barang' => 'BRG-0046', 'nama_barang' => 'Extension Cable 5m', 'satuan' => 'Unit', 'jumlah' => 10, 'lokasi' => 'Gudang Listrik', 'keterangan' => 'Kabel ekstensi listrik'],
            ['kode_barang' => 'BRG-0047', 'nama_barang' => 'Whiteboard 120x90', 'satuan' => 'Unit', 'jumlah' => 3, 'lokasi' => 'Ruang Meeting', 'keterangan' => 'Papan tulis kantor'],
            ['kode_barang' => 'BRG-0048', 'nama_barang' => 'Stopwatch Digital', 'satuan' => 'Unit', 'jumlah' => 5, 'lokasi' => 'Ruang Pelatihan', 'keterangan' => 'Untuk kegiatan pelatihan'],
            ['kode_barang' => 'BRG-0049', 'nama_barang' => 'Buku Catatan A5', 'satuan' => 'Pack', 'jumlah' => 20, 'lokasi' => 'Gudang ATK', 'keterangan' => 'Buku catatan staf'],
            ['kode_barang' => 'BRG-0050', 'nama_barang' => 'Masker Medis', 'satuan' => 'Box', 'jumlah' => 40, 'lokasi' => 'Gudang Kesehatan', 'keterangan' => 'Perlengkapan kesehatan kantor'],
        ];

        foreach ($items as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('items')->insert($items);
    }
}
