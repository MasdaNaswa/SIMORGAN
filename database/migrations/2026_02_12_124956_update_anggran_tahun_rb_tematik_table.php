<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan package doctrine/dbal sudah terinstall:
        // composer require doctrine/dbal

        Schema::table('rb_tematik', function (Blueprint $table) {
            // Ubah kolom anggaran_tahun dari year menjadi varchar(90)
            if (Schema::hasColumn('rb_tematik', 'anggaran_tahun')) {
                $table->string('anggaran_tahun', 90)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {
            // Kembalikan kolom anggaran_tahun ke year
            if (Schema::hasColumn('rb_tematik', 'anggaran_tahun')) {
                $table->year('anggaran_tahun')->nullable()->change();
            }
        });
    }
};
