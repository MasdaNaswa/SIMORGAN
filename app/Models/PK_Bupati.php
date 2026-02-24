<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PK_Bupati extends Model
{
    use HasFactory;

    protected $table = 'pk_bupati';

    protected $fillable = [
        'no',
        'tahun',
        'sasaran_strategis',
        'indikator_kinerja',
        'semester',
        'target_2025',
        'satuan',
        'target_tw1',
        'realisasi_tw1',
        'target_tw2',
        'realisasi_tw2',
        'target_tw3',
        'realisasi_tw3',
        'target_tw4',
        'realisasi_tw4',
        'pagu_anggaran_tw1',
        'realisasi_anggaran_tw1',
        'pagu_anggaran_tw2',
        'realisasi_anggaran_tw2',
        'pagu_anggaran_tw3',
        'realisasi_anggaran_tw3',
        'pagu_anggaran_tw4',
        'realisasi_anggaran_tw4',
        'program',
        'penjelasan_analisis',
        'penanggung_jawab',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'no' => 'integer',
    ];
}