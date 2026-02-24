<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Potong data dulu agar tidak error saat ubah kolom
        DB::table('skm_reports')
            ->whereRaw('CHAR_LENGTH(waktu_pelaksanaan_bulan) > 20')
            ->update(['waktu_pelaksanaan_bulan' => DB::raw('LEFT(waktu_pelaksanaan_bulan, 20)')]);

        // Ubah panjang kolom
        Schema::table('skm_reports', function (Blueprint $table) {
            $table->string('waktu_pelaksanaan_bulan', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke panjang sebelumnya (misal 255)
        Schema::table('skm_reports', function (Blueprint $table) {
            $table->string('waktu_pelaksanaan_bulan', 255)->change();
        });
    }
};
