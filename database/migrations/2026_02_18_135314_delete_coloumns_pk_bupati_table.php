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
                'pagu_anggaran',
                'realisasi_anggaran',
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
            $table->string('pagu_anggaran')->nullable();
            $table->string('realisasi_anggaran')->nullable();
        });
    }
};