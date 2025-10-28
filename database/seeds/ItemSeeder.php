<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'kode_barang' => 'BRG-0001',
                'nama_barang' => 'Laptop Dell Inspiron',
                'satuan' => 'Unit',
                'jumlah' => 5,
                'lokasi' => 'Gudang Utama',
                'keterangan' => 'Laptop untuk keperluan kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_barang' => 'BRG-0002',
                'nama_barang' => 'Printer HP LaserJet',
                'satuan' => 'Unit',
                'jumlah' => 3,
                'lokasi' => 'Gudang Utama',
                'keterangan' => 'Printer untuk keperluan cetak dokumen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_barang' => 'BRG-0003',
                'nama_barang' => 'Kertas A4',
                'satuan' => 'Rim',
                'jumlah' => 50,
                'lokasi' => 'Gudang Utama',
                'keterangan' => 'Kertas untuk keperluan cetak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_barang' => 'BRG-0004',
                'nama_barang' => 'Meja Kerja',
                'satuan' => 'Unit',
                'jumlah' => 8,
                'lokasi' => 'Gudang Utama',
                'keterangan' => 'Meja untuk keperluan kerja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_barang' => 'BRG-0005',
                'nama_barang' => 'Kursi Kantor',
                'satuan' => 'Unit',
                'jumlah' => 12,
                'lokasi' => 'Gudang Utama',
                'keterangan' => 'Kursi untuk keperluan kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($items as $item) {
            DB::table('items')->insert($item);
        }
    }
}
