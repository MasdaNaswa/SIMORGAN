<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rb_general', function (Blueprint $table) {
            // Tambahkan kolom yang ada di modal tambah tapi belum di database
            $table->string('no')->nullable()->after('id');
            $table->string('satuan_output')->nullable()->after('rencana_aksi');
            $table->string('indikator_output')->nullable()->after('satuan_output');
            $table->string('pelaksana')->nullable()->after('unit_kerja');
        });
    }

    public function down(): void
    {
        Schema::table('rb_general', function (Blueprint $table) {
            $table->dropColumn(['no', 'satuan_output', 'indikator_output', 'pelaksana']);
        });
    }
};