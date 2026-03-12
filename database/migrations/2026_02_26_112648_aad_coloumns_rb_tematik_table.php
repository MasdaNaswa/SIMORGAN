<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {

            $table->string('realisasi_renaksi_tw1_target', 80)->nullable()->after('renaksi_tw1_target');
            $table->string('realisasi_renaksi_tw1_rp', 80)->nullable()->after('renaksi_tw1_rp');

            $table->string('realisasi_renaksi_tw2_target', 80)->nullable()->after('renaksi_tw2_target');
            $table->string('realisasi_renaksi_tw2_rp', 80)->nullable()->after('renaksi_tw2_rp');

            $table->string('realisasi_renaksi_tw3_target', 80)->nullable()->after('renaksi_tw3_target');
            $table->string('realisasi_renaksi_tw3_rp', 80)->nullable()->after('renaksi_tw3_rp');

            $table->string('realisasi_renaksi_tw4_target', 80)->nullable()->after('renaksi_tw4_target');
            $table->string('realisasi_renaksi_tw4_rp', 80)->nullable()->after('renaksi_tw4_rp');
        });
    }

    public function down(): void
    {
        Schema::table('rb_general', function (Blueprint $table) {

            $table->dropColumn([
                'realisasi_renaksi_tw1_target',
                'realisasi_renaksi_tw1_rp',
                'realisasi_renaksi_tw2_target',
                'realisasi_renaksi_tw2_rp',
                'realisasi_renaksi_tw3_target',
                'realisasi_renaksi_tw3_rp',
                'realisasi_renaksi_tw4_target',
                'realisasi_renaksi_tw4_rp',
            ]);
        });
    }
};