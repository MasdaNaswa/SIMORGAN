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
            $table->text('catatan_evaluasi')->nullable()->after('updated_at');
            $table->text('catatan_perbaikan')->nullable()->after('catatan_evaluasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {
            $table->dropColumn(['catatan_evaluasi', 'catatan_perbaikan']);
        });
    }
};