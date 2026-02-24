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
            // Ubah kolom nip_penandatangan dari string ke BIGINT
            $table->unsignedBigInteger('nip_penandatangan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skm_reports', function (Blueprint $table) {
            // Kembalikan ke string 19
            $table->string('nip_penandatangan', 19)->change();
        });
    }
};
