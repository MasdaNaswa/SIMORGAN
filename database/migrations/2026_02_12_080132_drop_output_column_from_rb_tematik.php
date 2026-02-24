<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {
            $table->dropColumn('output');
        });
    }

    public function down(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {
            $table->string('output')->nullable();
        });
    }
};