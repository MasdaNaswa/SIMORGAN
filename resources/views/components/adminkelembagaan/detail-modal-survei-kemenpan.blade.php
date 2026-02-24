<div
    x-data="{
    
        open: false,
        evaluasi: null,
        jawabanStruktur: [],
        jawabanProses: [],
        detailPerhitungan: {},
        interpretasi: {},
        activeTab: 'struktur',
        
        
        // Array pertanyaan lengkap (tanpa grouping)
        pertanyaanStruktur: [
            '1. Desain organisasi yang ada saat ini perlu disesuaikan dengan ketentuan peraturan perundang-undangan.',
            '2. Terdapat indikasi bahwa desain organisasi yang ada bersifat kompleks.',
            '3. Terdapat indikasi bahwa desain organisasi yang ada bersifat sederhana.',
            '4. Tingkatan unit organisasi yang ada saat ini perlu disesuaikan tugas dan fungsinya dari tingkatan unit organisasi paling atas sampai paling bawah.',
            '5. Terdapat indikasi adanya tingkatan unit organisasi yang tugas dan fungsinya bersifat umum.',
            '6. Terdapat indikasi adanya tingkatan unit organisasi yang tugas dan fungsinya bersifat spesifik.',
            '7. Penataan perangkat daerah telah ditetapkan sesuai dengan substansi pewadahan dan/atau perumpunan urusan pemerintahan yang menjadi kewenangan daerah.',
            '8. Jumlah Cabang Dinas/UPTD yang dibentuk menunjukkan indikasi melebihi kebutuhan. (Hanya untuk OPD yang memiliki UPTD)',
            '9. Cabang Dinas/UPTD yang dibentuk dinilai secara sinergis mendukung tercapainya tujuan pembentukan organisasi. (Hanya untuk OPD yang memiliki UPTD)',
            '10. Nomenklatur unit organisasi saat ini perlu disesuaikan dengan tugas dan fungsinya.',
            '11. Jenjang jabatan yang ada sudah sesuai kebutuhan.',
            '12. Jumlah jabatan pada setiap tingkatan sudah sesuai kebutuhan.',
            '13. Jabatan-jabatan fungsional sudah memenuhi kebutuhan.',
            '14. Penempatan jabatan fungsional mendukung efisiensi dan efektivitas unit operasional.',
            '15. Tugas dan fungsi unit organisasi yang ada saat ini perlu dirumuskan secara jelas sesuai dengan strategi organisasi dalam peraturan tentang organisasi dan tata kerja',
            '16. Mekanisme pelaksanaan tugas dan fungsi serta kewenangan setiap unit kerja dari manajemen tertinggi sampai manajemen menengah ke bawah telah dituangkan secara jelas dalam prosedur formal yang berkekuatan hukum di dalam organisasi.',
            '17. Mekanisme hubungan antar unit organisasi yang ada saat ini perlu dirumuskan secara jelas sesuai dengan strategi organisasi dalam peraturan tentang organisasi dan tata kerja.',
            '18. Rencana strategis dituangkan secara jelas di dalam keputusan resmi organisasi.',
            '19. Kebijakan-kebijakan organisasi selalu dituangkan secara jelas dan tegas di dalam keputusan resmi organisasi.',
            '20. Seluruh proses kerja telah dituangkan secara sistematis di dalam peraturan tentang standar operasional prosedur.',
            '21. Standarisasi pelayanan publik telah diformalkan.',
            '22. Kewenangan pengambilan keputusan yang ada saat ini perlu dirumuskan secara jelas sesuai dengan strategi organisasi.',
            '23. Setiap tingkatan manajemen dapat mengambil keputusan sesuai dengan kewenangan yang dimiliki.',
            '24. Terdapat indikasi bahwa tingkatan manajemen yang lebih tinggi mengambil alih keputusan dari kewenangan manajemen yang lebih rendah (di bawahnya).',
            '25. Terdapat indikasi bahwa tingkatan manajemen yang lebih rendah dapat mengambil keputusan melebihi kewenangannya.',
            '26. Permasalahan yang bersifat lintas bidang atau sektoral telah dituangkan dalam Keputusan instansi pemerintah guna mencapai kinerja instansi induk.',
            '27. Permasalahan yang bersifat lintas bidang atau sektoral harus diputuskan oleh manajemen tertinggi dari instansi induk.',
            '28. Pimpinan utama instansi hanya membuat keputusan-keputusan yang bersifat strategis dan kebijakan.',
            '29. Pimpinan madya pada tingkat manajemen menengah mempunyai wewenang untuk membuat keputusan-keputusan taktis dan manajerial.',
            '30. Pimpinan pratama pada unit operasional mempunyai wewenang untuk membuat keputusan-keputusan teknis operasional.',
            '31. Pendelegasian kewenangan membuat keputusan-keputusan telah diberikan oleh pimpinan instansi kepada pimpinan unit organisasi tingkat menengah.',
            '32. Pendelegasian wewenang untuk melaksanakan tugas dan fungsi yang bersifat teknis dan operasional telah diberikan kepada pimpinan unit organisasi tingkat menengah ke pimpinan organisasi tingkat bawah.'
        ],
        
        pertanyaanProses: [
            '1. Seluruh sasaran strategis dari atas sampai bawah terkait dengan visi dan misi organisasi.',
            '2. Setiap proses kerja dalam Proses Bisnis/SOP memiliki keterkaitan dengan sasaran strategis.',
            '3. Setiap proses kerja memiliki keterkaitan dengan jabatan dalam struktur organisasi.',
            '4. Proses kerja unit bawah merupakan penjabaran dari proses kerja unit atas (vertikal).',
            '5. Keterkaitan proses kerja antar unit telah dipetakan dengan baik.',
            '6. Koordinasi antar unit kerja telah dilakukan dengan baik.',
            '7. Keterkaitan proses kerja lintas sektor telah dipetakan.',
            '8. Koordinasi lintas organisasi telah terlaksana dengan baik.',
            '9. Struktur Organisasi dan Tata Kerja (SOTK) organisasi dari tingkatan manajemen tertinggi sampai tingkatan menengah ke bawah telah sesuai dengan peraturan perundangan.',
            '10. Seluruh kepentingan strategis pemangku kepentingan organisasi, mulai dari tingkat manajemen tertinggi sampai tingkat manajemen menengah ke bawah telah dipetakan dengan baik.',
            '11. Setiap proses kerja yang terkait dengan kebutuhan informasi publik dan tidak bersifat rahasia telah dijalankan secara transparan (transparansi).',
            '12. Setiap tahapan pekerjaan yang terdapat di dalam proses kerja pada tingkatan manajemen tertinggi sampai manajemen menengah ke bawah telah memiliki kesesuaian dan kejelasan fungsi, struktur, dan penanggung jawab pekerjaan (akuntabilitas).',
            '13. Setiap proses kerja telah memiliki sistem dan mekanisme pertanggungjawaban (termasuk pelaporan) yang jelas (tanggung jawab).',
            '14. Tidak terdapat indikasi intervensi yang signifikan di dalam setiap pelaksanaan proses kerja dalam organisasi, baik pada tingkatan manajemen tertinggi sampai dengan manajemen menengah ke bawah.',
            '15. Aparat pelaksana proses kerja dapat melaksanakan tugas secara mandiri sesuai dengan kewenangan tugas pokok dan fungsinya masing-masing.',
            '16. Standar operasional prosedur selalu diperbarui secara periodik.',
            '17. Standar operasional prosedur sebagian besar (lebih dari 50%) dinilai perlu segera diperbaharui karena sudah tidak relevan dan telah dibuat lebih dari 5 (lima) tahun.',
            '18. Organisasi selalu melakukan pengembangan terhadap sistem proses kerja.',
            '19. Terdapat indikasi bahwa organisasi lebih berorientasi pada hal-hal yang bersifat rutinitas dibandingkan dengan hal-hal yang bersifat strategis.',
            '20. Manajemen risiko organisasi telah diperkenalkan di dalam organisasi.',
            '21. Organisasi telah memiliki kebijakan manajemen risiko yang memadai.',
            '22. Risiko-risiko utama organisasi telah diidentifikasi dengan baik.',
            '23. Risiko-risiko utama organisasi yang telah diidentifikasi belum diukur (peluang terjadinya maupun dampaknya) dengan metode yang memadai.',
            '24. Organisasi belum melaksanakan kebijakan manajemen risiko.',
            '25. Organisasi telah memiliki sistem monitoring risiko yang memadai.',
            '26. Organisasi telah memiliki rancangan arsitektur penerapan Teknologi informasi.',
            '27. Organisasi telah memiliki kebijakan IT (e-government) yang memadai.',
            '28. Sebagian besar proses kerja telah memanfaatkan teknologi informasi secara memadai.',
            '29. Sebagian besar proses kerja masih dilaksanakan secara manual.',
            '30. Seluruh informasi publik terkait dengan keberadaan dan tupoksi organisasi telah dipublikasikan secara periodik di dalam website organisasi.'
        ],
        
        load(id) {
            this.open = true;
            
            // Tampilkan loading
            this.evaluasi = null;
            this.jawabanStruktur = [];
            this.jawabanProses = [];
            this.detailPerhitungan = {};
            this.interpretasi = {};
            
            fetch(`/adminkelembagaan/kematangan-kelembagaan/kemenpan/${id}`)
                .then(r => {
                    if (!r.ok) throw new Error('Network response was not ok');
                    return r.json();
                })
                .then(d => {
                    console.log('Data dari server:', d);
                    
                    if (d.error) {
                        alert('Error: ' + d.error);
                        return;
                    }
                    
                    // Set data evaluasi
                    this.evaluasi = d.evaluasi || {};
                    
                    // Set detail perhitungan
                    this.detailPerhitungan = d.detailPerhitungan || {};
                    
                    // Set interpretasi
                    this.interpretasi = d.interpretasi || {};
                    
                    // DEBUG: Lihat struktur data jawaban
                    console.log('Data jawaban:', d.jawaban);
                    console.log('Tipe d.jawaban:', typeof d.jawaban);
                    
                    // Ambil jawaban struktur (1-32)
                    this.jawabanStruktur = [];
                    if (d.jawaban && d.jawaban.struktur) {
                        if (Array.isArray(d.jawaban.struktur)) {
                            this.jawabanStruktur = [...d.jawaban.struktur];
                        } else if (typeof d.jawaban.struktur === 'string') {
                            try {
                                const parsed = JSON.parse(d.jawaban.struktur);
                                if (Array.isArray(parsed)) {
                                    this.jawabanStruktur = [...parsed];
                                }
                            } catch(e) {
                                console.error('Error parsing struktur:', e);
                            }
                        }
                    }
                    
                    // Jika masih kosong, buat array default
                    if (this.jawabanStruktur.length === 0 || this.jawabanStruktur.length < 32) {
                        console.log('Membuat jawabanStruktur default');
                        this.jawabanStruktur = Array(32).fill('Tidak Diisi');
                    }
                    
                    // Ambil jawaban proses (1-30)
                    this.jawabanProses = [];
                    if (d.jawaban && d.jawaban.proses) {
                        if (Array.isArray(d.jawaban.proses)) {
                            this.jawabanProses = [...d.jawaban.proses];
                        } else if (typeof d.jawaban.proses === 'string') {
                            try {
                                const parsed = JSON.parse(d.jawaban.proses);
                                if (Array.isArray(parsed)) {
                                    this.jawabanProses = [...parsed];
                                }
                            } catch(e) {
                                console.error('Error parsing proses:', e);
                            }
                        }
                    }
                    
                    // Jika masih kosong, buat array default
                    if (this.jawabanProses.length === 0 || this.jawabanProses.length < 30) {
                        console.log('Membuat jawabanProses default');
                        this.jawabanProses = Array(30).fill('Tidak Diisi');
                    }
                    
                    console.log('Jawaban struktur akhir:', this.jawabanStruktur);
                    console.log('Jawaban proses akhir:', this.jawabanProses);
                })
                .catch(e => {
                    console.error('Error loading data:', e);
                    alert('Gagal memuat data: ' + e.message);
                });
        }
    }"
     x-init="() => {
        // Pastikan modal tersembunyi saat pertama kali di-load
        $el.style.display = 'none';
    }"
    x-on:open-modal-kemenpan.window="load($event.detail)"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/60"
    style="display: none;"
>
    <div class="bg-white w-full max-w-6xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto m-4">
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-5 rounded-t-xl">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">
                        <span x-show="evaluasi">Detail Survei Kemenpan - </span>
                        <span x-text="evaluasi?.nama_opd || 'Memuat...'"></span>
                    </h2>
                    <div class="flex items-center gap-4 mt-1 text-sm text-blue-100" x-show="evaluasi">
                        <span><i class="fas fa-calendar mr-1"></i> <span x-text="evaluasi?.created_at"></span></span>
                        <span><i class="fas fa-envelope mr-1"></i> <span x-text="evaluasi?.email"></span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- LOADING -->
        <div x-show="!evaluasi" class="p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Memuat data survei...</p>
        </div>

        <!-- BODY -->
        <div x-show="evaluasi" class="p-6 space-y-6">
            <!-- SKOR UTAMA -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 p-4 rounded-xl text-center shadow-sm">
                    <p class="text-sm font-medium text-blue-700 mb-1">Skor Struktur</p>
                    <p class="text-3xl font-bold text-blue-800" x-text="evaluasi.skor_struktur || '0'"></p>
                    <div class="mt-2 h-2 bg-blue-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600" :style="'width: ' + Math.min(100, (evaluasi.skor_struktur || 0)) + '%'"></div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 p-4 rounded-xl text-center shadow-sm">
                    <p class="text-sm font-medium text-purple-700 mb-1">Skor Proses</p>
                    <p class="text-3xl font-bold text-purple-800" x-text="evaluasi.skor_proses || '0'"></p>
                    <div class="mt-2 h-2 bg-purple-200 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-600" :style="'width: ' + Math.min(100, (evaluasi.skor_proses || 0)) + '%'"></div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 p-4 rounded-xl text-center shadow-sm">
                    <p class="text-sm font-medium text-green-700 mb-1">Total Skor</p>
                    <p class="text-3xl font-bold text-green-800" x-text="evaluasi.total_skor || '0'"></p>
                    <div class="mt-2 h-2 bg-green-200 rounded-full overflow-hidden">
                        <div class="h-full bg-green-600" :style="'width: ' + Math.min(100, (evaluasi.total_skor || 0)) + '%'"></div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 p-4 rounded-xl text-center shadow-sm">
                    <p class="text-sm font-medium text-indigo-700 mb-1">Kematangan</p>
                    <p class="text-xl font-bold" 
                       :class="{
                           'text-green-700': interpretasi.level === 'SANGAT BAIK',
                           'text-blue-700': interpretasi.level === 'BAIK',
                           'text-yellow-700': interpretasi.level === 'CUKUP BAIK',
                           'text-orange-700': interpretasi.level === 'KURANG BAIK',
                           'text-red-700': interpretasi.level === 'SANGAT KURANG',
                           'text-gray-700': !interpretasi.level
                       }"
                       x-text="interpretasi.level || '-'"></p>
                    <p class="text-xs text-gray-600 mt-1 truncate" :title="interpretasi.deskripsi || ''" x-text="interpretasi.deskripsi || ''"></p>
                </div>
            </div>

            <!-- DETAIL SKOR PER DIMENSI -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200" x-show="Object.keys(detailPerhitungan).length > 0">
                <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                    Detail Skor per Subdimensi
                </h3>
                <div class="space-y-4">
                    <template x-for="(nilai, dimensi) in detailPerhitungan" :key="dimensi">
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-gray-800" x-text="dimensi"></span>
                                <span class="font-bold text-blue-700" 
                                      x-text="(nilai.skor_mentah || 0).toFixed(2)"></span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600" 
                                         :style="'width: ' + Math.min(100, ((nilai.skor_mentah || 0) / (nilai.max_skor || 100)) * 100) + '%'">
                                    </div>
                                </div>
                                <span class="text-sm text-gray-600 min-w-[60px] text-right"
                                      x-text="(((nilai.skor_mentah || 0) / (nilai.max_skor || 100)) * 100).toFixed(1) + '%'">
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- JAWABAN OPD DENGAN TAB YANG BENAR -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <!-- Tab Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                    <div class="flex">
                        <button @click="activeTab = 'struktur'" 
                                :class="{'bg-white text-blue-700 border-b-0 border-r shadow-sm': activeTab === 'struktur', 'text-gray-600 hover:text-gray-800 hover:bg-gray-50': activeTab !== 'struktur'}"
                                class="flex-1 px-6 py-4 text-left font-semibold border-b border-gray-200 transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-layer-group mr-2" :class="{'text-blue-500': activeTab === 'struktur'}"></i>
                                    <span>Dimensi Struktur</span>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                                      x-text="jawabanStruktur.length + ' jawaban'">
                                </span>
                            </div>
                        </button>
                        <button @click="activeTab = 'proses'" 
                                :class="{'bg-white text-purple-700 border-b-0 border-l shadow-sm': activeTab === 'proses', 'text-gray-600 hover:text-gray-800 hover:bg-gray-50': activeTab !== 'proses'}"
                                class="flex-1 px-6 py-4 text-left font-semibold border-b border-gray-200 transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-cogs mr-2" :class="{'text-purple-500': activeTab === 'proses'}"></i>
                                    <span>Dimensi Proses</span>
                                </div>
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                                      x-text="jawabanProses.length + ' jawaban'">
                                </span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="p-0">
                    <!-- Tab Struktur - PAKAI x-if UNTUK FORCE RERENDER -->
                    <template x-if="activeTab === 'struktur'">
                        <div class="p-0">
                            <div class="max-h-[400px] overflow-y-auto">
                                <template x-for="(jawaban, index) in jawabanStruktur" :key="index">
                                    <div class="border-b border-gray-100 last:border-b-0 hover:bg-blue-50/30">
                                        <div class="p-5">
                                            <div class="flex gap-4">
                                                <div class="flex-shrink-0">
                                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">
                                                        <span x-text="index + 1"></span>
                                                    </span>
                                                </div>
                                                <div class="flex-grow min-w-0">
                                                    <!-- Pertanyaan -->
                                                    <p class="text-gray-800 font-medium mb-3 leading-relaxed" 
                                                       x-text="pertanyaanStruktur[index] || ('Struktur ' + (index + 1))"></p>
                                                    
                                                    <!-- Jawaban -->
                                                    <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
                                                        <div>
                                                            <span class="text-sm font-medium text-gray-700">Jawaban OPD:</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold shadow-sm"
                                                                  x-text="jawaban || 'Tidak Diisi'"
                                                                  :class="{
                                                                      'bg-green-50 text-green-700 border border-green-200': jawaban === 'Sangat Setuju',
                                                                      'bg-blue-50 text-blue-700 border border-blue-200': jawaban === 'Setuju',
                                                                      'bg-yellow-50 text-yellow-700 border border-yellow-200': jawaban === 'Tidak Setuju',
                                                                      'bg-red-50 text-red-700 border border-red-200': jawaban === 'Sangat Tidak Setuju',
                                                                      'bg-gray-50 text-gray-700 border border-gray-300': jawaban === 'Tidak Diisi' || !jawaban
                                                                  }">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="jawabanStruktur.length === 0" 
                                     class="text-center py-10 text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p class="font-medium">Tidak ada jawaban struktur tersedia</p>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Tab Proses - PAKAI x-if UNTUK FORCE RERENDER -->
                    <template x-if="activeTab === 'proses'">
                        <div class="p-0">
                            <div class="max-h-[400px] overflow-y-auto">
                                <template x-for="(jawaban, index) in jawabanProses" :key="index">
                                    <div class="border-b border-gray-100 last:border-b-0 hover:bg-purple-50/30">
                                        <div class="p-5">
                                            <div class="flex gap-4">
                                                <div class="flex-shrink-0">
                                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 text-purple-600 font-semibold text-sm">
                                                        <span x-text="index + 1"></span>
                                                    </span>
                                                </div>
                                                <div class="flex-grow min-w-0">
                                                    <!-- Pertanyaan -->
                                                    <p class="text-gray-800 font-medium mb-3 leading-relaxed" 
                                                       x-text="pertanyaanProses[index] || ('Proses ' + (index + 1))"></p>
                                                    
                                                    <!-- Jawaban -->
                                                    <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
                                                        <div>
                                                            <span class="text-sm font-medium text-gray-700">Jawaban OPD:</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold shadow-sm"
                                                                  x-text="jawaban || 'Tidak Diisi'"
                                                                  :class="{
                                                                      'bg-green-50 text-green-700 border border-green-200': jawaban === 'Sangat Setuju',
                                                                      'bg-blue-50 text-blue-700 border border-blue-200': jawaban === 'Setuju',
                                                                      'bg-yellow-50 text-yellow-700 border border-yellow-200': jawaban === 'Tidak Setuju',
                                                                      'bg-red-50 text-red-700 border border-red-200': jawaban === 'Sangat Tidak Setuju',
                                                                      'bg-gray-50 text-gray-700 border border-gray-300': jawaban === 'Tidak Diisi' || !jawaban
                                                                  }">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="jawabanProses.length === 0" 
                                     class="text-center py-10 text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p class="font-medium">Tidak ada jawaban proses tersedia</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="flex justify-between items-center border-t px-6 py-4 bg-gray-50 rounded-b-xl">
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Evaluasi Kelembagaan Instansi Pemerintah - Permen PAN RB Nomor 20 Tahun 2018
            </div>
            <button @click="open=false"
                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-medium shadow-sm transition-all duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>