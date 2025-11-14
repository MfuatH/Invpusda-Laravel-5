<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaporanRapat extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'laporan_rapat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'catering_id',
        'pengunggah',
        'nip',
        'keterangan',
        'file_laporan',
        'file_original_name',
        'file_size',
        'mime_type',
        'status',
        'verified_by',
        'verified_at',
        'created_by'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'verified_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'catering_id' => 'integer',
        'file_size' => 'integer',
        'verified_by' => 'integer',
        'created_by' => 'integer'
    ];

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_VERIFIED = 'verified';

    /**
     * Get the catering associated with the laporan.
     */
    public function catering()
    {
        return $this->belongsTo('App\Catering', 'catering_id');
    }

    /**
     * Get the user who created the laporan.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Get the user who verified the laporan.
     */
    public function verifier()
    {
        return $this->belongsTo('App\User', 'verified_by');
    }

    /**
     * Scope a query to only include submitted reports.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    /**
     * Scope a query to only include verified reports.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    /**
     * Get formatted file size
     *
     * @return string
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }
}