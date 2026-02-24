<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom semester menjadi ENUM dengan nilai 'I' dan 'II'
        DB::statement("ALTER TABLE pk_bupati MODIFY COLUMN semester ENUM('I', 'II') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke tipe data sebelumnya (misal varchar)
        DB::statement("ALTER TABLE pk_bupati MODIFY COLUMN semester VARCHAR(4) NOT NULL");
    }
};