<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catering extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'catering';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_pemesan', 
        'nip', 
        'keperluan', 
        'tanggal_kegiatan',
        'tempat', 
        'jumlah_peserta', 
        'jenis_konsumsi', 
        'keterangan',
        'nota_dinas_file', 
        'nota_dinas_original_name', 
        'status',
        'approved_by', 
        'approved_at', 
        'rejection_reason', 
        'created_by'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'tanggal_kegiatan', 
        'approved_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'jumlah_peserta' => 'integer',
        'approved_by' => 'integer',
        'created_by' => 'integer'
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    /**
     * Get the user who created the catering request.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Get the user who approved the catering request.
     */
    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    /**
     * Get the laporan rapat for the catering.
     */
    public function laporanRapat()
    {
        return $this->hasMany('App\LaporanRapat', 'catering_id');
    }

    /**
     * Scope a query to only include pending requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include approved requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Get jenis konsumsi as array
     *
     * @return array
     */
    public function getJenisKonsumsiArrayAttribute()
    {
        return json_decode($this->jenis_konsumsi, true) ?: [];
    }

    /**
     * Set jenis konsumsi from array
     *
     * @param array $value
     */
    public function setJenisKonsumsiAttribute($value)
    {
        $this->attributes['jenis_konsumsi'] = is_array($value) ? json_encode($value) : $value;
    }
}