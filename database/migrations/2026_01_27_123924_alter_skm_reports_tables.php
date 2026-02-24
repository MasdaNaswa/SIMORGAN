<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skm_reports', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambah
            // TANPA menggunakan 'after' untuk kolom yang belum ada
            $columnsToAdd = [
                'latar_belakang' => ['type' => 'text', 'nullable' => true],
                'tujuan_manfaat' => ['type' => 'text', 'nullable' => true],
                'waktu_pelaksanaan_bulan' => ['type' => 'string', 'nullable' => true],
                'jumlah_populasi' => ['type' => 'integer', 'nullable' => true],
                'jumlah_sampel' => ['type' => 'integer', 'nullable' => true],
                'analisis_responden' => ['type' => 'text', 'nullable' => true],
                'jenis_layanan' => ['type' => 'string', 'nullable' => true],
                'rerata_ikm' => ['type' => 'decimal', 'parameters' => [5,2], 'nullable' => true],
                'ikm_unit_layanan' => ['type' => 'decimal', 'parameters' => [5,2], 'nullable' => true],
                'mutu_unit_layanan' => ['type' => 'string', 'nullable' => true],
                'analisis_masalah' => ['type' => 'text', 'nullable' => true], // Tambahkan ini dulu
                'rencana_tindak_lanjut_analisis' => ['type' => 'text', 'nullable' => true],
                'tren_skm' => ['type' => 'text', 'nullable' => true],
                'hasil_skm_sebelumnya' => ['type' => 'text', 'nullable' => true],
                'tindak_lanjut_sebelumnya' => ['type' => 'text', 'nullable' => true],
                'dokumentasi_foto' => ['type' => 'text', 'nullable' => true],
                'kuesioner_path' => ['type' => 'string', 'nullable' => true],
                'warna_grafik' => ['type' => 'string', 'length' => 7, 'nullable' => true, 'default' => '#36A2EB'],
            ];

            foreach ($columnsToAdd as $columnName => $config) {
                if (!Schema::hasColumn('skm_reports', $columnName)) {
                    // Handle different column types
                    if ($config['type'] === 'decimal' && isset($config['parameters'])) {
                        $column = $table->decimal($columnName, ...$config['parameters']);
                    } else {
                        $column = $table->{$config['type']}(
                            $columnName, 
                            $config['length'] ?? null
                        );
                    }
                    
                    if (isset($config['nullable']) && $config['nullable']) {
                        $column->nullable();
                    }
                    
                    if (isset($config['default'])) {
                        $column->default($config['default']);
                    }
                }
            }
        });

        // Pastikan nama tabel konsisten
        $tableName = 'laporans'; // Ganti dengan 'laporan' jika perlu
        
        if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'id_skm_report')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('id_skm_report')->nullable()->after('id_user');
                $table->foreign('id_skm_report')->references('id_skm_report')->on('skm_reports')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tableName = 'laporans'; // HARUS SAMA dengan di up()
        
        if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'id_skm_report')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['id_skm_report']);
                $table->dropColumn('id_skm_report');
            });
        }

        // Hapus kolom yang ditambahkan
        Schema::table('skm_reports', function (Blueprint $table) {
            $columnsToDrop = [
                'latar_belakang',
                'tujuan_manfaat',
                'waktu_pelaksanaan_bulan',
                'jumlah_populasi',
                'jumlah_sampel',
                'analisis_responden',
                'jenis_layanan',
                'rerata_ikm',
                'ikm_unit_layanan',
                'mutu_unit_layanan',
                'analisis_masalah',
                'rencana_tindak_lanjut_analisis',
                'tren_skm',
                'hasil_skm_sebelumnya',
                'tindak_lanjut_sebelumnya',
                'dokumentasi_foto',
                'kuesioner_path',
                'warna_grafik',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('skm_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};