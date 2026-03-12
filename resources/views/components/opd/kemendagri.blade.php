@props(['action', 'cancelUrl'])

@php
    $variables = [
        'VARIABEL I' => [
            'title' => 'PERENCANAAN PEMBANGUNAN DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Penentuan kegiatan yang diprioritaskan dalam dokumen perencanaan tahunan (Renja/RKPD) dilakukan tanpa ada kriteria yang terukur.',
                'Tingkat II' => 'Penentuan kegiatan yang diprioritaskan dalam dokumen rencana tahunan dilakukan berdasarkan analisis terhadap hasil (outcome) apa yang akan dicapai kegiatan tersebut',
                'Tingkat III' => 'Penentuan prioritas kegiatan dalam dokumen rencana tahunan dilakukan berdasarkan analisis hasil (outcome) dan analisis kemampuan kegiatan menghasilkan hasil (outcome).',
                'Tingkat IV' => 'Penentuan prioritas kegiatan dilakukan berdasarkan analisis yang membandingkan hasil (outcome) yang akan dicapai antara satu alternatif kegiatan dengan alternatif kegiatan yang lain.',
                'Tingkat V' => 'Penentuan prioritas kegiatan dalam dokumen tahunan dilakukan dengan perbandingan hasil (outcome) antara satu alternatif kegiatan dengan alternatif kegiatan yang lain dan dibantu dengan teknologi informasi.'
            ]
        ],
        'VARIABEL II' => [
            'title' => 'MONITORING DAN PENGENDALIAN PELAKSANAAN TUGAS PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Monitoring dan pengendalian dilakukan dengan cara sederhana dan tidak terstruktur.',
                'Tingkat II' => 'Monitoring dan pengendalian dilakukan secara berkala dengan fokus yang ditentukan.',
                'Tingkat III' => 'Monitoring dan pengendalian dilakukan secara berkala dengan kriteria penyimpangan yang terstandarisasi pada setiap tahap kegiatan.',
                'Tingkat IV' => 'Monitoring dan pengendalian dilakukan secara berkala dengan kriteria penyimpangan yang terstandarisasi dan diikuti dengan umpan balik berupa perbaikan yang terdokumentasi dengan baik',
                'Tingkat V' => 'Monitoring dan pengendalian dilakukan secara sistematis, terstandarisasi termasuk umpan balik yang didukung oleh penggunaan teknologi informasi berbasis internet.'
            ]
        ],
        'VARIABEL III' => [
            'title' => 'PENJAMINAN MUTU LAYANAN PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Tidak ada penjaminan mutu atas produk yang dihasilkan dan atas proses kerja yang dilakukan.',
                'Tingkat II' => 'Penjaminan mutu produk dan proses kerja dilakukan secara berkala namun tidak mempunyai standar mutu produk dan proses yang ditetapkan.',
                'Tingkat III' => 'Mutu produk dan proses sudah distandarisasi dan dilakukan pengujian secara berkala secara internal.',
                'Tingkat IV' => 'Penjaminan mutu produk dan proses sudah distandarisasi serta dilakukan pengukuran/ pengujian secara berkala oleh tenaga yang bersertifikat.',
                'Tingkat V' => 'Penjaminan mutu produk dan proses dilakukan terstandarisasi dan berkala oleh tenaga ahli bersertifikat serta didukung oleh teknologi informasi berbasis internet.'
            ]
        ],
        'VARIABEL IV' => [
            'title' => 'STANDAR OPERASIONAL PROSEDUR (SOP) PELAYANAN PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Tidak ada definisi resmi proses pelaksanaan pekerjaan pada perangkat daerah.',
                'Tingkat II' => 'Definisi proses organisasi sudah dituangkan dalam standar operasi prosedur (SOP).',
                'Tingkat III' => 'Definisi proses organisasi sudah dituangkan ke dalam SOP dan telah dilakukan evaluasi berkala terhadap penerapan SOP.',
                'Tingkat IV' => 'Definisi proses organisasi sudah dituangkan dalam SOP, sudah dievaluasi secara berkala dan dilakukan tindak lanjut terhadap hasil evaluasi penerapan SOP berupa tindakan koreksi atau perbaikan SOP.',
                'Tingkat V' => 'Definisi proses organisasi sudah dituangkan dalam SOP dan sudah dilakukan evaluasi serta tindak lanjut, kemudian disesuaikan dengan kebutuhan/keluhan pelanggan serta didukung oleh teknologi berbasis internet.'
            ]
        ],
        'VARIABEL V' => [
            'title' => 'PENDIDIKAN DAN PELATIHAN APARATUR',
            'tingkat' => [
                'Tingkat I' => 'Belum ada dokumen resmi rencana kebutuhan pendidikan dan pelatihan pada perangkat daerah yang bersangkutan.',
                'Tingkat II' => 'Dokumen rencana kebutuhan pengembangan pegawai sudah tersusun secara parsial untuk jabatan tertentu.',
                'Tingkat III' => 'Dokumen rencana kebutuhan pengembangan pegawai disusun untuk seluruh jabatan.',
                'Tingkat IV' => 'Rencana pengembangan pegawai dievaluasi secara regular dan seluruh pengembangan pegawai sudah dilaksanakan sesuai dengan dokumen rencana pengembangan pegawai yang sudah ditetapkan.',
                'Tingkat V' => 'Hasil (outcome) pengembangan pegawai dievalusi secara regular sebagai umpan balik.'
            ]
        ],
        'VARIABEL VI' => [
            'title' => 'ANALISIS KEBIJAKAN DAN PEMECAHAN MASALAH TUGAS PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Analisis kebijakan dan pemecahan masalah dilakukan secara sederhana dan dengan metode yang tidak terukur.',
                'Tingkat II' => 'Analisis kebijakan yang berdampak ke publik dilakukan oleh tim internal perangkat daerah yang bersangkutan.',
                'Tingkat III' => 'Analisis kebijakan dan pemecahan masalah yang berdampak ke publik dilakukan menggunakan metode/teknik ilmiah oleh tim internal dengan melibatkan instansi pemerintah terkait.',
                'Tingkat IV' => 'Analisis kebijakan dan pemecahan masalah yang bersifat strategis/berdampak ke publik melibatkan tim ahli.',
                'Tingkat V' => 'Analisis kebijakan dan pemecahan masalah strategis/berdampak ke publik melibatkan tim ahli dengan melakukan konsultasi publik dan analisis umpan balik yang terukur dan terdokumentasi.'
            ]
        ],
        'VARIABEL VII' => [
            'title' => 'MANAJEMEN SUMBER DAYA PERALATAN DAN PERLENGKAPAN KERJA YANG TERUKUR',
            'tingkat' => [
                'Tingkat I' => 'Penggunaan sumber daya dilakukan hanya berdasarkan ketentuan formal yang berlaku.',
                'Tingkat II' => 'Penentuan penggunaan input proyek dilakukan berdasarkan analisis kebutuhan bahan/ sumber daya yang sudah ditetapkan.',
                'Tingkat III' => 'Analisis kebutuhan input/sumber daya proyek sudah distandarisasi dengan proses ujicoba secara terbuka dan menggunakan metode ilmiah.',
                'Tingkat IV' => 'Penyediaan sumber daya dalam pelaksanaan proyek dimonitor secara ketat berdasarkan standar input sumber daya, SOP dan prosedur penjaminan mutu produk.',
                'Tingkat V' => 'Penyediaan sumber daya dan pelaksanaan proyek dimonitor secara ketat berdasarkan SOP dan prosedur penjaminan mutu produk dan didukung oleh teknologi informasi berbasis internet.'
            ]
        ],
        'VARIABEL VIII' => [
            'title' => 'MANAJEMEN RESIKO PELAKSANAAN TUGAS APARATUR',
            'tingkat' => [
                'Tingkat I' => 'Belum ada manajemen resiko dalam pelaksanaan tugas pada perangkat daerah.',
                'Tingkat II' => 'Sudah ada sebagian pegawai yang melakukan analisis resiko dalam pelaksanaan tugasnya, namun hanya bersifat individu.',
                'Tingkat III' => 'Perangkat daerah sudah menetapkan prosedur pengelolaan resiko dalam pelaksanaan tugas tertentu yang dipandang mempunyai resiko tinggi.',
                'Tingkat IV' => 'Perangkat daerah sudah menetapkan prosedur pengelolaan resiko untuk seluruh tugas pada perangkat daerah yang bersangkutan, namun belum dilakukan evaluasi secara berkala.',
                'Tingkat V' => 'Perangkat Daerah sudah menetapkan prosedur pengelolaan resiko dalam pelaksanaan tugas serta semua resiko dapat dikendalikan tanpa ada kerugian baik bagi pegawai maupun instansi.'
            ]
        ],
        'VARIABEL IX' => [
            'title' => 'PENGUKURAN KINERJA PERANGKAT DAERAH DAN APARATUR',
            'tingkat' => [
                'Tingkat I' => 'Belum ada target/rencana kinerja perangkat daerah yang terukur',
                'Tingkat II' => 'Sudah ada target kinerja perangkat daerah, tapi belum konsisten mengacu dokumen perencanaan daerah.',
                'Tingkat III' => 'Sudah ada target kinerja perangkat daerah yang konsisten dengan dokumen perencanaan.',
                'Tingkat IV' => 'Target kinerja perangkat daerah sudah dilakukan pengukuran pencapaiannya.',
                'Tingkat V' => 'Pencapaian target kinerja perangkat daerah sudah diukur dan sudah tercapai dengan baik (diatas 90 %) serta telah dilakukan evaluasi pencapaian target kinerja serta didukung dengan teknologi informasi.'
            ]
        ],
        'VARIABEL X' => [
            'title' => 'PENGEMBANGAN INOVASI LAYANAN PERANGKAT DAERAH',
            'tingkat' => [
                'Tingkat I' => 'Belum ada rencana pengembangan produk yang akan dilakukan secara sistematis.',
                'Tingkat II' => 'Pengembangan produk dilakukan dengan mengadopsi inovasi yang dikembangkan oleh daerah lain (replikasi inovasi)',
                'Tingkat III' => 'Telah disusun rencana pengembangan inovasi baik jenis, mutu maupun metodenya.',
                'Tingkat IV' => 'Telah ada inovasi yang dikembangkan sendiri oleh perangkat daerah yang bersangkutan.',
                'Tingkat V' => 'Perangkat daerah sudah mempunyai program pengkajian dan inovasi secara terencana dan berkelanjutan.'
            ]
        ],
        'VARIABEL XI' => [
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

    $skorMaturitas = [
        'SANGAT RENDAH' => '10 - 19',
        'RENDAH' => '19,1 - 28',
        'SEDANG' => '28,1 - 37',
        'TINGGI' => '37,1 - 46',
        'SANGAT TINGGI' => '46,1 - 55'
    ];
@endphp

<div class="max-h-[90vh] overflow-y-auto">
    <form action="{{ $action }}" method="POST" class="space-y-0 bg-white" enctype="multipart/form-data">
        @csrf

        <!-- Header -->
        <div class="sticky top-0 z-10 bg-gradient-to-r from-green-700 to-green-900 p-6 shadow-lg">
            <div class="flex items-start justify-between">
                <div class="pr-4">
                    <h3 class="text-2xl font-bold text-white mb-1">EVALUASI KELEMBAGAAN INSTANSI PEMERINTAHAN</h3>
                    <p class="text-green-100 text-sm mb-2">
                        <i class="fas fa-balance-scale mr-2"></i>
                        SESUAI PERATURAN MENTERI DALAM NEGERI NOMOR 99 TAHUN 2018
                    </p>

                    <!-- Skor Kematangan -->
                    <div class="bg-white/10 rounded-lg p-3 mt-3 border border-white/20">
                        <h4 class="text-white font-semibold text-sm mb-2">SKOR TINGKAT KEMATANGAN ORGANISASI :</h4>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                            @foreach($skorMaturitas as $label => $range)
                                <div class="text-xs text-white bg-white/10 p-2 rounded">
                                    <div class="font-semibold">{{ $label }}</div>
                                    <div class="text-green-100">{{ $range }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container Utama -->
        <div class="p-6 space-y-8">
            <!-- Informasi OPD -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-building text-green-600 text-lg"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800">Informasi Organisasi Perangkat Daerah</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom 1: Nama OPD -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                            <i class="fas fa-landmark text-green-500 text-sm"></i>
                            Nama Organisasi Perangkat Daerah
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_opd" value="{{ old('nama_opd', $user->nama_opd) }}" required
                            placeholder="Masukkan nama OPD"
                            class="mt-1 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm"
                            readonly>
                        @error('nama_opd')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kolom 2: Email OPD -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                            <i class="fas fa-envelope-open-text text-green-500 text-sm"></i>
                            Email Organisasi Perangkat Daerah
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            placeholder="Email"
                            class="mt-1 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm"
                            readonly>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Separator -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white px-4 text-sm text-green-600 font-semibold">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        KUESIONER EVALUASI KELEMBAGAAN
                    </span>
                </div>
            </div>

            <!-- Container Variabel -->
            <div class="space-y-8">
                @foreach($variables as $kode => $data)
                @php 
                $varKey ='variabel_' . strtolower(str_replace('VARIABEL ', '', $kode));
                @endphp
                    <div
                        class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                        <!-- Header Variabel -->
                        <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-white/20 p-2 rounded-lg">
                                        <i class="fas fa-chart-line text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-white">{{ $kode }} - {{ $data['title'] }}</h4>
                                    </div>
                                </div>
                                <span class="bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Pilih Salah Satu
                                </span>
                            </div>
                        </div>

                        <!-- Body Tingkat Kematangan -->
                        <div class="p-6 space-y-6">
                            <!-- Deskripsi -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-gray-700 font-medium text-sm">
                                    <i class="fas fa-info-circle text-green-600 mr-2"></i>
                                    VARIABEL, KUALIFIKASI DAN INDIKATOR
                                </p>
                            </div>

                            <!-- Radio Options -->
                            <div class="space-y-4">
                                @foreach($data['tingkat'] as $tingkat => $deskripsi)
                                    @php
                                        $tingkatColors = [
                                            'Tingkat I' => 'border-red-200 hover:border-red-300 hover:bg-red-50',
                                            'Tingkat II' => 'border-orange-200 hover:border-orange-300 hover:bg-orange-50',
                                            'Tingkat III' => 'border-yellow-200 hover:border-yellow-300 hover:bg-yellow-50',
                                            'Tingkat IV' => 'border-blue-200 hover:border-blue-300 hover:bg-blue-50',
                                            'Tingkat V' => 'border-green-200 hover:border-green-300 hover:bg-green-50'
                                        ];
                                        $colorClass = $tingkatColors[$tingkat] ?? 'border-gray-200 hover:border-gray-300 hover:bg-gray-50';
                                    @endphp

                                    <label
                                        class="flex items-start p-5 rounded-lg border-2 cursor-pointer transition-all duration-200 bg-white {{ $colorClass }} group">
                                        <div class="flex-shrink-0 mt-1 mr-4">
                                            <input type="radio" name="{{ $varKey }}" value="{{ $tingkat }}" required
                                                class="mt-1 h-5 w-5 text-green-600">
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700 font-bold text-sm">
                                                    {{ substr($tingkat, -1) }}
                                                </span>
                                                <span class="font-semibold text-gray-800">{{ $tingkat }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm leading-relaxed">{{ $deskripsi }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Upload File -->
                            <div class="mt-6 bg-blue-50 rounded-xl p-5 border border-blue-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-cloud-upload-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-800">Upload Data Pendukung</h5>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                            Maksimal ukuran file yang diupload 1 MB dan jumlah maksimal file 3 (format JPG
                                            dan PDF)
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="relative">
                                                    <input type="file" 
       name="variabel_{{ strtolower(str_replace('VARIABEL ', '', $kode)) }}_files[]" 
       id="fileInput_variabel_{{ strtolower(str_replace('VARIABEL ', '', $kode)) }}"
       multiple
       accept=".jpg,.jpeg,.pdf,image/jpeg,image/jpg,application/pdf"
       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <ul id="fileList_{{  strtolower(str_replace('VARIABEL ', '', $kode)) }}"class="mt-2 text-sm text-gray-700"></ul>

                                    </div>

                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                        <span>Upload up to 3 supported files: PDF or drawing. Max 10 MB per file.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Info Skor -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-xl p-5 border border-green-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <i class="fas fa-calculator text-green-600"></i>
                    </div>
                    <h5 class="font-semibold text-gray-800">Informasi Penilaian</h5>
                </div>
                <div class="text-sm text-gray-700 space-y-2">
                    <p><span class="font-medium">Setiap Tingkat I - V</span> memiliki skor yang berbeda untuk
                        perhitungan total skor kematangan organisasi.</p>
                    <p>Skor akan dihitung otomatis setelah semua variabel diisi.</p>
                </div>
            </div>

            <!-- Tombol Action -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="fas fa-info-circle text-green-500"></i>
                    <span>Pastikan semua pertanyaan telah diisi sebelum mengirim</span>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="modalKemendagri = false"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium shadow-sm flex items-center gap-2 hover:shadow">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-lg hover:from-green-700 hover:to-emerald-800 transition-all duration-200 font-medium shadow-sm flex items-center gap-2 hover:shadow-lg active:scale-[0.98] transform transition-transform">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Evaluasi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Simple file manager untuk setiap variabel
class FileManager {
    constructor(variable) {
        this.variable = variable;
        this.varKey = `variabel_${variable}`;
        this.files = [];
        this.init();
    }
    
    init() {
        const fileInput = document.getElementById(`fileInput_${this.varKey}`);
        const fileListContainer = document.getElementById(`fileList_${this.variable}`);
        
        if (fileInput && fileListContainer) {
            fileInput.addEventListener('change', (e) => this.handleFileSelect(e));
            this.updatePreview();
        }
    }
    
    handleFileSelect(e) {
        const newFiles = Array.from(e.target.files);
        
        // Validasi jumlah file
        if (this.files.length + newFiles.length > 3) {
            alert('Maksimal 3 file per variabel');
            e.target.value = '';
            return;
        }
        
        // Validasi setiap file
        newFiles.forEach(file => {
            // Validasi ukuran (max 1 MB)
            if (file.size > 10485760) {
                alert(`File "${file.name}" melebihi 10 MB`);
                return;
            }
            
            // Validasi tipe file
            const isJPG = file.type === 'image/jpeg' || file.type === 'image/jpg';
            const isPDF = file.type === 'application/pdf';
            const ext = file.name.toLowerCase().split('.').pop();
            const validExt = ['jpg', 'jpeg', 'pdf'].includes(ext);
            
            if (!(isJPG || isPDF) || !validExt) {
                alert(`File "${file.name}" harus JPG/JPEG atau PDF`);
                return;
            }
            
            // Tambahkan file
            this.files.push(file);
        });
        
        this.updatePreview();
        e.target.value = '';
    }
    
    removeFile(index) {
        this.files.splice(index, 1);
        this.updatePreview();
    }
    
    updatePreview() {
        const container = document.getElementById(`fileList_${this.variable}`);
        if (!container) return;
        
        container.innerHTML = '';
        
        if (this.files.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm italic">Belum ada file yang diupload</p>';
            return;
        }
        
        this.files.forEach((file, index) => {
            const fileElement = document.createElement('div');
            fileElement.className = 'flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg mb-2 hover:bg-gray-50 transition-colors';
            
            const fileName = file.name.length > 25 ? file.name.substring(0, 22) + '...' : file.name;
            const fileSize = this.formatSize(file.size);
            
            // Tentukan icon
            let icon = 'fa-file';
            let iconColor = 'text-blue-600';
            let bgColor = 'bg-blue-50';
            let typeLabel = 'FILE';
            
            if (file.type.includes('image')) {
                icon = 'fa-file-image';
                iconColor = 'text-green-600';
                bgColor = 'bg-green-50';
                typeLabel = 'JPG';
            } else if (file.type.includes('pdf')) {
                icon = 'fa-file-pdf';
                iconColor = 'text-red-600';
                bgColor = 'bg-red-50';
                typeLabel = 'PDF';
            }
            
            fileElement.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 ${bgColor} rounded-lg flex items-center justify-center">
                        <i class="fas ${icon} ${iconColor} text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">${fileName}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-gray-500">${fileSize}</span>
                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded">${typeLabel}</span>
                        </div>
                    </div>
                </div>
                <button type="button" 
                        onclick="window.fileManagers['${this.variable}'].removeFile(${index})"
                        class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(fileElement);
        });
        
        // Tambahkan info
        const info = document.createElement('div');
        info.className = 'text-sm text-gray-600 mt-2 flex items-center gap-1';
        info.innerHTML = `<i class="fas fa-info-circle text-blue-500"></i> ${this.files.length} dari 3 file`;
        container.appendChild(info);
    }
    
    formatSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    getFiles() {
        return this.files;
    }
}

// Inisialisasi semua file managers
document.addEventListener('DOMContentLoaded', function() {
    window.fileManagers = {};
    const variables = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x', 'xi'];
    
    variables.forEach(v => {
        window.fileManagers[v] = new FileManager(v);
    });
    

// Handle form submission
const form = document.querySelector('form[enctype="multipart/form-data"]');
if (form) {
    form.addEventListener('submit', function(e) {
        console.log('Form submission started...');
        
        // Hapus semua file input yang ada
        document.querySelectorAll('input[type="file"]').forEach(input => {
            console.log('Removing input:', input.name);
            input.remove();
        });
        
        // Debug: Tampilkan semua file managers
        console.log('File managers:', window.fileManagers);
        
        // Tambahkan file dari setiap manager
        variables.forEach(v => {
            const manager = window.fileManagers[v];
            if (manager) {
                const files = manager.getFiles();
                console.log(`Variabel ${v}: ${files.length} files`);
                
                if (files.length > 0) {
                    const dataTransfer = new DataTransfer();
                    files.forEach(file => {
                        console.log(`  - Adding file: ${file.name} (${file.size} bytes)`);
                        dataTransfer.items.add(file);
                    });
                    
                    const newInput = document.createElement('input');
                    newInput.type = 'file';
                    newInput.name = `variabel_${v}_files[]`; // PERBAIKAN: Gunakan []
                    newInput.multiple = true;
                    newInput.style.display = 'none';
                    newInput.files = dataTransfer.files;
                    
                    console.log(`Created input: ${newInput.name} with ${newInput.files.length} files`);
                    form.appendChild(newInput);
                }
            } else {
                console.warn(`Manager for variabel ${v} not found`);
            }
        });
        
        // Debug: Tampilkan semua inputs sebelum submit
        const allInputs = Array.from(form.elements).filter(el => el.tagName === 'INPUT');
        console.log('Total inputs before submit:', allInputs.length);
        allInputs.forEach(input => {
            console.log(`  - ${input.name}: ${input.type}`);
        });
        
        // Jangan mencegah submit, biarkan form submit normal
    });
}
});
</script>