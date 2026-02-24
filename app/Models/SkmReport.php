<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkmReport extends Model
{
    use HasFactory;

    protected $table = 'skm_reports';
    protected $primaryKey = 'id_skm_report';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'nama_opd',
        'triwulan',
        'tahun',
        'jabatan_penandatangan',
        'nama_penandatangan',
        'nip_penandatangan',
        'tanggal_pengesahan',
        'latar_belakang',
        'tujuan_manfaat',
        'metode_pengumpulan',
        'waktu_pelaksanaan_bulan',
        'jumlah_populasi',
        'jumlah_sampel',
        'analisis_responden',
        'jenis_layanan',
        'rerata_ikm',
        'ikm_unit_layanan',
        'mutu_unit_layanan',
        'warna_grafik',
        'analisis_masalah',
        'rencana_tindak_lanjut_analisis',
        'tren_skm',
        'hasil_skm_sebelumnya',
        'tindak_lanjut_sebelumnya',
        'kesimpulan',
        'saran',
        'dokumentasi_foto',
        'status',
        'file_path',
        'generated_at'
    ];

    protected $casts = [
        'tanggal_pengesahan' => 'date',
        'jabatan_penandatangan' => 'string',
        'nama_pendandatangan' => 'string',
        'nip_penandatangan' => 'string',
        'generated_at' => 'datetime',
        'waktu_pelaksanaan_bulan' => 'integer',
        'jumlah_populasi' => 'integer',
        'jumlah_sampel' => 'integer',
        'ikm_unit_layanan' => 'decimal:2',
        'analisis_responden' => 'array',
        'jenis_layanan' => 'array',
        'rerata_ikm' => 'array',
        'tren_skm' => 'array',
        'hasil_skm_sebelumnya' => 'array',
        'tindak_lanjut_sebelumnya' => 'array',
        'rencana_tindak_lanjut_analisis' => 'array',
        'dokumentasi_foto' => 'array',
    ];

    // Relasi dengan user (OPD)
    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    // Relasi dengan laporan
    public function laporan()
    {
        return $this->hasOne(Laporan::class, 'id_skm_report', 'id_skm_report');
    }
    
    // Accessor untuk triwulan text
    public function getTriwulanTextAttribute()
    {
        return match ((int) $this->triwulan) {
            1 => 'I (Januari - Maret)',
            2 => 'II (April - Juni)',
            3 => 'III (Juli - September)',
            4 => 'IV (Oktober - Desember)',
            default => 'I'
        };
    }
}