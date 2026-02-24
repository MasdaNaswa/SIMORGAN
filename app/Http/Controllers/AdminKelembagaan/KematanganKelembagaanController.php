<?php

namespace App\Http\Controllers\AdminKelembagaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EvaluasiKemendagri;
use App\Models\EvaluasiKemenpan;
use App\Models\Pengguna;

class KematanganKelembagaanController extends Controller
{
    /**
     * Menampilkan semua hasil survei kematangan kelembagaan
     */
    public function index(Request $request)
    {
        // ============================
        // URUTAN OPD MANUAL
        // ============================
        $urutanOPD = [
            "Sekretariat Daerah",
            "Sekretariat DPRD",
            "Inspektorat Daerah",
            "Dinas Pendidikan dan Kebudayaan",
            "Dinas Kesehatan",
            "Rumah Sakit Umum Daerah Muhammad Sani",
            "Rumah Sakit Umum Daerah Tanjung Batu Kundur",
            "Dinas Pekerjaan Umum dan Penataan Ruang",
            "Dinas Perumahan Rakyat dan Kawasan Pemukiman",
            "Dinas Sosial",
            "Dinas Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan dan Perlindungan Anak",
            "Dinas Lingkungan Hidup",
            "Dinas Kependudukan dan Pencatatan Sipil",
            "Dinas Pemberdayaan Masyarakat dan Desa",
            "Dinas Perhubungan",
            "Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu",
            "Dinas Kepemudaan dan Olahraga",
            "Dinas Pariwisata",
            "Dinas Perpustakaan dan Kearsipan",
            "Dinas Perikanan",
            "Dinas Pangan dan Pertanian",
            "Dinas Koperasi Usaha Mikro, Perdagangan dan Energi Sumber Daya Mineral",
            "Dinas Tenaga Kerja dan Perindustrian",
            "Diskominfo",
            "Satuan Polisi Pamong Praja",
            "Badan Perencanaan, Penelitian dan Pengembangan",
            "Badan Pendapatan Daerah",
            "Badan Pengelola Keuangan dan Aset Daerah",
            "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
            "Badan Kesatuan Bangsa dan Politik",
            "Badan Penanggulangan Bencana Daerah dan Pemadam Kebakaran",
            "Kecamatan Karimun",
            "Kecamatan Tebing",
            "Kecamatan Meral",
            "Kecamatan Meral Barat",
            "Kecamatan Buru",
            "Kecamatan Kundur",
            "Kecamatan Kundur Barat",
            "Kecamatan Kundur Utara",
            "Kecamatan Belat",
            "Kecamatan Ungar",
            "Kecamatan Moro",
            "Kecamatan Durai",
            "Kecamatan Selat Gelam",
            "Kecamatan Sugie Besar"
        ];

        // ============================
        // QUERY KEMENPAN
        // ============================
        $queryKemenpan = EvaluasiKemenpan::select(
            'id_evaluasi_kemenpan as id',
            'nama_opd',
            'tanggal_pengisian',
            'skor_struktur',
            'skor_proses',
            'total_skor',
            'tingkat_kematangan',
            'created_at'
        )->with('user:id,nama_opd');

        // ============================
        // QUERY KEMENDAGRI
        // ============================
        $queryKemendagri = EvaluasiKemendagri::select(
            'id_evaluasi_kemendagri as id',
            'nama_opd',
            'total_skor',
            'tingkat_maturitas',
            'created_at'
        )->with('user:id,nama_opd');

        // ============================
        // FILTER OPD
        // ============================
        if ($request->filled('opd')) {
            $queryKemenpan->where('nama_opd', 'like', '%' . $request->opd . '%');
            $queryKemendagri->where('nama_opd', 'like', '%' . $request->opd . '%');
        }

        // ============================
        // ORDER BY URUTAN OPD
        // ============================
        $orderByOPD = "FIELD(nama_opd, '" . implode("','", $urutanOPD) . "')";

        $queryKemenpan
            ->orderByRaw("$orderByOPD = 0") // OPD tidak terdaftar di bawah
            ->orderByRaw($orderByOPD);

        $queryKemendagri
            ->orderByRaw("$orderByOPD = 0")
            ->orderByRaw($orderByOPD);

        // ============================
        // PAGINATION
        // ============================
        $evaluasiKemenpan = $queryKemenpan
            ->paginate(10, ['*'], 'kemenpan_page');

        $evaluasiKemendagri = $queryKemendagri
            ->paginate(10, ['*'], 'kemendagri_page');

        // ============================
        // STATISTIK
        // ============================
        $stats = [
            'total_kemenpan' => EvaluasiKemenpan::count(),
            'total_kemendagri' => EvaluasiKemendagri::count(),
            'avg_kemenpan' => (float) EvaluasiKemenpan::avg('total_skor') ?? 0,
            'avg_kemendagri' => (float) EvaluasiKemendagri::avg('total_skor') ?? 0,
        ];

        // ============================
        // LIST OPD UNTUK FILTER
        // ============================
        $listOPD = Pengguna::whereIn('role', ['OPD'])
            ->distinct()
            ->pluck('nama_opd');

        return view(
            'adminkelembagaan.kematangan-kelembagaan.index',
            compact(
                'evaluasiKemenpan',
                'evaluasiKemendagri',
                'stats',
                'listOPD'
            )
        );
    }

    /**
     * Hapus hasil survei
     */
    public function destroy($id)
    {
        try {
            if (request()->has('type')) {
                if (request()->type === 'kemenpan') {
                    $evaluasi = EvaluasiKemenpan::findOrFail($id);
                } elseif (request()->type === 'kemendagri') {
                    $evaluasi = EvaluasiKemendagri::findOrFail($id);

                    // Hapus file jika ada
                    $files = $evaluasi->getAllFilePaths();
                    foreach ($files as $variabelFiles) {
                        foreach ($variabelFiles as $filePath) {
                            if (\Storage::disk('public')->exists($filePath)) {
                                \Storage::disk('public')->delete($filePath);
                            }
                        }
                    }
                } else {
                    return redirect()->back()->with('error', 'Tipe evaluasi tidak valid.');
                }

                $evaluasi->delete();

                return redirect()
                    ->route('adminkelembagaan.kematangan-kelembagaan.index')
                    ->with('success', 'Data hasil survei berhasil dihapus.');
            }

            return redirect()->back()->with('error', 'Tipe evaluasi harus ditentukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail Kemenpan (JSON)
     */
    public function showKemenpanJson($id)
    {
        $evaluasi = EvaluasiKemenpan::find($id);

        if (!$evaluasi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $detailRaw = $evaluasi->getDetailPerhitungan() ?? ['struktur' => [], 'proses' => []];
        $detailPerhitungan = [];

        foreach (['struktur', 'proses'] as $jenis) {
            if (isset($detailRaw[$jenis])) {
                foreach ($detailRaw[$jenis] as $sub => $data) {
                    $detailPerhitungan[$sub] = [
                        'skor_mentah' => $data['skor'] ?? 0,
                        'max_skor' => 100
                    ];
                }
            }
        }

        $jawabanStruktur = [];
        for ($i = 1; $i <= 32; $i++) {
            $jawabanStruktur[] = $evaluasi->{"struktur_$i"} ?? 'Tidak Diisi';
        }

        $jawabanProses = [];
        for ($i = 1; $i <= 30; $i++) {
            $jawabanProses[] = $evaluasi->{"proses_$i"} ?? 'Tidak Diisi';
        }

        return response()->json([
            'evaluasi' => [
                'nama_opd' => $evaluasi->nama_opd ?? '',
                'email' => $evaluasi->email ?? '',
                'created_at' => optional($evaluasi->created_at)->format('d-m-Y'),
                'skor_struktur' => $evaluasi->skor_struktur ?? 0,
                'skor_proses' => $evaluasi->skor_proses ?? 0,
                'total_skor' => $evaluasi->total_skor ?? 0,
            ],
            'jawaban' => [
                'struktur' => $jawabanStruktur,
                'proses' => $jawabanProses
            ],
            'detailPerhitungan' => $detailPerhitungan,
            'interpretasi' => method_exists($evaluasi, 'getInterpretasi')
                ? $evaluasi->getInterpretasi()
                : []
        ]);
    }

    /**
     * Detail Kemendagri (JSON)
     */
    public function showKemendagriJson($id)
    {
        $evaluasi = EvaluasiKemendagri::with('user')->find($id);

        if (!$evaluasi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $variabels = ['i','ii','iii','iv','v','vi','vii','viii','ix','x','xi'];
        $jawaban = [];

        foreach ($variabels as $var) {
            $files = [];
            for ($i = 1; $i <= 3; $i++) {
                $path = $evaluasi->{"file_path_{$var}_$i"};
                if ($path) {
                    $files[] = [
                        'name' => basename($path),
                        'url' => asset('storage/' . $path),
                        'path' => $path
                    ];
                }
            }

            $jawaban["VARIABEL " . strtoupper($var)] = [
                'tingkat' => $evaluasi->{"variabel_{$var}"},
                'files' => $files
            ];
        }

        return response()->json([
            'evaluasi' => [
                'id' => $evaluasi->id,
                'nama_opd' => $evaluasi->nama_opd,
                'email' => $evaluasi->email,
                'created_at' => $evaluasi->created_at->format('d M Y H:i'),
                'total_skor' => $evaluasi->total_skor,
                'tingkat_maturitas' => $evaluasi->tingkat_maturitas,
                'status' => $evaluasi->status ?? 'Diproses',
                'catatan' => $evaluasi->catatan
            ],
            'jawaban' => $jawaban
        ]);
    }
}
