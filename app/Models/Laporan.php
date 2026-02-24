<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';
    public $timestamps = false;

    protected $fillable = [
        'id_user', 
        'kategori', 
        'judul', 
        'file_path', 
        'tanggal_upload',
        'status',
        'catatan',
        'periode_triwulan',
        'periode_tahun',
        'id_skm_report'
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'periode_triwulan' => 'integer',
        'periode_tahun' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    public function skmReport()
    {
        return $this->belongsTo(SkmReport::class, 'id_skm_report', 'id_skm_report');
    }

    // Accessor untuk format tanggal
    public function getTanggalUploadFormattedAttribute()
    {
        return Carbon::parse($this->tanggal_upload)->format('d M Y');
    }

    // Scope untuk laporan SKM
    public function scopeSkm($query)
    {
        return $query->where('kategori', 'SKM');
    }

    // Scope untuk laporan Yanlik
    public function scopeYanlik($query)
    {
        return $query->where('kategori', 'Yanlik');
    }

    // Scope untuk laporan user tertentu
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }
}