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
        Schema::table('skm_reports', function (Blueprint $table) {
            // Tambah kolom baru
            $table->string('nip_penandatangan', 19)->nullable();
            $table->string('nama_penandatangan', 90)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skm_reports', function (Blueprint $table) {
            // Hapus kolom kalau rollback
            $table->dropColumn(['nip_penandatangan', 'nama_penandatangan']);
        });
    }
};
