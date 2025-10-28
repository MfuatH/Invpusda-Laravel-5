<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bidang = [
            ['nama' => 'sekretariat', 'pesan_template' => 'Halo @nama, rapat @kegiatan pada @tanggal sudah disetujui. Link Zoom: @link'],
            ['nama' => 'psda', 'pesan_template' => 'Halo @nama, rapat @kegiatan pada @tanggal sudah disetujui. Link Zoom: @link'],
            ['nama' => 'irigasi', 'pesan_template' => 'Halo @nama, rapat @kegiatan pada @tanggal sudah disetujui. Link Zoom: @link'],
            ['nama' => 'swp', 'pesan_template' => 'Halo @nama, rapat @kegiatan pada @tanggal sudah disetujui. Link Zoom: @link'],
            ['nama' => 'binfat', 'pesan_template' => 'Halo @nama, rapat @kegiatan pada @tanggal sudah disetujui. Link Zoom: @link'],
        ];

        foreach ($bidang as $b) {
            DB::table('bidang')->insert([
                'nama' => $b['nama'],
                'pesan_template' => $b['pesan_template'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
