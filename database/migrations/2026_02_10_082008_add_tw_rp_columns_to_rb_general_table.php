<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rb_general', function (Blueprint $table) {
            // Tambahkan kolom anggaran per TW
            $table->string('tw1_rp')->nullable()->after('renaksi_tahun_2025_tw1');
            $table->string('tw2_rp')->nullable()->after('renaksi_tahun_2025_tw2');
            $table->string('tw3_rp')->nullable()->after('renaksi_tahun_2025_tw3');
            $table->string('tw4_rp')->nullable()->after('renaksi_tahun_2025_tw4');            

        });
    }

    public function down(): void
    {
        Schema::table('rb_general', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            $table->dropColumn(['tw1_rp', 'tw2_rp', 'tw3_rp', 'tw4_rp', 'no', 'satuan_output', 'indikator_output', 'pelaksana']);
        });
    }
};