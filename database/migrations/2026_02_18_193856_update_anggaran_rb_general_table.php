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
        Schema::table('rb_general', function (Blueprint $table) {
            $table->renameColumn('anggaran_total', 'anggaran_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rb_general', function (Blueprint $table) {
            $table->renameColumn('anggaran_tahun', 'anggaran_total');
        });
    }
};
