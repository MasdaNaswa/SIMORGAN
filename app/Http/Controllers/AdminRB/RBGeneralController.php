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

    /**
     * Export data ke Excel
     */
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
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(12);
        $sheet->getColumnDimension('L')->setWidth(10);
        $sheet->getColumnDimension('M')->setWidth(12);
        $sheet->getColumnDimension('N')->setWidth(12);
        $sheet->getColumnDimension('O')->setWidth(10);
        $sheet->getColumnDimension('P')->setWidth(12);
        $sheet->getColumnDimension('Q')->setWidth(12);
        $sheet->getColumnDimension('R')->setWidth(10);
        $sheet->getColumnDimension('S')->setWidth(12);
        $sheet->getColumnDimension('T')->setWidth(12);
        $sheet->getColumnDimension('U')->setWidth(10);
        $sheet->getColumnDimension('V')->setWidth(12);
        $sheet->getColumnDimension('W')->setWidth(12);
        $sheet->getColumnDimension('X')->setWidth(10);
        $sheet->getColumnDimension('Y')->setWidth(12);
        $sheet->getColumnDimension('Z')->setWidth(12);
        $sheet->getColumnDimension('AA')->setWidth(10);
        $sheet->getColumnDimension('AB')->setWidth(12);
        $sheet->getColumnDimension('AC')->setWidth(12);
        $sheet->getColumnDimension('AD')->setWidth(10);
        $sheet->getColumnDimension('AE')->setWidth(12);
        $sheet->getColumnDimension('AF')->setWidth(12);
        $sheet->getColumnDimension('AG')->setWidth(10);
        $sheet->getColumnDimension('AH')->setWidth(12);
        $sheet->getColumnDimension('AI')->setWidth(25);
        $sheet->getColumnDimension('AJ')->setWidth(20);
        $sheet->getColumnDimension('AK')->setWidth(20);
        $sheet->getColumnDimension('AL')->setWidth(25);
        $sheet->getColumnDimension('AM')->setWidth(20);
        $sheet->getColumnDimension('AN')->setWidth(20);

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
                'startColor' => ['rgb' => '2F75B5'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Style untuk data
        $dataStyle = [
            'font' => [
                'size' => 9,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DDEBF7'],
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

        // Baris 1: Judul
        $sheet->mergeCells('A1:AN1');
        $sheet->setCellValue('A1', 'RENCANA AKSI RB GENERAL TAHUN ' . $year);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        $sheet->getRowDimension(2)->setRowHeight(10);

        // Header baris 3
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'SASARAN STRATEGI');
        $sheet->setCellValue('C3', 'INDIKATOR CAPAIAN SASARAN STRATEGI DAN IMPLEMENTASI KEBIJAKAN PERCEPATAN');
        $sheet->setCellValue('D3', 'TARGET');
        $sheet->setCellValue('E3', 'SATUAN');
        $sheet->setCellValue('F3', 'Rencana Aksi');
        $sheet->mergeCells('G3:H3');
        $sheet->setCellValue('G3', 'OUT PUT');
        $sheet->setCellValue('I3', 'Target Tahun ' . $year);
        $sheet->setCellValue('J3', 'Anggaran Tahun ' . $year);
        $sheet->mergeCells('K3:V3');
        $sheet->setCellValue('K3', 'Renaksi Tahun ' . $year);
        $sheet->mergeCells('W3:AH3');
        $sheet->setCellValue('W3', 'Realisasi Renaksi Tahun ' . $year);
        $sheet->setCellValue('AI3', 'RUMUS');
        $sheet->setCellValue('AJ3', 'CATATAN EVALUASI');
        $sheet->setCellValue('AK3', 'CATATAN PERBAIKAN');
        $sheet->mergeCells('AL3:AN3');
        $sheet->setCellValue('AL3', 'UNIT KERJA / SATUAN KERJA PELAKSANAAN');

        // Header baris 4
        $sheet->setCellValue('A4', '');
        $sheet->setCellValue('B4', '');
        $sheet->setCellValue('C4', '');
        $sheet->setCellValue('D4', '');
        $sheet->setCellValue('E4', '');
        $sheet->setCellValue('F4', '');
        $sheet->setCellValue('G4', 'SATUAN');
        $sheet->setCellValue('H4', 'INDIKATOR');
        $sheet->setCellValue('I4', '');
        $sheet->setCellValue('J4', '');
        $sheet->mergeCells('K4:L4');
        $sheet->setCellValue('K4', 'TW1');
        $sheet->mergeCells('M4:O4');
        $sheet->setCellValue('M4', 'TW2');
        $sheet->mergeCells('P4:R4');
        $sheet->setCellValue('P4', 'TW3');
        $sheet->mergeCells('S4:U4');
        $sheet->setCellValue('S4', 'TW4');
        $sheet->mergeCells('W4:X4');
        $sheet->setCellValue('W4', 'TW1');
        $sheet->mergeCells('Y4:AA4');
        $sheet->setCellValue('Y4', 'TW2');
        $sheet->mergeCells('AB4:AD4');
        $sheet->setCellValue('AB4', 'TW3');
        $sheet->mergeCells('AE4:AG4');
        $sheet->setCellValue('AE4', 'TW4');
        $sheet->setCellValue('AI4', '');
        $sheet->setCellValue('AJ4', '');
        $sheet->setCellValue('AK4', '');
        $sheet->setCellValue('AL4', 'KOORDINATOR');
        $sheet->setCellValue('AM4', 'PELAKSANA');
        $sheet->setCellValue('AN4', '');

        // Header baris 5
        $sheet->setCellValue('K5', 'Target');
        $sheet->setCellValue('L5', 'Rp');
        $sheet->setCellValue('M5', 'Target');
        $sheet->setCellValue('N5', 'Rp');
        $sheet->setCellValue('O5', 'Target');
        $sheet->setCellValue('P5', 'Rp');
        $sheet->setCellValue('Q5', 'Target');
        $sheet->setCellValue('R5', 'Rp');
        $sheet->setCellValue('S5', 'Target');
        $sheet->setCellValue('T5', 'Rp');
        $sheet->setCellValue('U5', 'Target');
        $sheet->setCellValue('V5', 'Rp');
        $sheet->setCellValue('W5', 'Target');
        $sheet->setCellValue('X5', 'Rp');
        $sheet->setCellValue('Y5', 'Target');
        $sheet->setCellValue('Z5', 'Rp');
        $sheet->setCellValue('AA5', 'Target');
        $sheet->setCellValue('AB5', 'Rp');
        $sheet->setCellValue('AC5', 'Target');
        $sheet->setCellValue('AD5', 'Rp');
        $sheet->setCellValue('AE5', 'Target');
        $sheet->setCellValue('AF5', 'Rp');
        $sheet->setCellValue('AG5', 'Target');
        $sheet->setCellValue('AH5', 'Rp');

        // Merge cells
        $sheet->mergeCells('A3:A5');
        $sheet->mergeCells('B3:B5');
        $sheet->mergeCells('C3:C5');
        $sheet->mergeCells('D3:D5');
        $sheet->mergeCells('E3:E5');
        $sheet->mergeCells('F3:F5');
        $sheet->mergeCells('G3:H3');
        $sheet->mergeCells('G4:G5');
        $sheet->mergeCells('H4:H5');
        $sheet->mergeCells('I3:I5');
        $sheet->mergeCells('J3:J5');
        $sheet->mergeCells('K3:V3');
        $sheet->mergeCells('W3:AH3');
        $sheet->mergeCells('AI3:AI5');
        $sheet->mergeCells('AJ3:AJ5');
        $sheet->mergeCells('AK3:AK5');
        $sheet->mergeCells('AL3:AN3');
        $sheet->mergeCells('AL4:AL5');
        $sheet->mergeCells('AM4:AM5');
        $sheet->mergeCells('AN4:AN5');

        // Apply style ke header
        $sheet->getStyle('A3:AN5')->applyFromArray($headerStyle);

        // Data mulai dari baris 6
        $row = 6;
        $no = 1;
        $currentSasaran = '';

        foreach ($rbData as $item) {
            $isNewSasaran = ($currentSasaran != $item->sasaran_strategi);
            if ($isNewSasaran) {
                $currentSasaran = $item->sasaran_strategi;
            }

            $anggaranTahun = $this->cleanRupiah($item->anggaran_tahun);
            $tw1Rp = $this->cleanRupiah($item->tw1_rp);
            $tw2Rp = $this->cleanRupiah($item->tw2_rp);
            $tw3Rp = $this->cleanRupiah($item->tw3_rp);
            $tw4Rp = $this->cleanRupiah($item->tw4_rp);
            $realisasiTw1Rp = $this->cleanRupiah($item->realisasi_tw1_rp);
            $realisasiTw2Rp = $this->cleanRupiah($item->realisasi_tw2_rp);
            $realisasiTw3Rp = $this->cleanRupiah($item->realisasi_tw3_rp);
            $realisasiTw4Rp = $this->cleanRupiah($item->realisasi_tw4_rp);

            $sheet->setCellValue('A' . $row, $isNewSasaran ? $no++ : '');
            $sheet->setCellValue('B' . $row, $isNewSasaran ? $item->sasaran_strategi : '');
            $sheet->setCellValue('C' . $row, $item->indikator_capaian);
            $sheet->setCellValue('D' . $row, $item->target);
            $sheet->setCellValue('E' . $row, $item->satuan);
            $sheet->setCellValue('F' . $row, $item->rencana_aksi);
            $sheet->setCellValue('G' . $row, $item->satuan_output);
            $sheet->setCellValue('H' . $row, $item->indikator_output);
            $sheet->setCellValue('I' . $row, $item->target_tahun);
            $sheet->setCellValue('J' . $row, $anggaranTahun ? number_format($anggaranTahun, 0, ',', '.') : '');
            $sheet->setCellValue('K' . $row, $item->renaksi_tw1_target);
            $sheet->setCellValue('L' . $row, $tw1Rp ? number_format($tw1Rp, 0, ',', '.') : '');
            $sheet->setCellValue('M' . $row, $item->renaksi_tw2_target);
            $sheet->setCellValue('N' . $row, $tw2Rp ? number_format($tw2Rp, 0, ',', '.') : '');
            $sheet->setCellValue('O' . $row, $item->renaksi_tw3_target);
            $sheet->setCellValue('P' . $row, $tw3Rp ? number_format($tw3Rp, 0, ',', '.') : '');
            $sheet->setCellValue('Q' . $row, $item->renaksi_tw4_target);
            $sheet->setCellValue('R' . $row, $tw4Rp ? number_format($tw4Rp, 0, ',', '.') : '');
            $sheet->setCellValue('S' . $row, $item->realisasi_tw1_target);
            $sheet->setCellValue('T' . $row, $realisasiTw1Rp ? number_format($realisasiTw1Rp, 0, ',', '.') : '');
            $sheet->setCellValue('U' . $row, $item->realisasi_tw2_target);
            $sheet->setCellValue('V' . $row, $realisasiTw2Rp ? number_format($realisasiTw2Rp, 0, ',', '.') : '');
            $sheet->setCellValue('W' . $row, $item->realisasi_tw3_target);
            $sheet->setCellValue('X' . $row, $realisasiTw3Rp ? number_format($realisasiTw3Rp, 0, ',', '.') : '');
            $sheet->setCellValue('Y' . $row, $item->realisasi_tw4_target);
            $sheet->setCellValue('Z' . $row, $realisasiTw4Rp ? number_format($realisasiTw4Rp, 0, ',', '.') : '');
            $sheet->setCellValue('AA' . $row, $item->rumus);
            $sheet->setCellValue('AB' . $row, $item->catatan_evaluasi);
            $sheet->setCellValue('AC' . $row, $item->catatan_perbaikan);
            $sheet->setCellValue('AD' . $row, $item->unit_kerja);
            $sheet->setCellValue('AE' . $row, $item->pelaksana);

            $row++;
        }

        if ($row > 6) {
            $sheet->getStyle('A6:AE' . ($row - 1))->applyFromArray($dataStyle);
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

            $anggaranTahun = $this->cleanRupiah($item->anggaran_tahun);
            $tw1Rp = $this->cleanRupiah($item->tw1_rp);
            $tw2Rp = $this->cleanRupiah($item->tw2_rp);
            $tw3Rp = $this->cleanRupiah($item->tw3_rp);
            $tw4Rp = $this->cleanRupiah($item->tw4_rp);
            $realisasiTw1Rp = $this->cleanRupiah($item->realisasi_tw1_rp);
            $realisasiTw2Rp = $this->cleanRupiah($item->realisasi_tw2_rp);
            $realisasiTw3Rp = $this->cleanRupiah($item->realisasi_tw3_rp);
            $realisasiTw4Rp = $this->cleanRupiah($item->realisasi_tw4_rp);

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
                        <td class="text-right">' . ($anggaranTahun ? number_format($anggaranTahun, 0, ',', '.') : '-') . '</td>
                        <td class="text-center">' . htmlspecialchars($item->renaksi_tw1_target) . '</td>
                        <td class="text-right">' . ($tw1Rp ? number_format($tw1Rp, 0, ',', '.') : '-') . '</td>
                        <td class="text-center">' . htmlspecialchars($item->renaksi_tw2_target) . '</td>
                        <td class="text-right">' . ($tw2Rp ? number_format($tw2Rp, 0, ',', '.') : '-') . '</td>
                        <td class="text-center">' . htmlspecialchars($item->renaksi_tw3_target) . '</td>
                        <td class="text-right">' . ($tw3Rp ? number_format($tw3Rp, 0, ',', '.') : '-') . '</td>
                        <td class="text-center">' . htmlspecialchars($item->renaksi_tw4_target) . '</td>
                        <td class="text-right">' . ($tw4Rp ? number_format($tw4Rp, 0, ',', '.') : '-') . '</td>
                        <td class="text-center">' . htmlspecialchars($item->realisasi_tw1_target) . '</td>
                        <td class="text-right">' . ($realisasiTw1Rp ? number_format($realisasiTw1Rp, 0, ',', '.') : '-') . '</td>
                        <td class="text-center">' . htmlspecialchars($item->realisasi_tw2_target) . '</td>
                        <td class="text-right">' . ($realisasiTw2Rp ? number_format($realisasiTw2Rp, 0, ',', '.') : '-') . '</td>
                        <td class="text-center">' . htmlspecialchars($item->realisasi_tw3_target) . '</td>
                        <td class="text-right">' . ($realisasiTw3Rp ? number_format($realisasiTw3Rp, 0, ',', '.') : '-') . '</td>
                        <td class="text-center">' . htmlspecialchars($item->realisasi_tw4_target) . '</td>
                        <td class="text-right">' . ($realisasiTw4Rp ? number_format($realisasiTw4Rp, 0, ',', '.') : '-') . '</td>
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