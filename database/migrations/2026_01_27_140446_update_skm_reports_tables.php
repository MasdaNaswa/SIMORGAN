<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cek apakah tabel skm_reports ada
        if (!Schema::hasTable('skm_reports')) {
            // Jika tidak ada, buat tabel baru
            Schema::create('skm_reports', function (Blueprint $table) {
                $table->id('id_skm_report');
                
                // Foreign key ke pengguna
                $table->unsignedBigInteger('id_user');
                
                // Data OPD
                $table->string('nama_opd', 100);
                
                // Periode
                $table->tinyInteger('triwulan')->comment('1, 2, 3, 4');
                $table->year('tahun');
                
                // Data Umum
                $table->string('jabatan_penandatangan', 100);
                $table->date('tanggal_pengesahan');
                
                // BAB I: Pendahuluan
                $table->text('latar_belakang')->nullable();
                $table->text('tujuan_manfaat')->nullable();
                $table->text('metode_pengumpulan')->nullable();
                $table->integer('waktu_pelaksanaan_bulan')->nullable();
                $table->integer('jumlah_populasi')->nullable();
                $table->integer('jumlah_sampel')->nullable();
                
                // BAB II: Analisis Data SKM (JSON)
                $table->json('analisis_responden')->nullable();
                $table->json('jenis_layanan')->nullable();
                $table->json('rerata_ikm')->nullable();
                $table->decimal('ikm_unit_layanan', 5, 2)->nullable();
                $table->string('mutu_unit_layanan', 1)->nullable();
                $table->string('warna_grafik', 20)->default('#36A2EB');
                $table->text('analisis_masalah')->nullable();
                $table->json('rencana_tindak_lanjut_analisis')->nullable();
                $table->json('tren_skm')->nullable();
                $table->json('hasil_skm_sebelumnya')->nullable();
                $table->json('tindak_lanjut_sebelumnya')->nullable();
                
                // BAB IV: Kesimpulan
                $table->text('kesimpulan')->nullable();
                $table->text('saran')->nullable();
                
                // Lampiran (JSON)
                $table->json('dokumentasi_foto')->nullable();
                
                // File dan Status
                $table->string('file_path', 255)->nullable();
                $table->enum('status', ['draft', 'generated', 'submitted', 'approved', 'rejected'])
                      ->default('generated');
                $table->timestamp('generated_at')->nullable();
                
                // Timestamps dan soft deletes
                $table->softDeletes();
                $table->timestamps();
                
                // Foreign key constraint
                $table->foreign('id_user')->references('id_user')->on('pengguna')->onDelete('cascade');
                
                // Indexes
                $table->index(['id_user', 'tahun', 'triwulan']);
                $table->index('status');
                $table->index('created_at');
            });
        } else {
            // Jika tabel sudah ada, tambahkan kolom yang belum ada
            
            // Array kolom yang perlu ditambahkan
            $columnsToAdd = [
                'metode_pengumpulan' => ['type' => 'text', 'nullable' => true],
                'latar_belakang' => ['type' => 'text', 'nullable' => true],
                'tujuan_manfaat' => ['type' => 'text', 'nullable' => true],
                'waktu_pelaksanaan_bulan' => ['type' => 'integer', 'nullable' => true],
                'jumlah_populasi' => ['type' => 'integer', 'nullable' => true],
                'jumlah_sampel' => ['type' => 'integer', 'nullable' => true],
                'analisis_responden' => ['type' => 'json', 'nullable' => true],
                'jenis_layanan' => ['type' => 'json', 'nullable' => true],
                'rerata_ikm' => ['type' => 'json', 'nullable' => true],
                'ikm_unit_layanan' => ['type' => 'decimal', 'precision' => 5, 'scale' => 2, 'nullable' => true],
                'mutu_unit_layanan' => ['type' => 'string', 'length' => 1, 'nullable' => true],
                'warna_grafik' => ['type' => 'string', 'length' => 20, 'nullable' => true, 'default' => '#36A2EB'],
                'analisis_masalah' => ['type' => 'text', 'nullable' => true],
                'rencana_tindak_lanjut_analisis' => ['type' => 'json', 'nullable' => true],
                'tren_skm' => ['type' => 'json', 'nullable' => true],
                'hasil_skm_sebelumnya' => ['type' => 'json', 'nullable' => true],
                'tindak_lanjut_sebelumnya' => ['type' => 'json', 'nullable' => true],
                'dokumentasi_foto' => ['type' => 'json', 'nullable' => true],
            ];
            
            // Tambahkan kolom yang belum ada
            Schema::table('skm_reports', function (Blueprint $table) use ($columnsToAdd) {
                foreach ($columnsToAdd as $columnName => $config) {
                    if (!Schema::hasColumn('skm_reports', $columnName)) {
                        switch ($config['type']) {
                            case 'json':
                                $table->json($columnName)->nullable();
                                break;
                            case 'decimal':
                                $table->decimal($columnName, $config['precision'], $config['scale'])->nullable();
                                break;
                            case 'integer':
                                $table->integer($columnName)->nullable();
                                break;
                            case 'string':
                                $table->string($columnName, $config['length'] ?? 255)->nullable();
                                break;
                            case 'text':
                                $table->text($columnName)->nullable();
                                break;
                        }
                        
                        // Set default value jika ada
                        if (isset($config['default'])) {
                            $table->{$columnName}->default($config['default']);
                        }
                    }
                }
            });
        }
        
        // 2. Update tabel laporan untuk menambahkan foreign key jika belum ada
        if (Schema::hasTable('laporan') && !Schema::hasColumn('laporan', 'id_skm_report')) {
            Schema::table('laporan', function (Blueprint $table) {
                $table->unsignedBigInteger('id_skm_report')->nullable()->after('id_user');
                $table->foreign('id_skm_report')->references('id_skm_report')->on('skm_reports')->nullOnDelete();
            });
        } elseif (Schema::hasTable('laporans') && !Schema::hasColumn('laporans', 'id_skm_report')) {
            // Cek dengan nama tabel 'laporans' (plural)
            Schema::table('laporans', function (Blueprint $table) {
                $table->unsignedBigInteger('id_skm_report')->nullable()->after('id_user');
                $table->foreign('id_skm_report')->references('id_skm_report')->on('skm_reports')->nullOnDelete();
            });
        }
        
        // 3. Hapus kolom yang tidak digunakan jika ada
        Schema::table('skm_reports', function (Blueprint $table) {
            $columnsToDrop = ['analisis', 'anggota_tim', 'rencana_tindak_lanjut'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('skm_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        // Jangan drop tabel untuk down migration, cukup hapus kolom yang ditambahkan
        Schema::table('skm_reports', function (Blueprint $table) {
            $columnsToDrop = [
                'metode_pengumpulan',
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
                'warna_grafik',
                'analisis_masalah',
                'rencana_tindak_lanjut_analisis',
                'tren_skm',
                'hasil_skm_sebelumnya',
                'tindak_lanjut_sebelumnya',
                'dokumentasi_foto'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('skm_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
        
        // Hapus foreign key dari tabel laporan
        if (Schema::hasTable('laporan') && Schema::hasColumn('laporan', 'id_skm_report')) {
            Schema::table('laporan', function (Blueprint $table) {
                $table->dropForeign(['id_skm_report']);
                $table->dropColumn('id_skm_report');
            });
        } elseif (Schema::hasTable('laporans') && Schema::hasColumn('laporans', 'id_skm_report')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->dropForeign(['id_skm_report']);
                $table->dropColumn('id_skm_report');
            });
        }
    }
};