<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rb_general', function (Blueprint $table) {
            // Ubah field target tahun (dari target_tahun_2025 ke target_tahun)
            if (Schema::hasColumn('rb_general', 'target_tahun_2025')) {
                $table->renameColumn('target_tahun_2025', 'target_tahun');
            }
            
            // Ubah field anggaran tahun (dari anggaran_tahun_2025 ke anggaran_total)
            if (Schema::hasColumn('rb_general', 'anggaran_tahun_2025')) {
                $table->renameColumn('anggaran_tahun_2025', 'anggaran_total');
            }
            
            // Ubah field renaksi TW1 (dari renaksi_tahun_2025_tw1 ke renaksi_tw1_target)
            if (Schema::hasColumn('rb_general', 'renaksi_tahun_2025_tw1')) {
                $table->renameColumn('renaksi_tahun_2025_tw1', 'renaksi_tw1_target');
            }
            
            // Ubah field renaksi TW2 (dari renaksi_tahun_2025_tw2 ke renaksi_tw2_target)
            if (Schema::hasColumn('rb_general', 'renaksi_tahun_2025_tw2')) {
                $table->renameColumn('renaksi_tahun_2025_tw2', 'renaksi_tw2_target');
            }
            
            // Ubah field renaksi TW3 (dari renaksi_tahun_2025_tw3 ke renaksi_tw3_target)
            if (Schema::hasColumn('rb_general', 'renaksi_tahun_2025_tw3')) {
                $table->renameColumn('renaksi_tahun_2025_tw3', 'renaksi_tw3_target');
            }
            
            // Ubah field renaksi TW4 (dari renaksi_tahun_2025_tw4 ke renaksi_tw4_target)
            if (Schema::hasColumn('rb_general', 'renaksi_tahun_2025_tw4')) {
                $table->renameColumn('renaksi_tahun_2025_tw4', 'renaksi_tw4_target');
            }
        });
    }

    public function down()
    {
        Schema::table('rb_general', function (Blueprint $table) {
            // Rollback perubahan
            if (Schema::hasColumn('rb_general', 'target_tahun')) {
                $table->renameColumn('target_tahun', 'target_tahun_2025');
            }
            
            if (Schema::hasColumn('rb_general', 'anggaran_total')) {
                $table->renameColumn('anggaran_total', 'anggaran_tahun_2025');
            }
            
            if (Schema::hasColumn('rb_general', 'renaksi_tw1_target')) {
                $table->renameColumn('renaksi_tw1_target', 'renaksi_tahun_2025_tw1');
            }
            
            if (Schema::hasColumn('rb_general', 'renaksi_tw2_target')) {
                $table->renameColumn('renaksi_tw2_target', 'renaksi_tahun_2025_tw2');
            }
            
            if (Schema::hasColumn('rb_general', 'renaksi_tw3_target')) {
                $table->renameColumn('renaksi_tw3_target', 'renaksi_tahun_2025_tw3');
            }
            
            if (Schema::hasColumn('rb_general', 'renaksi_tw4_target')) {
                $table->renameColumn('renaksi_tw4_target', 'renaksi_tahun_2025_tw4');
            }
        });
    }
};