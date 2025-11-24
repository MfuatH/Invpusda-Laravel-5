<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    protected $fillable = [
        'jenis', 'plat_no', 'status'
    ];

    public function peminjaman()
    {
        return $this->hasMany('App\PeminjamanKendaraan', 'kendaraan_id');
    }
}
