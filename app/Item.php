<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = ['kode_barang', 'nama_barang', 'satuan', 'jumlah', 'lokasi', 'keterangan'];
}