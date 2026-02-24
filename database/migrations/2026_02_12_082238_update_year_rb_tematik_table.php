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
        Schema::table('rb_tematik', function (Blueprint $table) {
            // Ubah kolom tahun dari integer menjadi year (4 digit)
            $table->year('tahun')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {
            // Kembalikan ke integer
            $table->integer('tahun')->nullable()->change();
        });
    }
};