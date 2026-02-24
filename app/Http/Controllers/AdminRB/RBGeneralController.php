<?php

namespace App\Http\Controllers\AdminRB;

use App\Http\Controllers\Controller;
use App\Models\RB_General;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RBGeneralController extends Controller
{
    /**
     * Tampilkan daftar RB General (dari semua sumber)
     */
    public function index()
    {
        $selectedYear = request()->get('year', date('Y'));

        // Ambil SEMUA data dari database berdasarkan tahun
        $rbData = RB_General::where('tahun', $selectedYear)
            ->orderBy('unit_kerja')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group data berdasarkan unit kerja untuk tampilan yang lebih rapi
        $groupedData = $rbData->groupBy('unit_kerja');

        return view('adminrb.rb-general.index', compact('rbData', 'groupedData', 'selectedYear'));
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
     * Update data
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'sasaran_strategi' => 'required|string',
                'indikator_capaian' => 'required|string',
                'target' => 'required|numeric',
                'satuan' => 'required|string',
                'target_tahun' => 'nullable|string',
                'rencana_aksi' => 'required|string',
                'satuan_output' => 'nullable|string',
                'indikator_output' => 'nullable|string',
                'renaksi_tw1_target' => 'nullable|numeric',
                'tw1_rp' => 'nullable|numeric',
                'renaksi_tw2_target' => 'nullable|numeric',
                'tw2_rp' => 'nullable|numeric',
                'renaksi_tw3_target' => 'nullable|numeric',
                'tw3_rp' => 'nullable|numeric',
                'renaksi_tw4_target' => 'nullable|numeric',
                'tw4_rp' => 'nullable|numeric',
                'realisasi_tw1_target' => 'nullable|numeric',
                'realisasi_tw1_rp' => 'nullable|numeric',
                'realisasi_tw2_target' => 'nullable|numeric',
                'realisasi_tw2_rp' => 'nullable|numeric',
                'realisasi_tw3_target' => 'nullable|numeric',
                'realisasi_tw3_rp' => 'nullable|numeric',
                'realisasi_tw4_target' => 'nullable|numeric',
                'realisasi_tw4_rp' => 'nullable|numeric',
                'anggaran_tahun' => 'nullable|numeric',
                'rumus' => 'nullable|string',
                'catatan_evaluasi' => 'nullable|string',
                'catatan_perbaikan' => 'nullable|string',
                'unit_kerja' => 'required|string',
                'pelaksana' => 'nullable|string',
            ]);

            $rb = RB_General::findOrFail($id);
            $rb->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data RB General berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        try {
            $rb = RB_General::findOrFail($id);
            $rb->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data RB General berhasil dihapus'
            ]);

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

        // Remove 'Rp ' prefix, dots, and non-numeric characters
        $value = str_replace('Rp ', '', $value);
        $value = str_replace('.', '', $value);
        return (float) preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Export data ke Excel - Format sesuai gambar dengan warna #2F75B5 dan #DDEBF7
     */
    public function exportExcel(Request $request)
    {
        $year = $request->get('year', date('Y'));

        // Ambil data RB General berdasarkan tahun
        $rbData = RB_General::where('tahun', $year)
            ->orderBy('unit_kerja')
            ->orderBy('created_at', 'desc')
            ->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RA RB General');

        // Set column widths sesuai dengan struktur
        $sheet->getColumnDimension('A')->setWidth(5);   // NO
        $sheet->getColumnDimension('B')->setWidth(30);  // SASARAN STRATEGI
        $sheet->getColumnDimension('C')->setWidth(30);  // INDIKATOR CAPAIAN
        $sheet->getColumnDimension('D')->setWidth(10);  // TARGET
        $sheet->getColumnDimension('E')->setWidth(10);  // SATUAN
        $sheet->getColumnDimension('F')->setWidth(25);  // Rencana Aksi
        $sheet->getColumnDimension('G')->setWidth(15);  // SATUAN OUTPUT
        $sheet->getColumnDimension('H')->setWidth(20);  // INDIKATOR OUTPUT
        $sheet->getColumnDimension('I')->setWidth(15);  // Target Tahun
        $sheet->getColumnDimension('J')->setWidth(15);  // Anggaran Tahun

        // Kolom untuk Renaksi (TW1 - TW4)
        $sheet->getColumnDimension('K')->setWidth(12);  // TW1 (merged dengan L untuk label TW1)
        $sheet->getColumnDimension('L')->setWidth(10);  // Target
        $sheet->getColumnDimension('M')->setWidth(12);  // Rp
        $sheet->getColumnDimension('N')->setWidth(12);  // TW2 (merged dengan O untuk label TW2)
        $sheet->getColumnDimension('O')->setWidth(10);  // Target
        $sheet->getColumnDimension('P')->setWidth(12);  // Rp
        $sheet->getColumnDimension('Q')->setWidth(12);  // TW3 (merged dengan R untuk label TW3)
        $sheet->getColumnDimension('R')->setWidth(10);  // Target
        $sheet->getColumnDimension('S')->setWidth(12);  // Rp
        $sheet->getColumnDimension('T')->setWidth(12);  // TW4 (merged dengan U untuk label TW4)
        $sheet->getColumnDimension('U')->setWidth(10);  // Target
        $sheet->getColumnDimension('V')->setWidth(12);  // Rp

        // Kolom untuk Realisasi (TW1 - TW4)
        $sheet->getColumnDimension('W')->setWidth(12);  // TW1 (merged dengan X untuk label TW1)
        $sheet->getColumnDimension('X')->setWidth(10);  // Target
        $sheet->getColumnDimension('Y')->setWidth(12);  // Rp
        $sheet->getColumnDimension('Z')->setWidth(12);  // TW2 (merged dengan AA untuk label TW2)
        $sheet->getColumnDimension('AA')->setWidth(10); // Target
        $sheet->getColumnDimension('AB')->setWidth(12); // Rp
        $sheet->getColumnDimension('AC')->setWidth(12); // TW3 (merged dengan AD untuk label TW3)
        $sheet->getColumnDimension('AD')->setWidth(10); // Target
        $sheet->getColumnDimension('AE')->setWidth(12); // Rp
        $sheet->getColumnDimension('AF')->setWidth(12); // TW4 (merged dengan AG untuk label TW4)
        $sheet->getColumnDimension('AG')->setWidth(10); // Target
        $sheet->getColumnDimension('AH')->setWidth(12); // Rp

        $sheet->getColumnDimension('AI')->setWidth(25); // RUMUS
        $sheet->getColumnDimension('AJ')->setWidth(20); // CATATAN EVALUASI
        $sheet->getColumnDimension('AK')->setWidth(20); // CATATAN PERBAIKAN
        $sheet->getColumnDimension('AL')->setWidth(25); // UNIT KERJA/SATUAN KERJA PELAKSANAAN
        $sheet->getColumnDimension('AM')->setWidth(20); // KOORDINATOR
        $sheet->getColumnDimension('AN')->setWidth(20); // PELAKSANA

        // Style untuk header dengan warna #2F75B5
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => 'FFFFFF'], // Teks putih
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2F75B5'], // Biru tua
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Style untuk data dengan warna background #DDEBF7
        $dataStyle = [
            'font' => [
                'size' => 9,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DDEBF7'], // Biru muda
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

        // Baris 1: Judul Utama
        $sheet->mergeCells('A1:AN1');
        $sheet->setCellValue('A1', 'RENCANA AKSI RB GENERAL TAHUN ' . $year);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Baris 2: Kosong
        $sheet->getRowDimension(2)->setRowHeight(10);

        // ==================== HEADER BARIS 3 ====================
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

        // Renaksi Tahun (header utama)
        $sheet->mergeCells('K3:V3');
        $sheet->setCellValue('K3', 'Renaksi Tahun ' . $year);

        // Realisasi Renaksi Tahun (header utama)
        $sheet->mergeCells('W3:AH3');
        $sheet->setCellValue('W3', 'Realisasi Renaksi Tahun ' . $year);

        $sheet->setCellValue('AI3', 'RUMUS');
        $sheet->setCellValue('AJ3', 'CATATAN EVALUASI');
        $sheet->setCellValue('AK3', 'CATATAN PERBAIKAN');
        $sheet->mergeCells('AL3:AN3');
        $sheet->setCellValue('AL3', 'UNIT KERJA / SATUAN KERJA PELAKSANAAN');

        // ==================== HEADER BARIS 4 ====================
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

        // Renaksi TW1 - TW4 (label di baris 4)
        $sheet->mergeCells('K4:L4');
        $sheet->setCellValue('K4', 'TW1');
        $sheet->mergeCells('M4:O4');
        $sheet->setCellValue('M4', 'TW2');
        $sheet->mergeCells('P4:R4');
        $sheet->setCellValue('P4', 'TW3');
        $sheet->mergeCells('S4:U4');
        $sheet->setCellValue('S4', 'TW4');

        // Realisasi TW1 - TW4 (label di baris 4)
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

        // ==================== HEADER BARIS 5 ====================
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

        // Merge cells yang diperlukan
        $sheet->mergeCells('A3:A5');      // NO
        $sheet->mergeCells('B3:B5');      // SASARAN STRATEGI
        $sheet->mergeCells('C3:C5');      // INDIKATOR CAPAIAN
        $sheet->mergeCells('D3:D5');      // TARGET
        $sheet->mergeCells('E3:E5');      // SATUAN
        $sheet->mergeCells('F3:F5');      // Rencana Aksi
        $sheet->mergeCells('G3:H3');      // OUT PUT (baris 3)
        $sheet->mergeCells('G4:G5');      // SATUAN
        $sheet->mergeCells('H4:H5');      // INDIKATOR
        $sheet->mergeCells('I3:I5');      // Target Tahun
        $sheet->mergeCells('J3:J5');      // Anggaran Tahun
        $sheet->mergeCells('K3:V3');      // Renaksi Tahun (baris 3)
        $sheet->mergeCells('W3:AH3');     // Realisasi Renaksi Tahun (baris 3)
        $sheet->mergeCells('AI3:AI5');    // RUMUS
        $sheet->mergeCells('AJ3:AJ5');    // CATATAN EVALUASI
        $sheet->mergeCells('AK3:AK5');    // CATATAN PERBAIKAN
        $sheet->mergeCells('AL3:AN3');    // UNIT KERJA / SATUAN KERJA PELAKSANAAN (baris 3)
        $sheet->mergeCells('AL4:AL5');    // KOORDINATOR
        $sheet->mergeCells('AM4:AM5');    // PELAKSANA
        $sheet->mergeCells('AN4:AN5');    // Kosong di bawah merge AL3:AN3

        // Apply style ke header (A3:AN5) dengan warna #2F75B5
        $sheet->getStyle('A3:AN5')->applyFromArray($headerStyle);

        // Data mulai dari baris 6
        $row = 6;
        $no = 1;
        $currentSasaran = '';

        foreach ($rbData as $item) {
            // Cek apakah sasaran strategi baru
            $isNewSasaran = ($currentSasaran != $item->sasaran_strategi);
            if ($isNewSasaran) {
                $currentSasaran = $item->sasaran_strategi;
            }

            // Bersihkan nilai anggaran
            $anggaranTahun = $this->cleanRupiah($item->anggaran_tahun);
            $tw1Rp = $this->cleanRupiah($item->tw1_rp);
            $tw2Rp = $this->cleanRupiah($item->tw2_rp);
            $tw3Rp = $this->cleanRupiah($item->tw3_rp);
            $tw4Rp = $this->cleanRupiah($item->tw4_rp);
            $realisasiTw1Rp = $this->cleanRupiah($item->realisasi_tw1_rp);
            $realisasiTw2Rp = $this->cleanRupiah($item->realisasi_tw2_rp);
            $realisasiTw3Rp = $this->cleanRupiah($item->realisasi_tw3_rp);
            $realisasiTw4Rp = $this->cleanRupiah($item->realisasi_tw4_rp);

            // Set nilai ke sheet
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

            // Renaksi TW1
            $sheet->setCellValue('K' . $row, $item->renaksi_tw1_target);
            $sheet->setCellValue('L' . $row, $tw1Rp ? number_format($tw1Rp, 0, ',', '.') : '');

            // Renaksi TW2
            $sheet->setCellValue('M' . $row, $item->renaksi_tw2_target);
            $sheet->setCellValue('N' . $row, $tw2Rp ? number_format($tw2Rp, 0, ',', '.') : '');

            // Renaksi TW3
            $sheet->setCellValue('O' . $row, $item->renaksi_tw3_target);
            $sheet->setCellValue('P' . $row, $tw3Rp ? number_format($tw3Rp, 0, ',', '.') : '');

            // Renaksi TW4
            $sheet->setCellValue('Q' . $row, $item->renaksi_tw4_target);
            $sheet->setCellValue('R' . $row, $tw4Rp ? number_format($tw4Rp, 0, ',', '.') : '');

            // Realisasi TW1
            $sheet->setCellValue('S' . $row, $item->realisasi_tw1_target);
            $sheet->setCellValue('T' . $row, $realisasiTw1Rp ? number_format($realisasiTw1Rp, 0, ',', '.') : '');

            // Realisasi TW2
            $sheet->setCellValue('U' . $row, $item->realisasi_tw2_target);
            $sheet->setCellValue('V' . $row, $realisasiTw2Rp ? number_format($realisasiTw2Rp, 0, ',', '.') : '');

            // Realisasi TW3
            $sheet->setCellValue('W' . $row, $item->realisasi_tw3_target);
            $sheet->setCellValue('X' . $row, $realisasiTw3Rp ? number_format($realisasiTw3Rp, 0, ',', '.') : '');

            // Realisasi TW4
            $sheet->setCellValue('Y' . $row, $item->realisasi_tw4_target);
            $sheet->setCellValue('Z' . $row, $realisasiTw4Rp ? number_format($realisasiTw4Rp, 0, ',', '.') : '');

            $sheet->setCellValue('AA' . $row, $item->rumus);
            $sheet->setCellValue('AB' . $row, $item->catatan_evaluasi);
            $sheet->setCellValue('AC' . $row, $item->catatan_perbaikan);
            $sheet->setCellValue('AD' . $row, $item->unit_kerja);
            $sheet->setCellValue('AE' . $row, $item->pelaksana);

            $row++;
        }

        // Apply style ke data (A6:AE6) dengan warna background #DDEBF7
        if ($row > 6) {
            $sheet->getStyle('A6:AE' . ($row - 1))->applyFromArray($dataStyle);
        }

        // Buat file Excel
        $writer = new Xlsx($spreadsheet);

        // Set headers untuk download
        $filename = 'RA_RB_GENERAL_' . $year . '.xlsx';

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

        // Ukuran F4 landscape
        $pdf->setPaper([0, 0, 936, 612], 'landscape');

        return $pdf->download('RA_RB_GENERAL_' . $year . '.pdf');
    }

    /**
 * Generate HTML untuk PDF dengan struktur yang benar - TANPA KOLOM KOSONG
 */
private function generatePdfHtml($rbData, $year)
{
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>RA RB General ' . $year . '</title>
        <style>
            @page {
                size: A4 landscape;
                margin: 0.3cm;
            }
            body { 
                font-family: Arial, sans-serif; 
                font-size: 6.5px; 
                margin: 0;
                padding: 0;
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
                font-size: 14px; 
                font-weight: bold; 
                text-align: center; 
                margin-bottom: 5px; 
            }
        </style>
    </head>
    <body>
        <div class="title">RENCANA AKSI RB GENERAL TAHUN ' . $year . '</div>
        
        <table>';

    // HEADER - BARIS 1
    $html .= '
            <thead>
                <tr>
                    <th rowspan="3" width="2%">NO</th>
                    <th rowspan="3" width="8%">SASARAN STRATEGI</th>
                    <th rowspan="3" width="12%">INDIKATOR CAPAIAN SASARAN STRATEGI DAN IMPLEMENTASI KEBIJAKAN PERCEPATAN</th>
                    <th rowspan="3" width="3%">TARGET</th>
                    <th rowspan="3" width="3%">SATUAN</th>
                    <th rowspan="3" width="7%">Rencana Aksi</th>
                    <th colspan="2" width="6%">OUT PUT</th>
                    <th rowspan="3" width="4%">Target Tahun ' . $year . '</th>
                    <th rowspan="3" width="4%">Anggaran Tahun ' . $year . '</th>
                    <th colspan="8" width="14%">Renaksi Tahun ' . $year . '</th>
                    <th colspan="8" width="14%">Realisasi Renaksi Tahun ' . $year . '</th>
                    <th rowspan="3" width="4%">RUMUS</th>
                    <th rowspan="3" width="4%">CATATAN EVALUASI</th>
                    <th rowspan="3" width="4%">CATATAN PERBAIKAN</th>
                    <th colspan="2" width="8%">UNIT KERJA / SATUAN KERJA PELAKSANAAN</th>
                </tr>';

    // HEADER - BARIS 2
    $html .= '
                <tr>
                    <th rowspan="2" width="3%">SATUAN</th>
                    <th rowspan="2" width="3%">INDIKATOR</th>
                    
                    <!-- Renaksi TW1 - TW4 (masing-masing 2 kolom: Target dan Rp) -->
                    <th colspan="2" width="3.5%">TW1</th>
                    <th colspan="2" width="3.5%">TW2</th>
                    <th colspan="2" width="3.5%">TW3</th>
                    <th colspan="2" width="3.5%">TW4</th>
                    
                    <!-- Realisasi TW1 - TW4 (masing-masing 2 kolom: Target dan Rp) -->
                    <th colspan="2" width="3.5%">TW1</th>
                    <th colspan="2" width="3.5%">TW2</th>
                    <th colspan="2" width="3.5%">TW3</th>
                    <th colspan="2" width="3.5%">TW4</th>
                    
                    <th rowspan="2" width="4%">UNIT KERJA</th>
                    <th rowspan="2" width="4%">PELAKSANA</th>
                </tr>';

    // HEADER - BARIS 3
    $html .= '
                <tr>
                    <!-- Renaksi Target dan Rp (8 kolom) -->
                    <th width="1.75%">Target</th>
                    <th width="1.75%">Rp</th>
                    <th width="1.75%">Target</th>
                    <th width="1.75%">Rp</th>
                    <th width="1.75%">Target</th>
                    <th width="1.75%">Rp</th>
                    <th width="1.75%">Target</th>
                    <th width="1.75%">Rp</th>
                    
                    <!-- Realisasi Target dan Rp (8 kolom) -->
                    <th width="1.75%">Target</th>
                    <th width="1.75%">Rp</th>
                    <th width="1.75%">Target</th>
                    <th width="1.75%">Rp</th>
                    <th width="1.75%">Target</th>
                    <th width="1.75%">Rp</th>
                    <th width="1.75%">Target</th>
                    <th width="1.75%">Rp</th>
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
        
        // Bersihkan nilai
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
                    
                    <!-- Renaksi TW1 -->
                    <td class="text-center">' . htmlspecialchars($item->renaksi_tw1_target) . '</td>
                    <td class="text-right">' . ($tw1Rp ? number_format($tw1Rp, 0, ',', '.') : '-') . '</td>
                    
                    <!-- Renaksi TW2 -->
                    <td class="text-center">' . htmlspecialchars($item->renaksi_tw2_target) . '</td>
                    <td class="text-right">' . ($tw2Rp ? number_format($tw2Rp, 0, ',', '.') : '-') . '</td>
                    
                    <!-- Renaksi TW3 -->
                    <td class="text-center">' . htmlspecialchars($item->renaksi_tw3_target) . '</td>
                    <td class="text-right">' . ($tw3Rp ? number_format($tw3Rp, 0, ',', '.') : '-') . '</td>
                    
                    <!-- Renaksi TW4 -->
                    <td class="text-center">' . htmlspecialchars($item->renaksi_tw4_target) . '</td>
                    <td class="text-right">' . ($tw4Rp ? number_format($tw4Rp, 0, ',', '.') : '-') . '</td>
                    
                    <!-- Realisasi TW1 -->
                    <td class="text-center">' . htmlspecialchars($item->realisasi_tw1_target) . '</td>
                    <td class="text-right">' . ($realisasiTw1Rp ? number_format($realisasiTw1Rp, 0, ',', '.') : '-') . '</td>
                    
                    <!-- Realisasi TW2 -->
                    <td class="text-center">' . htmlspecialchars($item->realisasi_tw2_target) . '</td>
                    <td class="text-right">' . ($realisasiTw2Rp ? number_format($realisasiTw2Rp, 0, ',', '.') : '-') . '</td>
                    
                    <!-- Realisasi TW3 -->
                    <td class="text-center">' . htmlspecialchars($item->realisasi_tw3_target) . '</td>
                    <td class="text-right">' . ($realisasiTw3Rp ? number_format($realisasiTw3Rp, 0, ',', '.') : '-') . '</td>
                    
                    <!-- Realisasi TW4 -->
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