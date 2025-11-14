<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'bidang_id', 'no_hp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
    /**
     * Get catering requests created by user
     */
    public function cateringRequests()
    {
        return $this->hasMany('App\Catering', 'created_by');
    }

    /**
     * Get catering requests approved by user
     */
    public function approvedCaterings()
    {
        return $this->hasMany('App\Catering', 'approved_by');
    }

    /**
     * Get laporan rapat created by user
     */
    public function laporanRapat()
    {
        return $this->hasMany('App\LaporanRapat', 'created_by');
    }

    /**
     * Get templates created by user
     */
    public function templateDokumen()
    {
        return $this->hasMany('App\TemplateDokumen', 'created_by');
    }

}
