<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'user_id',
        'request_id',
        'jumlah',
        'tipe',
        'tanggal',
    ];

    /**
     * Mendefinisikan relasi ke model Item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Mendefinisikan relasi ke model ItemRequest.
     */
    public function request()
    {
        return $this->belongsTo(ItemRequest::class);
    }

    /**
     * Mendefinisikan relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}