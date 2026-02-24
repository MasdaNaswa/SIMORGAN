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
        Schema::table('pk_bupati', function (Blueprint $table) {
            // Hapus 3 kolom yang tidak diperlukan
            $table->dropColumn([
                'target_per_tw',
                'realisasi_per_tw',
                'realisasi_per_tri_wulan'  // Sesuai dengan nama di migration awal
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pk_bupati', function (Blueprint $table) {
            // Tambahkan kembali kolom jika rollback
            $table->string('target_per_tw')->nullable();
            $table->string('realisasi_per_tw')->nullable();
            $table->string('realisasi_per_tri_wulan')->nullable();
        });
    }
};