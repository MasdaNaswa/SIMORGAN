<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Template_Laporan;
use App\Models\Laporan;
use App\Models\SkmReport;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Dompdf\Dompdf;
use Dompdf\Options;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PelayananPublikController extends Controller
{
    public function index()
    {
        $kategories = Kategori::orderBy('nama_kategori')->get();
        $templates = Template_Laporan::with('kategori')->get();

        // Hanya ambil laporan kategori (upload manual)
        $laporans = Laporan::where('id_user', auth()->id())
            ->whereNotIn('kategori', ['Laporan SKM', 'Petajab', 'Anjab & ABK', 'Evajab'])
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(10);

        // Ambil juga laporan SKM untuk tab terpisah
        $skmLaporans = Laporan::where('kategori', 'Laporan SKM')
            ->where('id_user', auth()->id())
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(10, ['*'], 'skm_page');

        // Cek apakah ada parameter active_tab dari redirect
        $activeTab = session('active_tab', 'template');

        // Cek apakah ada laporan yang berstatus "Revisi"
        $hasRevision = Laporan::where('kategori', 'Laporan SKM')
            ->where('id_user', auth()->id())
            ->where('status', 'Revisi')
            ->exists();

        return view('opd.pelayanan-publik.index', compact(
            'kategories',
            'templates',
            'laporans',
            'skmLaporans',
            'activeTab',
            'hasRevision'
        ));
    }

    // Upload laporan MANUAL (doc, docx, pdf)
    public function upload(Request $request)
    {
        $request->validate([
            'laporan' => 'required|file|mimes:doc,docx,pdf|max:10240',
            'kategori' => 'required|exists:kategori,id_kategori'
        ]);

        $kategori = Kategori::findOrFail($request->kategori);

        $file = $request->file('laporan');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('laporan', $filename, 'public');

        Laporan::create([
            'id_user' => auth()->id(),
            'judul' => $file->getClientOriginalName(),
            'kategori' => $kategori->nama_kategori,
            'id_kategori' => $kategori->id_kategori,
            'file_path' => $filename,
            'tanggal_upload' => now(),
            'status' => 'Diproses'
        ]);

        return redirect()->route('pelayanan-publik.index')
            ->with(['success' => 'Laporan berhasil diunggah.', 'active_tab' => 'upload']);
    }

    // Download laporan (bisa untuk kedua jenis)
    public function download($id)
    {
        $laporan = Laporan::findOrFail($id);

        $filePath = storage_path('app/public/laporan/' . $laporan->file_path);

        if (!file_exists($filePath)) {
            $filePath = storage_path('app/public/laporan/skm/' . $laporan->file_path);

            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }
        }

        return response()->download($filePath, $laporan->judul . '.pdf');
    }

    // View PDF inline (khusus SKM)
    public function viewPdf($id)
    {
        $laporan = Laporan::findOrFail($id);
        $filePath = storage_path('app/public/' . $laporan->file_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }

    // Hapus laporan MANUAL (hanya yang kategori Yanlik)
    public function hapusLaporan($id)
    {
        $laporan = Laporan::findOrFail($id);

        // BLOKIR KHUSUS SKM (GENERATED)
        if ($laporan->kategori === 'Laporan SKM') {
            return redirect()->back()
                ->with('error', 'Laporan SKM yang digenerate tidak dapat dihapus.');
        }

        // HAPUS FILE MANUAL
        if (Storage::disk('public')->exists('laporan/' . $laporan->file_path)) {
            Storage::disk('public')->delete('laporan/' . $laporan->file_path);
        }

        $laporan->delete();

        return redirect()->route('pelayanan-publik.index')
            ->with([
                'success' => 'Laporan manual berhasil dihapus.',
                'active_tab' => 'upload'
            ]);
    }

    public function hapusSkm($id)
    {
        $laporan = Laporan::findOrFail($id);

        // Pastikan ini kategori SKM
        if ($laporan->kategori !== 'Laporan SKM') {
            return redirect()->back()->with('error', 'Ini bukan laporan SKM.');
        }

        // Hapus file PDF
        if (Storage::disk('public')->exists($laporan->file_path)) {
            Storage::disk('public')->delete($laporan->file_path);
        }

        // Hapus terkait SKM report
        if ($laporan->skmReport) {
            $laporan->skmReport->delete();
        }

        $laporan->delete();

        return redirect()->route('pelayanan-publik.index')
            ->with('success', 'Laporan SKM berhasil dihapus.')
            ->with('active_tab', 'skm-list');
    }

    /**
     * Generate Laporan SKM (OTOMATIS) - DIPERBAIKI
     */
    public function generateSkm(Request $request)
    {
        try {
            // 1. Validasi yang sesuai dengan form
            $validator = Validator::make($request->all(), [
                'triwulan' => 'required|in:1,2,3,4',
                'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
                'jabatan_penandatangan' => 'required|string|max:255',
                'tanggal_pengesahan' => 'required|date',
                'latar_belakang' => 'required|string|min:10',
                'tujuan_manfaat' => 'required|string|min:10',
                'metode_pengumpulan' => 'required|string|min:10',
                'waktu_pelaksanaan_bulan' => 'required|integer|min:1|max:12',
                'jumlah_populasi' => 'required|integer|min:1',
                'jumlah_sampel' => 'required|integer|min:1',
                'analisis_masalah' => 'nullable|string',
                'kesimpulan' => 'required|string|min:10',
                'saran' => 'required|string|min:10',
                'ikm_unit_layanan' => 'required|numeric|min:0|max:100',
                'mutu_unit_layanan' => 'required|string|max:1',
                'warna_grafik' => 'required|string',

                // TAMBAH FIELD BARU UNTUK SIGNATURE
                'nama_penandatangan' => 'required|string|max:90',
                'nip_penandatangan' => 'required|string|max:19',

                // Array data
                'analisis_responden' => 'required|array',
                'analisis_responden.*.no' => 'nullable|string',
                'analisis_responden.*.karakteristik' => 'nullable|string',
                'analisis_responden.*.indikator' => 'nullable|string',
                'analisis_responden.*.jumlah' => 'nullable|integer',
                'analisis_responden.*.persentase' => 'nullable|numeric',

                'jenis_layanan' => 'required|array|min:1',
                'jenis_layanan.*.no' => 'nullable|string',
                'jenis_layanan.*.jenis_layanan' => 'required|string',
                'jenis_layanan.*.jumlah_responden' => 'required|integer|min:1',
                'jenis_layanan.*.nilai' => 'required|array|size:9',
                'jenis_layanan.*.nilai.*' => 'required|numeric|min:0|max:100',
                'jenis_layanan.*.ikm_per_jenis' => 'required|numeric|min:0|max:100',

                'rerata_ikm' => 'required|array|size:9',
                'rerata_ikm.*' => 'required|numeric|min:0|max:100',

                'rencana_tindak_lanjut' => 'nullable|array',
                'rencana_tindak_lanjut.*.no' => 'nullable|string',
                'rencana_tindak_lanjut.*.unsur' => 'nullable|string',
                'rencana_tindak_lanjut.*.rencana' => 'nullable|string',
                'rencana_tindak_lanjut.*.waktu' => 'nullable|string',
                'rencana_tindak_lanjut.*.penanggung_jawab' => 'nullable|string',

                'tren_skm' => 'nullable|array',
                'tren_skm.*.tahun' => 'nullable|integer',
                'tren_skm.*.ikm' => 'nullable|numeric',
                'tren_skm.*.mutu' => 'nullable|string',

                'hasil_skm_sebelumnya' => 'nullable|array',
                'hasil_skm_sebelumnya.*.no' => 'nullable|string',
                'hasil_skm_sebelumnya.*.unsur' => 'nullable|string',
                'hasil_skm_sebelumnya.*.ikm' => 'nullable|numeric',

                'tindak_lanjut_sebelumnya' => 'nullable|array',
                'tindak_lanjut_sebelumnya.*.no' => 'nullable|string',
                'tindak_lanjut_sebelumnya.*.rencana' => 'nullable|string',
                'tindak_lanjut_sebelumnya.*.status' => 'nullable|string',
                'tindak_lanjut_sebelumnya.*.deskripsi' => 'nullable|string',
                'tindak_lanjut_sebelumnya.*.dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png|max:6048',

                // File uploads
                'kuesioner_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
                'dokumentasi_foto' => 'nullable|array',
                'dokumentasi_foto.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', ['errors' => $validator->errors()->all()]);
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Validasi gagal: ' . implode(', ', $validator->errors()->all()));
            }

            $validated = $validator->validated();
            $user = Auth::user();

            if (!$user) {
                return back()->with('error', 'Anda harus login terlebih dahulu.');
            }

            Log::info('Starting SKM generation for user: ' . $user->id_user);

            // 2. Proses upload file
            $filePaths = [];

            if ($request->hasFile('kuesioner_file')) {
                $kuesionerFile = $request->file('kuesioner_file');
                $kuesionerName = time() . '_kuesioner_' . Str::slug($kuesionerFile->getClientOriginalName());
                $kuesionerFile->storeAs('laporan/skm', $kuesionerName, 'public');
                $filePaths['kuesioner'] = $kuesionerName;
            }

            if ($request->hasFile('dokumentasi_foto')) {
                $filePaths['dokumentasi'] = [];
                foreach ($request->file('dokumentasi_foto') as $index => $file) {
                    if ($file->isValid()) {
                        $fileName = time() . '_dokumentasi_' . $index . '_' . Str::slug($file->getClientOriginalName());
                        $file->storeAs('laporan/skm/dokumentasi', $fileName, 'public');
                        $filePaths['dokumentasi'][] = $fileName;
                    }
                }
            }

            // 3. Filter data array
            $analisisResponden = array_filter($validated['analisis_responden'], function ($item) {
                return !empty($item['karakteristik']) || !empty($item['indikator']);
            });

            $jenisLayanan = array_filter($validated['jenis_layanan'], function ($item) {
                return !empty($item['jenis_layanan']);
            });

            $rencanaTindakLanjut = isset($validated['rencana_tindak_lanjut'])
                ? array_filter($validated['rencana_tindak_lanjut'], function ($item) {
                    return !empty($item['rencana']);
                })
                : [];

            $trenSkm = isset($validated['tren_skm'])
                ? array_filter($validated['tren_skm'], function ($item) {
                    return !empty($item['tahun']);
                })
                : [];

            $hasilSkmSebelumnya = isset($validated['hasil_skm_sebelumnya'])
                ? array_filter($validated['hasil_skm_sebelumnya'], function ($item) {
                    return !empty($item['unsur']);
                })
                : [];

            $tindakLanjutSebelumnya = isset($validated['tindak_lanjut_sebelumnya'])
                ? array_filter($validated['tindak_lanjut_sebelumnya'], function ($item) {
                    return !empty($item['rencana']);
                })
                : [];

            // 4. Simpan ke database SKM Report
            $skmReport = SkmReport::create([
                'id_user' => $user->id_user,
                'nama_opd' => $user->nama_opd,
                'triwulan' => $validated['triwulan'],
                'tahun' => $validated['tahun'],
                'jabatan_penandatangan' => $validated['jabatan_penandatangan'],
                'tanggal_pengesahan' => $validated['tanggal_pengesahan'],
                'latar_belakang' => $validated['latar_belakang'],

                // TAMBAH FIELD BARU
                'nama_penandatangan' => $validated['nama_penandatangan'],
                'nip_penandatangan' => $validated['nip_penandatangan'],
                'tujuan_manfaat' => $validated['tujuan_manfaat'],
                'metode_pengumpulan' => $validated['metode_pengumpulan'],
                'waktu_pelaksanaan_bulan' => $validated['waktu_pelaksanaan_bulan'],
                'jumlah_populasi' => $validated['jumlah_populasi'],
                'jumlah_sampel' => $validated['jumlah_sampel'],
                'analisis_responden' => json_encode($analisisResponden),
                'jenis_layanan' => json_encode($jenisLayanan),
                'rerata_ikm' => json_encode($validated['rerata_ikm']),
                'ikm_unit_layanan' => $validated['ikm_unit_layanan'],
                'mutu_unit_layanan' => $validated['mutu_unit_layanan'],
                'warna_grafik' => $validated['warna_grafik'],
                'analisis_masalah' => $validated['analisis_masalah'] ?? '',
                'rencana_tindak_lanjut_analisis' => json_encode($rencanaTindakLanjut),
                'tren_skm' => json_encode($trenSkm),
                'hasil_skm_sebelumnya' => json_encode($hasilSkmSebelumnya),
                'tindak_lanjut_sebelumnya' => json_encode($tindakLanjutSebelumnya),
                'kesimpulan' => $validated['kesimpulan'],
                'saran' => $validated['saran'],
                'dokumentasi_foto' => json_encode($filePaths),
                'status' => 'generated',
                'generated_at' => now()
            ]);

            // 5. Generate chart images
            $chartImagePath = $this->generateChartImage(
                $validated['rerata_ikm'],
                $validated['warna_grafik'],
                $user->nama_opd,
                $validated['triwulan'],
                $validated['tahun']
            );

            // Generate tren chart image
            $trenChartPath = $this->generateTrenChartImage(
                $trenSkm,
                $validated['warna_grafik'],
                $user->nama_opd
            );

            // Prepare data untuk PDF
            $triwulanText = match ((int) $validated['triwulan']) {
                1 => 'I (Januari - Maret)',
                2 => 'II (April - Juni)',
                3 => 'III (Juli - September)',
                4 => 'IV (Oktober - Desember)',
                default => 'I'
            };

            // 6. Persiapan gambar untuk lampiran
            $dokumentasiImages = [];
            if (!empty($filePaths['dokumentasi'])) {
                foreach ($filePaths['dokumentasi'] as $index => $fileName) {
                    $docPath = storage_path('app/public/laporan/skm/dokumentasi/' . $fileName);
                    if (file_exists($docPath)) {
                        // Resize gambar jika terlalu besar
                        $resizedImage = $this->resizeImageForPDF($docPath, 500, 400);
                        if ($resizedImage) {
                            $dokumentasiImages[] = [
                                'base64' => base64_encode($resizedImage),
                                'filename' => $fileName,
                                'extension' => pathinfo($fileName, PATHINFO_EXTENSION)
                            ];
                        }
                    }
                }
            }

            // Persiapan kuesioner image
            $kuesionerImage = null;
            if (!empty($filePaths['kuesioner'])) {
                $kuesionerPath = storage_path('app/public/laporan/skm/' . $filePaths['kuesioner']);
                if (file_exists($kuesionerPath)) {
                    $resizedKuesioner = $this->resizeImageForPDF($kuesionerPath, 800, 600);
                    if ($resizedKuesioner) {
                        $kuesionerImage = [
                            'base64' => base64_encode($resizedKuesioner),
                            'filename' => $filePaths['kuesioner'],
                            'extension' => pathinfo($filePaths['kuesioner'], PATHINFO_EXTENSION)
                        ];
                    }
                }
            }

            // 7. Persiapan gambar untuk tindak lanjut sebelumnya
            $tindakLanjutWithImages = [];
            if (!empty($tindakLanjutSebelumnya)) {
                foreach ($tindakLanjutSebelumnya as $index => $tl) {
                    // Tambahkan data dasar
                    $tindakLanjutWithImages[$index] = $tl;
                    
                    // Cek jika ada file upload untuk dokumentasi
                    if ($request->hasFile('tindak_lanjut_sebelumnya.' . $index . '.dokumentasi')) {
                        $dokFile = $request->file('tindak_lanjut_sebelumnya.' . $index . '.dokumentasi');
                        if ($dokFile->isValid()) {
                            // Simpan file
                            $dokFileName = time() . '_tindaklanjut_' . $index . '_' . Str::slug($dokFile->getClientOriginalName());
                            $dokFile->storeAs('laporan/skm/tindaklanjut', $dokFileName, 'public');
                            
                            // Resize dan konversi ke base64 untuk PDF
                            $dokPath = storage_path('app/public/laporan/skm/tindaklanjut/' . $dokFileName);
                            if (file_exists($dokPath)) {
                                $resizedImage = $this->resizeImageForPDF($dokPath, 250, 150); // Ukuran kecil untuk tabel
                                if ($resizedImage) {
                                    $tindakLanjutWithImages[$index]['dokumentasi_image'] = [
                                        'base64' => base64_encode($resizedImage),
                                        'filename' => $dokFileName,
                                        'extension' => pathinfo($dokFileName, PATHINFO_EXTENSION)
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            $data = [
                'opd' => $user,
                'skm' => (object) [
                    'triwulan' => $validated['triwulan'],
                    'tahun' => $validated['tahun'],
                    'jabatan_penandatangan' => $validated['jabatan_penandatangan'],
                    'nama_penandatangan' => $validated['nama_penandatangan'],
                    'tanggal_pengesahan' => $validated['tanggal_pengesahan'],
                    'latar_belakang' => $validated['latar_belakang'],
                    'tujuan_manfaat' => $validated['tujuan_manfaat'],
                    'metode_pengumpulan' => $validated['metode_pengumpulan'],
                    'waktu_pelaksanaan_bulan' => $validated['waktu_pelaksanaan_bulan'],
                    'jumlah_populasi' => $validated['jumlah_populasi'],
                    'jumlah_sampel' => $validated['jumlah_sampel'],
                    'analisis_masalah' => $validated['analisis_masalah'] ?? '',
                    'kesimpulan' => $validated['kesimpulan'],
                    'saran' => $validated['saran'],
                    'ikm_unit_layanan' => $validated['ikm_unit_layanan'],
                    'mutu_unit_layanan' => $validated['mutu_unit_layanan'],
                    'warna_grafik' => $validated['warna_grafik'],
                    'nip_penandatangan' => $validated['nip_penandatangan']
                ],
                'analisis_responden' => $analisisResponden,
                'jenis_layanan' => $jenisLayanan,
                'rerata_ikm' => $validated['rerata_ikm'],
                'rencana_tindak_lanjut_analisis' => $rencanaTindakLanjut,
                'tren_skm' => $trenSkm,
                'hasil_skm_sebelumnya' => $hasilSkmSebelumnya,
                'tindak_lanjut_sebelumnya' => $tindakLanjutWithImages, // Pakai data dengan gambar
                'dokumentasi_foto' => $filePaths['dokumentasi'] ?? [],
                'filePaths' => $filePaths,
                'triwulan_text' => $triwulanText,
                'tanggal_pengesahan_formatted' => Carbon::parse($validated['tanggal_pengesahan'])
                    ->locale('id')
                    ->translatedFormat('d F Y'),
                'generated_at_formatted' => now()->locale('id')->translatedFormat('d F Y H:i:s'),
                'chart_image_path' => $chartImagePath,
                'tren_chart_path' => $trenChartPath,
                'unsur_labels' => ['Persyaratan', 'Prosedur', 'Waktu', 'Biaya', 'Produk', 'Kompetensi', 'Perilaku', 'Aduan', 'Sarpras'],
                // Data gambar untuk lampiran
                'dokumentasi_images' => $dokumentasiImages,
                'kuesioner_image' => $kuesionerImage,
            ];

            if (!$chartImagePath) {
                Log::warning('Chart generation failed for user: ' . $user->id_user);
            }

            Log::info('Tren chart generated at: ' . $trenChartPath);

            // 8. Konfigurasi mPDF
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            $mpdfConfig = [
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => 11,
                'default_font' => 'arial',
                'margin_left' => 25.4,
                'margin_right' => 25.4,
                'margin_top' => 25.4,
                'margin_bottom' => 25.4,
                'margin_header' => 10,
                'margin_footer' => 15,
                'tempDir' => storage_path('app/mpdf/tmp'),
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'useSubstitutions' => true,
            ];

            // Buat mPDF instance
            $mpdf = new Mpdf($mpdfConfig);

            // ==================== TAMBAHAN: ATUR NOMOR HALAMAN ====================
            
            // Atur footer dengan nomor halaman yang rapi
            $footerHtml = '
            <div style="text-align: right; font-family: Arial; font-size: 10pt; color: #666; padding-top: 10px;">
                {PAGENO}
            </div>';

            // Atur footer untuk semua halaman
            $mpdf->SetHTMLFooter($footerHtml);
            
            // Untuk halaman cover (halaman 1), hapus nomor halaman
            $mpdf->WriteHTML(view('exports.skm-report-cover', $data)->render());
            
            // Reset footer untuk halaman berikutnya (mulai dari halaman 2)
            $mpdf->AddPage();
            
            // ==================== END TAMBAHAN ====================

            // 9. Tambahkan halaman satu per satu
            // Halaman 2: Daftar Isi
            $mpdf->WriteHTML(view('exports.skm-report-toc', $data)->render());

            // Halaman 3: BAB I
            $mpdf->AddPage();
            $mpdf->WriteHTML(view('exports.skm-report-bab1', $data)->render());

            // Halaman 4: Analisis Responden
            $mpdf->AddPage();
            $mpdf->WriteHTML(view('exports.skm-report-analisis-responden', $data)->render());

            // Halaman 5: Tabel Jenis Layanan (LANDSCAPE)
            $mpdf->AddPage('L'); // Landscape
            $mpdf->WriteHTML(view('exports.skm-report-landscape', $data)->render());

            // Halaman 6: Grafik (Portrait)
            $mpdf->AddPage('P'); // Kembali ke Portrait
            $mpdf->WriteHTML(view('exports.skm-report-grafik', $data)->render());

            // Halaman 7: Analisis Masalah
            $mpdf->AddPage();
            $mpdf->WriteHTML(view('exports.skm-report-analisis-masalah', $data)->render());

            // Halaman 8: Tren SKM
            $mpdf->AddPage();
            $mpdf->WriteHTML(view('exports.skm-report-tren', $data)->render());

            // Halaman 9: Hasil Tindak Lanjut (Portrait untuk tabel dengan gambar)
            $mpdf->AddPage('P'); // Portrait untuk tabel dengan gambar
            $mpdf->WriteHTML(view('exports.skm-report-tindak-lanjut', $data)->render());

            // Halaman 10: Rencana Tindak Lanjut Landscape
            $mpdf->AddPage('L');
            $mpdf->WriteHTML(view('exports.skm-report-tindak-lanjut-landscape', $data)->render());

            // Halaman 11: Kesimpulan
            $mpdf->AddPage(orientation: 'P');
            $mpdf->WriteHTML(view('exports.skm-report-kesimpulan', $data)->render());

            // Halaman 12: Lampiran
            $mpdf->AddPage();
            $mpdf->WriteHTML(view('exports.skm-report-lampiran', $data)->render());

            // 10. Output dan Simpan PDF
            $opdName = Str::slug($user->nama_opd, '_');
            $fileName = "SKM_TW{$validated['triwulan']}_{$validated['tahun']}_{$opdName}_" . time() . ".pdf";
            $filePath = 'laporan/skm/' . $fileName;

            // Simpan ke storage
            $mpdf->Output(storage_path('app/public/' . $filePath), 'F');

            Log::info('PDF generated with mPDF: ' . $filePath);

            // 11. Simpan ke tabel laporan
            $laporan = Laporan::create([
                'id_user' => $user->id_user,
                'judul' => "Laporan SKM Triwulan {$validated['triwulan']} Tahun {$validated['tahun']} - {$user->nama_opd}",
                'kategori' => 'Laporan SKM',
                'file_path' => $filePath,
                'tanggal_upload' => now(),
                'status' => 'Diproses',
                'periode_triwulan' => $validated['triwulan'],
                'periode_tahun' => $validated['tahun'],
                'id_skm_report' => $skmReport->id_skm_report,
            ]);

            // 12. Redirect dengan success
            return redirect()->route('pelayanan-publik.index')
                ->with([
                    'success' => 'Laporan SKM berhasil digenerate!',
                    'new_skm_id' => $laporan->id_laporan,
                    'active_tab' => 'skm-list'
                ]);

        } catch (\Exception $e) {
            Log::error('Exception in generateSkm: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());

            return back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Resize image untuk PDF (agar tidak terlalu besar)
     */
    private function resizeImageForPDF($imagePath, $maxWidth = 500, $maxHeight = 400)
    {
        try {
            if (!file_exists($imagePath)) {
                return null;
            }

            $imageInfo = getimagesize($imagePath);
            if (!$imageInfo) {
                return null;
            }

            $type = $imageInfo[2];
            
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($imagePath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($imagePath);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($imagePath);
                    break;
                default:
                    return null;
            }

            $width = imagesx($image);
            $height = imagesy($image);

            // Hitung rasio resize
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = $width * $ratio;
            $newHeight = $height * $ratio;

            // Buat gambar baru
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Untuk PNG, pertahankan transparansi
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resize gambar
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // Simpan ke buffer
            ob_start();
            
            switch ($type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($newImage, null, 85); // 85% quality
                    break;
                case IMAGETYPE_PNG:
                    imagepng($newImage, null, 8); // Compression level 8
                    break;
                case IMAGETYPE_GIF:
                    imagegif($newImage);
                    break;
            }
            
            $imageData = ob_get_contents();
            ob_end_clean();

            // Clean up
            imagedestroy($image);
            imagedestroy($newImage);

            return $imageData;

        } catch (\Exception $e) {
            Log::error('Error resizing image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate chart image untuk grafik batang
     */
    private function generateChartImage($rerataIkm, $warnaGrafik, $opdName, $triwulan, $tahun)
    {
        try {
            // Label unsur
            $unsurLabels = [
                'Persyaratan',
                'Prosedur',
                'Waktu',
                'Biaya',
                'Produk',
                'Kompetensi',
                'Perilaku',
                'Aduan',
                'Sarpras'
            ];

            // Dimensi gambar
            $width = 1000;
            $height = 550;

            // Create image
            $image = imagecreatetruecolor($width, $height);

            // Parse warna
            $color = $warnaGrafik;
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
                $color = '#4A90E2';
            }

            $r = hexdec(substr($color, 1, 2));
            $g = hexdec(substr($color, 3, 2));
            $b = hexdec(substr($color, 5, 2));

            // Define colors
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $gray = imagecolorallocate($image, 200, 200, 200);
            $lightGray = imagecolorallocate($image, 245, 245, 245);
            $darkGray = imagecolorallocate($image, 100, 100, 100);
            $barColor = imagecolorallocate($image, $r, $g, $b);
            $barBorderColor = imagecolorallocate(
                $image,
                max(0, $r - 30),
                max(0, $g - 30),
                max(0, $b - 30)
            );
            
            // Warna untuk font bold
            $boldGray = imagecolorallocate($image, 60, 60, 60);

            // Fill background
            imagefilledrectangle($image, 0, 0, $width, $height, $white);

            // Chart area
            $chartX1 = 100;
            $chartY1 = 60;
            $chartWidth = $width - $chartX1 - 180;
            $chartHeight = $height - $chartY1 - 120;

            // Draw chart background
            imagefilledrectangle(
                $image,
                $chartX1,
                $chartY1,
                $chartX1 + $chartWidth,
                $chartY1 + $chartHeight,
                $lightGray
            );

            // Draw grid lines
            $gridLines = 5;
            for ($i = 0; $i <= $gridLines; $i++) {
                $y = $chartY1 + ($i * ($chartHeight / $gridLines));
                imageline($image, $chartX1, $y, $chartX1 + $chartWidth, $y, $gray);

                // Y-axis labels dengan font 12 (simulasi dengan font size 4)
                $value = 100 - ($i * 20);
                $labelText = $value . '%';
                $textWidth = imagefontwidth(4) * strlen($labelText);
                imagestring($image, 4, $chartX1 - $textWidth - 10, $y - 8, $labelText, $darkGray);
            }

            // Draw axes
            imageline($image, $chartX1, $chartY1, $chartX1, $chartY1 + $chartHeight, $black);
            imageline(
                $image,
                $chartX1,
                $chartY1 + $chartHeight,
                $chartX1 + $chartWidth,
                $chartY1 + $chartHeight,
                $black
            );

            // Calculate bar dimensions
            $barCount = count($rerataIkm);
            $barWidth = ($chartWidth / $barCount) * 0.5;
            $barSpacing = ($chartWidth / $barCount) * 0.5;

            // Draw each bar
            foreach ($rerataIkm as $index => $value) {
                $barHeight = ($value / 100) * $chartHeight;
                $x1 = $chartX1 + ($index * ($barWidth + $barSpacing)) + ($barSpacing / 2);
                $y1 = $chartY1 + $chartHeight - $barHeight;
                $x2 = $x1 + $barWidth;
                $y2 = $chartY1 + $chartHeight;

                // Draw bar
                imagefilledrectangle($image, $x1, $y1, $x2, $y2, $barColor);
                imagerectangle($image, $x1, $y1, $x2, $y2, $barBorderColor);

                // Draw value on top dengan font 12 (size 4)
                $valueText = number_format($value, 1);
                $textWidth = imagefontwidth(4) * strlen($valueText);
                $textX = $x1 + (($barWidth - $textWidth) / 2);
                $textY = $y1 - 30;

                // Background for value
                imagefilledrectangle(
                    $image,
                    $textX - 5,
                    $textY - 4,
                    $textX + $textWidth + 5,
                    $textY + 12,
                    $white
                );
                imagerectangle(
                    $image,
                    $textX - 5,
                    $textY - 4,
                    $textX + $textWidth + 5,
                    $textY + 12,
                    $darkGray
                );

                // Value text dengan font 12
                imagestring($image, 4, $textX, $textY, $valueText, $black);

                // Label di bawah dengan font italic miring
                $label = $unsurLabels[$index];
                
                // Untuk membuat efek bold pada KOMPETENSI
                $labelColor = ($label === 'Kompetensi') ? $boldGray : $black;
                
                // Font size untuk label (size 4 = ~12pt)
                $fontSize = 4;
                
                // Split label panjang menjadi 2 baris
                if (strlen($label) > 8) {
                    $words = explode(' ', $label);
                    $line1 = $words[0] ?? $label;
                    $line2 = implode(' ', array_slice($words, 1)) ?? '';
                    
                    // Hitung posisi untuk dua baris
                    $line1Width = imagefontwidth($fontSize) * strlen($line1);
                    $line2Width = $line2 ? imagefontwidth($fontSize) * strlen($line2) : 0;
                    
                    $labelX1 = $x1 + (($barWidth - $line1Width) / 2);
                    $labelX2 = $line2 ? ($x1 + (($barWidth - $line2Width) / 2)) : 0;
                    
                    // Gambar dua baris dengan efek italic (diputar sedikit)
                    // Line 1
                    for ($i = -1; $i <= 1; $i++) {
                        imagestring($image, $fontSize, $labelX1 + $i, $y2 + 15, $line1, $labelColor);
                    }
                    imagestring($image, $fontSize, $labelX1, $y2 + 15, $line1, $labelColor);
                    
                    // Line 2 jika ada
                    if ($line2) {
                        for ($i = -1; $i <= 1; $i++) {
                            imagestring($image, $fontSize, $labelX2 + $i, $y2 + 32, $line2, $labelColor);
                        }
                        imagestring($image, $fontSize, $labelX2, $y2 + 32, $line2, $labelColor);
                    }
                } else {
                    // Label pendek, satu baris saja
                    $labelWidth = imagefontwidth($fontSize) * strlen($label);
                    $labelX = $x1 + (($barWidth - $labelWidth) / 2);
                    
                    // Efek italic (miring) dengan menggambar beberapa kali dengan offset
                    for ($i = -1; $i <= 1; $i++) {
                        imagestring($image, $fontSize, $labelX + $i, $y2 + 20, $label, $labelColor);
                    }
                    imagestring($image, $fontSize, $labelX, $y2 + 20, $label, $labelColor);
                    
                    // Jika ini KOMPETENSI, tambahkan efek bold ekstra
                    if ($label === 'Kompetensi') {
                        // Tambahkan outline lebih tebal
                        for ($i = -2; $i <= 2; $i++) {
                            for ($j = -2; $j <= 2; $j++) {
                                if ($i != 0 || $j != 0) {
                                    imagestring($image, $fontSize, $labelX + $i, $y2 + 20 + $j, $label, $labelColor);
                                }
                            }
                        }
                    }
                }
            }

            // Draw legend box
            $legendX = $chartX1 + $chartWidth + 30;
            $legendY = $chartY1 + 50;
            $legendWidth = 200;
            $legendHeight = 120;

            // Legend background
            imagefilledrectangle(
                $image,
                $legendX,
                $legendY,
                $legendX + $legendWidth,
                $legendY + $legendHeight,
                $white
            );
            imagerectangle(
                $image,
                $legendX,
                $legendY,
                $legendX + $legendWidth,
                $legendY + $legendHeight,
                $darkGray
            );

            // Legend content
            $legendTitle = "KETERANGAN";
            $legendTitleWidth = imagefontwidth(4) * strlen($legendTitle);
            $legendTitleX = $legendX + (($legendWidth - $legendTitleWidth) / 2);
            imagestring($image, 4, $legendTitleX, $legendY + 10, $legendTitle, $black);

            // Color box
            $boxSize = 18;
            imagefilledrectangle(
                $image,
                $legendX + 15,
                $legendY + 35,
                $legendX + 15 + $boxSize,
                $legendY + 35 + $boxSize,
                $barColor
            );
            imagerectangle(
                $image,
                $legendX + 15,
                $legendY + 35,
                $legendX + 15 + $boxSize,
                $legendY + 35 + $boxSize,
                $barBorderColor
            );

            // Legend text
            imagestring($image, 4, $legendX + 45, $legendY + 38, "Rerata IKM", $black);

            // Additional info
            $average = array_sum($rerataIkm) / count($rerataIkm);
            $mutu = $this->getMutuCategory($average);
            
            // Format nilai rata-rata
            imagestring($image, 4, $legendX + 15, $legendY + 65, "Rata-rata:", $black);
            imagestring($image, 4, $legendX + 90, $legendY + 65, number_format($average, 1), $boldGray);
            
            imagestring($image, 4, $legendX + 15, $legendY + 85, "Mutu:", $black);
            
            // Tampilkan mutu dengan warna sesuai nilai
            $mutuColor = $mutu == 'A' ? imagecolorallocate($image, 0, 128, 0) :
                        ($mutu == 'B' ? imagecolorallocate($image, 0, 0, 255) :
                        ($mutu == 'C' ? imagecolorallocate($image, 255, 165, 0) :
                        imagecolorallocate($image, 255, 0, 0)));
                        
            imagestring($image, 5, $legendX + 90, $legendY + 82, $mutu, $mutuColor);
            
            // Info triwulan
            imagestring($image, 4, $legendX + 15, $legendY + 105, "Triwulan:", $black);
            imagestring($image, 4, $legendX + 90, $legendY + 105, $triwulan . "/" . $tahun, $boldGray);

            // Subtitle OPD dengan font lebih kecil
            $subTitle = $opdName;
            $subTitleWidth = imagefontwidth(3) * strlen($subTitle);
            $subTitleX = ($width - $subTitleWidth) / 2;
            imagestring($image, 3, $subTitleX, 25, $subTitle, $darkGray);

            // Save image
            $fileName = 'chart_skm_' . $triwulan . '_' . $tahun . '_' . time() . '.png';
            $folderPath = 'images/charts/';

            // Create directory
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0777, true);
            }

            $filePath = public_path($folderPath . $fileName);
            imagepng($image, $filePath, 8);
            imagedestroy($image);

            return $folderPath . $fileName;

        } catch (\Exception $e) {
            \Log::error('Error generating chart: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate tren chart image (LINE CHART untuk data tren tahunan)
     */
    private function generateTrenChartImage($trenSkm, $warnaGrafik, $opdName)
    {
        if (empty($trenSkm)) {
            return null;
        }

        try {
            // Ambil data dari array tren
            $years = array_column($trenSkm, 'tahun');
            $ikmValues = array_column($trenSkm, 'ikm');
            
            // Dimensi gambar
            $width = 800;
            $height = 450;

            // Create image
            $image = imagecreatetruecolor($width, $height);

            // Parse warna
            $color = $warnaGrafik;
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
                $color = '#4A90E2';
            }

            $r = hexdec(substr($color, 1, 2));
            $g = hexdec(substr($color, 3, 2));
            $b = hexdec(substr($color, 5, 2));

            // Define colors
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $gray = imagecolorallocate($image, 200, 200, 200);
            $lightGray = imagecolorallocate($image, 245, 245, 245);
            $darkGray = imagecolorallocate($image, 100, 100, 100);
            $lineColor = imagecolorallocate($image, $r, $g, $b);
            $pointColor = imagecolorallocate($image, 
                min(255, $r + 30), 
                min(255, $g + 30), 
                min(255, $b + 30)
            );
            
            // Fill background
            imagefilledrectangle($image, 0, 0, $width, $height, $white);
            
            // Chart area dimensions
            $marginTop = 60;
            $marginBottom = 80;
            $marginLeft = 80;
            $marginRight = 180;
            
            $chartWidth = $width - $marginLeft - $marginRight;
            $chartHeight = $height - $marginTop - $marginBottom;
            
            // Draw chart background
            imagefilledrectangle($image, 
                $marginLeft, $marginTop, 
                $marginLeft + $chartWidth, $marginTop + $chartHeight, 
                $lightGray
            );
            
            // Draw grid lines horizontal
            $gridLines = 5;
            for ($i = 0; $i <= $gridLines; $i++) {
                $y = $marginTop + ($i * ($chartHeight / $gridLines));
                imageline($image, $marginLeft, $y, $marginLeft + $chartWidth, $y, $gray);
                
                // Y-axis labels
                $value = 100 - ($i * 20);
                $labelText = $value . '%';
                $textWidth = imagefontwidth(2) * strlen($labelText);
                imagestring($image, 2, $marginLeft - $textWidth - 10, $y - 5, $labelText, $darkGray);
            }
            
            // Draw axes
            imageline($image, $marginLeft, $marginTop, $marginLeft, $marginTop + $chartHeight, $black);
            imageline($image, $marginLeft, $marginTop + $chartHeight, $marginLeft + $chartWidth, $marginTop + $chartHeight, $black);
            
            // Plot data points and lines
            $pointCount = count($years);
            $previousX = null;
            $previousY = null;
            
            for ($i = 0; $i < $pointCount; $i++) {
                // Calculate X position
                $x = $marginLeft + ($i * ($chartWidth / ($pointCount - 1)));
                
                // Calculate Y position
                $value = $ikmValues[$i];
                $y = $marginTop + $chartHeight - (($value / 100) * $chartHeight);
                
                // Draw connecting line
                if ($previousX !== null && $previousY !== null) {
                    imageline($image, $previousX, $previousY, $x, $y, $lineColor);
                }
                
                // Draw data point
                imagefilledellipse($image, $x, $y, 12, 12, $pointColor);
                imageellipse($image, $x, $y, 12, 12, $black);
                
                // Draw value label
                $valueText = number_format($value, 1);
                $textWidth = imagefontwidth(2) * strlen($valueText);
                $textX = $x - ($textWidth / 2);
                $textY = $y - 20;
                
                // Background untuk nilai
                imagefilledrectangle($image, 
                    $textX - 3, $textY - 2,
                    $textX + $textWidth + 3, $textY + 10,
                    $white
                );
                imagerectangle($image, 
                    $textX - 3, $textY - 2,
                    $textX + $textWidth + 3, $textY + 10,
                    $darkGray
                );
                
                // Nilai
                imagestring($image, 2, $textX, $textY, $valueText, $black);
                
                // Draw year label
                $yearLabel = $years[$i];
                $labelWidth = imagefontwidth(2) * strlen($yearLabel);
                $labelX = $x - ($labelWidth / 2);
                imagestring($image, 2, $labelX, $marginTop + $chartHeight + 10, $yearLabel, $black);
                
                $previousX = $x;
                $previousY = $y;
            }
            
            // Title
            $title = "Tren Nilai IKM " . $opdName . " (" . min($years) . " - " . max($years) . ")";
            $titleWidth = imagefontwidth(4) * strlen($title);
            $titleX = ($width - $titleWidth) / 2;
            imagestring($image, 4, $titleX, 15, $title, $black);
            
            // Draw legend box
            $legendX = $marginLeft + $chartWidth + 20;
            $legendY = $marginTop;
            $legendWidth = 150;
            $legendHeight = 120;
            
            // Legend background
            imagefilledrectangle($image, $legendX, $legendY, 
                               $legendX + $legendWidth, $legendY + $legendHeight, 
                               $white);
            imagerectangle($image, $legendX, $legendY, 
                          $legendX + $legendWidth, $legendY + $legendHeight, 
                          $darkGray);
            
            // Legend title
            imagestring($image, 3, $legendX + 10, $legendY + 10, "KETERANGAN:", $black);
            
            // Line sample
            imageline($image, $legendX + 10, $legendY + 35, $legendX + 40, $legendY + 35, $lineColor);
            
            // Point sample
            imagefilledellipse($image, $legendX + 25, $legendY + 35, 10, 10, $pointColor);
            imageellipse($image, $legendX + 25, $legendY + 35, 10, 10, $black);
            
            // Legend text
            imagestring($image, 2, $legendX + 45, $legendY + 30, "Tren IKM", $black);
            
            // Additional info
            $average = array_sum($ikmValues) / count($ikmValues);
            $trendDirection = end($ikmValues) > reset($ikmValues) ? "Naik" : 
                            (end($ikmValues) < reset($ikmValues) ? "Turun" : "Stabil");
            
            imagestring($image, 2, $legendX + 10, $legendY + 50, "Rata-rata: " . number_format($average, 1), $black);
            imagestring($image, 2, $legendX + 10, $legendY + 65, "Tren: " . $trendDirection, $black);
            imagestring($image, 2, $legendX + 10, $legendY + 80, "Periode: " . min($years) . "-" . max($years), $black);
            imagestring($image, 2, $legendX + 10, $legendY + 95, "Data: " . $pointCount . " tahun", $black);
            
            // Save image
            $fileName = 'tren_chart_' . time() . '_' . uniqid() . '.png';
            $folderPath = 'images/charts/';
            
            // Create directory if not exists
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0777, true);
            }
            
            $filePath = public_path($folderPath . $fileName);
            imagepng($image, $filePath, 8);
            imagedestroy($image);
            
            Log::info('Tren chart generated: ' . $filePath);
            
            return $folderPath . $fileName;

        } catch (\Exception $e) {
            Log::error('Error generating trend chart: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Helper untuk menentukan kategori mutu
     */
    private function getMutuCategory($value)
    {
        if ($value >= 90)
            return 'A';
        if ($value >= 80)
            return 'B';
        if ($value >= 70)
            return 'C';
        return 'D';
    }

    /**
     * Get SKM detail for AJAX request
     */
    public function getSkmDetail($id)
    {
        try {
            $laporan = Laporan::with('skmReport')->findOrFail($id);

            if ($laporan->id_user != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $data = [
                'id' => $laporan->id_laporan,
                'judul' => $laporan->judul,
                'tanggal_upload' => $laporan->tanggal_upload->format('d M Y H:i'),
                'status' => $laporan->status,
                'triwulan' => $laporan->periode_triwulan,
                'tahun' => $laporan->periode_tahun,
                'file_path' => $laporan->file_path,
            ];

            if ($laporan->skmReport) {
                $data['anggota_tim'] = json_decode($laporan->skmReport->anggota_tim, true) ?? [];
                $data['rencana_tindak_lanjut'] = json_decode($laporan->skmReport->rencana_tindak_lanjut_analisis, true) ?? [];
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getSkmDetail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching SKM detail'
            ], 500);
        }
    }
}