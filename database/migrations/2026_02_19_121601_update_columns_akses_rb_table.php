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
        Schema::table('akses_rb', function (Blueprint $table) {
            $table->enum('jenis_rb', [
                'RB General',
                'RB Tematik',
                'PK Bupati'
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('akses_rb', function (Blueprint $table) {
            $table->enum('jenis_rb', [
                'rb_general',
                'rb_tematik',
                'pk_bupati'
            ])->change();
        });
    }
};
