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
            $table->year('anggaran_tahun')->nullable()->after('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {
            $table->dropColumn('anggaran_tahun');
        });
    }
};
