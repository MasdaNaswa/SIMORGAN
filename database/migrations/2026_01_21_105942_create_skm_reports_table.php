<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah tabel sudah ada
        if (Schema::hasTable('skm_reports')) {
            // Jika sudah ada, hapus dulu
            Schema::dropIfExists('skm_reports');
        }

        Schema::create('skm_reports', function (Blueprint $table) {
            $table->id('id_skm_report');
            
            // Foreign key ke pengguna
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('pengguna')->onDelete('cascade');
            
            // Nama OPD - varchar 80
            $table->string('nama_opd', 80);
            
            // Periode
            $table->tinyInteger('triwulan')->comment('1, 2, 3, 4');
            $table->year('tahun');
            
            // Informasi umum - varchar 80
            $table->string('jabatan_penandatangan', 80);
            $table->date('tanggal_pengesahan');
            
            // Konten laporan
            $table->text('analisis')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('saran')->nullable();
            
            // Anggota tim (JSON)
            $table->json('anggota_tim')->nullable()->comment('Array JSON data anggota tim');
            
            // Rencana tindak lanjut (JSON)
            $table->json('rencana_tindak_lanjut')->nullable()->comment('Array JSON rencana tindak lanjut');
            
            // File path - varchar 80
            $table->string('file_path', 80)->nullable()->comment('Path file PDF di storage');
            
            $table->enum('status', ['draft', 'generated', 'submitted', 'approved', 'rejected'])
                  ->default('draft');
            
            // Metadata
            $table->timestamp('generated_at')->nullable()->comment('Waktu generate PDF');
            $table->timestamp('submitted_at')->nullable()->comment('Waktu submit ke admin');
            $table->timestamp('reviewed_at')->nullable()->comment('Waktu direview admin');
            $table->text('catatan_review')->nullable()->comment('Catatan dari admin');
            
            // Soft deletes
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index(['nama_opd', 'tahun', 'triwulan']);
            $table->index(['status', 'created_at']);
            $table->index('id_user');
        });
        
        // Tambah kolom id_skm_report ke tabel laporans jika belum ada
        if (!Schema::hasColumn('laporan', 'id_skm_report')) {
            Schema::table('laporan', function (Blueprint $table) {
                $table->unsignedBigInteger('id_skm_report')->nullable()->after('id_user');
                $table->foreign('id_skm_report')->references('id_skm_report')->on('skm_reports')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Hapus foreign key di laporans terlebih dahulu
        if (Schema::hasColumn('laporans', 'id_skm_report')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->dropForeign(['id_skm_report']);
                $table->dropColumn('id_skm_report');
            });
        }
        
        Schema::dropIfExists('skm_reports');
    }
};