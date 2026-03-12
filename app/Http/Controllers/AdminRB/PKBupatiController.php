<?php

namespace App\Http\Controllers\AdminRB;

use App\Http\Controllers\Controller;
use App\Models\PK_Bupati;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PKBupatiController extends Controller
{
    /**
     * Display a listing of the resource for admin.
     */
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $selectedYear = $request->get('year', $currentYear);
        $selectedSemester = $request->get('semester', '1');

        // Konversi semester dari form (1/2) ke format database (I/II)
        $semesterDb = $selectedSemester == '1' ? 'I' : 'II';

        // Ambil data dari database berdasarkan filter
        $pkData = PK_Bupati::where('tahun', $selectedYear)
            ->where('semester', $semesterDb)
            ->orderBy('no', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Generate tahun untuk filter
        $startYear = 2024;
        $years = range($startYear, $currentYear + 1);
        rsort($years);

        // Data untuk dropdown indikator
        $indikatorData = [
            1 => ["Nilai Investasi"],
            2 => ["Nilai PDRB Sektor Pertanian, Kehutanan Dan Perikanan (Dalam Miliar Rp)"],
            3 => ["Persentase PAD Terhadap Pendapatan Daerah"],
            4 => ["Rasio KK yang Terlayani Insfrastruktur Dasar"],
            5 => ["Rasio Panjang Dalam Kondisi Baik", "Rasio Konektivitas Angkutan Laut", "Rasio Konektivitas Angkutan Darat"],
            6 => ["Angka Harapan Hidup"],
            7 => ["Angka Harapan Lama Sekolah", "Rata - Rata Lama Sekolah"],
            8 => ["Indeks Pembangunan Gender"],
            9 => ["Laju Pertumbuhan Penduduk"],
            10 => ["Indeks Pembangunan Pemuda"],
            11 => ["Rasio SDM Kebudayaan Berprestasi"],
            12 => ["Persentase PPKS Mandiri"],
            13 => ["Tingkat Pengangguran Terbuka"],
            14 => ["Luas Ruang Terbuka Hijau", "Indeks Kinerja Pengelolaan sampah"],
            15 => ["Indeks Kualitas Air", "Indeks Kualitas Udara", "Indeks Kualitas Lahan"],
            16 => ["Nilai LPPD", "Indeks Reformasi Birokrasi", "Nilai Manajemen Risiko Indeks"],
            17 => ["Indeks Pelayanan Publik"]
        ];

        // Data untuk dropdown sasaran strategis
        $sasaranOptions = [
            1 => "1. Meningkatnya Investasi Daerah",
            2 => "2. Berkembangnya Sektor Ekonomi Dominan",
            3 => "3. Meningkatnya Pendapatan Asli Daerah",
            4 => "4. Meningkatnya Akses Kebutuhan Insfrastruktur Dasar Masyarakat Yang Merata",
            5 => "5. Terwujudnya Prasarana Penghubung yang Optimal",
            6 => "6. Meningkatnya Derajat Kesehatan Masyarakat",
            7 => "7. Meningkatnya Derajat Pendidikan Masyarakat",
            8 => "8. Terwujudnya Kesetaraan Gender",
            9 => "9. Terwujudnya Pengendalian Penduduk",
            10 => "10. Meningkatnya Peran Pemuda Dalam Pembangunan",
            11 => "11. Meningkatnya Peran Serta Masyarakat Dalam Pelestarian Nilai Budaya Daerah",
            12 => "12. Meningkatnya Kesejahteraan Sosial",
            13 => "13. Mendorong Perluasan Dan Kesempatan Kerja Bagi Tenaga Kerja di Daerah",
            14 => "14. Meningkatnya Pengelolaan dan Kelestarian Lingkungan Hidup",
            15 => "15. Meningkatnya Kualitas Udara, Tanah dan Air",
            16 => "16. Terwujudnya Birokrasi Yang Profesional, Bersih dan Akuntabel",
            17 => "17. Meningkatnya Kualitas Pelayanan Publik"
        ];

        // Data untuk dropdown penanggung jawab
        $penanggungJawabOptions = [
            "Dinas Penanaman Modal dan Pelayanan Satu Pintu",
            "Dinas Pangan dan Pertanian",
            "Dinas Perikanan",
            "Badan Pendapatan Daerah",
            "Dinas Pekerjaan Umum dan Penataan Ruang",
            "Dinas Perhubungan",
            "Dinas Kesehatan",
            "Dinas Pendidikan dan Kebudayaan",
            "Dinas Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan dan Perlindungan Anak",
            "Dinas Kepemudaan dan Olahraga",
            "Dinas Sosial",
            "Dinas Tenaga Kerja dan Periindustrian",
            "Dinas Lingkungan Hidup",
            "Bagian Tata Pembangunan",
            "Bagian Organisasi",
            "Baperlitbang Kabupaten Karimun",
            "Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu",
            "Dinas Kependudukan dan Pencatatan Sipil",
            "RSUD M.SANI"
        ];

        return view('adminrb.pk-bupati.index', compact(
            'pkData',
            'currentYear',
            'selectedYear',
            'selectedSemester',
            'years',
            'indikatorData',
            'sasaranOptions',
            'penanggungJawabOptions'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = PK_Bupati::findOrFail($id);

            // Data untuk dropdown sasaran strategis
            $sasaranOptions = [
                1 => "1. Meningkatnya Investasi Daerah",
                2 => "2. Berkembangnya Sektor Ekonomi Dominan",
                3 => "3. Meningkatnya Pendapatan Asli Daerah",
                4 => "4. Meningkatnya Akses Kebutuhan Insfrastruktur Dasar Masyarakat Yang Merata",
                5 => "5. Terwujudnya Prasarana Penghubung yang Optimal",
                6 => "6. Meningkatnya Derajat Kesehatan Masyarakat",
                7 => "7. Meningkatnya Derajat Pendidikan Masyarakat",
                8 => "8. Terwujudnya Kesetaraan Gender",
                9 => "9. Terwujudnya Pengendalian Penduduk",
                10 => "10. Meningkatnya Peran Pemuda Dalam Pembangunan",
                11 => "11. Meningkatnya Peran Serta Masyarakat Dalam Pelestarian Nilai Budaya Daerah",
                12 => "12. Meningkatnya Kesejahteraan Sosial",
                13 => "13. Mendorong Perluasan Dan Kesempatan Kerja Bagi Tenaga Kerja di Daerah",
                14 => "14. Meningkatnya Pengelolaan dan Kelestarian Lingkungan Hidup",
                15 => "15. Meningkatnya Kualitas Udara, Tanah dan Air",
                16 => "16. Terwujudnya Birokrasi Yang Profesional, Bersih dan Akuntabel",
                17 => "17. Meningkatnya Kualitas Pelayanan Publik"
            ];

            // Cari kunci sasaran strategis berdasarkan teks lengkap
            $sasaranKey = null;
            foreach ($sasaranOptions as $key => $value) {
                if ($value === $data->sasaran_strategis) {
                    $sasaranKey = $key;
                    break;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $data->id,
                    'no' => $data->no,
                    'tahun' => $data->tahun,
                    'semester' => $data->semester,
                    'sasaranStrategis' => $data->sasaran_strategis, // Kirim teks lengkap untuk detail
                    'sasaranKey' => $sasaranKey, // Kirim key untuk edit (angka)
                    'indikatorKinerja' => $data->indikator_kinerja, // Kirim teks lengkap
                    'target2025' => $data->target_2025,
                    'satuan' => $data->satuan,
                    'targetTW1' => $data->target_tw1,
                    'realisasiTW1' => $data->realisasi_tw1,
                    'targetTW2' => $data->target_tw2,
                    'realisasiTW2' => $data->realisasi_tw2,
                    'targetTW3' => $data->target_tw3,
                    'realisasiTW3' => $data->realisasi_tw3,
                    'targetTW4' => $data->target_tw4,
                    'realisasiTW4' => $data->realisasi_tw4,
                    'paguAnggaranTW1' => $data->pagu_anggaran_tw1,
                    'realisasiAnggaranTW1' => $data->realisasi_anggaran_tw1,
                    'paguAnggaranTW2' => $data->pagu_anggaran_tw2,
                    'realisasiAnggaranTW2' => $data->realisasi_anggaran_tw2,
                    'paguAnggaranTW3' => $data->pagu_anggaran_tw3,
                    'realisasiAnggaranTW3' => $data->realisasi_anggaran_tw3,
                    'paguAnggaranTW4' => $data->pagu_anggaran_tw4,
                    'realisasiAnggaranTW4' => $data->realisasi_anggaran_tw4,
                    'program' => $data->program,
                    'analisisEvaluasi' => $data->penjelasan_analisis,
                    'penanggungJawab' => $data->penanggung_jawab,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error show PK Bupati (Admin): ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get data for editing.
     */
    public function edit($id)
    {
        return $this->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'no' => 'required|integer|min:1|max:999',
            'sasaranStrategis' => 'required|string|max:500',
            'indikatorKinerja' => 'required|string|max:500',
            'target2025' => 'required|string',
            'satuan' => 'required|string|max:100',

            // Triwulan 1
            'targetTW1' => 'nullable|string|max:255',
            'realisasiTW1' => 'nullable|string|max:255',
            'paguAnggaranTW1' => 'nullable|string|max:255',
            'realisasiAnggaranTW1' => 'nullable|string|max:255',

            // Triwulan 2
            'targetTW2' => 'nullable|string|max:255',
            'realisasiTW2' => 'nullable|string|max:255',
            'paguAnggaranTW2' => 'nullable|string|max:255',
            'realisasiAnggaranTW2' => 'nullable|string|max:255',

            // Triwulan 3
            'targetTW3' => 'nullable|string|max:255',
            'realisasiTW3' => 'nullable|string|max:255',
            'paguAnggaranTW3' => 'nullable|string|max:255',
            'realisasiAnggaranTW3' => 'nullable|string|max:255',

            // Triwulan 4
            'targetTW4' => 'nullable|string|max:255',
            'realisasiTW4' => 'nullable|string|max:255',
            'paguAnggaranTW4' => 'nullable|string|max:255',
            'realisasiAnggaranTW4' => 'nullable|string|max:255',

            // Program dan Analisis
            'program' => 'nullable|string|max:1000',
            'analisisEvaluasi' => 'nullable|string|max:2000',
            'penanggungJawab' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $pkBupati = PK_Bupati::findOrFail($id);

            // Cek duplikasi no kecuali untuk data yang sama
            $exists = PK_Bupati::where('no', $request->no)
                ->where('tahun', $pkBupati->tahun)
                ->where('semester', $pkBupati->semester)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor urut sudah digunakan untuk tahun dan semester ini'
                ], 422);
            }

            // Data untuk dropdown sasaran strategis
            $sasaranOptions = [
                1 => "1. Meningkatnya Investasi Daerah",
                2 => "2. Berkembangnya Sektor Ekonomi Dominan",
                3 => "3. Meningkatnya Pendapatan Asli Daerah",
                4 => "4. Meningkatnya Akses Kebutuhan Insfrastruktur Dasar Masyarakat Yang Merata",
                5 => "5. Terwujudnya Prasarana Penghubung yang Optimal",
                6 => "6. Meningkatnya Derajat Kesehatan Masyarakat",
                7 => "7. Meningkatnya Derajat Pendidikan Masyarakat",
                8 => "8. Terwujudnya Kesetaraan Gender",
                9 => "9. Terwujudnya Pengendalian Penduduk",
                10 => "10. Meningkatnya Peran Pemuda Dalam Pembangunan",
                11 => "11. Meningkatnya Peran Serta Masyarakat Dalam Pelestarian Nilai Budaya Daerah",
                12 => "12. Meningkatnya Kesejahteraan Sosial",
                13 => "13. Mendorong Perluasan Dan Kesempatan Kerja Bagi Tenaga Kerja di Daerah",
                14 => "14. Meningkatnya Pengelolaan dan Kelestarian Lingkungan Hidup",
                15 => "15. Meningkatnya Kualitas Udara, Tanah dan Air",
                16 => "16. Terwujudnya Birokrasi Yang Profesional, Bersih dan Akuntabel",
                17 => "17. Meningkatnya Kualitas Pelayanan Publik"
            ];

            // Dapatkan teks lengkap sasaran strategis berdasarkan key
            $sasaranText = $sasaranOptions[$request->sasaranStrategis] ?? $request->sasaranStrategis;

            $data = [
                'no' => $request->no,
                'sasaran_strategis' => $sasaranText, // Simpan teks lengkap
                'indikator_kinerja' => $request->indikatorKinerja,
                'target_2025' => $request->target2025,
                'satuan' => $request->satuan,
                'target_tw1' => $request->targetTW1,
                'realisasi_tw1' => $request->realisasiTW1,
                'target_tw2' => $request->targetTW2,
                'realisasi_tw2' => $request->realisasiTW2,
                'target_tw3' => $request->targetTW3,
                'realisasi_tw3' => $request->realisasiTW3,
                'target_tw4' => $request->targetTW4,
                'realisasi_tw4' => $request->realisasiTW4,
                'pagu_anggaran_tw1' => $request->paguAnggaranTW1,
                'realisasi_anggaran_tw1' => $request->realisasiAnggaranTW1,
                'pagu_anggaran_tw2' => $request->paguAnggaranTW2,
                'realisasi_anggaran_tw2' => $request->realisasiAnggaranTW2,
                'pagu_anggaran_tw3' => $request->paguAnggaranTW3,
                'realisasi_anggaran_tw3' => $request->realisasiAnggaranTW3,
                'pagu_anggaran_tw4' => $request->paguAnggaranTW4,
                'realisasi_anggaran_tw4' => $request->realisasiAnggaranTW4,
                'program' => $request->program,
                'penjelasan_analisis' => $request->analisisEvaluasi,
                'penanggung_jawab' => $request->penanggungJawab,
            ];

            $pkBupati->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $pkBupati
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update PK Bupati (Admin): ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pkBupati = PK_Bupati::findOrFail($id);
            $pkBupati->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete PK Bupati (Admin): ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data ke Excel
     */
    public function exportExcel(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $semester = $request->get('semester', '1');

        // Konversi semester dari form (1/2) ke format database (I/II)
        $semesterDb = $semester == '1' ? 'I' : 'II';

        // Ambil data PK Bupati berdasarkan tahun dan semester
        $pkData = PK_Bupati::where('tahun', $year)
            ->where('semester', $semesterDb)
            ->orderBy('no', 'asc')
            ->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('PK Bupati');

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(10);
        $sheet->getColumnDimension('M')->setWidth(10);
        $sheet->getColumnDimension('N')->setWidth(10);
        $sheet->getColumnDimension('O')->setWidth(12);
        $sheet->getColumnDimension('P')->setWidth(12);
        $sheet->getColumnDimension('Q')->setWidth(12);
        $sheet->getColumnDimension('R')->setWidth(12);
        $sheet->getColumnDimension('S')->setWidth(12);
        $sheet->getColumnDimension('T')->setWidth(12);
        $sheet->getColumnDimension('U')->setWidth(12);
        $sheet->getColumnDimension('V')->setWidth(12);
        $sheet->getColumnDimension('W')->setWidth(30);
        $sheet->getColumnDimension('X')->setWidth(25);

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

        // Style untuk angka
        $numberStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        // Baris 1: Judul Utama
        $sheet->mergeCells('A1:X1');
        $semesterText = $semester == '1' ? 'I' : 'II';
        $sheet->setCellValue('A1', 'PERJANJIAN KINERJA BUPATI - SEMESTER ' . $semesterText . ' TAHUN ' . $year);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Baris 2: Kosong
        $sheet->getRowDimension(2)->setRowHeight(10);

        // HEADER BARIS 3
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'SASARAN STRATEGIS');
        $sheet->setCellValue('C3', 'INDIKATOR KINERJA');
        $sheet->setCellValue('D3', 'TARGET 2025');
        $sheet->setCellValue('E3', 'SATUAN');
        $sheet->setCellValue('F3', 'PROGRAM / KEGIATAN');

        // Target dan Realisasi
        $sheet->mergeCells('G3:N3');
        $sheet->setCellValue('G3', 'TARGET DAN REALISASI KINERJA');

        // Pagu dan Realisasi Anggaran
        $sheet->mergeCells('O3:V3');
        $sheet->setCellValue('O3', 'PAGU DAN REALISASI ANGGARAN');

        $sheet->setCellValue('W3', 'ANALISIS / EVALUASI');
        $sheet->setCellValue('X3', 'PENANGGUNG JAWAB');

        // HEADER BARIS 4
        $sheet->setCellValue('A4', '');
        $sheet->setCellValue('B4', '');
        $sheet->setCellValue('C4', '');
        $sheet->setCellValue('D4', '');
        $sheet->setCellValue('E4', '');
        $sheet->setCellValue('F4', '');

        // Target dan Realisasi - Label Triwulan
        $sheet->mergeCells('G4:H4');
        $sheet->setCellValue('G4', 'TW I');
        $sheet->mergeCells('I4:J4');
        $sheet->setCellValue('I4', 'TW II');
        $sheet->mergeCells('K4:L4');
        $sheet->setCellValue('K4', 'TW III');
        $sheet->mergeCells('M4:N4');
        $sheet->setCellValue('M4', 'TW IV');

        // Pagu dan Realisasi Anggaran - Label Triwulan
        $sheet->mergeCells('O4:P4');
        $sheet->setCellValue('O4', 'TW I');
        $sheet->mergeCells('Q4:R4');
        $sheet->setCellValue('Q4', 'TW II');
        $sheet->mergeCells('S4:T4');
        $sheet->setCellValue('S4', 'TW III');
        $sheet->mergeCells('U4:V4');
        $sheet->setCellValue('U4', 'TW IV');

        $sheet->setCellValue('W4', '');
        $sheet->setCellValue('X4', '');

        // HEADER BARIS 5
        $sheet->setCellValue('G5', 'Target');
        $sheet->setCellValue('H5', 'Realisasi');
        $sheet->setCellValue('I5', 'Target');
        $sheet->setCellValue('J5', 'Realisasi');
        $sheet->setCellValue('K5', 'Target');
        $sheet->setCellValue('L5', 'Realisasi');
        $sheet->setCellValue('M5', 'Target');
        $sheet->setCellValue('N5', 'Realisasi');

        $sheet->setCellValue('O5', 'Pagu');
        $sheet->setCellValue('P5', 'Realisasi');
        $sheet->setCellValue('Q5', 'Pagu');
        $sheet->setCellValue('R5', 'Realisasi');
        $sheet->setCellValue('S5', 'Pagu');
        $sheet->setCellValue('T5', 'Realisasi');
        $sheet->setCellValue('U5', 'Pagu');
        $sheet->setCellValue('V5', 'Realisasi');

        // Merge cells yang diperlukan
        $sheet->mergeCells('A3:A5');
        $sheet->mergeCells('B3:B5');
        $sheet->mergeCells('C3:C5');
        $sheet->mergeCells('D3:D5');
        $sheet->mergeCells('E3:E5');
        $sheet->mergeCells('F3:F5');
        $sheet->mergeCells('G3:N3');
        $sheet->mergeCells('O3:V3');
        $sheet->mergeCells('W3:W5');
        $sheet->mergeCells('X3:X5');

        // Apply style ke header
        $sheet->getStyle('A3:X5')->applyFromArray($headerStyle);

        // Data mulai dari baris 6
        $row = 6;

        foreach ($pkData as $item) {
            // Bersihkan nilai rupiah
            $paguTw1 = $this->cleanRupiah($item->pagu_anggaran_tw1);
            $realisasiAnggaranTw1 = $this->cleanRupiah($item->realisasi_anggaran_tw1);
            $paguTw2 = $this->cleanRupiah($item->pagu_anggaran_tw2);
            $realisasiAnggaranTw2 = $this->cleanRupiah($item->realisasi_anggaran_tw2);
            $paguTw3 = $this->cleanRupiah($item->pagu_anggaran_tw3);
            $realisasiAnggaranTw3 = $this->cleanRupiah($item->realisasi_anggaran_tw3);
            $paguTw4 = $this->cleanRupiah($item->pagu_anggaran_tw4);
            $realisasiAnggaranTw4 = $this->cleanRupiah($item->realisasi_anggaran_tw4);

            // Set nilai ke sheet
            $sheet->setCellValue('A' . $row, $item->no);
            $sheet->setCellValue('B' . $row, $item->sasaran_strategis);
            $sheet->setCellValue('C' . $row, $item->indikator_kinerja);
            $sheet->setCellValue('D' . $row, $item->target_2025);
            $sheet->setCellValue('E' . $row, $item->satuan);
            $sheet->setCellValue('F' . $row, $item->program);

            // Target dan Realisasi Kinerja per Triwulan
            $sheet->setCellValue('G' . $row, $item->target_tw1);
            $sheet->setCellValue('H' . $row, $item->realisasi_tw1);
            $sheet->setCellValue('I' . $row, $item->target_tw2);
            $sheet->setCellValue('J' . $row, $item->realisasi_tw2);
            $sheet->setCellValue('K' . $row, $item->target_tw3);
            $sheet->setCellValue('L' . $row, $item->realisasi_tw3);
            $sheet->setCellValue('M' . $row, $item->target_tw4);
            $sheet->setCellValue('N' . $row, $item->realisasi_tw4);

            // Pagu dan Realisasi Anggaran per Triwulan
            $sheet->setCellValue('O' . $row, $paguTw1 ? number_format($paguTw1, 0, ',', '.') : '-');
            $sheet->setCellValue('P' . $row, $realisasiAnggaranTw1 ? number_format($realisasiAnggaranTw1, 0, ',', '.') : '-');
            $sheet->setCellValue('Q' . $row, $paguTw2 ? number_format($paguTw2, 0, ',', '.') : '-');
            $sheet->setCellValue('R' . $row, $realisasiAnggaranTw2 ? number_format($realisasiAnggaranTw2, 0, ',', '.') : '-');
            $sheet->setCellValue('S' . $row, $paguTw3 ? number_format($paguTw3, 0, ',', '.') : '-');
            $sheet->setCellValue('T' . $row, $realisasiAnggaranTw3 ? number_format($realisasiAnggaranTw3, 0, ',', '.') : '-');
            $sheet->setCellValue('U' . $row, $paguTw4 ? number_format($paguTw4, 0, ',', '.') : '-');
            $sheet->setCellValue('V' . $row, $realisasiAnggaranTw4 ? number_format($realisasiAnggaranTw4, 0, ',', '.') : '-');

            $sheet->setCellValue('W' . $row, $item->penjelasan_analisis);
            $sheet->setCellValue('X' . $row, $item->penanggung_jawab);

            $row++;
        }

        // Apply style ke data
        if ($row > 6) {
            $lastRow = $row - 1;
            $sheet->getStyle('A6:X' . $lastRow)->applyFromArray($dataStyle);
            $sheet->getStyle('D6:D' . $lastRow)->applyFromArray($numberStyle);
            $sheet->getStyle('O6:V' . $lastRow)->applyFromArray($numberStyle);
        }

        // Buat file Excel
        $writer = new Xlsx($spreadsheet);

        // Set headers untuk download
        $semesterText = $semester == '1' ? 'I' : 'II';
        $filename = 'PK_BUPATI_SEMESTER_' . $semesterText . '_TAHUN_' . $year . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $semester = $request->get('semester', '1');

        // Konversi semester dari form (1/2) ke format database (I/II)
        $semesterDb = $semester == '1' ? 'I' : 'II';

        // Ambil data PK Bupati berdasarkan tahun dan semester
        $pkData = PK_Bupati::where('tahun', $year)
            ->where('semester', $semesterDb)
            ->orderBy('no', 'asc')
            ->get();

        $html = $this->generatePdfHtml($pkData, $year, $semesterDb);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('legal', 'landscape');

        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'chroot' => public_path(),
            'enable_php' => true,
            'enable_css_float' => true,
            'enable_css_position' => true,
        ]);

        $semesterText = $semester == '1' ? 'I' : 'II';
        $filename = 'PK_BUPATI_SEMESTER_' . $semesterText . '_TAHUN_' . $year . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate HTML untuk PDF
     */
    private function generatePdfHtml($pkData, $year, $semester)
    {
        $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>PERJANJIAN KINERJA BUPATI - SEMESTER ' . $semester . ' TAHUN ' . $year . '</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 7px;
                line-height: 1.2;
                margin: 5px;
            }
            table { 
                border-collapse: collapse; 
                width: 100%; 
                table-layout: fixed;
            }
            th, td { 
                border: 1px solid #000; 
                padding: 3px; 
                vertical-align: middle; 
                word-break: break-word;
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
                margin-bottom: 10px; 
            }
            .sub-title {
                font-size: 10px;
                font-weight: bold;
                text-align: center;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class="title">PERJANJIAN KINERJA BUPATI</div>
        <div class="sub-title">SEMESTER ' . $semester . ' TAHUN ' . $year . '</div>
        
        <table>
            <thead>
                <tr>
                    <th rowspan="3" width="2%">NO</th>
                    <th rowspan="3" width="12%">SASARAN STRATEGIS</th>
                    <th rowspan="3" width="12%">INDIKATOR KINERJA</th>
                    <th rowspan="3" width="3%">TARGET 2025</th>
                    <th rowspan="3" width="3%">SATUAN</th>
                    <th rowspan="3" width="10%">PROGRAM / KEGIATAN</th>
                    <th colspan="8" width="16%">TARGET DAN REALISASI KINERJA</th>
                    <th colspan="8" width="16%">PAGU DAN REALISASI ANGGARAN</th>
                    <th rowspan="3" width="10%">ANALISIS / EVALUASI</th>
                    <th rowspan="3" width="8%">PENANGGUNG JAWAB</th>
                </tr>
                <tr>
                    <th colspan="2" width="4%">TW I</th>
                    <th colspan="2" width="4%">TW II</th>
                    <th colspan="2" width="4%">TW III</th>
                    <th colspan="2" width="4%">TW IV</th>
                    <th colspan="2" width="4%">TW I</th>
                    <th colspan="2" width="4%">TW II</th>
                    <th colspan="2" width="4%">TW III</th>
                    <th colspan="2" width="4%">TW IV</th>
                </tr>
                <tr>
                    <th width="2%">Target</th>
                    <th width="2%">Realisasi</th>
                    <th width="2%">Target</th>
                    <th width="2%">Realisasi</th>
                    <th width="2%">Target</th>
                    <th width="2%">Realisasi</th>
                    <th width="2%">Target</th>
                    <th width="2%">Realisasi</th>
                    <th width="2%">Pagu</th>
                    <th width="2%">Realisasi</th>
                    <th width="2%">Pagu</th>
                    <th width="2%">Realisasi</th>
                    <th width="2%">Pagu</th>
                    <th width="2%">Realisasi</th>
                    <th width="2%">Pagu</th>
                    <th width="2%">Realisasi</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($pkData as $item) {
            // Bersihkan nilai rupiah
            $paguTw1 = $this->cleanRupiah($item->pagu_anggaran_tw1);
            $realisasiAnggaranTw1 = $this->cleanRupiah($item->realisasi_anggaran_tw1);
            $paguTw2 = $this->cleanRupiah($item->pagu_anggaran_tw2);
            $realisasiAnggaranTw2 = $this->cleanRupiah($item->realisasi_anggaran_tw2);
            $paguTw3 = $this->cleanRupiah($item->pagu_anggaran_tw3);
            $realisasiAnggaranTw3 = $this->cleanRupiah($item->realisasi_anggaran_tw3);
            $paguTw4 = $this->cleanRupiah($item->pagu_anggaran_tw4);
            $realisasiAnggaranTw4 = $this->cleanRupiah($item->realisasi_anggaran_tw4);

            $html .= '
                <tr>
                    <td class="text-center">' . $item->no . '</td>
                    <td>' . htmlspecialchars($item->sasaran_strategis) . '</td>
                    <td>' . htmlspecialchars($item->indikator_kinerja) . '</td>
                    <td class="text-right">' . htmlspecialchars($item->target_2025) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->satuan) . '</td>
                    <td>' . htmlspecialchars($item->program) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->target_tw1) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->realisasi_tw1) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->target_tw2) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->realisasi_tw2) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->target_tw3) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->realisasi_tw3) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->target_tw4) . '</td>
                    <td class="text-center">' . htmlspecialchars($item->realisasi_tw4) . '</td>
                    <td class="text-right">' . ($paguTw1 ? number_format($paguTw1, 0, ',', '.') : '-') . '</td>
                    <td class="text-right">' . ($realisasiAnggaranTw1 ? number_format($realisasiAnggaranTw1, 0, ',', '.') : '-') . '</td>
                    <td class="text-right">' . ($paguTw2 ? number_format($paguTw2, 0, ',', '.') : '-') . '</td>
                    <td class="text-right">' . ($realisasiAnggaranTw2 ? number_format($realisasiAnggaranTw2, 0, ',', '.') : '-') . '</td>
                    <td class="text-right">' . ($paguTw3 ? number_format($paguTw3, 0, ',', '.') : '-') . '</td>
                    <td class="text-right">' . ($realisasiAnggaranTw3 ? number_format($realisasiAnggaranTw3, 0, ',', '.') : '-') . '</td>
                    <td class="text-right">' . ($paguTw4 ? number_format($paguTw4, 0, ',', '.') : '-') . '</td>
                    <td class="text-right">' . ($realisasiAnggaranTw4 ? number_format($realisasiAnggaranTw4, 0, ',', '.') : '-') . '</td>
                    <td>' . htmlspecialchars($item->penjelasan_analisis) . '</td>
                    <td>' . htmlspecialchars($item->penanggung_jawab) . '</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>
    </body>
    </html>';

        return $html;
    }

    /**
     * Membersihkan format rupiah menjadi numeric
     */
    private function cleanRupiah($value)
    {
        if (empty($value) || $value === '-') {
            return null;
        }

        if (is_numeric($value)) {
            return $value;
        }

        $cleaned = preg_replace('/[^0-9,-]/', '', $value);
        $cleaned = str_replace(',', '.', $cleaned);
        $cleaned = str_replace('.', '', $cleaned);

        return is_numeric($cleaned) ? (float) $cleaned : null;
    }
}