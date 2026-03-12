{{-- resources/views/components/adminrb/tambah-modal-rb-general.blade.php --}}
<!-- Modal Tambah Data RB General (Admin) -->
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
                <form id="adminTambahRenaksiRB" method="POST" action="{{ route('adminrb.rb-general.store') }}">
                    @csrf
                    
                    <!-- Input Hidden untuk Tahun -->
                    <input type="hidden" name="tahun" id="adminAddTahun" value="{{ $selectedYear }}">

                    <!-- Header Tahun -->
                    <div class="text-center mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-indigo-700">
                            RENCANA AKSI RB GENERAL TAHUN {{ $selectedYear }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">(Input Data oleh Admin)</p>
                    </div>

                    <!-- ===== SECTION 1: INFORMASI UTAMA ===== -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-indigo-500">
                        <h4 class="text-md font-semibold text-gray-700 mb-4">INFORMASI UTAMA</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="adminNo" class="block text-sm font-medium text-gray-700 mb-1">NO</label>
                                <input type="text" id="adminNo" name="no" 
                                    placeholder="Masukkan nomor"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="adminSasaranStrategi" class="block text-sm font-medium text-gray-700 mb-1">
                                    SASARAN STRATEGI <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="adminSasaranStrategi" name="sasaran_strategi" required
                                    placeholder="Masukkan sasaran strategi"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="adminIndikator" class="block text-sm font-medium text-gray-700 mb-1">
                                    INDIKATOR <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="adminIndikator" name="indikator_capaian" required
                                    placeholder="Masukkan indikator"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="adminTarget" class="block text-sm font-medium text-gray-700 mb-1">
                                    TARGET <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="adminTarget" name="target" required
                                    placeholder="Masukkan target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="adminSatuan" class="block text-sm font-medium text-gray-700 mb-1">
                                    SATUAN <span class="text-red-500">*</span>
                                </label>
                                <select id="adminSatuan" name="satuan" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="Nilai">Nilai</option>
                                    <option value="Persen">Persen</option>
                                    <option value="Bulan">Bulan</option>
                                    <option value="OPD">OPD</option>
                                    <option value="Laporan">Laporan</option>
                                    <option value="Dokumen">Dokumen</option>
                                    <option value="Kegiatan">Kegiatan</option>
                                    <option value="Surat Keputusan">Surat Keputusan</option>
                                    <option value="Kali">Kali</option>
                                    <option value="Orang">Orang</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="adminTargetTahun" class="block text-sm font-medium text-gray-700 mb-1">
                                    TARGET TAHUN <span class="targetYearText font-semibold">{{ $selectedYear }}</span>
                                </label>
                                <input type="text" id="adminTargetTahun" name="target_tahun"
                                    placeholder="Masukkan target {{ $selectedYear }}"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECTION 2: RENCANA AKSI ===== -->
                    <div class="mb-6 p-6 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-gray-700 mb-4">RENCANA AKSI</h4>
                        
                        <div class="mb-4">
                            <textarea id="adminRencanaAksi" name="rencana_aksi" rows="3" required
                                placeholder="Masukkan rencana aksi"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="adminSatuanOutput" class="block text-sm font-medium text-gray-700 mb-1">
                                    SATUAN OUTPUT
                                </label>
                                <input type="text" id="adminSatuanOutput" name="satuan_output"
                                    placeholder="Masukkan satuan output"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="adminIndikatorOutput" class="block text-sm font-medium text-gray-700 mb-1">
                                    INDIKATOR OUTPUT
                                </label>
                                <input type="text" id="adminIndikatorOutput" name="indikator_output"
                                    placeholder="Masukkan indikator output"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECTION 3: RENAKSI PER TRIWULAN ===== -->
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
                                <input type="text" id="tw1_rp" name="tw1_rp" 
                                    placeholder="Masukkan anggaran TW1"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
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
                                <input type="text" id="tw2_rp" name="tw2_rp" 
                                    placeholder="Masukkan anggaran TW2"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
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
                                <input type="text" id="tw3_rp" name="tw3_rp" 
                                    placeholder="Masukkan anggaran TW3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
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
                                <input type="text" id="tw4_rp" name="tw4_rp" 
                                    placeholder="Masukkan anggaran TW4"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECTION 4: ANGGARAN TAHUN ===== -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-purple-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-calculator text-purple-600"></i>
                            Anggaran Tahun
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="adminAnggaranTahun" class="block text-sm font-medium text-gray-700 mb-1">
                                    ANGGARAN TAHUN <span class="text-purple-700 font-semibold">{{ $selectedYear }}</span>
                                </label>
                                <input type="text" id="adminAnggaranTahun" name="anggaran_tahun"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500 rupiah-input"
                                    placeholder="Masukkan anggaran tahun (contoh: 100.000.000)" />
                                <p class="text-xs text-gray-500 mt-1">Gunakan titik sebagai pemisah ribuan</p>
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECTION 5: REALISASI RENAKSI ===== -->
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
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
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
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
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
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
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
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECTION 6: RUMUS & CATATAN ===== -->
                    <div class="mb-6 p-6 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                        <h4 class="text-md font-semibold text-orange-800 mb-4">RUMUS</h4>
                        
                        <div class="mb-4">
                            <input type="text" id="rumus" name="rumus" 
                                placeholder="Masukkan rumus perhitungan (contoh: (Realisasi/Target) x 100%)"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition">
                        </div>

                        <h4 class="text-md font-semibold text-orange-800 mb-4 mt-6">CATATAN</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="catatan_evaluasi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Catatan Evaluasi
                                </label>
                                <textarea id="catatan_evaluasi" name="catatan_evaluasi" rows="2"
                                    placeholder="Masukkan catatan evaluasi"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="catatan_perbaikan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Catatan Perbaikan
                                </label>
                                <textarea id="catatan_perbaikan" name="catatan_perbaikan" rows="2"
                                    placeholder="Masukkan catatan perbaikan"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ===== SECTION 7: UNIT KERJA ===== -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-gray-700 mb-4">Unit Kerja/Satuan Kerja</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="unit_kerja" class="block text-sm font-medium text-gray-700 mb-1">
                                    KOORDINATOR <span class="text-red-500">*</span>
                                </label>
                                <select id="unit_kerja" name="unit_kerja" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">Pilih Unit Kerja</option>
                                    <option value="Inspektorat Daerah">Inspektorat Daerah</option>
                                    <option value="Bappeda">Bappeda</option>
                                    <option value="Bagian Organisasi Sekda">Bagian Organisasi Sekda</option>
                                    <option value="Badan Pendapatan Daerah">Badan Pendapatan Daerah</option>
                                    <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                                    <option value="Dinas Pendidikan dan Kebudayaan">Dinas Pendidikan dan Kebudayaan</option>
                                    <option value="Dinas PUPR">Dinas Pekerjaan Umum dan Penataan Ruang</option>
                                    <option value="Dinas Perhubungan">Dinas Perhubungan</option>
                                    <option value="Dinas Sosial">Dinas Sosial</option>
                                    <option value="Dinas PMPTSP">Dinas PMPTSP</option>
                                    <option value="Dinas Kependudukan dan Pencatatan Sipil">Dinas Kependudukan dan Pencatatan Sipil</option>
                                    <option value="Dinas Lingkungan Hidup">Dinas Lingkungan Hidup</option>
                                    <option value="Dinas Tenaga Kerja">Dinas Tenaga Kerja</option>
                                    <option value="Dinas Perikanan">Dinas Perikanan</option>
                                    <option value="Dinas Pertanian">Dinas Pertanian</option>
                                    <option value="Dinas Perdagangan">Dinas Perdagangan</option>
                                    <option value="Dinas Koperasi dan UKM">Dinas Koperasi dan UKM</option>
                                    <option value="Dinas Pariwisata">Dinas Pariwisata</option>
                                    <option value="Dinas Arsip dan Perpustakaan">Dinas Arsip dan Perpustakaan</option>
                                    <option value="Satpol PP">Satpol PP</option>
                                    <option value="BPKAD">BPKAD</option>
                                    <option value="BKD">BKD</option>
                                    <option value="RSUD">RSUD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pelaksana" class="block text-sm font-medium text-gray-700 mb-1">
                                    PELAKSANA
                                </label>
                                <select id="pelaksana" name="pelaksana"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="">Pilih Pelaksana</option>
                                    <option value="bagian_hukum_sekda">Bagian Hukum Sekretariat Daerah</option>
                                    <option value="bagian_pbj_setda">Bagian PBJ Setda</option>
                                    <option value="bagian_perekonomian_setda">Bagian Perekonomian Setda</option>
                                    <option value="bakesbangpol">Bakesbangpol</option>
                                    <option value="bpkad">BPKAD</option>
                                    <option value="disdukcapil">Dinas Kependudukan dan Pencatatan Sipil</option>
                                    <option value="diskominfo">Diskominfo</option>
                                    <option value="disnakerin">Dinas Tenaga Kerja dan Perindustrian</option>
                                    <option value="dispora">Dinas Kepemudaan dan Olahraga</option>
                                    <option value="dispusip">Dinas Perpustakaan dan Arsip Daerah</option>
                                    <option value="dinas_kesehatan">Dinas Kesehatan</option>
                                    <option value="dinas_koperasi">Dinas Koperasi, Usaha Mikro, Perdagangan, dan ESDM</option>
                                    <option value="dinas_lingkungan_hidup">Dinas Lingkungan Hidup</option>
                                    <option value="dinas_pangan">Dinas Pangan</option>
                                    <option value="dinas_pangan_dan_pertanian">Dinas Pangan dan Pertanian</option>
                                    <option value="dinas_pemberdayaan_masyarakat_desa">Dinas Pemberdayaan Masyarakat Desa</option>
                                    <option value="dinas_pengendalian_penduduk">Dinas Pengendalian Penduduk, KB, PP, dan PA</option>
                                    <option value="dinas_perhubungan">Dinas Perhubungan</option>
                                    <option value="dinas_perikanan">Dinas Perikanan</option>
                                    <option value="dinas_perumahan_rakyat_dan_kawasan_permukiman">Dinas Perumahan Rakyat dan Kawasan Pemukiman</option>
                                    <option value="dinas_pertanian">Dinas Pertanian</option>
                                    <option value="dinas_pendidikan_dan_kebudayaan">Dinas Pendidikan dan Kebudayaan</option>
                                    <option value="dinas_pupr">Dinas Pekerjaan Umum dan Penataan Ruang</option>
                                    <option value="dinas_sosial">Dinas Sosial</option>
                                    <option value="dpmptsp">DPMPTSP</option>
                                    <option value="ptsp">PTSP</option>
                                    <option value="rsud">RSUD M.SANI</option>
                                    <option value="satpolpp">Satpol PP</option>
                                    <option value="sekda">Sekretariat Daerah</option>
                                    <option value="sekretariat_dprd">Sekretariat DPRD</option>
                                    <option value="ukpbj">UKPBJ</option>
                                    <option value="unit_pelayan_publik">Unit Pelayan Publik</option>
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
                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2 font-medium">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format Rupiah untuk input
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('rupiah-input')) {
            let value = e.target.value;
            // Hapus semua karakter non-digit
            value = value.replace(/[^\d]/g, '');
            
            if (value !== '') {
                // Format dengan titik setiap 3 digit
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                e.target.value = value;
            } else {
                e.target.value = '';
            }
        }
    });

    // Handle form submit
    const adminForm = document.getElementById('adminTambahRenaksiRB');
    let isSubmitting = false;

    if (adminForm) {
        adminForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData(this);

                // Log data untuk debugging
                console.log('Mengirim data form (Admin):');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }

                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                console.log('Response server (Admin):', result);

                if (result.success) {
                    // Tampilkan notifikasi sukses
                    alert('Data berhasil ditambahkan!');
                    
                    // Tutup modal
                    closeModal('addModal');
                    
                    // Reset form
                    adminForm.reset();

                    // Reload halaman setelah 500ms
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    if (result.errors) {
                        let errorMessage = 'Terjadi kesalahan validasi:\n';
                        for (const field in result.errors) {
                            errorMessage += `• ${result.errors[field][0]}\n`;
                        }
                        alert(errorMessage);
                    } else {
                        alert('Gagal menyimpan: ' + (result.message || 'Unknown error'));
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

    // Update tahun di modal saat filter tahun berubah
    const yearFilter = document.getElementById('yearFilter');
    if (yearFilter) {
        yearFilter.addEventListener('change', function() {
            const selectedYear = this.value;
            const tahunInput = document.getElementById('adminAddTahun');
            const tahunSpans = document.querySelectorAll('.targetYearText, .renaksiYearText, .realisasiYearText');
            
            if (tahunInput) {
                tahunInput.value = selectedYear;
            }
            
            tahunSpans.forEach(span => {
                if (span) span.textContent = selectedYear;
            });

            // Update header tahun
            const headerTahun = document.querySelector('#addModal h2');
            if (headerTahun) {
                headerTahun.innerHTML = `RENCANA AKSI RB GENERAL TAHUN ${selectedYear}`;
            }

            // Update semua teks tahun yang ada
            const allYearSpans = document.querySelectorAll('[class*="YearText"]');
            allYearSpans.forEach(span => {
                if (span) span.textContent = selectedYear;
            });
        });
    }
});
</script>