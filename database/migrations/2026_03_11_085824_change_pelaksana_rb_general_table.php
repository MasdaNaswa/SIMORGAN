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
            $table->string('pelaksana', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rb_general', function (Blueprint $table) {
            $table->string('pelaksana', 225)->change();
        });
    }
};