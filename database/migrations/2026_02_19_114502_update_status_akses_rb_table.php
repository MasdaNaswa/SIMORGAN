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
        // Ubah tipe data kolom status yang sudah ada menjadi enum
        DB::statement("ALTER TABLE `akses_rb` MODIFY `status` ENUM('Dibuka', 'Ditutup') NOT NULL DEFAULT 'Dibuka'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke tipe data sebelumnya (varchar)
        DB::statement("ALTER TABLE `akses_rb` MODIFY `status` VARCHAR(255) NOT NULL DEFAULT 'Dibuka'");
    }
};