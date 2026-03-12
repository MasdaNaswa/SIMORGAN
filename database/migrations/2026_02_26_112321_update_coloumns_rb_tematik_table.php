<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rb_tematik', function (Blueprint $table) {

            $table->string('renaksi_tw1_target', 80)->nullable()->change();
            $table->string('renaksi_tw1_rp', 80)->nullable()->change();

            $table->string('renaksi_tw2_target', 80)->nullable()->change();
            $table->string('renaksi_tw2_rp', 80)->nullable()->change();

            $table->string('renaksi_tw3_target', 80)->nullable()->change();
            $table->string('renaksi_tw3_rp', 80)->nullable()->change();

            $table->string('renaksi_tw4_target', 80)->nullable()->change();
            $table->string('renaksi_tw4_rp', 80)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rb_general', function (Blueprint $table) {

            $table->string('renaksi_tw1_target', 255)->nullable()->change();
            $table->string('renaksi_tw1_rp', 255)->nullable()->change();

            $table->string('renaksi_tw2_target', 255)->nullable()->change();
            $table->string('renaksi_tw2_rp', 255)->nullable()->change();

            $table->string('renaksi_tw3_target', 255)->nullable()->change();
            $table->string('renaksi_tw3_rp', 255)->nullable()->change();

            $table->string('renaksi_tw4_target', 255)->nullable()->change();
            $table->string('renaksi_tw4_rp', 255)->nullable()->change();
        });
    }
};