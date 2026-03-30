<!-- Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-[9999]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-indigo-600 text-white p-6 rounded-t-lg sticky top-0 z-10">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    Tambah Rencana Aksi RB General
                </h3>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="tambahRenaksiRB" method="POST" action="{{ route('rb-general.store') }}">
                    @csrf
                    <!-- Input Hidden untuk Tahun -->
                    <input type="hidden" name="tahun" id="addTahun" value="{{ $selectedYear }}">

                    <!-- Header Tahun -->
                    <div class="text-center mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-indigo-700">
                            RENCANA AKSI RB GENERAL TAHUN {{ $selectedYear }}
                        </h2>
                    </div>

                    <!-- Section 1: Informasi Utama -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-indigo-500">
                        <h4 class="text-md font-semibold text-gray-700 mb-4">INFORMASI UTAMA</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="no" class="block text-sm font-medium text-gray-700 mb-1">
                                    NO 
                                </label>
                                <input type="text" id="no" name="no" 
                                    placeholder="Akan terisi otomatis"
                                    class="w-full p-2 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="sasaran_strategi" class="block text-sm font-medium text-gray-700 mb-1">
                                    SASARAN STRATEGI
                                </label>
                                <select id="sasaran_strategi" name="sasaran_strategi" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">-- Pilih Sasaran Strategi --</option>
                                    <option value="Terwujudnya Transformasi Digital">Terwujudnya Transformasi Digital
                                    </option>
                                    <option
                                        value="Terciptanya Aparatur Negara yang Kompeten dan Berkinerja Tinggi Berdasarkan Sistem Merit">
                                        Terciptanya Aparatur Negara yang Kompeten dan Berkinerja Tinggi Berdasarkan
                                        Sistem Merit
                                    </option>
                                    <option value="Terbangunnya Perilaku Birokrasi yang Beretika dan Inovatif">
                                        Terbangunnya Perilaku Birokrasi yang Beretika dan Inovatif
                                    </option>
                                    <option
                                        value="Terbangunnya Kapabilitas Kelembagaan Berkinerja Tinggi yang berbaris Jejaring dan Lincah">
                                        Terbangunnya Kapabilitas Kelembagaan Berkinerja Tinggi yang berbaris Jejaring
                                        dan Lincah
                                    </option>
                                    <option
                                        value="Terwujudnya Kebijakan dan Pelayanan Publik yang Berkualitas dan Inklusif">
                                        Terwujudnya Kebijakan dan Pelayanan Publik yang Berkualitas dan Inklusif
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="indikator_capaian" class="block text-sm font-medium text-gray-700 mb-1">
                                    INDIKATOR
                                </label>
                                <input type="text" id="indikator_capaian" name="indikator_capaian" required
                                    placeholder="Masukkan indikator"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="target" class="block text-sm font-medium text-gray-700 mb-1">TARGET</label>
                                <input type="text" id="target" name="target" required placeholder="Masukkan target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">SATUAN</label>
                                <select id="satuan" name="satuan" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="Nilai">Nilai</option>
                                    <option value="Persen">Persen</option>
                                    <option value="Bulan">Bulan</option>
                                    <option value="OPD">OPD</option>
                                    <option value="Laporan">Laporan</option>
                                    <option value="Surat Keputusan">Surat Keputusan</option>
                                    <opstion value="Dan Lain-Lain">Dan Lain-Lain</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="target_tahun" class="block text-sm font-medium text-gray-700 mb-1">
                                    TARGET TAHUN <span class="targetYearText">{{ $selectedYear }}</span>
                                </label>
                                <input type="text" id="target_tahun" name="target_tahun"
                                    placeholder="Masukkan target {{ $selectedYear }}"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Rencana Aksi -->
                    <div class="mb-6 p-6 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-gray-700 mb-1">RENCANA AKSI</h4>
                        <textarea id="rencana_aksi" name="rencana_aksi" rows="3" required
                            placeholder="Masukkan rencana aksi"
                            class="w-full p-2 mt-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="form-group">
                                <label for="satuan_output" class="block text-sm font-medium text-gray-700 mb-1">
                                    SATUAN OUTPUT
                                </label>
                                <input type="text" id="satuan_output" name="satuan_output"
                                    placeholder="Masukkan satuan output"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="indikator_output" class="block text-sm font-medium text-gray-700 mb-1">
                                    INDIKATOR OUTPUT
                                </label>
                                <input type="text" id="indikator_output" name="indikator_output"
                                    placeholder="Masukkan indikator output"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Renaksi Triwulan -->
                    <div class="mb-6 p-6 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                        <h4 class="text-md font-semibold text-purple-800 mb-4">
                            RENAKSI TAHUN <span class="renaksiYearText">{{ $selectedYear }}</span>
                        </h4>

                        <!-- TW1 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="renaksi_tw1_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW1 Target
                                </label>
                                <input type="text" id="renaksi_tw1_target" name="renaksi_tw1_target"
                                    placeholder="Masukkan target TW1"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="tw1_rp" class="block text-sm font-medium text-gray-700 mb-1">TW1 Rp</label>
                                <input type="text" id="tw1_rp" name="tw1_rp" placeholder="Masukkan anggaran TW1"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <!-- TW2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="renaksi_tw2_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW2 Target
                                </label>
                                <input type="text" id="renaksi_tw2_target" name="renaksi_tw2_target"
                                    placeholder="Masukkan target TW2"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="tw2_rp" class="block text-sm font-medium text-gray-700 mb-1">TW2 Rp</label>
                                <input type="text" id="tw2_rp" name="tw2_rp" placeholder="Masukkan anggaran TW2"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <!-- TW3 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="renaksi_tw3_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW3 Target
                                </label>
                                <input type="text" id="renaksi_tw3_target" name="renaksi_tw3_target"
                                    placeholder="Masukkan target TW3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="tw3_rp" class="block text-sm font-medium text-gray-700 mb-1">TW3 Rp</label>
                                <input type="text" id="tw3_rp" name="tw3_rp" placeholder="Masukkan anggaran TW3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <!-- TW4 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="renaksi_tw4_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW4 Target
                                </label>
                                <input type="text" id="renaksi_tw4_target" name="renaksi_tw4_target"
                                    placeholder="Masukkan target TW4"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="tw4_rp" class="block text-sm font-medium text-gray-700 mb-1">TW4 Rp</label>
                                <input type="text" id="tw4_rp" name="tw4_rp" placeholder="Masukkan anggaran TW4"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>
                    </div>

                    <!-- ===== ANGGARAN TAHUN ===== -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-purple-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-calculator text-purple-600"></i>
                            Anggaran Tahun
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="anggaran_tahun" class="block text-sm font-medium text-gray-700 mb-1">
                                    ANGGARAN TAHUN <span
                                        class="text-purple-700 font-semibold">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                                </label>
                                <input type="text" id="anggaran_tahun" name="anggaran_tahun"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Masukkan anggaran tahun" />
                            </div>
                        </div>
                    </div>
                    <!-- Section 5: Realisasi Renaksi -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                        <h4 class="text-md font-semibold text-blue-800 mb-4">
                            REALISASI RENAKSI TAHUN <span class="realisasiYearText">{{ $selectedYear }}</span>
                        </h4>

                        <!-- TW1 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="realisasi_tw1_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW1 Target
                                </label>
                                <input type="text" id="realisasi_tw1_target" name="realisasi_tw1_target"
                                    placeholder="Masukkan realisasi TW1 Target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="realisasi_tw1_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW1 Rp
                                </label>
                                <input type="text" id="realisasi_tw1_rp" name="realisasi_tw1_rp"
                                    placeholder="Masukkan realisasi TW1 Rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <!-- TW2 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="realisasi_tw2_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW2 Target
                                </label>
                                <input type="text" id="realisasi_tw2_target" name="realisasi_tw2_target"
                                    placeholder="Masukkan realisasi TW2 Target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="realisasi_tw2_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW2 Rp
                                </label>
                                <input type="text" id="realisasi_tw2_rp" name="realisasi_tw2_rp"
                                    placeholder="Masukkan realisasi TW2 Rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <!-- TW3 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="realisasi_tw3_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW3 Target
                                </label>
                                <input type="text" id="realisasi_tw3_target" name="realisasi_tw3_target"
                                    placeholder="Masukkan realisasi TW3 Target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="realisasi_tw3_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW3 Rp
                                </label>
                                <input type="text" id="realisasi_tw3_rp" name="realisasi_tw3_rp"
                                    placeholder="Masukkan realisasi TW3 Rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <!-- TW4 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="realisasi_tw4_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW4 Target
                                </label>
                                <input type="text" id="realisasi_tw4_target" name="realisasi_tw4_target"
                                    placeholder="Masukkan realisasi TW4 Target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="realisasi_tw4_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW4 Rp
                                </label>
                                <input type="text" id="realisasi_tw4_rp" name="realisasi_tw4_rp"
                                    placeholder="Masukkan realisasi TW4 Rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Rumus -->
                    <div class="mb-6 p-4 bg-gray-100 rounded-lg">
                        <label for="rumus" class="block text-sm font-medium text-gray-700 mb-1">RUMUS</label>
                        <input type="text" id="rumus" name="rumus" placeholder="Masukkan rumus perhitungan"
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition">
                    </div>

                    <!-- Section 7: Catatan Khusus (Readonly untuk OPD) -->
                    <div class="mb-8 p-6 bg-amber-50 rounded-lg border-l-4 border-amber-500">
                        <h4 class="text-md font-semibold text-amber-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-check"></i> CATATAN KHUSUS INSPEKTORAT
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="catatan_evaluasi" class="block text-sm font-medium text-amber-700 mb-1">
                                    Catatan Evaluasi
                                </label>
                                <textarea id="catatan_evaluasi" name="catatan_evaluasi" rows="2" readonly
                                    placeholder="Hanya dapat diisi oleh Inspektorat"
                                    class="w-full p-2 border border-amber-300 rounded-md bg-amber-50 text-amber-800 cursor-not-allowed resize-none"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="catatan_perbaikan" class="block text-sm font-medium text-amber-700 mb-1">
                                    Catatan Perbaikan
                                </label>
                                <textarea id="catatan_perbaikan" name="catatan_perbaikan" rows="2" readonly
                                    placeholder="Hanya dapat diisi oleh Inspektorat"
                                    class="w-full p-2 border border-amber-300 rounded-md bg-amber-50 text-amber-800 cursor-not-allowed resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 8: Unit Kerja -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-gray-700 mb-4">Unit Kerja/Satuan Kerja</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="unit_kerja" class="block text-sm font-medium text-gray-700 mb-1">
                                    KOORDINATOR <span class="text-red-500">*</span>
                                </label>
                                {{-- Biarkan user memilih koordinator --}}
                                <select id="unit_kerja" name="unit_kerja" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">Pilih Koordinator</option>
                                    <option value="Inspektorat Daerah">Inspektorat Daerah</option>
                                    <option value="Bappeda">Bappeda</option>
                                    <option value="Bagian Organisasi Sekda">Bagian Organisasi Sekda</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih OPD yang menjadi koordinator</p>
                            </div>

                            <div class="form-group">
                                <label for="pelaksana" class="block text-sm font-medium text-gray-700 mb-1">
                                    PELAKSANA
                                </label>
                                <select id="pelaksana" name="pelaksana"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">Pilih Pelaksana</option>
                                    <option value="Bagian Hukum Sekda">Bagian Hukum Sekretariat Daerah</option>
                                    <option value="Bagian PBJ Setda">Bagian PBJ Setda</option>
                                    <option value="Bagian Perekonomian Setda">Bagian Perekonomian Setda</option>
                                    <option value="Bakesbangpol">Bakesbangpol</option>
                                    <option value="BPKAD">BPKAD</option>
                                    <option value="Dinas Kependudukan dan Pencatatan Sipil">Dinas Kependudukan dan
                                        Pencatatan Sipil</option>
                                    <option value="Diskominfo">Diskominfo</option>
                                    <option value="Dinas Tenaga Kerja dan Perindustrian">Dinas Tenaga Kerja dan
                                        Perindustrian</option>
                                    <option value="Dinas Kepemudaan dan Olahraga">Dinas Kepemudaan dan Olahraga</option>
                                    <option value="Dinas Perpustakaan dan Arsip Daerah">Dinas Perpustakaan dan Arsip
                                        Daerah</option>
                                    <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                                    <option value="Dinas Koperasi, Usaha Mikro, Perdagangan, dan ESDM">Dinas Koperasi,
                                        Usaha Mikro, Perdagangan, dan ESDM
                                    </option>
                                    <option value="Dinas Lingkungan Hidup">Dinas Lingkungan Hidup</option>
                                    <option value="Dinas Pangan">Dinas Pangan</option>
                                    <option value="Dinas Pangan dan Pertanian">Dinas Pangan dan Pertanian</option>
                                    <option value="Dinas Pemberdayaan Masyarakat Desa">Dinas Pemberdayaan Masyarakat
                                        Desa</option>
                                    <option value="Dinas Pengendalian Penduduk">Dinas Pengendalian Penduduk, KB, PP, dan
                                        PA</option>
                                    <option value="Dinas Perhubungan">Dinas Perhubungan</option>
                                    <option value="Dinas Perikanan">Dinas Perikanan</option>
                                    <option value="Dinas Perumahan Rakyat dan Kawasan Pemukiman">Dinas Perumahan Rakyat
                                        dan Kawasan Pemukiman</option>
                                    <option value="Dinas Pertanian">Dinas Pertanian</option>
                                    <option value="Dinas Pendidikan dan Kebudayaan">Dinas Pendidikan dan Kebudayaan
                                    </option>
                                    <option value="Dinas Pekerjaan Umum dan Penataan Ruang">Dinas Pekerjaan Umum dan
                                        Penataan Ruang</option>
                                    <option value="Dinas Sosial">Dinas Sosial</option>
                                    <option value="DPMPTSP">DPMPTSP</option>
                                    <option value="Kecamatan Belat">Kecamatan Belat</option>
                                    <option value="Kecamatan Buru">Kecamatan Buru</option>
                                    <option value="Kecamatan Durai">Kecamatan Durai</option>
                                    <option value="Kecamatan Karimun">Kecamatan Karimun</option>
                                    <option value="Kecamatan Kundur">Kecamatan Kundur</option>
                                    <option value="Kecamatan Kundur Barat">Kecamatan Kundur Barat</option>
                                    <option value="Kecamatan Kundur Utara">Kecamatan Kundur Utara</option>
                                    <option value="Kecamatan Meral">Kecamatan Meral</option>
                                    <option value="Kecamatan Meral Barat">Kecamatan Meral Barat</option>
                                    <option value="Kecamatan Moro">Kecamatan Moro</option>
                                    <option value="Kecamatan Selat Gelam">Kecamatan Selat Gelam</option>
                                    <option value="Kecamatan Sugie Besar">Kecamatan Sugie Besar</option>
                                    <option value="Kecamatan Tebing">Kecamatan Tebing</option>
                                    <option value="Kecamatan Unggar">Kecamatan Unggar</option>
                                    <option value="PTSP">PTSP</option>
                                    <option value="RSUD M.SANI">RSUD M.SANI</option>
                                    <option value="RSUD Tj Batu kundur">RSUD Tanjung Batu Kundur</option>
                                    <option value="Satpol PP">Satpol PP</option>
                                    <option value="Sekretariat Daerah">Sekretariat Daerah</option>
                                    <option value="Sekretariat DPRD">Sekretariat DPRD</option>
                                    <option value="UKPBJ">UKPBJ</option>
                                    <option value="Unit Pelayan Publik">Unit Pelayan Publik</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                        <button type="button"
                            class="px-5 py-2.5 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors flex items-center justify-center gap-2 font-medium"
                            onclick="closeModal('addModal')">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2 font-medium">Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fungsi untuk mendapatkan nomor urut berikutnya dari tabel
        function getNextNumber() {
            // Ambil semua baris data dari tabel (tbody)
            const tableRows = document.querySelectorAll('#dataTable tbody tr');
            let maxNumber = 0;
            
            tableRows.forEach(row => {
                // Ambil kolom pertama (NO)
                const noCell = row.querySelector('td:first-child');
                if (noCell) {
                    const noText = noCell.textContent.trim();
                    const noValue = parseInt(noText);
                    if (!isNaN(noValue) && noValue > maxNumber) {
                        maxNumber = noValue;
                    }
                }
            });
            
            // Kembalikan nomor berikutnya
            return maxNumber + 1;
        }

        // Fungsi untuk mengisi nomor otomatis
        function setAutoNumber() {
            const noInput = document.getElementById('no');
            if (noInput) {
                const nextNumber = getNextNumber();
                noInput.value = nextNumber;
                // Tambahkan efek visual
                noInput.style.backgroundColor = '#f0fdf4';
                setTimeout(() => {
                    noInput.style.backgroundColor = '';
                }, 500);
            }
        }

        // Panggil setAutoNumber saat modal dibuka
        const originalOpenModal = window.openModal;
        window.openModal = function(modalId) {
            if (originalOpenModal) {
                originalOpenModal(modalId);
            } else {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }
            // Set auto number saat modal tambah dibuka
            if (modalId === 'addModal') {
                setTimeout(() => setAutoNumber(), 100);
            }
        };

        // Update nomor saat tahun berubah
        const yearFilter = document.getElementById('yearFilter');
        if (yearFilter) {
            yearFilter.addEventListener('change', function() {
                setTimeout(() => setAutoNumber(), 200);
            });
        }

        const tambahForm = document.getElementById('tambahRenaksiRB');
        let isSubmitting = false;

        if (tambahForm) {
            tambahForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (isSubmitting) return;
                isSubmitting = true;

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;

                try {
                    const formData = new FormData(this);

                    // Log data untuk debugging
                    console.log('Mengirim data form:');
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const result = await response.json();
                    console.log('Response server:', result);

                    if (result.success) {
                        closeModal('addModal');
                        tambahForm.reset();

                        // Reload setelah 500ms
                        setTimeout(() => window.location.reload(), 500);
                    } else {
                        if (result.errors) {
                            let errorMessage = 'Terjadi kesalahan:\n';
                            for (const field in result.errors) {
                                errorMessage += `• ${result.errors[field][0]}\n`;
                            }
                            alert(errorMessage);
                        } else {
                            alert('Gagal menyimpan: ' + result.message);
                        }
                        isSubmitting = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                    isSubmitting = false;
                } finally {
                    if (!isSubmitting) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }
            });
        }
    });
</script>