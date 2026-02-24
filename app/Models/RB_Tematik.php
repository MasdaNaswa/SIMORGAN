<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RB_Tematik extends Model
{
    use HasFactory;

    protected $table = 'rb_tematik';

    protected $fillable = [
        'tahun',
        'permasalahan',
        'sasaran_tematik',
        'indikator',
        'target',
        'satuan',
        'rencana_aksi',
        'satuan_output',
        'indikator_output',
        'anggaran_tahun',
        'target_tahun',
        'renaksi_tw1_target',
        'renaksi_tw1_rp',
        'renaksi_tw2_target',
        'renaksi_tw2_rp',
        'renaksi_tw3_target',
        'renaksi_tw3_rp',
        'renaksi_tw4_target',
        'renaksi_tw4_rp',
        'rumus',
        'unit_kerja',
        'koordinator',
        'pelaksana',
    ];

}