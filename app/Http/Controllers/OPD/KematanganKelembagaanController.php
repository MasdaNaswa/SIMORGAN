<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\EvaluasiKemendagri;
use App\Models\EvaluasiKemenpan;

class KematanganKelembagaanController extends Controller
{
    /**
     * List OPD tanpa UPTD
     */
    private $opdTanpaUPTD = [
        'Sekretariat Daerah',
        'Sekretariat DPRD',
        'Inspektorat Daerah',
        'Dinas Pendidikan dan Kebudayaan',
        'Rumah Sakit Umum Daerah Muhammad Sani',
        'Rumah Sakit Umum Daerah Tanjung Batu Kundur',
        'Dinas Perumahan Rakyat dan Kawasan Pemukiman',
        'Dinas Sosial',
        'Dinas Kependudukan dan Pencatatan Sipil',
        'Dinas Pemberdayaan Masyarakat dan Desa',
        'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
        'Dinas Kepemudaan dan Olahraga',
        'Dinas Pariwisata',
        'Dinas Perpustakaan dan Kearsipan',
        'Dinas Tenaga Kerja dan Perindustrian',
        'Diskominfo',
        'Satuan Polisi Pamong Praja',
        'Badan Perencanaan, Penelitian dan Pengembangan',
        'Badan Pengelola Keuangan dan Aset Daerah',
        'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia',
        'Badan Kesatuan Bangsa dan Politik',
        'Badan Penanggulangan Bencana Daerah dan Pemadam Kebakaran',
        'Kecamatan Karimun',
        'Kecamatan Tebing',
        'Kecamatan Meral',
        'Kecamatan Meral Barat',
        'Kecamatan Buru',
        'Kecamatan Kundur',
        'Kecamatan Kundur Barat',
        'Kecamatan Kundur Utara',
        'Kecamatan Belat',
        'Kecamatan Ungar',
        'Kecamatan Moro',
        'Kecamatan Durai',
        'Kecamatan Selat Gelam',
        'Kecamatan Sugie Besar'
    ];

    /**
     * Tampilkan halaman utama survei kematangan kelembagaan
     */
    public function index()
    {
        $user = Auth::user();
        $currentMonth = date('m');
        $currentYear = date('Y');

        $evaluasiKemendagri = EvaluasiKemendagri::where('id_user', $user->id_user)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->latest()
            ->first();

        $evaluasiKemenpan = EvaluasiKemenpan::where('id_user', $user->id_user)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->latest()
            ->first();

        $progressKemenpan = $evaluasiKemenpan ? $this->hitungProgressKemenpan($evaluasiKemenpan) : null;
        $progressKemendagri = $evaluasiKemendagri ? $this->hitungProgressKemendagri($evaluasiKemendagri) : null;

        return view('opd.kematangan.index', compact(
            'evaluasiKemendagri',
            'evaluasiKemenpan',
            'progressKemenpan',
            'progressKemendagri',
            'user'
        ));
    }

    /**
     * Submit form KemenPAN-RB
     */
    public function submitKemenpan(Request $request)
    {
        try {
            $user = Auth::user();
            $namaOPD = $request->nama_opd ?? $user->nama_opd;

            // ===============================
            // RULES VALIDASI YANG DIPERBAIKI
            // ===============================
            $rules = [
                'nama_opd' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ];

            // Struktur 1-32
            for ($i = 1; $i <= 32; $i++) {
                // Perbaikan: OPD tanpa UPTD tidak perlu mengisi struktur 8 dan 9
                if (in_array($i, [8, 9]) && in_array($namaOPD, $this->opdTanpaUPTD)) {
                    // Opsional - tidak perlu validasi required
                    $rules["struktur_$i"] = 'nullable|in:Sangat Setuju,Setuju,Tidak Setuju,Sangat Tidak Setuju,Tidak Diisi';
                } else {
                    $rules["struktur_$i"] = 'required|in:Sangat Setuju,Setuju,Tidak Setuju,Sangat Tidak Setuju';
                }
            }

            // Proses 1-30 selalu wajib
            for ($i = 1; $i <= 30; $i++) {
                $rules["proses_$i"] = 'required|in:Sangat Setuju,Setuju,Tidak Setuju,Sangat Tidak Setuju';
            }

            // ===============================
            // VALIDASI
            // ===============================
            $validated = $request->validate($rules);

            // ===============================
            // Cek apakah sudah isi bulan ini
            // ===============================
            $currentMonth = date('m');
            $currentYear = date('Y');

            $existing = EvaluasiKemenpan::where('id_user', $user->id_user)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->with('warning', 'Anda sudah mengisi evaluasi Kemenpan.');
            }

            // ===============================
            // SIMPAN DATA
            // ===============================
            $evaluasi = new EvaluasiKemenpan();
            $evaluasi->id_user = $user->id_user;
            $evaluasi->nama_opd = $request->nama_opd;
            $evaluasi->email = $request->email;

            // Simpan jawaban struktur dengan nilai default untuk OPD tanpa UPTD
            for ($i = 1; $i <= 32; $i++) {
                $value = $request->{"struktur_$i"} ?? null;

                if (in_array($i, [8, 9]) && in_array($namaOPD, $this->opdTanpaUPTD)) {
                    // Untuk OPD tanpa UPTD, set ke NULL jika "Tidak Diisi"
                    $evaluasi->{"struktur_$i"} = ($value === 'Tidak Diisi') ? null : $value;
                } else {
                    $evaluasi->{"struktur_$i"} = $value;
                }
            }

            // Simpan jawaban Proses
            for ($i = 1; $i <= 30; $i++) {
                $evaluasi->{"proses_$i"} = $request->{"proses_$i"} ?? 'Tidak Diisi';
            }

            // Hitung skor
            if (method_exists($evaluasi, 'hitungSkor')) {
                $totalSkor = $evaluasi->hitungSkor();
                $evaluasi->total_skor = $totalSkor;
            }

            $evaluasi->status = 'Diproses';
            $evaluasi->save();

            $interpretasi = method_exists($evaluasi, 'getInterpretasi') ? $evaluasi->getInterpretasi() : [];

            return redirect()->route('kematangan.index')
                ->with('success', 'Evaluasi Kemenpan berhasil dikirim!')
                ->with('skor', [
                    'total' => $evaluasi->total_skor ?? null,
                    'level' => $interpretasi['level'] ?? null,
                    'deskripsi' => $interpretasi['deskripsi'] ?? null
                ]);

        } catch (\Exception $e) {
            \Log::error('Error submit Kemenpan: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Submit form Kemendagri
     */
    /**
 * Submit form Kemendagri
 */
public function submitKemendagri(Request $request)
{
    try {
        $user = Auth::user();
        
        // DEBUG: Lihat semua input yang diterima
        \Log::info('=== DEBUG SUBMIT KEMENDAGRI ===');
        \Log::info('Request data:', $request->all());
        \Log::info('Files received:', array_keys($request->allFiles()));
        
        // ===============================
        // 1. VALIDASI DASAR
        // ===============================
        $rules = [
            'nama_opd' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];

        // Tambahkan validasi untuk 11 variabel
        $variabels = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x', 'xi'];
        foreach ($variabels as $var) {
            $rules["variabel_{$var}"] = 'required|in:Tingkat I,Tingkat II,Tingkat III,Tingkat IV,Tingkat V';
        }

        $request->validate($rules);

        // ===============================
        // 2. VALIDASI FILE - PERBAIKI NAMA INPUT
        // ===============================
        foreach ($variabels as $var) {
            // PERBAIKAN: Gunakan underscore (_) bukan dash (-)
            $inputName = "variabel_{$var}_files";
            \Log::info("Checking for files: $inputName");
            
            if ($request->hasFile($inputName)) {
                $files = $request->file($inputName);
                
                // Pastikan $files adalah array
                if (!is_array($files)) {
                    $files = [$files];
                }
                
                \Log::info("Found " . count($files) . " files for variabel $var");
                
                // Cek jumlah file maksimal 5 per variabel
                if (count($files) > 5) {
                    return back()->withErrors([
                        $inputName => "Maksimal 5 file per variabel. Anda mengupload " . count($files) . " file."
                    ])->withInput();
                }
                
                foreach ($files as $index => $file) {
                    if (!$file->isValid()) {
                        \Log::error("File tidak valid: " . $file->getClientOriginalName());
                        return back()->withErrors([
                            $inputName => "File ke-" . ($index + 1) . " tidak valid."
                        ])->withInput();
                    }
                    
                    // Cek ekstensi file (PDF, JPG, JPEG, PNG)
                    $extension = strtolower($file->getClientOriginalExtension());
                    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                    
                    if (!in_array($extension, $allowedExtensions)) {
                        return back()->withErrors([
                            $inputName => "File untuk Variabel " . strtoupper($var) . " harus PDF, JPG, JPEG, atau PNG saja. Format .$extension tidak diizinkan."
                        ])->withInput();
                    }
                    
                    // Cek ukuran file (maksimal 10MB)
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        return back()->withErrors([
                            $inputName => "File untuk Variabel " . strtoupper($var) . " maksimal 10MB. File Anda: " . round($file->getSize() / 1024 / 1024, 2) . "MB"
                        ])->withInput();
                    }
                }
            } else {
                \Log::info("No files found for variabel $var");
            }
        }

        // ===============================
        // 3. CEK APAKAH SUDAH ISI BULAN INI
        // ===============================
        $currentMonth = date('m');
        $currentYear = date('Y');

        $existing = EvaluasiKemendagri::where('id_user', $user->id_user)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('warning', 'Anda sudah mengisi evaluasi Kemendagri.');
        }

        // ===============================
        // 4. BUAT DATA EVALUASI BARU
        // ===============================
        $evaluasi = new EvaluasiKemendagri();
        $evaluasi->id_user = $user->id_user;
        $evaluasi->nama_opd = $request->nama_opd;
        $evaluasi->email = $request->email;

        // ===============================
        // 5. SIMPAN NILAI VARIABEL I - XI
        // ===============================
        foreach ($variabels as $v) {
            $evaluasi->{"variabel_$v"} = $request->{"variabel_$v"} ?? 'Tingkat I';
        }

        // ===============================
        // 6. UPLOAD FILE KE STORAGE - PERBAIKI NAMA INPUT
        // ===============================
        \Log::info('=== MEMULAI UPLOAD FILE ===');
        
        foreach ($variabels as $v) {
            // PERBAIKAN: Gunakan underscore (_) bukan dash (-)
            $inputName = "variabel_{$v}_files";
            $uploadedCount = 0;
            
            \Log::info("Processing files for variabel $v");

            if ($request->hasFile($inputName)) {
                $files = $request->file($inputName);
                
                // Pastikan $files adalah array
                if (!is_array($files)) {
                    $files = [$files];
                }
                
                \Log::info("Files to upload for $v: " . count($files));

                foreach ($files as $index => $file) {
                    // Hanya simpan 3 file pertama
                    if ($uploadedCount < 3) {
                        try {
                            // Generate nama file yang unik
                            $filename = time() . '_' . $v . '_' . ($index + 1) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            
                            // Path untuk storage
                            $storagePath = "kemendagri/{$user->id_user}/{$v}/{$filename}";
                            
                            \Log::info("Storing file: $filename to path: $storagePath");
                            
                            // Simpan file ke storage public
                            $path = $file->storeAs(
                                "kemendagri/{$user->id_user}/{$v}",
                                $filename,
                                'public'
                            );
                            
                            \Log::info("File stored successfully: $path");
                            
                            // Verifikasi file tersimpan
                            $exists = Storage::disk('public')->exists($path);
                            \Log::info("File verification: " . ($exists ? 'SUCCESS' : 'FAILED'));
                            
                            if (!$exists) {
                                \Log::error("File failed to save: $path");
                            } else {
                                // Simpan path ke database
                                $column = "file_path_{$v}_" . ($uploadedCount + 1);
                                $evaluasi->$column = $path;
                                
                                $uploadedCount++;
                                \Log::info("Saved to column: $column with path: $path");
                            }
                            
                        } catch (\Exception $e) {
                            \Log::error("Error uploading file for variabel $v: " . $e->getMessage());
                            \Log::error($e->getTraceAsString());
                            continue;
                        }
                    } else {
                        \Log::info("Skipping file $index for $v - already have 3 files");
                    }
                }
            }
            
            // Kosongkan sisa kolom file jika kurang dari 3
            for ($i = $uploadedCount + 1; $i <= 3; $i++) {
                $evaluasi->{"file_path_{$v}_$i"} = '';
            }
            
            \Log::info("Finished processing $v. Uploaded: $uploadedCount files");
        }

        // ===============================
        // 7. HITUNG SKOR & TENTUKAN MATURITAS
        // ===============================
        $totalSkor = $evaluasi->hitungSkor();
        $evaluasi->total_skor = $totalSkor;
        $evaluasi->tingkat_maturitas = $evaluasi->tentukanPeringkatKomposit($totalSkor);
        $evaluasi->status = 'Diproses'; // Tambahkan status

        // ===============================
        // 8. DEBUG DATA SEBELUM DISIMPAN
        // ===============================
        \Log::info('=== DATA YANG AKAN DISIMPAN ===');
        \Log::info('Total Skor: ' . $evaluasi->total_skor);
        \Log::info('Tingkat Maturitas: ' . $evaluasi->tingkat_maturitas);
        \Log::info('File Paths:');
        
        foreach ($variabels as $v) {
            for ($i = 1; $i <= 3; $i++) {
                $path = $evaluasi->{"file_path_{$v}_$i"};
                if (!empty($path)) {
                    \Log::info("  Variabel $v, file $i: $path");
                }
            }
        }

        // ===============================
        // 9. SIMPAN KE DATABASE
        // ===============================
        $saved = $evaluasi->save();
        
        if (!$saved) {
            \Log::error('Gagal menyimpan evaluasi ke database');
            throw new \Exception('Gagal menyimpan data evaluasi');
        }

        \Log::info('=== EVALUASI BERHASIL DISIMPAN ===');
        \Log::info('ID Evaluasi: ' . $evaluasi->id_evaluasi_kemendagri);
        \Log::info('Created At: ' . $evaluasi->created_at);

        // ===============================
        // 10. REDIRECT DENGAN PESAN SUKSES
        // ===============================
        return redirect()->route('kematangan.index')
            ->with('success', 'Evaluasi Kemendagri berhasil dikirim!')
            ->with('peringkat', [
                'skor' => $evaluasi->total_skor,
                'tingkat' => $evaluasi->tingkat_maturitas,
                'deskripsi' => $evaluasi->getDeskripsiPeringkat()
            ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error: ' . json_encode($e->errors()));
        return back()->withErrors($e->validator)->withInput();
            
    } catch (\Exception $e) {
        \Log::error('Error submit Kemendagri: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
}

    private function hitungProgressKemenpan($evaluasi)
    {
        return [
            'total_skor' => $evaluasi->total_skor,
            'skor_struktur' => $evaluasi->skor_struktur,
            'skor_proses' => $evaluasi->skor_proses,
            'interpretasi' => $evaluasi->getInterpretasi(),
            'detail' => $evaluasi->getDetailPerhitungan(),
            'created_at' => $evaluasi->created_at,
            'status' => $evaluasi->status
        ];
    }

    private function hitungProgressKemendagri($evaluasi)
    {
        return [
            'total_skor' => $evaluasi->total_skor,
            'tingkat_maturitas' => $evaluasi->tingkat_maturitas,
            'status' => $evaluasi->status,
            'files' => $evaluasi->getAllFilePaths(),
            'created_at' => $evaluasi->created_at
        ];
    }

    public function showKemenpan($id)
    {
        $evaluasi = EvaluasiKemenpan::findOrFail($id);
        $interpretasi = $evaluasi->getInterpretasi();
        return view('opd.kematangan.show-kemenpan', compact('evaluasi', 'interpretasi'));
    }

    public function showKemendagri($id)
    {
        $evaluasi = EvaluasiKemendagri::findOrFail($id);
        return view('opd.kematangan.show-kemendagri', compact('evaluasi'));
    }
}