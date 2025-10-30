<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = ['kode_barang', 'nama_barang', 'satuan', 'jumlah', 'lokasi', 'keterangan'];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

}