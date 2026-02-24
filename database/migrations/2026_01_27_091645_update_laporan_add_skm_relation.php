<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {

            // 1. Tambah kolom jika belum ada
            if (!Schema::hasColumn('laporan', 'id_skm_report')) {
                $table->unsignedBigInteger('id_skm_report')
                      ->nullable()
                      ->after('id_user');
            }

            // 2. HAPUS foreign key lama (jika ada)
            try {
                $table->dropForeign(['id_skm_report']);
            } catch (\Throwable $e) {
                // aman diabaikan kalau belum ada
            }

            // 3. Buat foreign key yang benar
            $table->foreign('id_skm_report')
                  ->references('id_skm_report')
                  ->on('skm_reports')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {

            // hapus FK dulu
            try {
                $table->dropForeign(['id_skm_report']);
            } catch (\Throwable $e) {}

            // hapus kolom
            if (Schema::hasColumn('laporan', 'id_skm_report')) {
                $table->dropColumn('id_skm_report');
            }
        });
    }
};
