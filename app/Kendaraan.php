<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    protected $fillable = [
        'jenis', 'plat_no', 'status'
    ];

    const STATUS_AVAILABLE = 'available';
    const STATUS_UNAVAILABLE = 'unavailable';
    const STATUS_MAINTENANCE = 'maintenance';

    public function peminjaman()
    {
        return $this->hasMany('App\PeminjamanKendaraan', 'kendaraan_id');
    }
}
