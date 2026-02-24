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
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(40);  // Permasalahan
        $sheet->getColumnDimension('C')->setWidth(30);  // Sasaran Tematik
        $sheet->getColumnDimension('D')->setWidth(25);  // Indikator
        $sheet->getColumnDimension('E')->setWidth(10);  // Target
        $sheet->getColumnDimension('F')->setWidth(10);  // Satuan
        $sheet->getColumnDimension('G')->setWidth(30);  // Rencana Aksi
        $sheet->getColumnDimension('H')->setWidth(15);  // Satuan Output
        $sheet->getColumnDimension('I')->setWidth(20);  // Indikator Output
        $sheet->getColumnDimension('J')->setWidth(15);  // Anggaran
        $sheet->getColumnDimension('K')->setWidth(10);  // TW1 Target
        $sheet->getColumnDimension('L')->setWidth(15);  // TW1 Rp
        $sheet->getColumnDimension('M')->setWidth(10);  // TW2 Target
        $sheet->getColumnDimension('N')->setWidth(15);  // TW2 Rp
        $sheet->getColumnDimension('O')->setWidth(10);  // TW3 Target
        $sheet->getColumnDimension('P')->setWidth(15);  // TW3 Rp
        $sheet->getColumnDimension('Q')->setWidth(10);  // TW4 Target
        $sheet->getColumnDimension('R')->setWidth(15);  // TW4 Rp
        $sheet->getColumnDimension('S')->setWidth(20);  // Koordinator
        $sheet->getColumnDimension('T')->setWidth(20);  // Pelaksana

        // Header style
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6E6E6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        // Title
        $sheet->mergeCells('A1:T1');
        $sheet->setCellValue('A1', 'RENCANA AKSI RB TEMATIK TAHUN ' . $year);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Headers
        $headers = [
            'A2' => 'No',
            'B2' => 'Permasalahan',
            'C2' => 'Sasaran Tematik',
            'D2' => 'Indikator',
            'E2' => 'Target',
            'F2' => 'Satuan',
            'G2' => 'Rencana Aksi',
            'H2' => 'Satuan Output',
            'I2' => 'Indikator Output',
            'J2' => 'Anggaran',
            'K2' => 'TW1 Target',
            'L2' => 'TW1 Rp',
            'M2' => 'TW2 Target',
            'N2' => 'TW2 Rp',
            'O2' => 'TW3 Target',
            'P2' => 'TW3 Rp',
            'Q2' => 'TW4 Target',
            'R2' => 'TW4 Rp',
            'S2' => 'Koordinator',
            'T2' => 'Pelaksana',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        $sheet->getStyle('A2:T2')->applyFromArray($headerStyle);

        // Data
        $row = 3;
        foreach ($rbTematik as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->permasalahan);
            $sheet->setCellValue('C' . $row, $item->sasaran_tematik);
            $sheet->setCellValue('D' . $row, $item->indikator);
            $sheet->setCellValue('E' . $row, $item->target);
            $sheet->setCellValue('F' . $row, $item->satuan);
            $sheet->setCellValue('G' . $row, $item->rencana_aksi);
            $sheet->setCellValue('H' . $row, $item->satuan_output);
            $sheet->setCellValue('I' . $row, $item->indikator_output);
            $sheet->setCellValue('J' . $row, $item->anggaran_tahun ? 'Rp ' . $this->formatRupiah($item->anggaran_tahun) : 'Rp 0');
            $sheet->setCellValue('K' . $row, $item->renaksi_tw1_target);
            $sheet->setCellValue('L' . $row, $item->renaksi_tw1_rp ? 'Rp ' . $this->formatRupiah($item->renaksi_tw1_rp) : 'Rp 0');
            $sheet->setCellValue('M' . $row, $item->renaksi_tw2_target);
            $sheet->setCellValue('N' . $row, $item->renaksi_tw2_rp ? 'Rp ' . $this->formatRupiah($item->renaksi_tw2_rp) : 'Rp 0');
            $sheet->setCellValue('O' . $row, $item->renaksi_tw3_target);
            $sheet->setCellValue('P' . $row, $item->renaksi_tw3_rp ? 'Rp ' . $this->formatRupiah($item->renaksi_tw3_rp) : 'Rp 0');
            $sheet->setCellValue('Q' . $row, $item->renaksi_tw4_target);
            $sheet->setCellValue('R' . $row, $item->renaksi_tw4_rp ? 'Rp ' . $this->formatRupiah($item->renaksi_tw4_rp) : 'Rp 0');
            $sheet->setCellValue('S' . $row, $item->koordinator);
            $sheet->setCellValue('T' . $row, $item->pelaksana);
            $row++;
        }

        // Style for data
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
        ];
        
        if ($row > 3) {
            $sheet->getStyle('A3:T' . ($row - 1))->applyFromArray($dataStyle);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'RB_TEMATIK_' . $year . '.xlsx';
        
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
        $rbTematik = RB_Tematik::where('tahun', $year)->get();
        
        $html = view('exports.rb-tematik-pdf', compact('rbTematik', 'year'))->render();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('RB_TEMATIK_' . $year . '.pdf');
    }
}