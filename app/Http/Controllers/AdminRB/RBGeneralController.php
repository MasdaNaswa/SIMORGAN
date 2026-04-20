<?php

namespace App\Http\Controllers\AdminRB;

use App\Http\Controllers\Controller;
use App\Models\RB_General;
use App\Models\AksesRb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RBGeneralController extends Controller
{
    /**
     * Tampilkan daftar RB General (Admin RB bisa CRUD)
     */
    public function index()
    {
        $selectedYear = request()->get('year', date('Y'));

        // Ambil data
        $rbData = RB_General::where('tahun', $selectedYear)
            ->orderBy('unit_kerja')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('adminrb.rb-general.index', compact(
            'rbData', 
            'selectedYear',
        ));
    }

    /**
     * Simpan data baru (Admin RB)
     */
    /**
     * Simpan data baru (Admin RB)
     * TIDAK ADA PENGECEKAN AKSES - ADMIN BISA TAMBAH KAPAN SAJA
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== ADMIN RB STORE RB GENERAL ===');
            Log::info('User: ' . auth()->user()->name);
            Log::info('User Role: ' . auth()->user()->role);
            
            // TIDAK ADA PENGECEKAN AKSES - ADMIN BISA TAMBAH KAPAN SAJA
            
            // Ambil semua data dari request
            $data = $request->all();
            
            // Bersihkan format rupiah
            $rupiahFields = [
                'tw1_rp', 'tw2_rp', 'tw3_rp', 'tw4_rp',
                'realisasi_tw1_rp', 'realisasi_tw2_rp', 'realisasi_tw3_rp', 'realisasi_tw4_rp',
                'anggaran_tahun'
            ];
            
            foreach ($rupiahFields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = $this->cleanRupiah($data[$field]);
                }
            }
            
            // Pastikan tahun ada
            if (empty($data['tahun'])) {
                $data['tahun'] = date('Y');
            }
            
            // Hapus field yang tidak diperlukan
            unset($data['_token']);
            unset($data['_method']);
            
            // INSERT LANGSUNG MENGGUNAKAN QUERY BUILDER (BYPASS SEMUA VALIDASI)
            $id = DB::table('rb_general')->insertGetId($data);
            
            Log::info('Data RB General berhasil disimpan dengan ID: ' . $id);
            
            return response()->json([
                'success' => true,
                'message' => 'Data RB General berhasil ditambahkan',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error store RB General: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan detail data (AJAX)
     */
    public function show($id)
    {
        try {
            $rb = RB_General::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $rb->id,
                    'no' => $rb->no,
                    'tahun' => $rb->tahun,
                    'sasaran_strategi' => $rb->sasaran_strategi,
                    'indikator_capaian' => $rb->indikator_capaian,
                    'target' => $rb->target,
                    'satuan' => $rb->satuan,
                    'target_tahun' => $rb->target_tahun,
                    'rencana_aksi' => $rb->rencana_aksi,
                    'satuan_output' => $rb->satuan_output,
                    'indikator_output' => $rb->indikator_output,
                    'renaksi_tw1_target' => $rb->renaksi_tw1_target,
                    'tw1_rp' => $rb->tw1_rp,
                    'renaksi_tw2_target' => $rb->renaksi_tw2_target,
                    'tw2_rp' => $rb->tw2_rp,
                    'renaksi_tw3_target' => $rb->renaksi_tw3_target,
                    'tw3_rp' => $rb->tw3_rp,
                    'renaksi_tw4_target' => $rb->renaksi_tw4_target,
                    'tw4_rp' => $rb->tw4_rp,
                    'realisasi_tw1_target' => $rb->realisasi_tw1_target,
                    'realisasi_tw1_rp' => $rb->realisasi_tw1_rp,
                    'realisasi_tw2_target' => $rb->realisasi_tw2_target,
                    'realisasi_tw2_rp' => $rb->realisasi_tw2_rp,
                    'realisasi_tw3_target' => $rb->realisasi_tw3_target,
                    'realisasi_tw3_rp' => $rb->realisasi_tw3_rp,
                    'realisasi_tw4_target' => $rb->realisasi_tw4_target,
                    'realisasi_tw4_rp' => $rb->realisasi_tw4_rp,
                    'anggaran_tahun' => $rb->anggaran_tahun,
                    'rumus' => $rb->rumus,
                    'catatan_evaluasi' => $rb->catatan_evaluasi,
                    'catatan_perbaikan' => $rb->catatan_perbaikan,
                    'unit_kerja' => $rb->unit_kerja,
                    'pelaksana' => $rb->pelaksana,
                    'status' => $rb->status ?? 'draft',
                    'lastUpdated' => $rb->updated_at->format('d F Y, H:i') . ' WIB'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Tampilkan form edit (AJAX)
     */
    public function edit($id)
    {
        try {
            $rb = RB_General::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $rb->id,
                    'no' => $rb->no,
                    'tahun' => $rb->tahun,
                    'sasaran_strategi' => $rb->sasaran_strategi,
                    'indikator_capaian' => $rb->indikator_capaian,
                    'target' => $rb->target,
                    'satuan' => $rb->satuan,
                    'target_tahun' => $rb->target_tahun,
                    'rencana_aksi' => $rb->rencana_aksi,
                    'satuan_output' => $rb->satuan_output,
                    'indikator_output' => $rb->indikator_output,
                    'renaksi_tw1_target' => $rb->renaksi_tw1_target,
                    'tw1_rp' => $rb->tw1_rp,
                    'renaksi_tw2_target' => $rb->renaksi_tw2_target,
                    'tw2_rp' => $rb->tw2_rp,
                    'renaksi_tw3_target' => $rb->renaksi_tw3_target,
                    'tw3_rp' => $rb->tw3_rp,
                    'renaksi_tw4_target' => $rb->renaksi_tw4_target,
                    'tw4_rp' => $rb->tw4_rp,
                    'realisasi_tw1_target' => $rb->realisasi_tw1_target,
                    'realisasi_tw1_rp' => $rb->realisasi_tw1_rp,
                    'realisasi_tw2_target' => $rb->realisasi_tw2_target,
                    'realisasi_tw2_rp' => $rb->realisasi_tw2_rp,
                    'realisasi_tw3_target' => $rb->realisasi_tw3_target,
                    'realisasi_tw3_rp' => $rb->realisasi_tw3_rp,
                    'realisasi_tw4_target' => $rb->realisasi_tw4_target,
                    'realisasi_tw4_rp' => $rb->realisasi_tw4_rp,
                    'anggaran_tahun' => $rb->anggaran_tahun,
                    'rumus' => $rb->rumus,
                    'catatan_evaluasi' => $rb->catatan_evaluasi,
                    'catatan_perbaikan' => $rb->catatan_perbaikan,
                    'unit_kerja' => $rb->unit_kerja,
                    'pelaksana' => $rb->pelaksana,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update data (Admin RB)
     */
    public function update(Request $request, $id)
    {
        try {
            // Ambil semua data dari request
            $data = $request->all();
            
            // Bersihkan format rupiah
            $rupiahFields = [
                'tw1_rp', 'tw2_rp', 'tw3_rp', 'tw4_rp',
                'realisasi_tw1_rp', 'realisasi_tw2_rp', 'realisasi_tw3_rp', 'realisasi_tw4_rp',
                'anggaran_tahun'
            ];
            
            foreach ($rupiahFields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = $this->cleanRupiah($data[$field]);
                }
            }
            
            // Hapus field yang tidak diperlukan
            unset($data['_token']);
            unset($data['_method']);
            
            // Update langsung menggunakan Query Builder
            $updated = DB::table('rb_general')->where('id', $id)->update($data);
            
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data RB General berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada perubahan data'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus data (Admin RB)
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('rb_general')->where('id', $id)->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data RB General berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean Rupiah format to number
     */
    private function cleanRupiah($value)
    {
        if (empty($value)) {
            return 0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = str_replace('Rp ', '', $value);
        $value = str_replace('.', '', $value);
        return (float) preg_replace('/[^0-9]/', '', $value);
    }

   public function exportExcel(Request $request)
{
    $year = $request->get('year', date('Y'));

    $rbData = RB_General::where('tahun', $year)
        ->orderBy('unit_kerja')
        ->orderBy('created_at', 'desc')
        ->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('RA RB General');

    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(25);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(10);
    $sheet->getColumnDimension('E')->setWidth(10);
    $sheet->getColumnDimension('F')->setWidth(25);
    $sheet->getColumnDimension('G')->setWidth(12);
    $sheet->getColumnDimension('H')->setWidth(15);
    $sheet->getColumnDimension('I')->setWidth(12);
    $sheet->getColumnDimension('J')->setWidth(15);
    $sheet->getColumnDimension('K')->setWidth(12);
    $sheet->getColumnDimension('L')->setWidth(15);
    $sheet->getColumnDimension('M')->setWidth(12);
    $sheet->getColumnDimension('N')->setWidth(15);
    $sheet->getColumnDimension('O')->setWidth(12);
    $sheet->getColumnDimension('P')->setWidth(15);
    $sheet->getColumnDimension('Q')->setWidth(12);
    $sheet->getColumnDimension('R')->setWidth(15);
    $sheet->getColumnDimension('S')->setWidth(12);
    $sheet->getColumnDimension('T')->setWidth(15);
    $sheet->getColumnDimension('U')->setWidth(12);
    $sheet->getColumnDimension('V')->setWidth(15);
    $sheet->getColumnDimension('W')->setWidth(12);
    $sheet->getColumnDimension('X')->setWidth(15);
    $sheet->getColumnDimension('Y')->setWidth(12);
    $sheet->getColumnDimension('Z')->setWidth(15);
    $sheet->getColumnDimension('AA')->setWidth(20);
    $sheet->getColumnDimension('AB')->setWidth(20);
    $sheet->getColumnDimension('AC')->setWidth(20);
    $sheet->getColumnDimension('AD')->setWidth(20);
    $sheet->getColumnDimension('AE')->setWidth(20);

    // Style untuk header
    $headerStyle = [
        'font' => [
            'bold' => true,
            'size' => 10,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '2F75B5'], // Biru tua untuk header
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
    ];

    // Style untuk data (tanpa fill, akan diatur per baris)
    $dataStyle = [
        'font' => [
            'size' => 9,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_TOP,
            'wrapText' => true,
        ],
    ];

    // Style untuk baris genap (warna abu-abu muda seperti PDF)
    $rowEvenStyle = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F2F2F2'], // Abu-abu sangat muda
        ],
    ];

    // Style untuk baris ganjil (warna putih)
    $rowOddStyle = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'FFFFFF'], // Putih
        ],
    ];

    // Baris 1: Judul
    $sheet->mergeCells('A1:AE1');
    $sheet->setCellValue('A1', 'RENCANA AKSI RB GENERAL TAHUN ' . $year);
    $sheet->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(30);
    $sheet->getRowDimension(2)->setRowHeight(5);

    // ========== HEADER BARIS 3 ==========
    $sheet->setCellValue('A3', 'NO');
    $sheet->setCellValue('B3', 'SASARAN STRATEGI');
    $sheet->setCellValue('C3', 'INDIKATOR CAPAIAN SASARAN STRATEGI DAN IMPLEMENTASI KEBIJAKAN PERCEPATAN');
    $sheet->setCellValue('D3', 'TARGET');
    $sheet->setCellValue('E3', 'SATUAN');
    $sheet->setCellValue('F3', 'RENCANA AKSI');
    $sheet->setCellValue('G3', 'OUTPUT');
    $sheet->setCellValue('H3', 'OUTPUT');
    $sheet->setCellValue('I3', 'TARGET TAHUN');
    $sheet->setCellValue('J3', 'ANGGARAN TAHUN');
    $sheet->setCellValue('K3', 'RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('L3', 'RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('M3', 'RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('N3', 'RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('O3', 'RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('P3', 'RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('Q3', 'RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('R3', 'RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('S3', 'REALISASI RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('T3', 'REALISASI RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('U3', 'REALISASI RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('V3', 'REALISASI RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('W3', 'REALISASI RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('X3', 'REALISASI RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('Y3', 'REALISASI RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('Z3', 'REALISASI RENAKSI TAHUN ' . $year);
    $sheet->setCellValue('AA3', 'RUMUS');
    $sheet->setCellValue('AB3', 'CATATAN EVALUASI');
    $sheet->setCellValue('AC3', 'CATATAN PERBAIKAN');
    $sheet->setCellValue('AD3', 'UNIT KERJA / SATUAN KERJA PELAKSANAAN');
    $sheet->setCellValue('AE3', 'UNIT KERJA / SATUAN KERJA PELAKSANAAN');

    // Merge untuk baris 3
    $sheet->mergeCells('A3:A5');
    $sheet->mergeCells('B3:B5');
    $sheet->mergeCells('C3:C5');
    $sheet->mergeCells('D3:D5');
    $sheet->mergeCells('E3:E5');
    $sheet->mergeCells('F3:F5');
    $sheet->mergeCells('G3:H3');
    $sheet->mergeCells('I3:I5');
    $sheet->mergeCells('J3:J5');
    $sheet->mergeCells('K3:R3');
    $sheet->mergeCells('S3:Z3');
    $sheet->mergeCells('AA3:AA5');
    $sheet->mergeCells('AB3:AB5');
    $sheet->mergeCells('AC3:AC5');
    $sheet->mergeCells('AD3:AE3');

    // ========== HEADER BARIS 4 ==========
    $sheet->setCellValue('G4', 'SATUAN');
    $sheet->setCellValue('H4', 'INDIKATOR');
    $sheet->setCellValue('K4', 'TW1');
    $sheet->setCellValue('L4', 'TW1');
    $sheet->setCellValue('M4', 'TW2');
    $sheet->setCellValue('N4', 'TW2');
    $sheet->setCellValue('O4', 'TW3');
    $sheet->setCellValue('P4', 'TW3');
    $sheet->setCellValue('Q4', 'TW4');
    $sheet->setCellValue('R4', 'TW4');
    $sheet->setCellValue('S4', 'TW1');
    $sheet->setCellValue('T4', 'TW1');
    $sheet->setCellValue('U4', 'TW2');
    $sheet->setCellValue('V4', 'TW2');
    $sheet->setCellValue('W4', 'TW3');
    $sheet->setCellValue('X4', 'TW3');
    $sheet->setCellValue('Y4', 'TW4');
    $sheet->setCellValue('Z4', 'TW4');
    $sheet->setCellValue('AD4', 'KOORDINATOR');
    $sheet->setCellValue('AE4', 'PELAKSANA');

    // Merge untuk baris 4
    $sheet->mergeCells('G4:G5');
    $sheet->mergeCells('H4:H5');
    $sheet->mergeCells('K4:L4');
    $sheet->mergeCells('M4:N4');
    $sheet->mergeCells('O4:P4');
    $sheet->mergeCells('Q4:R4');
    $sheet->mergeCells('S4:T4');
    $sheet->mergeCells('U4:V4');
    $sheet->mergeCells('W4:X4');
    $sheet->mergeCells('Y4:Z4');
    $sheet->mergeCells('AD4:AD5');
    $sheet->mergeCells('AE4:AE5');

    // ========== HEADER BARIS 5 ==========
    $sheet->setCellValue('K5', 'TARGET');
    $sheet->setCellValue('L5', 'RP');
    $sheet->setCellValue('M5', 'TARGET');
    $sheet->setCellValue('N5', 'RP');
    $sheet->setCellValue('O5', 'TARGET');
    $sheet->setCellValue('P5', 'RP');
    $sheet->setCellValue('Q5', 'TARGET');
    $sheet->setCellValue('R5', 'RP');
    $sheet->setCellValue('S5', 'TARGET');
    $sheet->setCellValue('T5', 'RP');
    $sheet->setCellValue('U5', 'TARGET');
    $sheet->setCellValue('V5', 'RP');
    $sheet->setCellValue('W5', 'TARGET');
    $sheet->setCellValue('X5', 'RP');
    $sheet->setCellValue('Y5', 'TARGET');
    $sheet->setCellValue('Z5', 'RP');

    // Apply style ke ALL header
    $sheet->getStyle('A3:AE5')->applyFromArray($headerStyle);

    // Set row heights
    $sheet->getRowDimension(3)->setRowHeight(40);
    $sheet->getRowDimension(4)->setRowHeight(30);
    $sheet->getRowDimension(5)->setRowHeight(25);

    // ========== DATA ==========
    $row = 6;
    $no = 1;
    $currentSasaran = '';
    $rowIndex = 0; // Untuk menghitung baris data (mulai dari 0)

    foreach ($rbData as $item) {
        $isNewSasaran = ($currentSasaran != $item->sasaran_strategi);
        if ($isNewSasaran) {
            $currentSasaran = $item->sasaran_strategi;
        }

        // Ambil nilai dari database, jika null atau empty set ke 0
        $anggaranTahun = !empty($item->anggaran_tahun) ? $this->cleanRupiah($item->anggaran_tahun) : 0;
        $tw1Rp = !empty($item->tw1_rp) ? $this->cleanRupiah($item->tw1_rp) : 0;
        $tw2Rp = !empty($item->tw2_rp) ? $this->cleanRupiah($item->tw2_rp) : 0;
        $tw3Rp = !empty($item->tw3_rp) ? $this->cleanRupiah($item->tw3_rp) : 0;
        $tw4Rp = !empty($item->tw4_rp) ? $this->cleanRupiah($item->tw4_rp) : 0;
        
        $realisasiTw1Rp = !empty($item->realisasi_tw1_rp) ? $this->cleanRupiah($item->realisasi_tw1_rp) : 0;
        $realisasiTw2Rp = !empty($item->realisasi_tw2_rp) ? $this->cleanRupiah($item->realisasi_tw2_rp) : 0;
        $realisasiTw3Rp = !empty($item->realisasi_tw3_rp) ? $this->cleanRupiah($item->realisasi_tw3_rp) : 0;
        $realisasiTw4Rp = !empty($item->realisasi_tw4_rp) ? $this->cleanRupiah($item->realisasi_tw4_rp) : 0;
        
        $realisasiTw1Target = !empty($item->realisasi_tw1_target) ? $item->realisasi_tw1_target : '';
        $realisasiTw2Target = !empty($item->realisasi_tw2_target) ? $item->realisasi_tw2_target : '';
        $realisasiTw3Target = !empty($item->realisasi_tw3_target) ? $item->realisasi_tw3_target : '';
        $realisasiTw4Target = !empty($item->realisasi_tw4_target) ? $item->realisasi_tw4_target : '';

        $sheet->setCellValue('A' . $row, $isNewSasaran ? $no++ : '');
        $sheet->setCellValue('B' . $row, $isNewSasaran ? $item->sasaran_strategi : '');
        $sheet->setCellValue('C' . $row, $item->indikator_capaian);
        $sheet->setCellValue('D' . $row, $item->target);
        $sheet->setCellValue('E' . $row, $item->satuan);
        $sheet->setCellValue('F' . $row, $item->rencana_aksi);
        $sheet->setCellValue('G' . $row, $item->satuan_output);
        $sheet->setCellValue('H' . $row, $item->indikator_output);
        $sheet->setCellValue('I' . $row, $item->target_tahun);
        $sheet->setCellValue('J' . $row, $anggaranTahun > 0 ? number_format($anggaranTahun, 0, ',', '.') : '0');
        $sheet->setCellValue('K' . $row, $item->renaksi_tw1_target);
        $sheet->setCellValue('L' . $row, $tw1Rp > 0 ? number_format($tw1Rp, 0, ',', '.') : '0');
        $sheet->setCellValue('M' . $row, $item->renaksi_tw2_target);
        $sheet->setCellValue('N' . $row, $tw2Rp > 0 ? number_format($tw2Rp, 0, ',', '.') : '0');
        $sheet->setCellValue('O' . $row, $item->renaksi_tw3_target);
        $sheet->setCellValue('P' . $row, $tw3Rp > 0 ? number_format($tw3Rp, 0, ',', '.') : '0');
        $sheet->setCellValue('Q' . $row, $item->renaksi_tw4_target);
        $sheet->setCellValue('R' . $row, $tw4Rp > 0 ? number_format($tw4Rp, 0, ',', '.') : '0');
        $sheet->setCellValue('S' . $row, $realisasiTw1Target);
        $sheet->setCellValue('T' . $row, $realisasiTw1Rp > 0 ? number_format($realisasiTw1Rp, 0, ',', '.') : '0');
        $sheet->setCellValue('U' . $row, $realisasiTw2Target);
        $sheet->setCellValue('V' . $row, $realisasiTw2Rp > 0 ? number_format($realisasiTw2Rp, 0, ',', '.') : '0');
        $sheet->setCellValue('W' . $row, $realisasiTw3Target);
        $sheet->setCellValue('X' . $row, $realisasiTw3Rp > 0 ? number_format($realisasiTw3Rp, 0, ',', '.') : '0');
        $sheet->setCellValue('Y' . $row, $realisasiTw4Target);
        $sheet->setCellValue('Z' . $row, $realisasiTw4Rp > 0 ? number_format($realisasiTw4Rp, 0, ',', '.') : '0');
        $sheet->setCellValue('AA' . $row, $item->rumus);
        $sheet->setCellValue('AB' . $row, $item->catatan_evaluasi);
        $sheet->setCellValue('AC' . $row, $item->catatan_perbaikan);
        $sheet->setCellValue('AD' . $row, $item->unit_kerja);
        $sheet->setCellValue('AE' . $row, $item->pelaksana);

        // Apply style dasar ke data
        $sheet->getStyle('A' . $row . ':AE' . $row)->applyFromArray($dataStyle);
        
        // Apply warna latar berselang-seling (zebra striping) seperti di PDF
        if ($rowIndex % 2 == 0) {
            $sheet->getStyle('A' . $row . ':AE' . $row)->applyFromArray($rowEvenStyle);
        } else {
            $sheet->getStyle('A' . $row . ':AE' . $row)->applyFromArray($rowOddStyle);
        }

        $row++;
        $rowIndex++;
    }

    // Apply alignment tambahan untuk kolom angka (setelah data diisi)
    if ($row > 6) {
        // Atur alignment untuk kolom angka
        $sheet->getStyle('L6:L' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('N6:N' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('P6:P' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('R6:R' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('T6:T' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('V6:V' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('X6:X' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('Z6:Z' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('J6:J' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Atur alignment untuk kolom tengah
        $sheet->getStyle('A6:A' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D6:D' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E6:E' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I6:I' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'RAD RB GENERAL ' . $year . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

    /**
     * Export data ke PDF
     */
    public function exportPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $rbData = RB_General::where('tahun', $year)->get();

        $html = $this->generatePdfHtml($rbData, $year);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('legal', 'landscape');

        return $pdf->download('RAD RB GENERAL ' . $year . '.pdf');
    }

    /**
     * Generate HTML untuk PDF
     */
    private function generatePdfHtml($rbData, $year)
{
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>RA RB General ' . $year . '</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 7px;
                line-height: 1.2;
                margin: 2px;
            }
            table { 
                border-collapse: collapse; 
                width: 100%; 
                table-layout: fixed;
            }
            th, td { 
                border: 1px solid #000; 
                padding: 2px; 
                vertical-align: middle; 
                word-break: break-word;
                line-height: 1.2;
            }
            th { 
                background-color: #2F75B5 !important; 
                color: #FFFFFF !important; 
                font-weight: bold; 
                text-align: center; 
                vertical-align: middle;
            }
            td { 
                background-color: #DDEBF7 !important; 
            }
            .text-center { 
                text-align: center; 
            }
            .text-right { 
                text-align: right; 
            }
            .title { 
                font-size: 12px; 
                font-weight: bold; 
                text-align: center; 
                margin-bottom: 5px; 
            }
        </style>
    </head>
    <body>
        <div class="title">RENCANA AKSI RB GENERAL TAHUN ' . $year . '</div>
        
        <table>';

    $html .= '
            <thead>
                <tr>
                    <th rowspan="3" width="2%">NO</th>
                    <th rowspan="3" width="8%">SASARAN STRATEGI</th>
                    <th rowspan="3" width="12%">INDIKATOR CAPAIAN</th>
                    <th rowspan="3" width="2%">TARGET</th>
                    <th rowspan="3" width="2%">SATUAN</th>
                    <th rowspan="3" width="8%">Rencana Aksi</th>
                    <th colspan="2" width="5%">OUT PUT</th>
                    <th rowspan="3" width="3%">Target Tahun ' . $year . '</th>
                    <th rowspan="3" width="4%">Anggaran Tahun ' . $year . '</th>
                    <th colspan="8" width="16%">Renaksi Tahun ' . $year . '</th>
                    <th colspan="8" width="16%">Realisasi Renaksi Tahun ' . $year . '</th>
                    <th rowspan="3" width="4%">RUMUS</th>
                    <th rowspan="3" width="4%">CATATAN EVALUASI</th>
                    <th rowspan="3" width="4%">CATATAN PERBAIKAN</th>
                    <th colspan="2" width="6%">UNIT KERJA / PELAKSANA</th>
                </tr>
                <tr>
                    <th rowspan="2" width="2.5%">SATUAN</th>
                    <th rowspan="2" width="2.5%">INDIKATOR</th>
                    <th colspan="2" width="4%">TW1</th>
                    <th colspan="2" width="4%">TW2</th>
                    <th colspan="2" width="4%">TW3</th>
                    <th colspan="2" width="4%">TW4</th>
                    <th colspan="2" width="4%">TW1</th>
                    <th colspan="2" width="4%">TW2</th>
                    <th colspan="2" width="4%">TW3</th>
                    <th colspan="2" width="4%">TW4</th>
                    <th rowspan="2" width="3%">KOORDINATOR</th>
                    <th rowspan="2" width="3%">PELAKSANA</th>
                </tr>
                <tr>
                    <th width="2%">Target</th>
                    <th width="2%">Rp</th>
                    <th width="2%">Target</th>
                    <th width="2%">Rp</th>
                    <th width="2%">Target</th>
                    <th width="2%">Rp</th>
                    <th width="2%">Target</th>
                    <th width="2%">Rp</th>
                    <th width="2%">Target</th>
                    <th width="2%">Rp</th>
                    <th width="2%">Target</th>
                    <th width="2%">Rp</th>
                    <th width="2%">Target</th>
                    <th width="2%">Rp</th>
                    <th width="2%">Target</th>
                    <th width="2%">Rp</th>
                </tr>
            </thead>
            <tbody>';

    $no = 1;
    $currentSasaran = '';

    foreach ($rbData as $item) {
        $isNewSasaran = ($currentSasaran != $item->sasaran_strategi);
        if ($isNewSasaran) {
            $currentSasaran = $item->sasaran_strategi;
        }

        // Ambil nilai dari database
        $anggaranTahun = !empty($item->anggaran_tahun) ? $this->cleanRupiah($item->anggaran_tahun) : 0;
        $tw1Rp = !empty($item->tw1_rp) ? $this->cleanRupiah($item->tw1_rp) : 0;
        $tw2Rp = !empty($item->tw2_rp) ? $this->cleanRupiah($item->tw2_rp) : 0;
        $tw3Rp = !empty($item->tw3_rp) ? $this->cleanRupiah($item->tw3_rp) : 0;
        $tw4Rp = !empty($item->tw4_rp) ? $this->cleanRupiah($item->tw4_rp) : 0;
        
        // PERBAIKAN: Gunakan nama kolom yang sesuai
        $realisasiTw1Rp = !empty($item->realisasi_tw1_rp) ? $this->cleanRupiah($item->realisasi_tw1_rp) : 0;
        $realisasiTw2Rp = !empty($item->realisasi_tw2_rp) ? $this->cleanRupiah($item->realisasi_tw2_rp) : 0;
        $realisasiTw3Rp = !empty($item->realisasi_tw3_rp) ? $this->cleanRupiah($item->realisasi_tw3_rp) : 0;
        $realisasiTw4Rp = !empty($item->realisasi_tw4_rp) ? $this->cleanRupiah($item->realisasi_tw4_rp) : 0;
        
        // Ambil nilai target realisasi
        $realisasiTw1Target = !empty($item->realisasi_tw1_target) ? $item->realisasi_tw1_target : '0';
        $realisasiTw2Target = !empty($item->realisasi_tw2_target) ? $item->realisasi_tw2_target : '0';
        $realisasiTw3Target = !empty($item->realisasi_tw3_target) ? $item->realisasi_tw3_target : '0';
        $realisasiTw4Target = !empty($item->realisasi_tw4_target) ? $item->realisasi_tw4_target : '0';

        $html .= '
                <tr>
                    <td class="text-center">' . ($isNewSasaran ? $no++ : '') . '</td>
                    <td>' . ($isNewSasaran ? htmlspecialchars($item->sasaran_strategi) : '') . '</td>
                    <td>' . htmlspecialchars($item->indikator_capaian) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->target) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->satuan) . '</td>
                    <td>' . htmlspecialchars($item->rencana_aksi) . '</td>
                    <td>' . htmlspecialchars($item->satuan_output) . '</td>
                    <td>' . htmlspecialchars($item->indikator_output) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->target_tahun) . '</td>
                    <td class="text-right">' . ($anggaranTahun > 0 ? number_format($anggaranTahun, 0, ',', '.') : '0') . '</td>
                    <td class="text-center">' . htmlspecialchars($item->renaksi_tw1_target) . '</td>
                    <td class="text-right">' . ($tw1Rp > 0 ? number_format($tw1Rp, 0, ',', '.') : '0') . '</td>
                    <td class="text-center">' . htmlspecialchars($item->renaksi_tw2_target) . '</td>
                    <td class="text-right">' . ($tw2Rp > 0 ? number_format($tw2Rp, 0, ',', '.') : '0') . '</td>
                    <td class="text-center">' . htmlspecialchars($item->renaksi_tw3_target) . '</td>
                    <td class="text-right">' . ($tw3Rp > 0 ? number_format($tw3Rp, 0, ',', '.') : '0') . '</td>
                    <td class="text-center">' . htmlspecialchars($item->renaksi_tw4_target) . '</td>
                    <td class="text-right">' . ($tw4Rp > 0 ? number_format($tw4Rp, 0, ',', '.') : '0') . '</td>
                    <td class="text-center">' . htmlspecialchars($realisasiTw1Target) . '</td>
                    <td class="text-right">' . ($realisasiTw1Rp > 0 ? number_format($realisasiTw1Rp, 0, ',', '.') : '0') . '</td>
                    <td class="text-center">' . htmlspecialchars($realisasiTw2Target) . '</td>
                    <td class="text-right">' . ($realisasiTw2Rp > 0 ? number_format($realisasiTw2Rp, 0, ',', '.') : '0') . '</td>
                    <td class="text-center">' . htmlspecialchars($realisasiTw3Target) . '</td>
                    <td class="text-right">' . ($realisasiTw3Rp > 0 ? number_format($realisasiTw3Rp, 0, ',', '.') : '0') . '</td>
                    <td class="text-center">' . htmlspecialchars($realisasiTw4Target) . '</td>
                    <td class="text-right">' . ($realisasiTw4Rp > 0 ? number_format($realisasiTw4Rp, 0, ',', '.') : '0') . '</td>
                    <td>' . htmlspecialchars($item->rumus) . '</td>
                    <td>' . htmlspecialchars($item->catatan_evaluasi) . '</td>
                    <td>' . htmlspecialchars($item->catatan_perbaikan) . '</td>
                    <td>' . htmlspecialchars($item->unit_kerja) . '</td>
                    <td>' . htmlspecialchars($item->pelaksana) . '</td>
                </tr>';
    }

    $html .= '
            </tbody>
        </table>
    </body>
    </html>';

    return $html;
}
}