<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use App\Models\PK_Bupati;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PKBupatiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $semester = $request->get('semester', '1');
        
        // Konversi semester dari form (1/2) ke format database (I/II)
        $semesterDb = $semester == '1' ? 'I' : 'II';
        
        // Ambil data dari database berdasarkan filter
        $pkData = PK_Bupati::where('tahun', $tahun)
            ->where('semester', $semesterDb)
            ->orderBy('no', 'asc')
            ->paginate(10) // Menggunakan paginate() bukan get()
            ->withQueryString(); // Mempertahankan query string saat berpindah halaman

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

        return view('opd.pk-bupati.index', compact(
            'pkData', 
            'tahun', 
            'semester', 
            'indikatorData',
            'sasaranOptions',
            'penanggungJawabOptions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Informasi Dasar
            'no' => 'required|integer|min:1|max:999',
            'sasaranStrategis' => 'required|string|max:500',
            'indikatorKinerja' => 'required|string|max:500',
            'target2025' => 'required|numeric|min:0|max:999999999.99',
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
            
            // Meta data
            'tahun' => 'required|integer|min:2020|max:2030',
            'semester' => 'required|in:1,2'
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

            // Konversi semester ke format database
            $semesterDb = $request->semester == '1' ? 'I' : 'II';

            // Cek apakah no sudah ada untuk tahun dan semester yang sama
            $exists = PK_Bupati::where('no', $request->no)
                ->where('tahun', $request->tahun)
                ->where('semester', $semesterDb)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor urut sudah digunakan untuk tahun dan semester ini'
                ], 422);
            }

            $data = [
                'no' => $request->no,
                'sasaran_strategis' => $request->sasaranStrategis,
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
                'tahun' => $request->tahun,
                'semester' => $semesterDb, // Simpan dalam format I/II
                'created_by' => Auth::id()
            ];

            $pkBupati = PK_Bupati::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $pkBupati
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = PK_Bupati::where('id', $id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $data->id,
                    'no' => $data->no,
                    'tahun' => $data->tahun,
                    'semester' => $data->semester,
                    'sasaranStrategis' => $data->sasaran_strategis,
                    'indikatorKinerja' => $data->indikator_kinerja,
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
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
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
            'target2025' => 'required|numeric|min:0|max:999999999.99',
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

            $pkBupati = PK_Bupati::where('id', $id)
                ->firstOrFail();

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

            $data = [
                'no' => $request->no,
                'sasaran_strategis' => $request->sasaranStrategis,
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
                'updated_by' => Auth::id()
            ];

            $pkBupati->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $pkBupati
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
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

            $pkBupati = PK_Bupati::where('id', $id)
                ->firstOrFail();

            $pkBupati->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data by year and semester for API
     */
    public function getDataByFilter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'nullable|integer|min:2020|max:2030',
            'semester' => 'nullable|in:1,2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter filter tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $tahun = $request->get('tahun', date('Y'));
        $semester = $request->get('semester', '1');
        
        // Konversi semester ke format database
        $semesterDb = $semester == '1' ? 'I' : 'II';

        $data = PK_Bupati::where('tahun', $tahun)
            ->where('semester', $semesterDb)
            ->orderBy('no', 'asc')
            ->get();

        $formattedData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'no' => $item->no,
                'tahun' => $item->tahun,
                'semester' => $item->semester,
                'sasaranStrategis' => $item->sasaran_strategis,
                'indikatorKinerja' => $item->indikator_kinerja,
                'target2025' => $item->target_2025,
                'satuan' => $item->satuan,
                'targetTW1' => $item->target_tw1,
                'realisasiTW1' => $item->realisasi_tw1,
                'targetTW2' => $item->target_tw2,
                'realisasiTW2' => $item->realisasi_tw2,
                'targetTW3' => $item->target_tw3,
                'realisasiTW3' => $item->realisasi_tw3,
                'targetTW4' => $item->target_tw4,
                'realisasiTW4' => $item->realisasi_tw4,
                'paguAnggaranTW1' => $item->pagu_anggaran_tw1,
                'realisasiAnggaranTW1' => $item->realisasi_anggaran_tw1,
                'paguAnggaranTW2' => $item->pagu_anggaran_tw2,
                'realisasiAnggaranTW2' => $item->realisasi_anggaran_tw2,
                'paguAnggaranTW3' => $item->pagu_anggaran_tw3,
                'realisasiAnggaranTW3' => $item->realisasi_anggaran_tw3,
                'paguAnggaranTW4' => $item->pagu_anggaran_tw4,
                'realisasiAnggaranTW4' => $item->realisasi_anggaran_tw4,
                'program' => $item->program,
                'analisisEvaluasi' => $item->penjelasan_analisis,
                'penanggungJawab' => $item->penanggung_jawab,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedData
        ]);
    }
}