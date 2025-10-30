<?php

namespace App;

// 1. 'use HasFactory' DIHAPUS dari sini
use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    // 2. Trait 'use HasFactory' DIHAPUS dari sini

    /**
     * Baris ini memberitahu Laravel untuk menggunakan tabel 'requests'
     * @var string
     */
    // Nama tabel ini 'request_barang', BUKAN 'requests'
    protected $table = 'request_barang'; 

    /**
     * Kolom yang boleh diisi secara massal.
     * @var array
     */
    protected $fillable = [
        'bidang_id',
        'nama_pemohon',
        'nip',
        'no_hp',
        'item_id',
        'jumlah_request',
        'status',
    ];

    /**
     * Mendefinisikan relasi ke model User.
     */
    public function user()
    {
        // Ini akan merujuk ke App\Models\User, sudah benar.
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi ke model Item.
     */
    public function item()
    {
        // Ini akan merujuk ke App\Models\Item, sudah benar.
        return $this->belongsTo(Item::class);
    }

    

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    

}