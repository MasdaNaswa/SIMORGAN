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
 * Tampilkan halaman form survei KemenPAN 
 */
public function kemenpanForm()
{
    $user = Auth::user();
    $isOPDTanpaUPTD = in_array($user->nama_opd, $this->opdTanpaUPTD);
    
    // Daftar pertanyaan Struktur (32 pertanyaan)
    $questionsStruktur = [
        "Desain organisasi yang ada saat ini perlu disesuaikan dengan ketentuan peraturan perundang-undangan.",
        "Terdapat indikasi bahwa desain organisasi yang ada bersifat kompleks.",
        "Terdapat indikasi bahwa desain organisasi yang ada bersifat sederhana.",
        "Tingkatan unit organisasi yang ada saat ini perlu disesuaikan tugas dan fungsinya dari tingkatan unit organisasi paling atas sampai paling bawah.",
        "Terdapat indikasi adanya tingkatan unit organisasi yang tugas dan fungsinya bersifat umum.",
        "Terdapat indikasi adanya tingkatan unit organisasi yang tugas dan fungsinya bersifat spesifik.",
        "Penataan perangkat daerah telah ditetapkan sesuai dengan substansi pewadahan dan/atau perumpunan urusan pemerintahan yang menjadi kewenangan daerah.",
        "Jumlah Cabang Dinas/UPTD yang dibentuk menunjukkan indikasi melebihi kebutuhan. (Hanya untuk OPD yang memiliki UPTD)",
        "Cabang Dinas/UPTD yang dibentuk dinilai secara sinergis mendukung tercapainya tujuan pembentukan organisasi. (Hanya untuk OPD yang memiliki UPTD)",
        "Nomenklatur unit organisasi saat ini perlu disesuaikan dengan tugas dan fungsinya.",
        "Jenjang jabatan yang ada sudah sesuai kebutuhan.",
        "Jumlah jabatan pada setiap tingkatan sudah sesuai kebutuhan.",
        "Jabatan-jabatan fungsional sudah memenuhi kebutuhan.",
        "Penempatan jabatan fungsional mendukung efisiensi dan efektivitas unit operasional.",
        "Tugas dan fungsi unit organisasi yang ada saat ini perlu dirumuskan secara jelas sesuai dengan strategi organisasi dalam peraturan tentang organisasi dan tata kerja.",
        "Mekanisme pelaksanaan tugas dan fungsi serta kewenangan setiap unit kerja dari manajemen tertinggi sampai manajemen menengah ke bawah telah dituangkan secara jelas dalam prosedur formal yang berkekuatan hukum di dalam organisasi.",
        "Mekanisme hubungan antar unit organisasi yang ada saat ini perlu dirumuskan secara jelas sesuai dengan strategi organisasi dalam peraturan tentang organisasi dan tata kerja.",
        "Rencana strategis dituangkan secara jelas di dalam keputusan resmi organisasi.",
        "Kebijakan-kebijakan organisasi selalu dituangkan secara jelas dan tegas di dalam keputusan resmi organisasi.",
        "Seluruh proses kerja telah dituangkan secara sistematis di dalam peraturan tentang standar operasional prosedur.",
        "Standarisasi pelayanan publik telah diformalkan.",
        "Kewenangan pengambilan keputusan yang ada saat ini perlu dirumuskan secara jelas sesuai dengan strategi organisasi.",
        "Setiap tingkatan manajemen dapat mengambil keputusan sesuai dengan kewenangan yang dimiliki.",
        "Terdapat indikasi bahwa tingkatan manajemen yang lebih tinggi mengambil alih keputusan dari kewenangan manajemen yang lebih rendah (di bawahnya).",
        "Terdapat indikasi bahwa tingkatan manajemen yang lebih rendah dapat mengambil keputusan melebihi kewenangannya.",
        "Permasalahan yang bersifat lintas bidang atau sektoral telah dituangkan dalam Keputusan instansi pemerintah guna mencapai kinerja instansi induk.",
        "Permasalahan yang bersifat lintas bidang atau sektoral harus diputuskan oleh manajemen tertinggi dari instansi induk.",
        "Pimpinan utama instansi hanya membuat keputusan-keputusan yang bersifat strategis dan kebijakan.",
        "Pimpinan madya pada tingkat manajemen menengah mempunyai wewenang untuk membuat keputusan-keputusan taktis dan manajerial.",
        "Pimpinan pratama pada unit operasional mempunyai wewenang untuk membuat keputusan-keputusan teknis operasional.",
        "Pendelegasian kewenangan membuat keputusan-keputusan telah diberikan oleh pimpinan instansi kepada pimpinan unit organisasi tingkat menengah.",
        "Pendelegasian wewenang untuk melaksanakan tugas dan fungsi yang bersifat teknis dan operasional telah diberikan kepada pimpinan unit organisasi tingkat menengah ke pimpinan organisasi tingkat bawah."
    ];

    // Daftar pertanyaan Proses (30 pertanyaan)
    $questionsProses = [
        "Seluruh sasaran strategis dari atas sampai bawah terkait dengan visi dan misi organisasi.",
        "Setiap proses kerja dalam Proses Bisnis/SOP memiliki keterkaitan dengan sasaran strategis.",
        "Setiap proses kerja memiliki keterkaitan dengan jabatan dalam struktur organisasi.",
        "Proses kerja unit bawah merupakan penjabaran dari proses kerja unit atas (vertikal).",
        "Keterkaitan proses kerja antar unit telah dipetakan dengan baik.",
        "Koordinasi antar unit kerja telah dilakukan dengan baik.",
        "Keterkaitan proses kerja lintas sektor telah dipetakan.",
        "Koordinasi lintas organisasi telah terlaksana dengan baik.",
        "Struktur Organisasi dan Tata Kerja (SOTK) organisasi dari tingkatan manajemen tertinggi sampai tingkatan menengah ke bawah telah sesuai dengan peraturan perundangan.",
        "Seluruh kepentingan strategis pemangku kepentingan organisasi, mulai dari tingkat manajemen tertinggi sampai tingkat manajemen menengah ke bawah telah dipetakan dengan baik.",
        "Setiap proses kerja yang terkait dengan kebutuhan informasi publik dan tidak bersifat rahasia telah dijalankan secara transparan (transparansi).",
        "Setiap tahapan pekerjaan yang terdapat di dalam proses kerja padaa tingkatan manajemen tertinggi sampai manajemen menengah ke bawah telah memiliki kesesuaian dan kejelasan fungsi, struktur, dan penanggung jawab pekerjaan (akuntabilitas).",
        "Setiap proses kerja telah memiliki sistem dan mekanisme pertanggungjawaban (termasuk pelaporan) yang jelas (tanggung jawab).",
        "Tidak terdapat indikasi intervensi yang signifikan di dalam setiap pelaksanaan proses kerja dalam organisasi, baik pada tingkatan manajemen tertinggi sampai dengan manajemen menengah ke bawah.",
        "Aparat pelaksana proses kerja dapat melaksanakan tugas secara mandiri sesuai dengan kewenangan tugas pokok dan fungsinya masing-masing.",
        "Standar operasional prosedur selalu diperbarui secara periodik.",
        "Standar operasional prosedur sebagian besar (lebih dari 50%) dinilai perlu segera diperbaharui karena sudah tidak relevan dan telah dibuat lebih dari 5 (lima) tahun.",
        "Organisasi selalu melakukan pengembangan terhadap sistem proses kerja.",
        "Terdapat indikasi bahwa organisasi lebih berorientasi pada hal-hal yang bersifat rutinitas dibandingkan dengan hal-hal yang bersifat strategis.",
        "Manajemen risiko organisasi telah diperkenalkan di dalam organisasi.",
        "Organisasi telah memiliki kebijakan manajemen risiko yang memadai.",
        "Risiko-risiko utama organisasi telah diidentifikasi dengan baik.",
        "Risiko-risiko utama organisasi yang telah diidentifikasi belum diukur (peluang terjadinya maupun dampaknya) dengan metode yang memadai.",
        "Organisasi belum melaksanakan kebijakan manajemen risiko.",
        "Organisasi telah memiliki sistem monitoring risiko yang memadai.",
        "Organisasi telah memiliki rancangan arsitektur penerapan Teknologi informasi.",
        "Organisasi telah memiliki kebijakan IT (e-government) yang memadai.",
        "Sebagian besar proses kerja telah memanfaatkan teknologi informasi secara memadai.",
        "Sebagian besar proses kerja masih dilaksanakan secara manual.",
        "Seluruh informasi publik terkait dengan keberadaan dan tupoksi organisasi telah dipublikasikan secara periodik di dalam website organisasi."
    ];

    return view('opd.kematangan.kemenpan', compact('user', 'questionsStruktur', 'questionsProses', 'isOPDTanpaUPTD'));
}

/**
 * Tampilkan halaman form survei Kemendagri
 */
public function kemendagriForm()
{
    $user = Auth::user();
    
    $variabelData = [
        'I' => [
            'title' => 'PERENCANAAN PEMBANGUNAN DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Penentuan kegiatan yang diprioritaskan dalam dokumen perencanaan tahunan (Renja/RKPD) dilakukan tanpa ada kriteria yang terukur.',
                'Tingkat II' => 'Penentuan kegiatan yang diprioritaskan dalam dokumen rencana tahunan dilakukan berdasarkan analisis terhadap hasil (outcome) apa yang akan dicapai kegiatan tersebut',
                'Tingkat III' => 'Penentuan prioritas kegiatan dalam dokumen rencana tahunan dilakukan berdasarkan analisis hasil (outcome) dan analisis kemampuan kegiatan menghasilkan hasil (outcome).',
                'Tingkat IV' => 'Penentuan prioritas kegiatan dilakukan berdasarkan analisis yang membandingkan hasil (outcome) yang akan dicapai antara satu alternatif kegiatan dengan alternatif kegiatan yang lain.',
                'Tingkat V' => 'Penentuan prioritas kegiatan dalam dokumen tahunan dilakukan dengan perbandingan hasil (outcome) antara satu alternatif kegiatan dengan alternatif kegiatan yang lain dan dibantu dengan teknologi informasi.'
            ]
        ],
        'II' => [
            'title' => 'MONITORING DAN PENGENDALIAN PELAKSANAAN TUGAS PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Monitoring dan pengendalian dilakukan dengan cara sederhana dan tidak terstruktur.',
                'Tingkat II' => 'Monitoring dan pengendalian dilakukan secara berkala dengan fokus yang ditentukan.',
                'Tingkat III' => 'Monitoring dan pengendalian dilakukan secara berkala dengan kriteria penyimpangan yang terstandarisasi pada setiap tahap kegiatan.',
                'Tingkat IV' => 'Monitoring dan pengendalian dilakukan secara berkala dengan kriteria penyimpangan yang terstandarisasi dan diikuti dengan umpan balik berupa perbaikan yang terdokumentasi dengan baik',
                'Tingkat V' => 'Monitoring dan pengendalian dilakukan secara sistematis, terstandarisasi termasuk umpan balik yang didukung oleh penggunaan teknologi informasi berbasis internet.'
            ]
        ],
        'III' => [
            'title' => 'PENJAMINAN MUTU LAYANAN PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Tidak ada penjaminan mutu atas produk yang dihasilkan dan atas proses kerja yang dilakukan.',
                'Tingkat II' => 'Penjaminan mutu produk dan proses kerja dilakukan secara berkala namun tidak mempunyai standar mutu produk dan proses yang ditetapkan.',
                'Tingkat III' => 'Mutu produk dan proses sudah distandarisasi dan dilakukan pengujian secara berkala secara internal.',
                'Tingkat IV' => 'Penjaminan mutu produk dan proses sudah distandarisasi serta dilakukan pengukuran/ pengujian secara berkala oleh tenaga yang bersertifikat.',
                'Tingkat V' => 'Penjaminan mutu produk dan proses dilakukan terstandarisasi dan berkala oleh tenaga ahli bersertifikat serta didukung oleh teknologi informasi berbasis internet.'
            ]
        ],
        'IV' => [
            'title' => 'STANDAR OPERASIONAL PROSEDUR (SOP) PELAYANAN PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Tidak ada definisi resmi proses pelaksanaan pekerjaan pada perangkat daerah.',
                'Tingkat II' => 'Definisi proses organisasi sudah dituangkan dalam standar operasi prosedur (SOP).',
                'Tingkat III' => 'Definisi proses organisasi sudah dituangkan ke dalam SOP dan telah dilakukan evaluasi berkala terhadap penerapan SOP.',
                'Tingkat IV' => 'Definisi proses organisasi sudah dituangkan dalam SOP, sudah dievaluasi secara berkala dan dilakukan tindak lanjut terhadap hasil evaluasi penerapan SOP berupa tindakan koreksi atau perbaikan SOP.',
                'Tingkat V' => 'Definisi proses organisasi sudah dituangkan dalam SOP dan sudah dilakukan evaluasi serta tindak lanjut, kemudian disesuaikan dengan kebutuhan/keluhan pelanggan serta didukung oleh teknologi berbasis internet.'
            ]
        ],
        'V' => [
            'title' => 'PENDIDIKAN DAN PELATIHAN APARATUR',
            'tingkat' => [
                'Tingkat I' => 'Belum ada dokumen resmi rencana kebutuhan pendidikan dan pelatihan pada perangkat daerah yang bersangkutan.',
                'Tingkat II' => 'Dokumen rencana kebutuhan pengembangan pegawai sudah tersusun secara parsial untuk jabatan tertentu.',
                'Tingkat III' => 'Dokumen rencana kebutuhan pengembangan pegawai disusun untuk seluruh jabatan.',
                'Tingkat IV' => 'Rencana pengembangan pegawai dievaluasi secara regular dan seluruh pengembangan pegawai sudah dilaksanakan sesuai dengan dokumen rencana pengembangan pegawai yang sudah ditetapkan.',
                'Tingkat V' => 'Hasil (outcome) pengembangan pegawai dievalusi secara regular sebagai umpan balik.'
            ]
        ],
        'VI' => [
            'title' => 'ANALISIS KEBIJAKAN DAN PEMECAHAN MASALAH TUGAS PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Analisis kebijakan dan pemecahan masalah dilakukan secara sederhana dan dengan metode yang tidak terukur.',
                'Tingkat II' => 'Analisis kebijakan yang berdampak ke publik dilakukan oleh tim internal perangkat daerah yang bersangkutan.',
                'Tingkat III' => 'Analisis kebijakan dan pemecahan masalah yang berdampak ke publik dilakukan menggunakan metode/teknik ilmiah oleh tim internal dengan melibatkan instansi pemerintah terkait.',
                'Tingkat IV' => 'Analisis kebijakan dan pemecahan masalah yang bersifat strategis/berdampak ke publik melibatkan tim ahli.',
                'Tingkat V' => 'Analisis kebijakan dan pemecahan masalah strategis/berdampak ke publik melibatkan tim ahli dengan melakukan konsultasi publik dan analisis umpan balik yang terukur dan terdokumentasi.'
            ]
        ],
        'VII' => [
            'title' => 'MANAJEMEN SUMBER DAYA PERALATAN DAN PERLENGKAPAN KERJA YANG TERUKUR',
            'tingkat' => [
                'Tingkat I' => 'Penggunaan sumber daya dilakukan hanya berdasarkan ketentuan formal yang berlaku.',
                'Tingkat II' => 'Penentuan penggunaan input proyek dilakukan berdasarkan analisis kebutuhan bahan/ sumber daya yang sudah ditetapkan.',
                'Tingkat III' => 'Analisis kebutuhan input/sumber daya proyek sudah distandarisasi dengan proses ujicoba secara terbuka dan menggunakan metode ilmiah.',
                'Tingkat IV' => 'Penyediaan sumber daya dalam pelaksanaan proyek dimonitor secara ketat berdasarkan standar input sumber daya, SOP dan prosedur penjaminan mutu produk.',
                'Tingkat V' => 'Penyediaan sumber daya dan pelaksanaan proyek dimonitor secara ketat berdasarkan SOP dan prosedur penjaminan mutu produk dan didukung oleh teknologi informasi berbasis internet.'
            ]
        ],
        'VIII' => [
            'title' => 'MANAJEMEN RESIKO PELAKSANAAN TUGAS APARATUR',
            'tingkat' => [
                'Tingkat I' => 'Belum ada manajemen resiko dalam pelaksanaan tugas pada perangkat daerah.',
                'Tingkat II' => 'Sudah ada sebagian pegawai yang melakukan analisis resiko dalam pelaksanaan tugasnya, namun hanya bersifat individu.',
                'Tingkat III' => 'Perangkat daerah sudah menetapkan prosedur pengelolaan resiko dalam pelaksanaan tugas tertentu yang dipandang mempunyai resiko tinggi.',
                'Tingkat IV' => 'Perangkat daerah sudah menetapkan prosedur pengelolaan resiko untuk seluruh tugas pada perangkat daerah yang bersangkutan, namun belum dilakukan evaluasi secara berkala.',
                'Tingkat V' => 'Perangkat Daerah sudah menetapkan prosedur pengelolaan resiko dalam pelaksanaan tugas serta semua resiko dapat dikendalikan tanpa ada kerugian baik bagi pegawai maupun instansi.'
            ]
        ],
        'IX' => [
            'title' => 'PENGUKURAN KINERJA PERANGKAT DAERAH DAN APARATUR',
            'tingkat' => [
                'Tingkat I' => 'Belum ada target/rencana kinerja perangkat daerah yang terukur',
                'Tingkat II' => 'Sudah ada target kinerja perangkat daerah, tapi belum konsisten mengacu dokumen perencanaan daerah.',
                'Tingkat III' => 'Sudah ada target kinerja perangkat daerah yang konsisten dengan dokumen perencanaan.',
                'Tingkat IV' => 'Target kinerja perangkat daerah sudah dilakukan pengukuran pencapaiannya.',
                'Tingkat V' => 'Pencapaian target kinerja perangkat daerah sudah diukur dan sudah tercapai dengan baik (diatas 90 %) serta telah dilakukan evaluasi pencapaian target kinerja serta didukung dengan teknologi informasi.'
            ]
        ],
        'X' => [
            'title' => 'PENGEMBANGAN INOVASI LAYANAN PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Belum ada rencana pengembangan produk yang akan dilakukan secara sistematis.',
                'Tingkat II' => 'Pengembangan produk dilakukan dengan mengadopsi inovasi yang dikembangkan oleh daerah lain (replikasi inovasi)',
                'Tingkat III' => 'Telah disusun rencana pengembangan inovasi baik jenis, mutu maupun metodenya.',
                'Tingkat IV' => 'Telah ada inovasi yang dikembangkan sendiri oleh perangkat daerah yang bersangkutan.',
                'Tingkat V' => 'Perangkat daerah sudah mempunyai program pengkajian dan inovasi secara terencana dan berkelanjutan.'
            ]
        ],
        'XI' => [
            'title' => 'BUDAYA ORGANISASI PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Belum ada budaya organisasi pada perangkat daerah.',
                'Tingkat II' => 'Sudah ada slogan-slogan yang menggambarkan nilai organisasi pada perangkat daerah yang bersangkutan.',
                'Tingkat III' => 'Sudah ada dokumen budaya organisasi yang resmi menggambarkan nilai-nilai, sikap dan perilaku di perangkat daerah yang bersangkutan.',
                'Tingkat IV' => 'Sudah ada program internalisasi budaya organisasi yang berkelanjutan berdasarkan dokumen resmi.',
                'Tingkat V' => 'Budaya organisasi sudah tercermin dalam sikap dan perilaku pegawai pada perangkat daerah yang bersangkutan berdasarkan hasil evaluasi secara rutin dan berkelanjutan.'
            ]
        ]
    ];
    
    return view('opd.kematangan.kemendagri', compact('user', 'variabelData'));
}

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