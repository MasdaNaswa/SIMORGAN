<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pk_bupati', function (Blueprint $table) {
            // Kolom yang kurang
            $table->integer('no')->after('id');
            $table->integer('tahun')->after('no');
            $table->string('target_tw1')->nullable()->after('satuan');
            $table->string('realisasi_tw1')->nullable()->after('target_tw1');
            $table->string('target_tw2')->nullable()->after('realisasi_tw1');
            $table->string('realisasi_tw2')->nullable()->after('target_tw2');
            $table->string('target_tw3')->nullable()->after('realisasi_tw2');
            $table->string('realisasi_tw3')->nullable()->after('target_tw3');
            $table->string('target_tw4')->nullable()->after('realisasi_tw3');
            $table->string('realisasi_tw4')->nullable()->after('target_tw4');
            $table->string('pagu_anggaran_tw1')->nullable()->after('realisasi_tw4');
            $table->string('realisasi_anggaran_tw1')->nullable()->after('pagu_anggaran_tw1');
            $table->string('pagu_anggaran_tw2')->nullable()->after('realisasi_anggaran_tw1');
            $table->string('realisasi_anggaran_tw2')->nullable()->after('pagu_anggaran_tw2');
            $table->string('pagu_anggaran_tw3')->nullable()->after('realisasi_anggaran_tw2');
            $table->string('realisasi_anggaran_tw3')->nullable()->after('pagu_anggaran_tw3');
            $table->string('pagu_anggaran_tw4')->nullable()->after('realisasi_anggaran_tw3');
            $table->string('realisasi_anggaran_tw4')->nullable()->after('pagu_anggaran_tw4');
        });
    }

    public function down(): void
    {
        Schema::table('pk_bupati', function (Blueprint $table) {
            $table->dropColumn([
                'no',
                'tahun',
                'target_tw1',
                'realisasi_tw1',
                'target_tw2',
                'realisasi_tw2',
                'target_tw3',
                'realisasi_tw3',
                'target_tw4',
                'realisasi_tw4',
                'pagu_anggaran_tw1',
                'realisasi_anggaran_tw1',
                'pagu_anggaran_tw2',
                'realisasi_anggaran_tw2',
                'pagu_anggaran_tw3',
                'realisasi_anggaran_tw3',
                'pagu_anggaran_tw4',
                'realisasi_anggaran_tw4',
            ]);
        });
    }
};