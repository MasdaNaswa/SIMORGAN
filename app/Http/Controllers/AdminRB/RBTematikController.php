<?php

namespace App\Http\Controllers\AdminRB;

use App\Http\Controllers\Controller;
use App\Models\RB_Tematik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RBTematikController extends Controller
{
    /**
     * Display a listing of the resource for admin.
     */
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $selectedYear = $request->get('year', $currentYear);

        // Ambil data berdasarkan tahun dari database
        $rbTematik = RB_Tematik::where('tahun', $selectedYear)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Generate tahun untuk filter (dari 2024 sampai tahun sekarang + 1)
        $startYear = 2024;
        $years = range($startYear, $currentYear + 1);
        rsort($years);

        return view('adminrb.rb-tematik.index', compact(
            'rbTematik',
            'currentYear',
            'selectedYear',
            'years'
        ));
    }

    /**
     * Display the specified resource for admin.
     */
    public function show($id)
    {
        try {
            $rbTematik = RB_Tematik::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $rbTematik
            ]);

        } catch (\Exception $e) {
            Log::error('Error show RB Tematik (Admin): ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get data for editing (admin can edit).
     */
    public function edit($id)
    {
        try {
            $rbTematik = RB_Tematik::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $rbTematik
            ]);

        } catch (\Exception $e) {
            Log::error('Error edit RB Tematik (Admin): ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage (admin can update).
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $rbTematik = RB_Tematik::findOrFail($id);

            // Validasi data
            $validator = Validator::make($request->all(), [
                'permasalahan' => 'required|string',
                'sasaran_tematik' => 'nullable|string|max:100',
                'indikator' => 'nullable|string|max:100',
                'target' => 'nullable|string|max:100',
                'satuan' => 'nullable|string|max:100',
                'rencana_aksi' => 'nullable|string',
                'satuan_output' => 'nullable|string|max:255',
                'indikator_output' => 'nullable|string|max:255',
                'anggaran_tahun' => 'nullable|string|max:255',
                'renaksi_tw1_target' => 'nullable|string|max:255',
                'renaksi_tw1_rp' => 'nullable|string|max:255',
                'renaksi_tw2_target' => 'nullable|string|max:255',
                'renaksi_tw2_rp' => 'nullable|string|max:255',
                'renaksi_tw3_target' => 'nullable|string|max:255',
                'renaksi_tw3_rp' => 'nullable|string|max:255',
                'renaksi_tw4_target' => 'nullable|string|max:255',
                'renaksi_tw4_rp' => 'nullable|string|max:255',
                'koordinator' => 'nullable|string|max:100',
                'pelaksana' => 'nullable|string|max:100',
                'rumus' => 'nullable|string',
                'realisasi_tw1_target' => 'nullable|string|max:255',
                'realisasi_tw1_rp' => 'nullable|string|max:255',
                'realisasi_tw2_target' => 'nullable|string|max:255',
                'realisasi_tw2_rp' => 'nullable|string|max:255',
                'realisasi_tw3_target' => 'nullable|string|max:255',
                'realisasi_tw3_rp' => 'nullable|string|max:255',
                'realisasi_tw4_target' => 'nullable|string|max:255',
                'realisasi_tw4_rp' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Hitung total anggaran dari triwulan
            $anggaran_total = 0;
            $anggaran_total += $this->cleanRupiah($validated['renaksi_tw1_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['renaksi_tw2_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['renaksi_tw3_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['renaksi_tw4_rp'] ?? 0);

            // Update data
            $rbTematik->update([
                'permasalahan' => $validated['permasalahan'],
                'sasaran_tematik' => $validated['sasaran_tematik'],
                'indikator' => $validated['indikator'],
                'target' => $validated['target'],
                'satuan' => $validated['satuan'],
                'target_tahun' => $validated['target'],
                'anggaran_tahun' => $this->cleanRupiah($validated['anggaran_tahun'] ?? $anggaran_total),
                'renaksi_tw1_target' => $validated['renaksi_tw1_target'],
                'renaksi_tw1_rp' => $this->cleanRupiah($validated['renaksi_tw1_rp'] ?? 0),
                'renaksi_tw2_target' => $validated['renaksi_tw2_target'],
                'renaksi_tw2_rp' => $this->cleanRupiah($validated['renaksi_tw2_rp'] ?? 0),
                'renaksi_tw3_target' => $validated['renaksi_tw3_target'],
                'renaksi_tw3_rp' => $this->cleanRupiah($validated['renaksi_tw3_rp'] ?? 0),
                'renaksi_tw4_target' => $validated['renaksi_tw4_target'],
                'renaksi_tw4_rp' => $this->cleanRupiah($validated['renaksi_tw4_rp'] ?? 0),
                'rencana_aksi' => $validated['rencana_aksi'],
                'satuan_output' => $validated['satuan_output'],
                'indikator_output' => $validated['indikator_output'],
                'koordinator' => $validated['koordinator'],
                'pelaksana' => $validated['pelaksana'],
                'rumus' => $validated['rumus'] ?? null,
                'realisasi_tw1_target' => $validated['realisasi_tw1_target'] ?? null,
                'realisasi_tw1_rp' => $this->cleanRupiah($validated['realisasi_tw1_rp'] ?? 0),
                'realisasi_tw2_target' => $validated['realisasi_tw2_target'] ?? null,
                'realisasi_tw2_rp' => $this->cleanRupiah($validated['realisasi_tw2_rp'] ?? 0),
                'realisasi_tw3_target' => $validated['realisasi_tw3_target'] ?? null,
                'realisasi_tw3_rp' => $this->cleanRupiah($validated['realisasi_tw3_rp'] ?? 0),
                'realisasi_tw4_target' => $validated['realisasi_tw4_target'] ?? null,
                'realisasi_tw4_rp' => $this->cleanRupiah($validated['realisasi_tw4_rp'] ?? 0),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $rbTematik
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update RB Tematik (Admin): ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage (admin can delete).
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $rbTematik = RB_Tematik::findOrFail($id);
            $rbTematik->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete RB Tematik (Admin): ' . $e->getMessage());

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
            return (int) $value;
        }

        // Remove 'Rp ' prefix, dots, and non-numeric characters
        $value = str_replace('Rp ', '', $value);
        $value = str_replace('.', '', $value);
        return (int) preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Format number to Rupiah
     */
    private function formatRupiah($value)
    {
        if (empty($value) || $value == 0) {
            return '0';
        }
        return number_format($value, 0, ',', '.');
    }

/**
 * Export data to Excel
 */
public function exportExcel(Request $request)
{
    $year = $request->get('year', date('Y'));
    
    $rbTematik = RB_Tematik::where('tahun', $year)
        ->orderBy('id', 'desc')
        ->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('RB Tematik');

    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(35);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(25);
    $sheet->getColumnDimension('E')->setWidth(10);
    $sheet->getColumnDimension('F')->setWidth(10);
    $sheet->getColumnDimension('G')->setWidth(30);
    $sheet->getColumnDimension('H')->setWidth(15);
    $sheet->getColumnDimension('I')->setWidth(20);
    $sheet->getColumnDimension('J')->setWidth(12);
    $sheet->getColumnDimension('K')->setWidth(15);
    $sheet->getColumnDimension('L')->setWidth(10);
    $sheet->getColumnDimension('M')->setWidth(15);
    $sheet->getColumnDimension('N')->setWidth(10);
    $sheet->getColumnDimension('O')->setWidth(15);
    $sheet->getColumnDimension('P')->setWidth(10);
    $sheet->getColumnDimension('Q')->setWidth(15);
    $sheet->getColumnDimension('R')->setWidth(10);
    $sheet->getColumnDimension('S')->setWidth(15);
    $sheet->getColumnDimension('T')->setWidth(10);
    $sheet->getColumnDimension('U')->setWidth(15);
    $sheet->getColumnDimension('V')->setWidth(10);
    $sheet->getColumnDimension('W')->setWidth(15);
    $sheet->getColumnDimension('X')->setWidth(10);
    $sheet->getColumnDimension('Y')->setWidth(15);
    $sheet->getColumnDimension('Z')->setWidth(10);
    $sheet->getColumnDimension('AA')->setWidth(15);
    $sheet->getColumnDimension('AB')->setWidth(20);
    $sheet->getColumnDimension('AC')->setWidth(15);
    $sheet->getColumnDimension('AD')->setWidth(15);

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
    $sheet->mergeCells('A1:AD1');
    $sheet->setCellValue('A1', 'RENCANA AKSI RB TEMATIK TAHUN ' . $year);
    $sheet->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(25);

    // Header baris 2 (Main Header)
    $sheet->setCellValue('A2', 'NO');
    $sheet->setCellValue('B2', 'PERMASALAHAN');
    $sheet->setCellValue('C2', 'SASARAN TEMATIK');
    $sheet->setCellValue('D2', 'INDIKATOR');
    $sheet->setCellValue('E2', 'TARGET');
    $sheet->setCellValue('F2', 'SATUAN');
    $sheet->setCellValue('G2', 'RENCANA AKSI');
    $sheet->mergeCells('H2:I2');
    $sheet->setCellValue('H2', 'OUTPUT');
    $sheet->setCellValue('J2', 'TARGET TAHUN');
    $sheet->setCellValue('K2', 'ANGGARAN TAHUN');
    $sheet->mergeCells('L2:S2');
    $sheet->setCellValue('L2', 'RENCANA AKSI PER TRIWULAN');
    $sheet->mergeCells('T2:AA2');
    $sheet->setCellValue('T2', 'REALISASI RENAKSI');
    $sheet->setCellValue('AB2', 'RUMUS');
    $sheet->setCellValue('AC2', 'KOORDINATOR');
    $sheet->setCellValue('AD2', 'PELAKSANA');

    // Header baris 3 (Sub Header)
    $sheet->setCellValue('A3', '');
    $sheet->setCellValue('B3', '');
    $sheet->setCellValue('C3', '');
    $sheet->setCellValue('D3', '');
    $sheet->setCellValue('E3', '');
    $sheet->setCellValue('F3', '');
    $sheet->setCellValue('G3', '');
    $sheet->setCellValue('H3', 'SATUAN');
    $sheet->setCellValue('I3', 'INDIKATOR');
    $sheet->setCellValue('J3', '');
    $sheet->setCellValue('K3', '');
    
    // Sub header untuk Rencana Aksi per Triwulan
    $sheet->mergeCells('L3:M3');
    $sheet->setCellValue('L3', 'TW1');
    $sheet->mergeCells('N3:O3');
    $sheet->setCellValue('N3', 'TW2');
    $sheet->mergeCells('P3:Q3');
    $sheet->setCellValue('P3', 'TW3');
    $sheet->mergeCells('R3:S3');
    $sheet->setCellValue('R3', 'TW4');
    
    // Sub header untuk Realisasi Renaksi
    $sheet->mergeCells('T3:U3');
    $sheet->setCellValue('T3', 'TW1');
    $sheet->mergeCells('V3:W3');
    $sheet->setCellValue('V3', 'TW2');
    $sheet->mergeCells('X3:Y3');
    $sheet->setCellValue('X3', 'TW3');
    $sheet->mergeCells('Z3:AA3');
    $sheet->setCellValue('Z3', 'TW4');
    
    $sheet->setCellValue('AB3', '');
    $sheet->setCellValue('AC3', '');
    $sheet->setCellValue('AD3', '');

    // Header baris 4 (Detail Target/Rp)
    $sheet->setCellValue('L4', 'Target');
    $sheet->setCellValue('M4', 'Rp');
    $sheet->setCellValue('N4', 'Target');
    $sheet->setCellValue('O4', 'Rp');
    $sheet->setCellValue('P4', 'Target');
    $sheet->setCellValue('Q4', 'Rp');
    $sheet->setCellValue('R4', 'Target');
    $sheet->setCellValue('S4', 'Rp');
    $sheet->setCellValue('T4', 'Target');
    $sheet->setCellValue('U4', 'Rp');
    $sheet->setCellValue('V4', 'Target');
    $sheet->setCellValue('W4', 'Rp');
    $sheet->setCellValue('X4', 'Target');
    $sheet->setCellValue('Y4', 'Rp');
    $sheet->setCellValue('Z4', 'Target');
    $sheet->setCellValue('AA4', 'Rp');

    // Merge cells untuk baris 2-4
    // Kolom A-G (single column, merge baris 2-4)
    $sheet->mergeCells('A2:A4');
    $sheet->mergeCells('B2:B4');
    $sheet->mergeCells('C2:C4');
    $sheet->mergeCells('D2:D4');
    $sheet->mergeCells('E2:E4');
    $sheet->mergeCells('F2:F4');
    $sheet->mergeCells('G2:G4');
    
    // Kolom H-I (OUTPUT)
    $sheet->mergeCells('H2:I2');
    $sheet->mergeCells('H3:H4');
    $sheet->mergeCells('I3:I4');
    
    // Kolom J-K
    $sheet->mergeCells('J2:J4');
    $sheet->mergeCells('K2:K4');
    
    // Rencana Aksi per Triwulan (L-S)
    $sheet->mergeCells('L2:S2');
    $sheet->mergeCells('L3:M3');
    $sheet->mergeCells('N3:O3');
    $sheet->mergeCells('P3:Q3');
    $sheet->mergeCells('R3:S3');
    
    // Realisasi Renaksi (T-AA)
    $sheet->mergeCells('T2:AA2');
    $sheet->mergeCells('T3:U3');
    $sheet->mergeCells('V3:W3');
    $sheet->mergeCells('X3:Y3');
    $sheet->mergeCells('Z3:AA3');
    
    // Kolom AB-AD
    $sheet->mergeCells('AB2:AB4');
    $sheet->mergeCells('AC2:AC4');
    $sheet->mergeCells('AD2:AD4');

    // Apply style ke header (baris 2-4)
    $sheet->getStyle('A2:AD4')->applyFromArray($headerStyle);
    
    // Set tinggi baris header
    $sheet->getRowDimension(2)->setRowHeight(30);
    $sheet->getRowDimension(3)->setRowHeight(25);
    $sheet->getRowDimension(4)->setRowHeight(20);

    // Data mulai dari baris 5
    $row = 5;
    $no = 1;

    foreach ($rbTematik as $item) {
        $anggaranTahun = $this->cleanRupiah($item->anggaran_tahun);
        
        // Rencana Aksi per Triwulan
        $tw1Target = $item->renaksi_tw1_target ?? '-';
        $tw1Rp = $this->cleanRupiah($item->renaksi_tw1_rp);
        $tw1RpFormatted = $tw1Rp ? number_format($tw1Rp, 0, ',', '.') : '0';
        
        $tw2Target = $item->renaksi_tw2_target ?? '-';
        $tw2Rp = $this->cleanRupiah($item->renaksi_tw2_rp);
        $tw2RpFormatted = $tw2Rp ? number_format($tw2Rp, 0, ',', '.') : '0';
        
        $tw3Target = $item->renaksi_tw3_target ?? '-';
        $tw3Rp = $this->cleanRupiah($item->renaksi_tw3_rp);
        $tw3RpFormatted = $tw3Rp ? number_format($tw3Rp, 0, ',', '.') : '0';
        
        $tw4Target = $item->renaksi_tw4_target ?? '-';
        $tw4Rp = $this->cleanRupiah($item->renaksi_tw4_rp);
        $tw4RpFormatted = $tw4Rp ? number_format($tw4Rp, 0, ',', '.') : '0';
        
        // REALISASI RENAKSI - PERBAIKAN: Gunakan nama field yang benar dari database
        $realisasiTw1Target = $item->realisasi_renaksi_tw1_target ?? '-';
        $realisasiTw1Rp = $this->cleanRupiah($item->realisasi_renaksi_tw1_rp);
        $realisasiTw1RpFormatted = $realisasiTw1Rp ? number_format($realisasiTw1Rp, 0, ',', '.') : '0';
        
        $realisasiTw2Target = $item->realisasi_renaksi_tw2_target ?? '-';
        $realisasiTw2Rp = $this->cleanRupiah($item->realisasi_renaksi_tw2_rp);
        $realisasiTw2RpFormatted = $realisasiTw2Rp ? number_format($realisasiTw2Rp, 0, ',', '.') : '0';
        
        $realisasiTw3Target = $item->realisasi_renaksi_tw3_target ?? '-';
        $realisasiTw3Rp = $this->cleanRupiah($item->realisasi_renaksi_tw3_rp);
        $realisasiTw3RpFormatted = $realisasiTw3Rp ? number_format($realisasiTw3Rp, 0, ',', '.') : '0';
        
        $realisasiTw4Target = $item->realisasi_renaksi_tw4_target ?? '-';
        $realisasiTw4Rp = $this->cleanRupiah($item->realisasi_renaksi_tw4_rp);
        $realisasiTw4RpFormatted = $realisasiTw4Rp ? number_format($realisasiTw4Rp, 0, ',', '.') : '0';

        // Set nilai per kolom
        $sheet->setCellValue('A' . $row, $no++);
        $sheet->setCellValue('B' . $row, $item->permasalahan);
        $sheet->setCellValue('C' . $row, $item->sasaran_tematik);
        $sheet->setCellValue('D' . $row, $item->indikator);
        $sheet->setCellValue('E' . $row, $item->target);
        $sheet->setCellValue('F' . $row, $item->satuan);
        $sheet->setCellValue('G' . $row, $item->rencana_aksi);
        $sheet->setCellValue('H' . $row, $item->satuan_output);
        $sheet->setCellValue('I' . $row, $item->indikator_output);
        $sheet->setCellValue('J' . $row, $item->target_tahun);
        $sheet->setCellValue('K' . $row, $anggaranTahun ? number_format($anggaranTahun, 0, ',', '.') : '0');
        
        // Rencana Aksi per Triwulan
        $sheet->setCellValue('L' . $row, $tw1Target);
        $sheet->setCellValue('M' . $row, $tw1RpFormatted);
        $sheet->setCellValue('N' . $row, $tw2Target);
        $sheet->setCellValue('O' . $row, $tw2RpFormatted);
        $sheet->setCellValue('P' . $row, $tw3Target);
        $sheet->setCellValue('Q' . $row, $tw3RpFormatted);
        $sheet->setCellValue('R' . $row, $tw4Target);
        $sheet->setCellValue('S' . $row, $tw4RpFormatted);
        
        // REALISASI RENAKSI - Gunakan variabel yang sudah diperbaiki
        $sheet->setCellValue('T' . $row, $realisasiTw1Target);
        $sheet->setCellValue('U' . $row, $realisasiTw1RpFormatted);
        $sheet->setCellValue('V' . $row, $realisasiTw2Target);
        $sheet->setCellValue('W' . $row, $realisasiTw2RpFormatted);
        $sheet->setCellValue('X' . $row, $realisasiTw3Target);
        $sheet->setCellValue('Y' . $row, $realisasiTw3RpFormatted);
        $sheet->setCellValue('Z' . $row, $realisasiTw4Target);
        $sheet->setCellValue('AA' . $row, $realisasiTw4RpFormatted);
        
        $sheet->setCellValue('AB' . $row, $item->rumus);
        $sheet->setCellValue('AC' . $row, $item->koordinator);
        $sheet->setCellValue('AD' . $row, $item->pelaksana);
        
        $row++;
    }

    if ($row > 5) {
        $sheet->getStyle('A5:AD' . ($row - 1))->applyFromArray($dataStyle);
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'RAD RB TEMATIK ' . $year . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}
/**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $rbTematik = RB_Tematik::where('tahun', $year)
            ->orderBy('id', 'desc')
            ->get();

        $html = $this->generatePdfHtml($rbTematik, $year);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('legal', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'Arial',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false
        ]);

        return $pdf->download('RAD RB TEMATIK ' . $year . '.pdf');
    }


   /**
 * Generate HTML untuk PDF
 */
private function generatePdfHtml($rbTematik, $year)
{
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>RB Tematik ' . $year . '</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: "Arial", sans-serif;
                font-size: 8px;
                line-height: 1.3;
                padding: 10px;
            }
            .title {
                text-align: center;
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 15px;
                padding-bottom: 5px;
                border-bottom: 2px solid #2F75B5;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
                page-break-inside: avoid;
            }
            th, td {
                border: 1px solid #000;
                padding: 5px 3px;
                vertical-align: top;
                word-break: break-word;
            }
            th {
                background-color: #2F75B5 !important;
                color: white !important;
                font-weight: bold;
                text-align: center;
                vertical-align: middle;
                font-size: 7px;
            }
            td {
                background-color: #DDEBF7 !important;
                font-size: 7px;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            .text-left {
                text-align: left;
            }
            .page-break {
                page-break-after: always;
            }
            /* Warna khusus untuk baris realisasi */
            tr.realisasi-row td {
                background-color: #E6F0FA !important;
            }
        </style>
    </head>
    <body>
        <div class="title">RENCANA AKSI RB TEMATIK TAHUN ' . $year . '</div>
        
        <table>
            <thead>
                <tr>
                    <th rowspan="3" width="3%">NO</th>
                    <th rowspan="3" width="10%">PERMASALAHAN</th>
                    <th rowspan="3" width="8%">SASARAN TEMATIK</th>
                    <th rowspan="3" width="7%">INDIKATOR</th>
                    <th rowspan="3" width="4%">TARGET</th>
                    <th rowspan="3" width="4%">SATUAN</th>
                    <th rowspan="3" width="8%">RENCANA AKSI</th>
                    <th colspan="2" width="6%">OUTPUT</th>
                    <th rowspan="3" width="6%">TARGET TAHUN</th>
                    <th rowspan="3" width="8%">ANGGARAN TAHUN</th>
                    <th colspan="8" width="16%">RENCANA AKSI PER TRIWULAN</th>
                    <th colspan="8" width="16%">REALISASI RENAKSI</th>
                    <th rowspan="3" width="8%">RUMUS</th>
                    <th rowspan="3" width="7%">KOORDINATOR</th>
                    <th rowspan="3" width="7%">PELAKSANA</th>
                </tr>
                <tr>
                    <th rowspan="2" width="3%">SATUAN</th>
                    <th rowspan="2" width="3%">INDIKATOR</th>
                    <th colspan="2" width="4%">TW1</th>
                    <th colspan="2" width="4%">TW2</th>
                    <th colspan="2" width="4%">TW3</th>
                    <th colspan="2" width="4%">TW4</th>
                    <th colspan="2" width="4%">TW1</th>
                    <th colspan="2" width="4%">TW2</th>
                    <th colspan="2" width="4%">TW3</th>
                    <th colspan="2" width="4%">TW4</th>
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

    foreach ($rbTematik as $item) {
        $anggaranTahun = $this->cleanRupiah($item->anggaran_tahun);
        
        // Rencana Aksi per Triwulan
        $tw1Target = htmlspecialchars($item->renaksi_tw1_target ?? '-');
        $tw1Rp = $this->cleanRupiah($item->renaksi_tw1_rp);
        $tw1RpFormatted = $tw1Rp ? number_format($tw1Rp, 0, ',', '.') : '0';
        
        $tw2Target = htmlspecialchars($item->renaksi_tw2_target ?? '-');
        $tw2Rp = $this->cleanRupiah($item->renaksi_tw2_rp);
        $tw2RpFormatted = $tw2Rp ? number_format($tw2Rp, 0, ',', '.') : '0';
        
        $tw3Target = htmlspecialchars($item->renaksi_tw3_target ?? '-');
        $tw3Rp = $this->cleanRupiah($item->renaksi_tw3_rp);
        $tw3RpFormatted = $tw3Rp ? number_format($tw3Rp, 0, ',', '.') : '0';
        
        $tw4Target = htmlspecialchars($item->renaksi_tw4_target ?? '-');
        $tw4Rp = $this->cleanRupiah($item->renaksi_tw4_rp);
        $tw4RpFormatted = $tw4Rp ? number_format($tw4Rp, 0, ',', '.') : '0';
        
        // REALISASI RENAKSI - PERBAIKAN: Gunakan nama field yang benar dari database
        $realisasiTw1Target = htmlspecialchars($item->realisasi_renaksi_tw1_target ?? '-');
        $realisasiTw1Rp = $this->cleanRupiah($item->realisasi_renaksi_tw1_rp);
        $realisasiTw1RpFormatted = $realisasiTw1Rp ? number_format($realisasiTw1Rp, 0, ',', '.') : '0';
        
        $realisasiTw2Target = htmlspecialchars($item->realisasi_renaksi_tw2_target ?? '-');
        $realisasiTw2Rp = $this->cleanRupiah($item->realisasi_renaksi_tw2_rp);
        $realisasiTw2RpFormatted = $realisasiTw2Rp ? number_format($realisasiTw2Rp, 0, ',', '.') : '0';
        
        $realisasiTw3Target = htmlspecialchars($item->realisasi_renaksi_tw3_target ?? '-');
        $realisasiTw3Rp = $this->cleanRupiah($item->realisasi_renaksi_tw3_rp);
        $realisasiTw3RpFormatted = $realisasiTw3Rp ? number_format($realisasiTw3Rp, 0, ',', '.') : '0';
        
        $realisasiTw4Target = htmlspecialchars($item->realisasi_renaksi_tw4_target ?? '-');
        $realisasiTw4Rp = $this->cleanRupiah($item->realisasi_renaksi_tw4_rp);
        $realisasiTw4RpFormatted = $realisasiTw4Rp ? number_format($realisasiTw4Rp, 0, ',', '.') : '0';

        $html .= '
            <tr>
                <td class="text-center" style="vertical-align: middle;">' . $no++ . '</td>
                <td style="vertical-align: top;">' . nl2br(htmlspecialchars($item->permasalahan ?? '-')) . '</td>
                <td style="vertical-align: top;">' . nl2br(htmlspecialchars($item->sasaran_tematik ?? '-')) . '</td>
                <td style="vertical-align: top;">' . nl2br(htmlspecialchars($item->indikator ?? '-')) . '</td>
                <td class="text-center" style="vertical-align: middle;">' . htmlspecialchars($item->target ?? '-') . '</td>
                <td class="text-center" style="vertical-align: middle;">' . htmlspecialchars($item->satuan ?? '-') . '</td>
                <td style="vertical-align: top;">' . nl2br(htmlspecialchars($item->rencana_aksi ?? '-')) . '</td>
                <td class="text-center" style="vertical-align: middle;">' . htmlspecialchars($item->satuan_output ?? '-') . '</td>
                <td style="vertical-align: top;">' . nl2br(htmlspecialchars($item->indikator_output ?? '-')) . '</td>
                <td class="text-center" style="vertical-align: middle;">' . htmlspecialchars($item->target_tahun ?? '-') . '</td>
                <td class="text-right" style="vertical-align: middle;">' . ($anggaranTahun ? number_format($anggaranTahun, 0, ',', '.') : '0') . '</td>
                <!-- Rencana Aksi TW1 -->
                <td class="text-center">' . $tw1Target . '</td>
                <td class="text-right">' . $tw1RpFormatted . '</td>
                <!-- Rencana Aksi TW2 -->
                <td class="text-center">' . $tw2Target . '</td>
                <td class="text-right">' . $tw2RpFormatted . '</td>
                <!-- Rencana Aksi TW3 -->
                <td class="text-center">' . $tw3Target . '</td>
                <td class="text-right">' . $tw3RpFormatted . '</td>
                <!-- Rencana Aksi TW4 -->
                <td class="text-center">' . $tw4Target . '</td>
                <td class="text-right">' . $tw4RpFormatted . '</td>
                <!-- Realisasi TW1 -->
                <td class="text-center">' . $realisasiTw1Target . '</td>
                <td class="text-right">' . $realisasiTw1RpFormatted . '</td>
                <!-- Realisasi TW2 -->
                <td class="text-center">' . $realisasiTw2Target . '</td>
                <td class="text-right">' . $realisasiTw2RpFormatted . '</td>
                <!-- Realisasi TW3 -->
                <td class="text-center">' . $realisasiTw3Target . '</td>
                <td class="text-right">' . $realisasiTw3RpFormatted . '</td>
                <!-- Realisasi TW4 -->
                <td class="text-center">' . $realisasiTw4Target . '</td>
                <td class="text-right">' . $realisasiTw4RpFormatted . '</td>
                <td style="vertical-align: top;">' . nl2br(htmlspecialchars($item->rumus ?? '-')) . '</td>
                <td style="vertical-align: middle;">' . nl2br(htmlspecialchars($item->koordinator ?? '-')) . '</td>
                <td style="vertical-align: middle;">' . nl2br(htmlspecialchars($item->pelaksana ?? '-')) . '</td>
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