<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('akses_rb', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_rb', ['rb_general', 'rb_tematik', 'pk_bupati'])->unique();
            $table->string('status');
            $table->boolean('is_open')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rb_akses');
    }
};