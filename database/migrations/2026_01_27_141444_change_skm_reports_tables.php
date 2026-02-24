<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Langkah 1: Backup data jika ada
        if (Schema::hasTable('skm_reports') && Schema::hasColumn('skm_reports', 'mutu_unit_layanan')) {
            // Update data yang mungkin ada NULL menjadi kosong
            DB::table('skm_reports')
                ->whereNull('mutu_unit_layanan')
                ->update(['mutu_unit_layanan' => '']);
                
            // Truncate data yang lebih panjang dari 10 karakter
            DB::table('skm_reports')
                ->whereRaw('LENGTH(mutu_unit_layanan) > 10')
                ->update(['mutu_unit_layanan' => DB::raw('SUBSTRING(mutu_unit_layanan, 1, 10)')]);
        }

        // Langkah 2: Ubah tipe data satu per satu dengan cara yang aman
        Schema::table('skm_reports', function (Blueprint $table) {
            // Ubah kolom JSON menjadi TEXT dulu
            $jsonColumns = [
                'analisis_responden',
                'jenis_layanan', 
                'rerata_ikm',
                'rencana_tindak_lanjut_analisis',
                'tren_skm',
                'hasil_skm_sebelumnya',
                'tindak_lanjut_sebelumnya',
                'dokumentasi_foto'
            ];
            
            foreach ($jsonColumns as $column) {
                if (Schema::hasColumn('skm_reports', $column)) {
                    // Gunakan DB::statement untuk lebih kontrol
                    DB::statement("ALTER TABLE skm_reports MODIFY COLUMN {$column} TEXT NULL");
                }
            }
            
            // Ubah kolom string lainnya (bukan NOT NULL)
            if (Schema::hasColumn('skm_reports', 'mutu_unit_layanan')) {
                // Bukan NOT NULL, biarkan nullable
                $table->string('mutu_unit_layanan', 10)->nullable()->change();
            }
            
            if (Schema::hasColumn('skm_reports', 'nama_opd')) {
                $table->string('nama_opd', 150)->change();
            }
            
            if (Schema::hasColumn('skm_reports', 'jabatan_penandatangan')) {
                $table->string('jabatan_penandatangan', 150)->change();
            }
            
            if (Schema::hasColumn('skm_reports', 'warna_grafik')) {
                $table->string('warna_grafik', 20)->change();
            }
            
            if (Schema::hasColumn('skm_reports', 'file_path')) {
                $table->string('file_path', 255)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // Rollback perubahan
        Schema::table('skm_reports', function (Blueprint $table) {
            // Kembalikan ke ukuran semula
            if (Schema::hasColumn('skm_reports', 'mutu_unit_layanan')) {
                $table->string('mutu_unit_layanan', 1)->nullable()->change();
            }
            
            if (Schema::hasColumn('skm_reports', 'nama_opd')) {
                $table->string('nama_opd', 80)->change();
            }
            
            if (Schema::hasColumn('skm_reports', 'jabatan_penandatangan')) {
                $table->string('jabatan_penandatangan', 80)->change();
            }
            
            if (Schema::hasColumn('skm_reports', 'warna_grafik')) {
                $table->string('warna_grafik', 7)->change();
            }
            
            if (Schema::hasColumn('skm_reports', 'file_path')) {
                $table->string('file_path', 80)->nullable()->change();
            }
            
            // Kolom JSON kembali ke string
            $jsonColumns = [
                'analisis_responden',
                'jenis_layanan',
                'rerata_ikm',
                'rencana_tindak_lanjut_analisis',
                'tren_skm',
                'hasil_skm_sebelumnya',
                'tindak_lanjut_sebelumnya',
                'dokumentasi_foto'
            ];
            
            foreach ($jsonColumns as $column) {
                if (Schema::hasColumn('skm_reports', $column)) {
                    DB::statement("ALTER TABLE skm_reports MODIFY COLUMN {$column} VARCHAR(1000) NULL");
                }
            }
        });
    }
};