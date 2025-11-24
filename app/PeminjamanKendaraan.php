<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeminjamanKendaraan extends Model
{
    protected $table = 'peminjaman_kendaraan';

    protected $fillable = [
        'nama', 'nip', 'no_hp', 'urgensi', 'tanggal_ambil', 'tanggal_kembali', 'plat_no', 'status'
    ];

    protected $dates = [
        'tanggal_ambil',
        'tanggal_kembali',
        'created_at',
        'updated_at'
    ];
}
