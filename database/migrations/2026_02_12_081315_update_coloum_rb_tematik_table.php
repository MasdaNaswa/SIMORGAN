<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {
            // Hapus kolom yang ADA di tabel
            $table->dropColumn([
                'target_tahun_2025', 
                'anggaran_tahun_2025', 
                'renaksi_tw1', 
                'renaksi_tw2', 
                'renaksi_tw3', 
                'renaksi_tw4'
                // 'satuan' JANGAN DIHAPUS! Masih dipakai
                // 'capaian' TIDAK ADA
                // 'status' TIDAK ADA
            ]);
            
            // Tambah kolom baru
            $table->integer('tahun')->nullable()->after('id');
            $table->string('satuan_output')->nullable()->after('rencana_aksi');
            $table->string('indikator_output')->nullable()->after('satuan_output');
            $table->string('target_tahun')->nullable()->after('satuan'); // tetap setelah satuan
            $table->string('anggaran_total')->nullable()->after('target_tahun');
            $table->string('renaksi_tw1_target')->nullable()->after('anggaran_total');
            $table->string('renaksi_tw1_rp')->nullable()->after('renaksi_tw1_target');
            $table->string('renaksi_tw2_target')->nullable()->after('renaksi_tw1_rp');
            $table->string('renaksi_tw2_rp')->nullable()->after('renaksi_tw2_target');
            $table->string('renaksi_tw3_target')->nullable()->after('renaksi_tw2_rp');
            $table->string('renaksi_tw3_rp')->nullable()->after('renaksi_tw3_target');
            $table->string('renaksi_tw4_target')->nullable()->after('renaksi_tw3_rp');
            $table->string('renaksi_tw4_rp')->nullable()->after('renaksi_tw4_target');
        });
    }

    public function down(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {
            // Drop kolom baru
            $table->dropColumn([
                'tahun', 
                'satuan_output', 
                'indikator_output', 
                'target_tahun', 
                'anggaran_total', 
                'renaksi_tw1_target', 
                'renaksi_tw1_rp', 
                'renaksi_tw2_target', 
                'renaksi_tw2_rp', 
                'renaksi_tw3_target', 
                'renaksi_tw3_rp', 
                'renaksi_tw4_target', 
                'renaksi_tw4_rp'
            ]);
            
            // Kembalikan kolom lama
            $table->string('target_tahun_2025')->nullable();
            $table->string('anggaran_tahun_2025')->nullable();
            $table->string('renaksi_tw1')->nullable();
            $table->string('renaksi_tw2')->nullable();
            $table->string('renaksi_tw3')->nullable();
            $table->string('renaksi_tw4')->nullable();
            // 'satuan' tetap ada, tidak perlu ditambah lagi
            // 'capaian' & 'status' tidak perlu dikembalikan karena tidak pernah ada
        });
    }
};