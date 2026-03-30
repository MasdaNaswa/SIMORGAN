<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RB_General extends Model
{
    use HasFactory;

    protected $table = 'rb_general';

    protected $fillable = [
        'no',
        'tahun',
        'sasaran_strategi',
        'indikator_capaian',
        'target',
        'satuan',
        'rencana_aksi',
        'satuan_output',
        'indikator_output',
        
        'target_tahun',         
        'anggaran_tahun',        
        'renaksi_tw1_target',   
        'renaksi_tw2_target',    
        'renaksi_tw3_target',    
        'renaksi_tw4_target',    
        
        'tw1_rp',
        'tw2_rp',
        'tw3_rp',
        'tw4_rp',
        
        'realisasi_tw1_target',
        'realisasi_tw1_rp',
        'realisasi_tw2_target',
        'realisasi_tw2_rp',
        'realisasi_tw3_target',
        'realisasi_tw3_rp',
        'realisasi_tw4_target',
        'realisasi_tw4_rp',
        
        'rumus',
        'catatan_evaluasi',
        'catatan_perbaikan',
        'unit_kerja',
        'opd_penginput',
        'pelaksana'
    ];
}