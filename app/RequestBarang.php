<?php
// app/RequestBarang.php
namespace App;
use Illuminate\Database\Eloquent\Model;

class RequestBarang extends Model
{
    protected $table = 'request_barang';
    // Gunakan guarded atau tentukan fillable, sesuai database Anda
    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali 'id'

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}