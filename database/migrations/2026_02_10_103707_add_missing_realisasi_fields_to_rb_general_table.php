<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rb_general', function (Blueprint $table) {
            // Hapus kolom lama jika ada yang salah nama
            if (Schema::hasColumn('rb_general', 'realisasi_renaksi_tw1')) {
                $table->dropColumn('realisasi_renaksi_tw1');
            }
            if (Schema::hasColumn('rb_general', 'realisasi_renaksi_tw2')) {
                $table->dropColumn('realisasi_renaksi_tw2');
            }
            if (Schema::hasColumn('rb_general', 'realisasi_renaksi_tw3')) {
                $table->dropColumn('realisasi_renaksi_tw3');
            }
            if (Schema::hasColumn('rb_general', 'realisasi_renaksi_tw4')) {
                $table->dropColumn('realisasi_renaksi_tw4');
            }
            
            // Tambahkan kolom-kolom baru yang sesuai dengan form
            // Realisasi TW1
            if (!Schema::hasColumn('rb_general', 'realisasi_tw1_target')) {
                $table->string('realisasi_tw1_target')->nullable()->after('tw4_rp');
            }
            if (!Schema::hasColumn('rb_general', 'realisasi_tw1_rp')) {
                $table->string('realisasi_tw1_rp')->nullable()->after('realisasi_tw1_target');
            }
            
            // Realisasi TW2
            if (!Schema::hasColumn('rb_general', 'realisasi_tw2_target')) {
                $table->string('realisasi_tw2_target')->nullable()->after('realisasi_tw1_rp');
            }
            if (!Schema::hasColumn('rb_general', 'realisasi_tw2_rp')) {
                $table->string('realisasi_tw2_rp')->nullable()->after('realisasi_tw2_target');
            }
            
            // Realisasi TW3 (sudah ada di model tapi pastikan)
            if (!Schema::hasColumn('rb_general', 'realisasi_tw3_target')) {
                $table->string('realisasi_tw3_target')->nullable()->after('realisasi_tw2_rp');
            }
            if (!Schema::hasColumn('rb_general', 'realisasi_tw3_rp')) {
                $table->string('realisasi_tw3_rp')->nullable()->after('realisasi_tw3_target');
            }
            
            // Realisasi TW4 (sudah ada di model tapi pastikan)
            if (!Schema::hasColumn('rb_general', 'realisasi_tw4_target')) {
                $table->string('realisasi_tw4_target')->nullable()->after('realisasi_tw3_rp');
            }
            if (!Schema::hasColumn('rb_general', 'realisasi_tw4_rp')) {
                $table->string('realisasi_tw4_rp')->nullable()->after('realisasi_tw4_target');
            }
        });
    }

    public function down()
    {
        Schema::table('rb_general', function (Blueprint $table) {
            // Kembalikan kolom yang di-drop (jika perlu)
            $table->dropColumn([
                'realisasi_tw1_target',
                'realisasi_tw1_rp',
                'realisasi_tw2_target',
                'realisasi_tw2_rp',
                'realisasi_tw3_target',
                'realisasi_tw3_rp',
                'realisasi_tw4_target',
                'realisasi_tw4_rp'
            ]);
        });
    }
};