<!-- resources/views/opd/pelayanan-publik/partials/skm-form-complete.blade.php -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-lg font-medium mb-6 pb-3 border-b border-gray-200 flex items-center gap-2">
        <i class="fas fa-file-alt text-primary"></i> Form Laporan SKM Lengkap
    </h2>

    <form id="skmFormComplete" method="POST" action="{{ route('laporan.skm.generate') }}" enctype="multipart/form-data">
        @csrf

        <!-- Data Umum -->
        <div class="mb-8">
            <h3 class="text-md font-semibold mb-4 text-gray-700 border-l-4 border-primary pl-3">I. DATA UMUM</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- item pertama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Triwulan</label>
                    <select name="triwulan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        required>
                        <option value="">Pilih Triwulan</option>
                        <option value="1">Triwulan I (Januari - Maret)</option>
                        <option value="2">Triwulan II (April - Juni)</option>
                        <option value="3">Triwulan III (Juli - September)</option>
                        <option value="4">Triwulan IV (Oktober - Desember)</option>
                    </select>
                </div>

                <!-- item kedua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number" name="tahun" min="2020" max="{{ date('Y') + 1 }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        value="{{ date('Y') }}" required>
                </div>

                <!-- item ketiga -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Penandatangan</label>
                    <input type="text" name="jabatan_penandatangan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Contoh: Kepala Dinas" required>
                </div>

                <!-- item keempat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penandatangan</label>
                    <input type="text" name="nama_penandatangan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Contoh: Dr. H. Ahmad Syaifudin, M.Si." required>
                </div>

                <!-- item kelima - NIP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        NIP Penandatangan
                    </label>
                    <input type="text" name="nip_penandatangan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Contoh: 19651212 198803 1 001" required id="nipInput" oninput="formatNIP(this)"
                        onblur="formatNIP(this)">
                    <p class="text-xs text-gray-500 mt-1">
                        Format: 18 digit angka
                    </p>
                </div>

                <!-- item keenam -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengesahan</label>
                    <input type="date" name="tanggal_pengesahan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        required>
                </div>
            </div>
        </div>

        <!-- Bab I - Pendahuluan -->
        <div class="mb-8">
            <h3 class="text-md font-semibold mb-4 text-gray-700 border-l-4 border-primary pl-3">BAB I - PENDAHULUAN</h3>

            <div class="space-y-4">
                <!-- Latar Belakang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">1.1 Latar Belakang</label>
                    <textarea name="latar_belakang" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Tulis latar belakang pelaksanaan SKM...">Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan Publik dan Peraturan Pemerintah Nomor 96 Tahun 2012 tentang Pelaksanaan Undang-undang Nomor 25 Tahun 2009 tentang Pelayanan Publik, mengamanatkan penyelenggara wajib mengikutsertakan masyarakat dalam penyelenggaraan Pelayanan Publik sebagai upaya membangun sistem penyelenggaraan Pelayanan Publik yang adil, transparan, dan akuntabel. Pelibatan masyarakat tersebut diharapkan dapat mendorong kebijakan penyelenggaraan pelayanan publik yang lebih tepat sasaran. Untuk menjalankan amanat kedua kebijakan tersebut, maka disusun Peraturan Menteri PANRB No. 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat (SKM) Unit Penyelenggara Pelayanan Publik. Pedoman ini memberikan gambaran bagi penyelenggara pelayanan untuk melibatkan masyarakat dalam penilaian kinerja pelayanan publik guna meningkatkan kualitas pelayanan yang diberikan. 

{{ Auth::user()->nama_opd }} menyelenggarakan survei kepuasan masyarakat untuk mengukur kualitas pelayanan. Hasil survei ini akan digunakan sebagai acuan perbaikan pelayanan publik yang dituangkan dalam rencana tindak lanjut sehingga dapat tercapai pelayanan prima yang sesuai dengan harapan dan tuntutan masyarakat sebagai pengguna layanan. Dalam laporan ini juga disampaikan realisasi tindak lanjut dari pelaksanaan survei pada periode sebelumnya, sebagai bentuk komitmen terhadap perbaikan berkelanjutan.</textarea>
                </div>

                <!-- Tujuan dan Manfaat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">1.2 Tujuan dan Manfaat</label>
                    <textarea name="tujuan_manfaat" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Tulis tujuan dan manfaat pelaksanaan SKM...">Pelaksanaan SKM bertujuan untuk mengetahui gambaran kepuasan masyarakat terhadap kualitas pelayanan dan menilai kinerja penyelenggaraan pelayanan. Adapun manfaat yang diperoleh melalui SKM, antara lain:
1. Mengidentifikasi kelemahan dalam penyelenggaraan pelayanan;
2. Mengetahui kinerja pelayanan yang telah dilaksanakan oleh unit pelayanan publik secara periodik;
3. Mengetahui indeks kepuasan masyarakat pada lingkup organisasi penyelenggara pelayanan maupun instansi pemerintah; 
4. Meningkatkan persaingan positif antar organisasi penyelenggara pelayanan;
5. Menjadi dasar penetapan kebijakan maupun perbaikan kualitas pelayanan; dan
6. Memberikan gambaran kepada masyarakat mengenai kinerja organisasi penyelenggara pelayanan.</textarea>
                </div>

                <!-- Metode Pengumpulan Data -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">1.3 Metode Pengumpulan Data</label>
                    <textarea name="metode_pengumpulan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Tulis metode pengumpulan data...">Survei Kepuasan Masyarakat dilaksanakan secara mandiri oleh {{ Auth::user()->nama_opd }}. Untuk mendukung pelaksanaan kegiatan tersebut, telah dibentuk tim pelaksana Survei Kepuasan Masyarakat yang bertanggung jawab dalam seluruh tahapan survei.

Pelaksanaan SKM menggunakan kuesioner manual yang disebarkan kepada pengguna layanan. Kuesioner terdiri atas 9 unsur pengukuran kepuasan masyarakat terhadap pelayanan yang diterima berdasarkan Peraturan Menteri PANRB Nomor 14 Tahun 2017 tentang Pedoman Survei Kepuasan Masyarakat Unit Penyelenggara Pelayanan Publik.</textarea>
                </div>

                <!-- Waktu Pelaksanaan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">1.4 Waktu Pelaksanaan SKM (dalam
                            bulan)</label>
                        <input type="number" name="waktu_pelaksanaan_bulan" min="1" max="12"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            value="6" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">1.5 Penentuan Jumlah
                            Responden</label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Jumlah Populasi:</span>
                                <input type="number" name="jumlah_populasi" min="1"
                                    class="w-32 px-3 py-1 border border-gray-300 rounded-md" value="2400">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Jumlah Sampel:</span>
                                <input type="number" name="jumlah_sampel" min="1"
                                    class="w-32 px-3 py-1 border border-gray-300 rounded-md" value="450">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bab II - Analisis Data SKM -->
        <div class="mb-8">
            <h3 class="text-md font-semibold mb-4 text-gray-700 border-l-4 border-primary pl-3">BAB II - ANALISIS DATA
                SKM</h3>

            <!-- 2.1 Analisis Responden -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-3">2.1 Analisis Responden</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">No</th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">
                                    Karakteristik</th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Indikator
                                </th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Jumlah
                                </th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">
                                    Persentase</th>
                            </tr>
                        </thead>
                        <tbody id="analisisRespondenTable">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <button type="button" onclick="addAnalisisRespondenRow()"
                    class="mt-2 px-3 py-1 bg-primary text-white text-sm rounded hover:bg-primary-dark">
                    <i class="fas fa-plus mr-1"></i> Tambah Baris
                </button>
            </div>

            <!-- 2.2 Indeks Kepuasan Masyarakat -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-3">2.2 Indeks Kepuasan Masyarakat Per Jenis Layanan</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                             <tr>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">No.</th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Jenis
                                    Layanan</th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Jumlah
                                    Responden</th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">
                                    Persyaratan</th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Prosedur
                                </th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Waktu
                                </th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Biaya
                                </th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Produk
                                </th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">
                                    Kompetensi</th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Perilaku
                                </th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Aduan
                                </th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Sarpras
                                </th>
                                <th class="border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">IKM Per
                                    Jenis Layanan</th>
                             </tr>
                        </thead>
                        <tbody id="jenisLayananTable">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                        <tfoot id="jenisLayananFooter">
                            <!-- Footer akan diisi via JavaScript -->
                        </tfoot>
                    </table>
                </div>
                <div class="mt-2">
                    <button type="button" onclick="addJenisLayananRow()"
                        class="px-3 py-1 bg-primary text-white text-sm rounded hover:bg-primary-dark">
                        <i class="fas fa-plus mr-1"></i> Tambah Layanan
                    </button>
                </div>

                <!-- Rerata IKM dan Mutu -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">IKM Unit Layanan</label>
                        <input type="number" step="0.01" name="ikm_unit_layanan" id="ikm_unit_layanan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mutu Unit Layanan</label>
                        <input type="text" name="mutu_unit_layanan" id="mutu_unit_layanan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Warna Grafik</label>
                        <select name="warna_grafik" id="warna_grafik"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="#FF6384">Merah</option>
                            <option value="#36A2EB">Biru</option>
                            <option value="#FFCE56">Kuning</option>
                            <option value="#4BC0C0">Hijau</option>
                            <option value="#9966FF">Ungu</option>
                            <option value="#FF9F40">Oranye</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 2.3 Analisis Masalah dan RTL -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-3">2.3 Analisis Masalah dan Rencana Tindak Lanjut</h4>

                <!-- Template Analisis Masalah -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Analisis Masalah</label>
                    
                    <!-- Textarea untuk input manual -->
                    <textarea name="analisis_masalah" rows="8"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Klik 'Isi Template Otomatis' untuk mengisi analisis masalah berdasarkan hasil SKM..."
                        id="inputAnalisisMasalah"></textarea>

                    <!-- Tombol untuk mengisi template otomatis -->
                    <div class="mt-2 flex gap-2">
                        <button type="button" onclick="isiTemplateAnalisisMasalah()"
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                            <i class="fas fa-magic mr-1"></i> Isi Template Otomatis
                        </button>
                        <button type="button" onclick="resetAnalisisMasalah()"
                            class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                            <i class="fas fa-redo mr-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Rencana Tindak Lanjut Table -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h5 class="font-medium text-gray-700">Rencana Tindak Lanjut</h5>
                        <button type="button" onclick="addRencanaTindakLanjutRow()"
                            class="px-3 py-1 bg-primary text-white text-sm rounded hover:bg-primary-dark">
                            <i class="fas fa-plus mr-1"></i> Tambah RTL
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-gray-50">
                                 <tr>
                                    <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">No.
                                    </th>
                                    <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Unsur
                                    </th>
                                    <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">
                                        Rencana Tindak Lanjut</th>
                                    <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Waktu
                                    </th>
                                    <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">
                                        Penanggung Jawab</th>
                                 </tr>
                            </thead>
                            <tbody id="rencanaTindakLanjutTable">
                                <!-- Data akan diisi via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2.4 Tren Nilai SKM -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-3">2.4 Tren Nilai SKM (5 Tahun Terakhir)</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                             <tr>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Tahun
                                </th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">IKM Unit
                                    Layanan</th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Mutu</th>
                             </tr>
                        </thead>
                        <tbody id="trenSkmTable">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <button type="button" onclick="addTrenSkmRow()"
                    class="mt-2 px-3 py-1 bg-primary text-white text-sm rounded hover:bg-primary-dark">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Tren
                </button>
            </div>
        </div>

        <!-- Bab III - Hasil Tindak Lanjut -->
        <div class="mb-8">
            <h3 class="text-md font-semibold mb-4 text-gray-700 border-l-4 border-primary pl-3">BAB III - HASIL TINDAK
                LANJUT SKM PERIODE SEBELUMNYA</h3>

            <!-- Hasil SKM Sebelumnya -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-3">Hasil SKM Periode Sebelumnya</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                             <tr>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">No</th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Unsur
                                </th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">IKM</th>
                             </tr>
                        </thead>
                        <tbody id="hasilSkmSebelumnyaTable">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tindak Lanjut Sebelumnya -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-3">Tindak Lanjut Periode Sebelumnya</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                             <tr>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">No</th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Rencana
                                    Tindak Lanjut</th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Apakah
                                    RTL Telah Ditindaklanjuti</th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">Deskripsi
                                    Tindak Lanjut</th>
                                <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700">
                                    Dokumentasi Kegiatan</th>
                             </tr>
                        </thead>
                        <tbody id="tindakLanjutSebelumnyaTable">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <button type="button" onclick="addTindakLanjutSebelumnyaRow()"
                    class="mt-2 px-3 py-1 bg-primary text-white text-sm rounded hover:bg-primary-dark">
                    <i class="fas fa-plus mr-1"></i> Tambah Tindak Lanjut
                </button>
            </div>
        </div>

        <!-- Bab IV - Kesimpulan -->
        <div class="mb-8">
            <h3 class="text-md font-semibold mb-4 text-gray-700 border-l-4 border-primary pl-3">BAB IV - KESIMPULAN</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kesimpulan</label>
                    <textarea name="kesimpulan" rows="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Tulis kesimpulan dari pelaksanaan SKM..." required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Saran</label>
                    <textarea name="saran" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Tulis saran untuk perbaikan pelayanan..." required></textarea>
                </div>
            </div>
        </div>

        <!-- Lampiran -->
        <div class="mb-8">
            <h3 class="text-md font-semibold mb-4 text-gray-700 border-l-4 border-primary pl-3">LAMPIRAN</h3>

            <!-- Upload Kuesioner -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-2">1. Kuesioner</h4>
                <div class="flex items-center gap-2">
                    <input type="file" name="kuesioner_file" accept=".jpg,.png"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md">
                    <span class="text-sm text-gray-500">(JPG, PNG)</span>
                </div>
            </div>

            <!-- Upload Dokumentasi Foto -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-2">2. Dokumentasi Pelaksanaan SKM (Foto)</h4>
                <div class="space-y-3">
                    <div id="dokumentasiContainer">
                        <!-- Input file akan ditambahkan di sini -->
                    </div>
                    <button type="button" onclick="addDokumentasiInput()"
                        class="px-3 py-1 bg-primary text-white text-sm rounded hover:bg-primary-dark">
                        <i class="fas fa-plus mr-1"></i> Tambah Foto
                    </button>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark flex items-center gap-2">
                <i class="fas fa-file-pdf mr-2"></i> Generate Laporan PDF
            </button>
        </div>
    </form>
</div>

<script>
    // ==================== DATA UNTUK TABEL ====================

    // Data untuk tabel analisis responden
    const analisisRespondenData = [
        { no: 1, karakteristik: 'Jenis Kelamin', indikator: 'Laki-Laki', jumlah: 175, persentase: 39 },
        { no: '', karakteristik: '', indikator: 'Perempuan', jumlah: 275, persentase: 61 },
        { no: 2, karakteristik: 'Pendidikan', indikator: 'Tidak Sekolah', jumlah: 0, persentase: 0 },
        { no: '', karakteristik: '', indikator: 'SD/Sederajat', jumlah: 0, persentase: 0 },
        { no: '', karakteristik: '', indikator: 'SMP/Sederajat', jumlah: 36, persentase: 8 },
        { no: '', karakteristik: '', indikator: 'SMA/Sederajat', jumlah: 216, persentase: 48 },
        { no: '', karakteristik: '', indikator: 'D1/D2/D3', jumlah: 0, persentase: 0 },
        { no: '', karakteristik: '', indikator: 'D4/S1', jumlah: 180, persentase: 40 },
        { no: '', karakteristik: '', indikator: 'S2', jumlah: 18, persentase: 4 },
        { no: '', karakteristik: '', indikator: 'S3', jumlah: 0, persentase: 0 },
        { no: 3, karakteristik: 'Pekerjaan', indikator: 'ASN', jumlah: 63, persentase: 14 },
        { no: '', karakteristik: '', indikator: 'TNI', jumlah: 5, persentase: 1 },
        { no: '', karakteristik: '', indikator: 'POLRI', jumlah: 135, persentase: 30 },
        { no: '', karakteristik: '', indikator: 'Swasta', jumlah: 40, persentase: 9 },
        { no: '', karakteristik: '', indikator: 'Wirausaha', jumlah: 207, persentase: 46 },
        { no: '', karakteristik: '', indikator: 'Ibu Rumah Tangga', jumlah: '', persentase: '' },
        { no: '', karakteristik: '', indikator: 'Pelajar/Mahasiswa', jumlah: '', persentase: '' },
        { no: '', karakteristik: '', indikator: 'Petani/Nelayan', jumlah: '', persentase: '' },
        { no: '', karakteristik: '', indikator: 'Pekerja Lepas/Freelance', jumlah: '', persentase: '' },
        { no: '', karakteristik: '', indikator: 'Pensiunan', jumlah: '', persentase: '' },
        { no: '', karakteristik: '', indikator: 'Lainnya', jumlah: '', persentase: '' },
        { no: 4, karakteristik: 'Kategorisasi Pengguna Layanan', indikator: 'Non Disabilitas', jumlah: 450, persentase: 90 },
        { no: '', karakteristik: '', indikator: 'Disabilitas', jumlah: 50, persentase: 10 },
        { no: 5, karakteristik: 'Kategorisasi Jenis Disabilitas', indikator: 'Disabilitas Fisik', jumlah: 40, persentase: 80 },
        { no: '', karakteristik: '', indikator: 'Disabilitas Intelektual', jumlah: 0, persentase: 0 },
        { no: '', karakteristik: '', indikator: 'Disabilitas Mental', jumlah: 0, persentase: 0 },
        { no: '', karakteristik: '', indikator: 'Disabilitas Sensorik', jumlah: 10, persentase: 20 }
    ];

    // Data untuk tabel jenis layanan
    const jenisLayananData = [
        {
            no: 1,
            jenis_layanan: 'Penerbitan KTP',
            jumlah_responden: 400,
            nilai: [80, 85, 90, 95, 80, 80, 70, 90, 80],
            ikm_per_jenis: 83.33
        },
        {
            no: 2,
            jenis_layanan: 'Penerbitan KK',
            jumlah_responden: 50,
            nilai: [70, 76, 77, 80, 81, 88, 90, 78, 90],
            ikm_per_jenis: 81.11
        }
    ];

    // Data untuk tren SKM
    const trenSkmData = [
        { tahun: 2021, ikm: 78.5, mutu: 'C' },
        { tahun: 2022, ikm: 80.0, mutu: 'B' },
        { tahun: 2023, ikm: 81.2, mutu: 'B' },
        { tahun: 2024, ikm: 82.5, mutu: 'B' },
        { tahun: 2025, ikm: 82.22, mutu: 'B' }
    ];

    // Data untuk hasil SKM sebelumnya
    const hasilSkmSebelumnyaData = [
        { no: 1, unsur: 'Persyaratan', ikm: '' },
        { no: 2, unsur: 'Sistem, Mekanisme, dan Prosedur', ikm: '' },
        { no: 3, unsur: 'Waktu Penyelesaian', ikm: '' },
        { no: 4, unsur: 'Biaya/Tarif', ikm: '' },
        { no: 5, unsur: 'Produk, Spesifikasi, dan Jenis Pelayanan', ikm: '' },
        { no: 6, unsur: 'Kompetensi Pelaksana', ikm: '' },
        { no: 7, unsur: 'Perilaku Pelaksana', ikm: '' },
        { no: 8, unsur: 'Penanganan Pengaduan, Saran, dan Masukan', ikm: '' },
        { no: 9, unsur: 'Sarana dan Prasarana', ikm: '' }
    ];

    // Unsur SKM
    const unsurSkm = [
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

    // Data nilai default untuk unsur
    const defaultNilaiUnsur = [75, 80, 82, 88, 84, 83, 80, 86, 87];

    // Template untuk Rencana Tindak Lanjut
    const templateRTL = {
        'Persyaratan': 'Menyederhanakan persyaratan layanan dengan mengurangi dokumen yang tidak diperlukan dan membuat panduan yang lebih jelas agar lebih mudah dipahami dan diakses oleh pengguna',
        'Perilaku': 'Meningkatkan keterampilan komunikasi petugas melalui pelatihan soft skills dan penguatan budaya pelayanan yang ramah, komunikatif, dan berorientasi pada kebutuhan pengguna',
        'Prosedur': 'Menyusun SOP yang lebih efisien dan mudah dipahami oleh pengguna layanan',
        'Waktu': 'Mengoptimalkan waktu pelayanan dengan sistem antrian online untuk mengurangi waktu tunggu',
        'Biaya': 'Sosialisasi transparansi biaya dan menyediakan fasilitas pembayaran yang lebih mudah diakses',
        'Produk': 'Meningkatkan kualitas produk layanan dan menambah variasi sesuai kebutuhan masyarakat',
        'Kompetensi': 'Melaksanakan pelatihan dan sertifikasi kompetensi petugas untuk meningkatkan kualitas layanan',
        'Aduan': 'Mengoptimalkan sistem pengaduan dan meningkatkan responsivitas dalam menangani keluhan',
        'Sarpras': 'Perbaikan dan penambahan fasilitas pendukung pelayanan untuk kenyamanan pengguna'
    };

    // Deskripsi masalah kualitatif berdasarkan unsur
    const deskripsiMasalahKualitatif = {
        'Persyaratan': 'persyaratan layanan dirasa terlalu banyak, rumit, dan terkesan birokratis',
        'Perilaku': 'sikap petugas yang kurang ramah, tidak komunikatif, dan belum sepenuhnya mencerminkan prinsip pelayanan publik yang berorientasi pada kebutuhan pengguna',
        'Prosedur': 'prosedur pelayanan yang masih berbelit-belit dan sulit dipahami oleh masyarakat',
        'Waktu': 'waktu pelayanan yang masih lama dan kurang efisien',
        'Biaya': 'ketidakjelasan informasi biaya dan tarif layanan',
        'Produk': 'kualitas produk layanan yang belum sesuai dengan harapan masyarakat',
        'Kompetensi': 'kemampuan petugas yang masih perlu ditingkatkan dalam memberikan pelayanan',
        'Aduan': 'sistem penanganan pengaduan yang belum optimal dan lambat dalam merespon',
        'Sarpras': 'ketersediaan sarana dan prasarana yang belum memadai dan nyaman'
    };

    // ==================== FUNGSI UTAMA ====================

    // Fungsi untuk menentukan mutu berdasarkan IKM
    function getMutuByIKM(ikm) {
        if (ikm >= 90) return 'A';
        if (ikm >= 80) return 'B';
        if (ikm >= 70) return 'C';
        if (ikm >= 60) return 'D';
        return 'E';
    }

    // Fungsi untuk format NIP
    function formatNIP(input) {
        if (!input) return;
        
        let value = input.value.replace(/\D/g, '');
        
        if (value.length > 0) {
            // Format: XXXXXXXX XXXXXX X XXX
            if (value.length > 8) {
                value = value.substring(0, 8) + ' ' + value.substring(8);
            }
            if (value.length > 15) {
                value = value.substring(0, 15) + ' ' + value.substring(15);
            }
            if (value.length > 18) {
                value = value.substring(0, 18);
            }
        }
        
        input.value = value;
        
        // Hanya highlight jika user sudah mengisi tapi kurang dari 18 digit
        const nipValue = input.value.replace(/\D/g, '');
        if (nipValue.length > 0 && nipValue.length < 18) {
            input.style.borderColor = '#f87171';
            input.style.backgroundColor = '#fef2f2';
        } else {
            input.style.borderColor = '#d1d5db';
            input.style.backgroundColor = '#ffffff';
        }
    }

    // Fungsi untuk validasi NIP pada form submit
    function validateNIP() {
        const nipInput = document.getElementById('nipInput');
        if (!nipInput) return true;
        
        const nipValue = nipInput.value.replace(/\D/g, '');
        if (nipValue.length !== 18) {
            alert('❌ NIP harus 18 digit! Silakan periksa kembali NIP Penandatangan.');
            nipInput.style.borderColor = '#f87171';
            nipInput.style.backgroundColor = '#fef2f2';
            nipInput.focus();
            return false;
        }
        return true;
    }

    // ==================== FUNGSI PENGISIAN TABEL ====================

    // Fungsi untuk mengisi tabel analisis responden
    function fillAnalisisRespondenTable() {
        const tableBody = document.getElementById('analisisRespondenTable');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        analisisRespondenData.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td class="border border-gray-300 px-3 py-2">
                <input type="text" name="analisis_responden[${index}][no]" 
                       value="${item.no}" 
                       class="w-12 text-center border-none bg-transparent">
             </td>
            <td class="border border-gray-300 px-3 py-2">
                <input type="text" name="analisis_responden[${index}][karakteristik]" 
                       value="${item.karakteristik}" 
                       class="w-full border-none bg-transparent">
             </td>
            <td class="border border-gray-300 px-3 py-2">
                <input type="text" name="analisis_responden[${index}][indikator]" 
                       value="${item.indikator}" 
                       class="w-full border-none bg-transparent">
             </td>
            <td class="border border-gray-300 px-3 py-2">
                <input type="number" name="analisis_responden[${index}][jumlah]" 
                       value="${item.jumlah}" 
                       class="w-24 text-center border-none bg-transparent">
             </td>
            <td class="border border-gray-300 px-3 py-2">
                <input type="number" step="0.1" name="analisis_responden[${index}][persentase]" 
                       value="${item.persentase}" 
                       class="w-24 text-center border-none bg-transparent">
             </td>
        `;
            tableBody.appendChild(row);
        });
    }

    // Fungsi untuk mengisi tabel jenis layanan
    function fillJenisLayananTable() {
        const tableBody = document.getElementById('jenisLayananTable');
        const tableFooter = document.getElementById('jenisLayananFooter');

        if (!tableBody) return;

        tableBody.innerHTML = '';
        tableFooter.innerHTML = '';

        jenisLayananData.forEach((item, index) => {
            const row = document.createElement('tr');

            const nilaiInputs = item.nilai.map((nilai, i) => `
            <td class="border border-gray-300 px-2 py-1">
                <input type="number" step="0.1" 
                       name="jenis_layanan[${index}][nilai][${i}]" 
                       value="${nilai}" 
                       class="w-16 text-center border-none bg-transparent">
             </td>
        `).join('');

            row.innerHTML = `
            <td class="border border-gray-300 px-2 py-1">
                <input type="text" name="jenis_layanan[${index}][no]" 
                       value="${item.no}" 
                       class="w-8 text-center border-none bg-transparent">
             </td>
            <td class="border border-gray-300 px-2 py-1">
                <input type="text" name="jenis_layanan[${index}][jenis_layanan]" 
                       value="${item.jenis_layanan}" 
                       class="w-full border-none bg-transparent">
             </td>
            <td class="border border-gray-300 px-2 py-1">
                <input type="number" name="jenis_layanan[${index}][jumlah_responden]" 
                       value="${item.jumlah_responden}" 
                       class="w-24 text-center border-none bg-transparent">
             </td>
            ${nilaiInputs}
            <td class="border border-gray-300 px-2 py-1">
                <input type="number" step="0.01" 
                       name="jenis_layanan[${index}][ikm_per_jenis]" 
                       value="${item.ikm_per_jenis}" 
                       class="w-24 text-center border-none bg-transparent">
             </td>
        `;
            tableBody.appendChild(row);
        });

        const footerRow = document.createElement('tr');
        footerRow.innerHTML = `
        <td colspan="2" class="border border-gray-300 px-2 py-1 font-medium text-center">
            Rerata IKM Per Unsur
         </td>
        <td class="border border-gray-300 px-2 py-1"></td>
        ${unsurSkm.map((_, i) => `
            <td class="border border-gray-300 px-2 py-1">
                <input type="number" step="0.1" id="rerata_unsur_${i}" 
                       name="rerata_ikm[${i}]" 
                       class="w-16 text-center border-none bg-transparent">
             </td>
        `).join('')}
        <td class="border border-gray-300 px-2 py-1">
            <input type="number" step="0.01" id="ikm_unit_layanan_display" 
                   name="ikm_unit_layanan_display" 
                   class="w-24 text-center border-none bg-transparent">
         </td>
    `;
        tableFooter.appendChild(footerRow);
    }

    // Fungsi untuk mengisi tabel tren SKM
    function fillTrenSkmTable() {
        const tableBody = document.getElementById('trenSkmTable');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        trenSkmData.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td class="border border-gray-300 px-3 py-2">
                <input type="number" name="tren_skm[${index}][tahun]" 
                       value="${item.tahun}" 
                       class="w-24 text-center border-none bg-transparent">
             </td>
            <td class="border border-gray-300 px-3 py-2">
                <input type="number" step="0.01" name="tren_skm[${index}][ikm]" 
                       value="${item.ikm}" 
                       class="w-32 text-center border-none bg-transparent">
             </td>
            <td class="border border-gray-300 px-3 py-2">
                <input type="text" name="tren_skm[${index}][mutu]" 
                       value="${item.mutu}" 
                       class="w-16 text-center border-none bg-transparent">
             </td>
        `;
            tableBody.appendChild(row);
        });
    }

    // Fungsi untuk mengisi tabel hasil SKM sebelumnya
    function fillHasilSkmSebelumnyaTable() {
        const tableBody = document.getElementById('hasilSkmSebelumnyaTable');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        hasilSkmSebelumnyaData.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td class="border border-gray-300 px-3 py-2">
                <input type="text" name="hasil_skm_sebelumnya[${index}][no]" 
                       value="${item.no}" 
                       class="w-12 text-center border-none bg-transparent" readonly>
             </td>
            <td class="border border-gray-300 px-3 py-2">
                <input type="text" name="hasil_skm_sebelumnya[${index}][unsur]" 
                       value="${item.unsur}" 
                       class="w-full border-none bg-transparent" readonly>
             </td>
            <td class="border border-gray-300 px-3 py-2">
                <input type="number" step="0.01" name="hasil_skm_sebelumnya[${index}][ikm]" 
                       class="w-24 text-center border-none bg-transparent">
             </td>
        `;
            tableBody.appendChild(row);
        });
    }

    // ==================== ANALISIS MASALAH ====================

    // Fungsi untuk mendapatkan nilai dan unsur terendah
    function getUnsurTerendah() {
        let nilaiRerata = [];
        let hasValidData = false;

        for (let i = 0; i < 9; i++) {
            const input = document.getElementById(`rerata_unsur_${i}`);
            if (input && input.value && input.value !== '' && parseFloat(input.value) > 0) {
                nilaiRerata.push(parseFloat(input.value));
                hasValidData = true;
            } else {
                nilaiRerata.push(0);
            }
        }

        if (!hasValidData || nilaiRerata.every(val => val === 0)) {
            nilaiRerata = [...defaultNilaiUnsur];
        }

        const nilaiNumber = nilaiRerata.map(val => parseFloat(val));
        const minValue = Math.min(...nilaiNumber);
        const minIndex = nilaiNumber.indexOf(minValue);

        const nilaiTanpaTerendah = [...nilaiNumber];
        nilaiTanpaTerendah[minIndex] = Infinity;
        const secondMinValue = Math.min(...nilaiTanpaTerendah);
        const secondMinIndex = nilaiNumber.indexOf(secondMinValue);

        return {
            unsurTerendah: unsurSkm[minIndex] || 'Persyaratan',
            nilaiTerendah: minValue.toFixed(1),
            unsurTerendahKedua: unsurSkm[secondMinIndex] || 'Perilaku',
            nilaiTerendahKedua: secondMinValue.toFixed(1),
            deskripsiMasalah1: deskripsiMasalahKualitatif[unsurSkm[minIndex]] || deskripsiMasalahKualitatif['Persyaratan'],
            deskripsiMasalah2: deskripsiMasalahKualitatif[unsurSkm[secondMinIndex]] || deskripsiMasalahKualitatif['Perilaku']
        };
    }

    // Fungsi untuk mengisi template analisis masalah otomatis
    function isiTemplateAnalisisMasalah() {
        const {
            unsurTerendah,
            nilaiTerendah,
            unsurTerendahKedua,
            nilaiTerendahKedua,
            deskripsiMasalah1,
            deskripsiMasalah2
        } = getUnsurTerendah();

        const formatUnsurUntukTeks = (unsur) => {
            const mapping = {
                'Persyaratan': 'persyaratan layanan',
                'Perilaku': 'perilaku pelaksana',
                'Prosedur': 'prosedur pelayanan',
                'Waktu': 'waktu pelayanan',
                'Biaya': 'biaya pelayanan',
                'Produk': 'produk layanan',
                'Kompetensi': 'kompetensi pelaksana',
                'Aduan': 'penanganan aduan',
                'Sarpras': 'sarana dan prasarana'
            };
            return mapping[unsur] || unsur.toLowerCase();
        };

        const unsur1 = formatUnsurUntukTeks(unsurTerendah);
        const unsur2 = formatUnsurUntukTeks(unsurTerendahKedua);

        const template = `Dari hasil analisis data SKM, kami mengidentifikasi bahwa aspek ${unsur1} dan ${unsur2} merupakan dua isu yang paling sering disorot oleh masyarakat. Secara kuantitatif, kedua dimensi ini memiliki nilai yang masih dapat ditingkatkan. ${unsurTerendah} mendapatkan nilai terendah yaitu ${nilaiTerendah}. Selanjutnya ${formatUnsurUntukTeks(unsurTerendahKedua)} mendapatkan nilai ${nilaiTerendahKedua} adalah nilai terendah kedua.

Sementara, secara kualitatif dari kritik dan saran, kami menerima banyak masukan yang menyatakan bahwa ${deskripsiMasalah1}. Selain itu, terdapat keluhan mengenai ${deskripsiMasalah2}.

Atas dasar temuan tersebut, unit kerja kami menyusun rencana tindak lanjut yang tidak hanya ditujukan untuk meningkatkan nilai SKM secara angka, tetapi lebih penting lagi, untuk menjawab permasalahan nyata yang dirasakan oleh masyarakat. Fokus utama kami adalah melakukan penyederhanaan persyaratan layanan, meningkatkan keterampilan komunikasi petugas, serta memperbaiki alur pelayanan agar lebih mudah dipahami dan diakses oleh pengguna. Berdasarkan hasil analisis tersebut, berikut rencana tindak lanjut yang telah Kami susun untuk perbaikan layanan kedepan:`;

        const inputAnalisis = document.getElementById('inputAnalisisMasalah');
        if (inputAnalisis) {
            inputAnalisis.value = template;
        }
    }

    // Fungsi untuk reset analisis masalah
    function resetAnalisisMasalah() {
        const inputAnalisis = document.getElementById('inputAnalisisMasalah');
        if (inputAnalisis) {
            inputAnalisis.value = '';
        }
    }

    // ==================== RENCANA TINDAK LANJUT ====================

    // Fungsi untuk mengisi RTL otomatis berdasarkan unsur
    function isiRTLByUnsur(selectElement) {
        if (!selectElement) return;

        const row = selectElement.closest('tr');
        if (!row) return;

        const unsur = selectElement.value;
        const rencanaInput = row.querySelector('textarea[name*="[rencana]"]');

        if (templateRTL[unsur] && rencanaInput) {
            rencanaInput.value = templateRTL[unsur];
        }
    }

    // Fungsi untuk menambah baris rencana tindak lanjut
    function addRencanaTindakLanjutRow() {
        const tableBody = document.getElementById('rencanaTindakLanjutTable');
        if (!tableBody) return;

        const index = tableBody.children.length;

        const { unsurTerendah, unsurTerendahKedua } = getUnsurTerendah();

        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="rencana_tindak_lanjut[${index}][no]" 
                   value="${index + 1}" 
                   class="w-12 text-center border-none bg-transparent">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <select name="rencana_tindak_lanjut[${index}][unsur]" 
                    class="w-full border-none bg-transparent unsur-select"
                    onchange="isiRTLByUnsur(this)">
                <option value="${unsurTerendah}">${unsurTerendah}</option>
                <option value="${unsurTerendahKedua}">${unsurTerendahKedua}</option>
                <option value="Persyaratan">Persyaratan</option>
                <option value="Prosedur">Prosedur</option>
                <option value="Waktu">Waktu</option>
                <option value="Biaya">Biaya</option>
                <option value="Produk">Produk</option>
                <option value="Kompetensi">Kompetensi</option>
                <option value="Perilaku">Perilaku</option>
                <option value="Aduan">Aduan</option>
                <option value="Sarpras">Sarpras</option>
            </select>
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <textarea name="rencana_tindak_lanjut[${index}][rencana]" 
                      rows="3" class="w-full border-none bg-transparent" 
                      placeholder="Deskripsi rencana tindak lanjut..."></textarea>
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="rencana_tindak_lanjut[${index}][waktu]" 
                   class="w-32 border-none bg-transparent" 
                   placeholder="Contoh: Maret 2025">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="rencana_tindak_lanjut[${index}][penanggung_jawab]" 
                   class="w-full border-none bg-transparent" 
                   placeholder="Nama penanggung jawab">
         </td>
    `;
        tableBody.appendChild(row);

        const selectElement = row.querySelector('.unsur-select');
        if (selectElement) {
            isiRTLByUnsur(selectElement);
        }
    }

    // ==================== FUNGSI TAMBAH BARIS ====================

    // Fungsi untuk menambah baris analisis responden
    function addAnalisisRespondenRow() {
        const tableBody = document.getElementById('analisisRespondenTable');
        if (!tableBody) return;

        const index = tableBody.children.length;

        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="analisis_responden[${index}][no]" 
                   class="w-12 text-center border-none bg-transparent">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="analisis_responden[${index}][karakteristik]" 
                   class="w-full border-none bg-transparent">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="analisis_responden[${index}][indikator]" 
                   class="w-full border-none bg-transparent">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="number" name="analisis_responden[${index}][jumlah]" 
                   class="w-24 text-center border-none bg-transparent">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="number" step="0.1" name="analisis_responden[${index}][persentase]" 
                   class="w-24 text-center border-none bg-transparent">
         </td>
    `;
        tableBody.appendChild(row);
    }

    // Fungsi untuk menambah baris jenis layanan
    function addJenisLayananRow() {
        const tableBody = document.getElementById('jenisLayananTable');
        if (!tableBody) return;

        const index = tableBody.children.length;

        const row = document.createElement('tr');

        const nilaiInputs = Array(9).fill(0).map((_, i) => `
        <td class="border border-gray-300 px-2 py-1">
            <input type="number" step="0.1" 
                   name="jenis_layanan[${index}][nilai][${i}]" 
                   class="w-16 text-center border-none bg-transparent">
         </td>
    `).join('');

        row.innerHTML = `
        <td class="border border-gray-300 px-2 py-1">
            <input type="text" name="jenis_layanan[${index}][no]" 
                   value="${index + 1}" 
                   class="w-8 text-center border-none bg-transparent">
         </td>
        <td class="border border-gray-300 px-2 py-1">
            <input type="text" name="jenis_layanan[${index}][jenis_layanan]" 
                   class="w-full border-none bg-transparent" 
                   placeholder="Nama Layanan">
         </td>
        <td class="border border-gray-300 px-2 py-1">
            <input type="number" name="jenis_layanan[${index}][jumlah_responden]" 
                   class="w-24 text-center border-none bg-transparent">
         </td>
        ${nilaiInputs}
        <td class="border border-gray-300 px-2 py-1">
            <input type="number" step="0.01" 
                   name="jenis_layanan[${index}][ikm_per_jenis]" 
                   class="w-24 text-center border-none bg-transparent">
         </td>
    `;
        tableBody.appendChild(row);
    }

    // Fungsi untuk menambah baris tren SKM
    function addTrenSkmRow() {
        const tableBody = document.getElementById('trenSkmTable');
        if (!tableBody) return;

        const index = tableBody.children.length;
        const currentYear = new Date().getFullYear();

        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="border border-gray-300 px-3 py-2">
            <input type="number" name="tren_skm[${index}][tahun]" 
                   value="${currentYear - 4 + index}" 
                   class="w-24 text-center border-none bg-transparent">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="number" step="0.01" name="tren_skm[${index}][ikm]" 
               class="w-32 text-center border-none bg-transparent">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="tren_skm[${index}][mutu]" 
                   class="w-16 text-center border-none bg-transparent">
         </td>
    `;
        tableBody.appendChild(row);
    }

    // Fungsi untuk menambah baris tindak lanjut sebelumnya
    function addTindakLanjutSebelumnyaRow() {
        const tableBody = document.getElementById('tindakLanjutSebelumnyaTable');
        if (!tableBody) return;

        const index = tableBody.children.length;
        const no = Math.floor(index / 3) + 1;
        const subNo = (index % 3) + 1;

        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="tindak_lanjut_sebelumnya[${index}][no]" 
                   value="${no}.${subNo}" 
                   class="w-12 text-center border-none bg-transparent" readonly>
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="text" name="tindak_lanjut_sebelumnya[${index}][rencana]" 
                   class="w-full border-none bg-transparent" 
                   placeholder="Nama kegiatan">
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <select name="tindak_lanjut_sebelumnya[${index}][status]" 
                    class="w-full border-none bg-transparent">
                <option value="Sudah">Sudah</option>
                <option value="Belum">Belum</option>
            </select>
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <textarea name="tindak_lanjut_sebelumnya[${index}][deskripsi]" 
                      rows="2" class="w-full border-none bg-transparent" 
                      placeholder="Deskripsi tindak lanjut"></textarea>
         </td>
        <td class="border border-gray-300 px-3 py-2">
            <input type="file" name="tindak_lanjut_sebelumnya[${index}][dokumentasi]" 
                   accept=".jpg,.jpeg,.png" 
                   class="w-full border-none bg-transparent">
         </td>
    `;
        tableBody.appendChild(row);
    }

    // Fungsi untuk menambah input dokumentasi
    function addDokumentasiInput() {
        const container = document.getElementById('dokumentasiContainer');
        if (!container) return;

        const index = container.children.length;

        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
        <input type="file" name="dokumentasi_foto[]" 
               accept=".jpg,.jpeg,.png" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md">
        <button type="button" onclick="removeDokumentasiInput(this)" 
                class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
            <i class="fas fa-times"></i>
        </button>
    `;
        container.appendChild(div);
    }

    // Fungsi untuk menghapus input dokumentasi
    function removeDokumentasiInput(button) {
        const div = button.closest('div');
        if (div) {
            div.remove();
        }
    }

    // ==================== INISIALISASI ====================

    document.addEventListener('DOMContentLoaded', function () {
        fillAnalisisRespondenTable();
        fillJenisLayananTable();
        fillTrenSkmTable();
        fillHasilSkmSebelumnyaTable();

        addDokumentasiInput();
        addDokumentasiInput();

        setTimeout(() => {
            addRencanaTindakLanjutRow();
            addRencanaTindakLanjutRow();
            const selects = document.querySelectorAll('.unsur-select');
            if (selects.length >= 2) {
                const { unsurTerendahKedua } = getUnsurTerendah();
                selects[1].value = unsurTerendahKedua;
                isiRTLByUnsur(selects[1]);
            }
        }, 500);

        const form = document.getElementById('skmFormComplete');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (!validateNIP()) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
</script>