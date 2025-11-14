<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateDokumen extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'template_dokumen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_template',
        'jenis_template',
        'file_path',
        'file_name',
        'deskripsi',
        'is_active',
        'created_by'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_by' => 'integer'
    ];

    /**
     * Template type constants
     */
    const JENIS_PRESENSI = 'presensi';
    const JENIS_NOTULEN = 'notulen';

    /**
     * Get all template types
     *
     * @return array
     */
    public static function getJenisTemplate()
    {
        return [
            self::JENIS_PRESENSI => 'Presensi',
            self::JENIS_NOTULEN => 'Notulen'
        ];
    }

    /**
     * Get the user who created the template.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Scope a query to only include active templates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include presensi templates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePresensi($query)
    {
        return $query->where('jenis_template', self::JENIS_PRESENSI);
    }

    /**
     * Scope a query to only include notulen templates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotulen($query)
    {
        return $query->where('jenis_template', self::JENIS_NOTULEN);
    }

    /**
     * Get full file path
     *
     * @return string
     */
    public function getFullPathAttribute()
    {
        return storage_path('app/public/' . $this->file_path);
    }

    /**
     * Check if file exists
     *
     * @return boolean
     */
    public function fileExists()
    {
        return file_exists($this->full_path);
    }
}