<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pk_bupati', function (Blueprint $table) {
            // Kolom yang kurang
            $table->string('semester')->after('tahun');
        });
    }

    public function down(): void
    {
        Schema::table('pk_bupati', function (Blueprint $table) {
            $table->dropColumn([
               'semester'
            ]);
        });
    }
};