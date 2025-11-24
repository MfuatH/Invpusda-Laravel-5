<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeminjamanKendaraan extends Model
{
    protected $table = 'peminjaman_kendaraan';

    protected $fillable = [
        'nama', 'nip', 'no_hp', 'urgensi', 'kendaraan_id', 'tanggal_ambil', 'tanggal_kembali', 'status'
    ];

    protected $dates = [
        'tanggal_ambil',
        'tanggal_kembali',
        'created_at',
        'updated_at'
    ];

    public function kendaraan()
    {
        return $this->belongsTo('App\Kendaraan', 'kendaraan_id');
    }
}

