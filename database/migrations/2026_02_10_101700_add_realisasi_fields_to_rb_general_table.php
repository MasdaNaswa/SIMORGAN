<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRealisasiFieldsToRbGeneralTable extends Migration
{
    public function up()
    {
        Schema::table('rb_general', function (Blueprint $table) {
            // Tambah field realisasi TW3 dan TW4 yang hilang
            $table->string('realisasi_tw3_target')->nullable()->after('realisasi_renaksi_tw4');
            $table->string('realisasi_tw3_rp')->nullable()->after('realisasi_tw3_target');
            $table->string('realisasi_tw4_target')->nullable()->after('realisasi_tw3_rp');
            $table->string('realisasi_tw4_rp')->nullable()->after('realisasi_tw4_target');
        });
    }

    public function down()
    {
        Schema::table('rb_general', function (Blueprint $table) {
            $table->dropColumn([
                'realisasi_tw3_target',
                'realisasi_tw3_rp',
                'realisasi_tw4_target',
                'realisasi_tw4_rp'
            ]);
        });
    }
}