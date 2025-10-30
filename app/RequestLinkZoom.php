<?php
// app/RequestLinkZoom.php
namespace App;
use Illuminate\Database\Eloquent\Model;

class RequestLinkZoom extends Model
{
    protected $table = 'request_linkzoom';
    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali 'id'
    
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}