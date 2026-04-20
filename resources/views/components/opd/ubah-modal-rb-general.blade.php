<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-[9999]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-amber-600 text-white p-6 rounded-t-lg sticky top-0 z-10">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <i class="fas fa-edit"></i> Ubah Rencana Aksi RB General
                </h3>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="editRenaksiRB" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    <input type="hidden" id="editIsInspektorat" name="is_inspektorat" value="{{ $isInspektorat ?? false }}">
                    <input type="hidden" id="editIsAdmin" name="is_admin" value="{{ Auth::user()->role === 'admin' ? 1 : 0 }}">

                    <!-- Bagian Header -->
                    <div class="text-center mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-amber-700">
                            UBAH RENCANA AKSI RB GENERAL TAHUN {{ $selectedYear }}
                        </h2>
                    </div>

                    <!-- Informasi Utama -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-amber-500">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editNo" class="block text-sm font-medium text-gray-700 mb-1">
                                    NO
                                </label>
                                <input type="text" id="editNo" name="no" readonly disabled
                                    class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed select-none"
                                    style="pointer-events: none; user-select: none;"
                                    placeholder="Masukkan nomor" />
                            </div>
                            <div class="form-group">
                                <label for="editSasaranStrategi"
                                    class="block text-sm font-medium text-gray-700 mb-1">SASARAN STRATEGI</label>
                                <select id="editSasaranStrategi" name="sasaran_strategi" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Sasaran Strategi</option>
                                    <option value="Terwujudnya Transformasi Digital">Terwujudnya Transformasi Digital
                                    </option>
                                    <option
                                        value="Terciptanya Aparatur Negara yang Kompeten dan Berkinerja Tinggi Berdasarkan Sistem Merit">
                                        Terciptanya Aparatur Negara yang Kompeten dan Berkinerja Tinggi Berdasarkan
                                        Sistem
                                        Merit</option>
                                    <option value="Terbangunnya Perilaku Birokrasi yang Beretika dan Inovatif">
                                        Terbangunnya
                                        Perilaku Birokrasi yang Beretika dan Inovatif</option>
                                    <option
                                        value="Terbangunnya Kapabilitas Kelembagaan Berkinerja Tinggi yang berbaris Jejaring dan Lincah">
                                        Terbangunnya Kapabilitas Kelembagaan Berkinerja Tinggi yang berbaris Jejaring
                                        dan
                                        Lincah</option>
                                    <option
                                        value="Terwujudnya Kebijakan dan Pelayanan Publik yang Berkualitas dan Inklusif">
                                        Terwujudnya Kebijakan dan Pelayanan Publik yang Berkualitas dan Inklusif
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editIndikator"
                                    class="block text-sm font-medium text-gray-700 mb-1">INDIKATOR</label>
                                <input type="text" id="editIndikator" name="indikator_capaian" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan indikator" />
                            </div>
                            <div class="form-group">
                                <label for="editTarget"
                                    class="block text-sm font-medium text-gray-700 mb-1">TARGET</label>
                                <input type="text" id="editTarget" name="target" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan target" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="editSatuan"
                                    class="block text-sm font-medium text-gray-700 mb-1">SATUAN</label>
                                <select id="editSatuan" name="satuan" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">--Pilih Satuan--</option>
                                    <option value="Nilai">Nilai</option>
                                    <option value="Persen">Persen</option>
                                    <option value="Bulan">Bulan</option>
                                    <option value="OPD">OPD</option>
                                    <option value="Laporan">Laporan</option>
                                    <option value="Surat Keputusan">Surat Keputusan</option>
                                    <option value="Dan Lain-Lain">Dan Lain-Lain</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editTargetTahun" class="block text-sm font-medium text-gray-700 mb-1">
                                    TARGET TAHUN <span class="editTargetYearText">{{ $selectedYear}}</span>
                                </label>
                                <input type="text" id="editTargetTahun" name="target_tahun"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan target tahun" />
                            </div>
                        </div>
                    </div>

                    <!-- Rencana Aksi -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-amber-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-amber-600"></i>
                            RENCANA AKSI
                        </h4>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RENCANA AKSI</label>
                        <textarea id="editRencanaAksi" name="rencana_aksi" rows="3" required
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                            placeholder="Masukkan rencana aksi"></textarea>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="form-group">
                                <label for="editSatuanOutput"
                                    class="block text-sm font-medium text-gray-700 mb-1">SATUAN
                                    OUTPUT</label>
                                <input type="text" id="editSatuanOutput" name="satuan_output"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan satuan output" />
                            </div>
                            <div class="form-group">
                                <label for="editIndikatorOutput"
                                    class="block text-sm font-medium text-gray-700 mb-1">INDIKATOR OUTPUT</label>
                                <input type="text" id="editIndikatorOutput" name="indikator_output"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan indikator output" />
                            </div>
                        </div>
                    </div>

                    <!-- Renaksi Tahun -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                            Renaksi Tahun <span class="editRenaksiYearText">{{ $selectedYear }}</span>
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editRenaksiTw1Target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TW1 Target</label>
                                <input type="text" id="editRenaksiTw1Target" name="renaksi_tw1_target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan target TW1" />
                            </div>
                            <div class="form-group">
                                <label for="editTw1Rp" class="block text-sm font-medium text-gray-700 mb-1">TW1
                                    Rp</label>
                                <input type="text" id="editTw1Rp" name="tw1_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan anggaran TW1" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editRenaksiTw2Target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TW2 Target</label>
                                <input type="text" id="editRenaksiTw2Target" name="renaksi_tw2_target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan target TW2" />
                            </div>
                            <div class="form-group">
                                <label for="editTw2Rp" class="block text-sm font-medium text-gray-700 mb-1">TW2
                                    Rp</label>
                                <input type="text" id="editTw2Rp" name="tw2_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan anggaran TW2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editRenaksiTw3Target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TW3 Target</label>
                                <input type="text" id="editRenaksiTw3Target" name="renaksi_tw3_target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan target TW3" />
                            </div>
                            <div class="form-group">
                                <label for="editTw3Rp" class="block text-sm font-medium text-gray-700 mb-1">TW3
                                    Rp</label>
                                <input type="text" id="editTw3Rp" name="tw3_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan anggaran TW3" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="editRenaksiTw4Target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TW4 Target</label>
                                <input type="text" id="editRenaksiTw4Target" name="renaksi_tw4_target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan target TW4" />
                            </div>
                            <div class="form-group">
                                <label for="editTw4Rp" class="block text-sm font-medium text-gray-700 mb-1">TW4
                                    Rp</label>
                                <input type="text" id="editTw4Rp" name="tw4_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan anggaran TW4" />
                            </div>
                        </div>
                    </div>


                    <!-- Anggaran Tahun -->
                    <div class="mb-6 p-4 bg-amber-50 rounded-lg">
                        <label for="anggaran_tahun" class="block text-sm font-medium text-amber-700 mb-1">
                            <i class="fas fa-money-bill-wave text-amber-600"></i>
                            ANGGARAN TAHUN <span
                                class="editanggaranYearText">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                        </label>
                        <input type="text" id="editAnggaranTahun" name="anggaran_tahun"
                            placeholder="Masukkan anggaran {{ $selectedYear ?? $currentYear ?? date('Y') }}"
                            class="w-full p-2 border border-amber-300 rounded-md focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition rupiah-input bg-amber-50">
                    </div>

                    <!-- Realisasi Renaksi Tahun -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-green-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-600"></i>
                            Realisasi Renaksi Tahun <span class="editRealisasiYearText">{{ $selectedYear }}</span>
                        </h4>

                        <!-- TW1 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editRealisasiTw1Target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TW1 Target</label>
                                <input type="text" id="editRealisasiTw1Target" name="realisasi_tw1_target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan realisasi TW1 Target" />
                            </div>
                            <div class="form-group">
                                <label for="editRealisasiTw1Rp" class="block text-sm font-medium text-gray-700 mb-1">TW1
                                    Rp</label>
                                <input type="text" id="editRealisasiTw1Rp" name="realisasi_tw1_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan realisasi TW1 Rp" />
                            </div>
                        </div>

                        <!-- TW2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editRealisasiTw2Target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TW2 Target</label>
                                <input type="text" id="editRealisasiTw2Target" name="realisasi_tw2_target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan realisasi TW2 Target" />
                            </div>
                            <div class="form-group">
                                <label for="editRealisasiTw2Rp" class="block text-sm font-medium text-gray-700 mb-1">TW2
                                    Rp</label>
                                <input type="text" id="editRealisasiTw2Rp" name="realisasi_tw2_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan realisasi TW2 Rp" />
                            </div>
                        </div>

                        <!-- TW3 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editRealisasiTw3Target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TW3 Target</label>
                                <input type="text" id="editRealisasiTw3Target" name="realisasi_tw3_target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan realisasi TW3 Target" />
                            </div>
                            <div class="form-group">
                                <label for="editRealisasiTw3Rp" class="block text-sm font-medium text-gray-700 mb-1">TW3
                                    Rp</label>
                                <input type="text" id="editRealisasiTw3Rp" name="realisasi_tw3_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan realisasi TW3 Rp" />
                            </div>
                        </div>

                        <!-- TW4 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="editRealisasiTw4Target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TW4 Target</label>
                                <input type="text" id="editRealisasiTw4Target" name="realisasi_tw4_target"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan realisasi TW4 Target" />
                            </div>
                            <div class="form-group">
                                <label for="editRealisasiTw4Rp" class="block text-sm font-medium text-gray-700 mb-1">TW4
                                    Rp</label>
                                <input type="text" id="editRealisasiTw4Rp" name="realisasi_tw4_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan realisasi TW4 Rp" />
                            </div>
                        </div>
                    </div>

                    <!-- Rumus -->
                    <div class="mb-4 p-6 bg-gray-50 rounded-lg">
                        <label for="editRumus" class="block text-sm font-medium text-amber-700 mb-1">
                            <i class="fas fa-calculator text-amber-600"></i> RUMUS
                        </label>
                        <input type="text" id="editRumus" name="rumus"
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 font-mono"
                            placeholder="Masukkan rumus perhitungan" />
                    </div>

                    <!-- Catatan Khusus -->
                    <div class="mb-6 p-6 bg-amber-50 rounded-lg border-l-4 border-amber-500">
                        <h4 class="text-md font-semibold text-amber-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-check text-amber-600"></i> Catatan Khusus Inspektorat
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="form-group">
        <label for="editCatatanEvaluasi"
            class="block text-sm font-medium text-amber-700 mb-1">Catatan Evaluasi</label>
        <textarea id="editCatatanEvaluasi" name="catatan_evaluasi" rows="2"
            class="w-full p-2 border border-amber-300 rounded-md bg-amber-100 text-amber-800 focus:ring-amber-500 focus:border-amber-500 catatan-field"
            {{-- Hanya Admin dan Inspektorat yang bisa edit --}}
            @if(Auth::user()->role !== 'admin' && Auth::user()->unit_kerja !== 'Inspektorat Daerah')
                readonly disabled
                style="pointer-events: none; cursor: not-allowed; background-color: #fef3c7;"
            @endif
        ></textarea>
    </div>
    <div class="form-group">
        <label for="editCatatanPerbaikan"
            class="block text-sm font-medium text-amber-700 mb-1">Catatan Perbaikan</label>
        <textarea id="editCatatanPerbaikan" name="catatan_perbaikan" rows="2"
            class="w-full p-2 border border-amber-300 rounded-md bg-amber-100 text-amber-800 focus:ring-amber-500 focus:border-amber-500 catatan-field"
            @if(Auth::user()->role !== 'admin' && Auth::user()->unit_kerja !== 'Inspektorat Daerah')
                readonly disabled
                style="pointer-events: none; cursor: not-allowed; background-color: #fef3c7;"
            @endif
        ></textarea>
    </div>
</div>
                    </div>

                    <!-- Unit Kerja -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-amber-500">
                        <h4 class="text-md font-semibold text-amber-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-building text-amber-600"></i> Unit Kerja
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="editUnitKerja" class="block text-sm font-medium text-amber-700 mb-1">UNIT
                                    KERJA</label>
                                <select id="editUnitKerja" name="unit_kerja" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Unit Kerja</option>
                                    <option value="Inspektorat Daerah">Inspektorat Daerah</option>
                                    <option value="Bagian Organisasi Sekda">Bagian Organisasi Sekda</option>
                                    <option value="Bappeda">Bappeda</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editPelaksana"
                                    class="block text-sm font-medium text-amber-700 mb-1">PELAKSANA</label>
                                <select id="editPelaksana" name="pelaksana"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Pelaksana</option>
                                    <!-- Inspektorat & Sekretariat -->
                                    <option value="Inspektorat Daerah">Inspektorat Daerah</option>
                                    <option value="Sekretariat Daerah">Sekretariat Daerah</option>
                                    <option value="Sekretariat DPRD">Sekretariat DPRD</option>
                                    <option value="Bagian Hukum Sekretariat Daerah">Bagian Hukum Sekretariat Daerah
                                    </option>
                                    <option value="Bagian PBJ Setda">Bagian PBJ Setda</option>
                                    <option value="Bagian Perekonomian Setda">Bagian Perekonomian Setda</option>
                                    <option value="Bagian Tata Pembangunan">Bagian Tata Pembangunan</option>
                                    <!-- Badan -->
                                    <option value="Badan Pendapatan Daerah">Badan Pendapatan Daerah</option>
                                    <option value="Baperlitbang Kabupaten Karimun">Baperlitbang Kabupaten Karimun
                                    </option>
                                    <option value="BPKAD">BPKAD</option>
                                    <option value="Bakesbangpol">Bakesbangpol</option>

                                    <!-- Dinas -->
                                    <option value="Dinas Penanaman Modal dan Pelayanan Satu Pintu">Dinas Penanaman Modal
                                        dan Pelayanan Satu Pintu</option>
                                    <option value="Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu">Dinas
                                        Penanaman Modal dan Pelayanan Terpadu Satu Pintu</option>
                                    <option value="Dinas Pangan dan Pertanian">Dinas Pangan dan Pertanian</option>
                                    <option value="Dinas Pangan">Dinas Pangan</option>
                                    <option value="Dinas Perikanan">Dinas Perikanan</option>
                                    <option value="Dinas Pekerjaan Umum dan Penataan Ruang">Dinas Pekerjaan Umum dan
                                        Penataan Ruang</option>
                                    <option value="Dinas Perhubungan">Dinas Perhubungan</option>
                                    <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                                    <option value="Dinas Pendidikan dan Kebudayaan">Dinas Pendidikan dan Kebudayaan
                                    </option>
                                    <option
                                        value="Dinas Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan dan Perlindungan Anak">
                                        Dinas Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan dan
                                        Perlindungan Anak</option>
                                    <option value="Dinas Pemberdayaan Perempuan dan Perlindungan Anak">Dinas
                                        Pemberdayaan Perempuan dan Perlindungan Anak</option>
                                    <option value="Dinas Kepemudaan dan Olahraga">Dinas Kepemudaan dan Olahraga</option>
                                    <option value="Dinas Kepemudaan, dan Olahraga">Dinas Kepemudaan, dan Olahraga
                                    </option>
                                    <option value="Dinas Sosial">Dinas Sosial</option>
                                    <option value="Dinas Tenaga Kerja dan Perindustrian">Dinas Tenaga Kerja dan
                                        Perindustrian</option>
                                    <option value="Dinas Lingkungan Hidup">Dinas Lingkungan Hidup</option>
                                    <option value="Dinas Koperasi, Usaha Mikro, Perdagangan, dan ESDM">Dinas Koperasi,
                                        Usaha Mikro, Perdagangan, dan ESDM</option>
                                    <option value="Dinas Pariwisata">Dinas Pariwisata</option>
                                    <option value="Dinas Pemberdayaan Masyarakat Desa">Dinas Pemberdayaan Masyarakat
                                        Desa</option>
                                    <option value="Dinas Perumahan Rakyat dan Kawasan Pemukiman">Dinas Perumahan Rakyat
                                        dan Kawasan Pemukiman</option>
                                    <option value="Dinas Pertanian">Dinas Pertanian</option>
                                    <option value="Dinas Kependudukan dan Pencatatan Sipil">Dinas Kependudukan dan
                                        Pencatatan Sipil</option>
                                    <option value="Dinas Perpustakaan dan Arsip Daerah">Dinas Perpustakaan dan Arsip
                                        Daerah</option>
                                    <option value="DPMPTSP">DPMPTSP</option>
                                    <option value="Diskominfo">Diskominfo</option>
                                    <option value="Satpol PP">Satpol PP</option>

                                    <!-- Kecamatan -->
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
                                    <option value="Kecamatan Ungar">Kecamatan Ungar</option>

                                    <!-- Rumah Sakit -->
                                    <option value="RSUD M.SANI">RSUD M.SANI</option>
                                    <option value="RSUD Tanjung Batu Kundur">RSUD Tanjung Batu Kundur</option>

                                    <!-- Unit Lainnya -->
                                    <option value="PTSP">PTSP</option>
                                    <option value="UKPBJ">UKPBJ</option>
                                    <option value="Unit Pelayan Publik">Unit Pelayan Publik</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="button"
                            class="px-4 py-2 bg-gray-500 text-black rounded-md hover:bg-gray-600 transition flex items-center justify-center gap-2"
                            onclick="closeModal('editModal')"> Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition flex items-center justify-center gap-2">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menutup modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Fungsi untuk mengatur akses edit berdasarkan role
    function setEditAccess(isInspektorat, isAdmin) {
        // Semua field selain NO dan catatan
        const fields = [
            'editSasaranStrategi', 'editIndikator', 'editTarget', 'editSatuan',
            'editTargetTahun', 'editRencanaAksi', 'editSatuanOutput', 'editIndikatorOutput',
            'editRenaksiTw1Target', 'editTw1Rp', 'editRenaksiTw2Target', 'editTw2Rp',
            'editRenaksiTw3Target', 'editTw3Rp', 'editRenaksiTw4Target', 'editTw4Rp',
            'editAnggaranTahun', 'editRealisasiTw1Target', 'editRealisasiTw1Rp',
            'editRealisasiTw2Target', 'editRealisasiTw2Rp', 'editRealisasiTw3Target',
            'editRealisasiTw3Rp', 'editRealisasiTw4Target', 'editRealisasiTw4Rp',
            'editRumus', 'editUnitKerja', 'editPelaksana'
        ];

        // NO field - selalu tidak bisa diakses
        const noField = document.getElementById('editNo');
        if (noField) {
            noField.disabled = true;
            noField.readOnly = true;
            noField.style.pointerEvents = 'none';
            noField.style.userSelect = 'none';
            noField.style.backgroundColor = '#f3f4f6';
        }

        // Catatan fields
        const catatanFields = ['editCatatanEvaluasi', 'editCatatanPerbaikan'];

        if (isAdmin === 1) {
            // ADMIN RB: Semua field (selain NO) bisa diedit, catatan juga bisa diedit
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.disabled = false;
                    field.readOnly = false;
                    field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    field.style.pointerEvents = 'auto';
                }
            });
            catatanFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.disabled = false;
                    field.readOnly = false;
                    field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    field.style.pointerEvents = 'auto';
                    field.style.backgroundColor = '#fffbeb';
                }
            });
        } else if (isInspektorat === true) {
            // INSPEKTORAT: Hanya catatan yang bisa diedit
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.disabled = true;
                    field.readOnly = true;
                    field.classList.add('bg-gray-100', 'cursor-not-allowed');
                    field.style.pointerEvents = 'none';
                }
            });
            catatanFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.disabled = false;
                    field.readOnly = false;
                    field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    field.style.pointerEvents = 'auto';
                    field.style.backgroundColor = '#fffbeb';
                }
            });
            // Ubah judul modal untuk Inspektorat
            const modalTitle = document.querySelector('#editModal h3');
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="fas fa-pen"></i> Edit Catatan RB General (Mode Inspektorat)';
            }
        } else {
            // OPD LAIN: Semua field (selain NO) bisa diedit, catatan tidak bisa diedit
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.disabled = false;
                    field.readOnly = false;
                    field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    field.style.pointerEvents = 'auto';
                }
            });
            catatanFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.disabled = true;
                    field.readOnly = true;
                    field.classList.add('bg-gray-100', 'cursor-not-allowed');
                    field.style.pointerEvents = 'none';
                    field.style.backgroundColor = '#f3f4f6';
                }
            });
        }
    }

    // Fungsi untuk membuka modal edit dengan data
    async function openEditModal(id) {
        try {
            console.log('Membuka edit modal untuk ID:', id);

            openModal('editModal');

            const response = await fetch(`/rb-general/${id}/edit`);
            const result = await response.json();

            console.log('Data untuk edit:', result.data);

            if (result.success) {
                const data = result.data;

                // Isi form dengan data
                document.getElementById('editId').value = data.id;
                document.getElementById('editNo').value = data.no || '';
                document.getElementById('editSasaranStrategi').value = data.sasaran_strategi || '';
                document.getElementById('editIndikator').value = data.indikator_capaian || '';
                document.getElementById('editTarget').value = data.target || '';
                document.getElementById('editSatuan').value = data.satuan || '';
                document.getElementById('editTargetTahun').value = data.target_tahun || '';
                document.getElementById('editRencanaAksi').value = data.rencana_aksi || '';
                document.getElementById('editSatuanOutput').value = data.satuan_output || '';
                document.getElementById('editIndikatorOutput').value = data.indikator_output || '';

                // Renaksi per TW
                document.getElementById('editRenaksiTw1Target').value = data.renaksi_tw1_target || '';
                document.getElementById('editTw1Rp').value = data.tw1_rp || '';
                document.getElementById('editRenaksiTw2Target').value = data.renaksi_tw2_target || '';
                document.getElementById('editTw2Rp').value = data.tw2_rp || '';
                document.getElementById('editRenaksiTw3Target').value = data.renaksi_tw3_target || '';
                document.getElementById('editTw3Rp').value = data.tw3_rp || '';
                document.getElementById('editRenaksiTw4Target').value = data.renaksi_tw4_target || '';
                document.getElementById('editTw4Rp').value = data.tw4_rp || '';

                // Anggaran
                document.getElementById('editAnggaranTahun').value = data.anggaran_tahun || '';

                // REALISASI
                document.getElementById('editRealisasiTw1Target').value = data.realisasi_tw1_target || '';
                document.getElementById('editRealisasiTw1Rp').value = data.realisasi_tw1_rp || '';
                document.getElementById('editRealisasiTw2Target').value = data.realisasi_tw2_target || '';
                document.getElementById('editRealisasiTw2Rp').value = data.realisasi_tw2_rp || '';
                document.getElementById('editRealisasiTw3Target').value = data.realisasi_tw3_target || '';
                document.getElementById('editRealisasiTw3Rp').value = data.realisasi_tw3_rp || '';
                document.getElementById('editRealisasiTw4Target').value = data.realisasi_tw4_target || '';
                document.getElementById('editRealisasiTw4Rp').value = data.realisasi_tw4_rp || '';

                // Lainnya
                document.getElementById('editRumus').value = data.rumus || '';
                document.getElementById('editCatatanEvaluasi').value = data.catatan_evaluasi || '';
                document.getElementById('editCatatanPerbaikan').value = data.catatan_perbaikan || '';
                document.getElementById('editUnitKerja').value = data.unit_kerja || '';
                document.getElementById('editPelaksana').value = data.pelaksana || '';

                // Set akses edit berdasarkan role
                const isInspektorat = data.isInspektorat || false;
                const isAdmin = document.getElementById('editIsAdmin')?.value === '1';
                setEditAccess(isInspektorat, isAdmin);

                console.log('Form edit berhasil diisi');

            } else {
                closeModal('editModal');
                alert('Gagal mengambil data untuk edit: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            closeModal('editModal');
            alert('Terjadi kesalahan saat mengambil data');
        }
    }

    // Handle submit form edit
    document.addEventListener('DOMContentLoaded', function () {
        const editForm = document.getElementById('editRenaksiRB');
        let isSubmitting = false;

        if (editForm) {
            editForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (isSubmitting) return;
                isSubmitting = true;

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;

                try {
                    const id = document.getElementById('editId').value;
                    const formData = new FormData(this);

                    // Cek apakah ini mode Inspektorat (hanya catatan yang dikirim)
                    const isInspektorat = document.getElementById('editIsInspektorat')?.value === '1';
                    
                    if (isInspektorat) {
                        // Hanya kirim catatan
                        const fieldsToKeep = ['_token', '_method', 'catatan_evaluasi', 'catatan_perbaikan'];
                        for (let [key] of formData.entries()) {
                            if (!fieldsToKeep.includes(key)) {
                                formData.delete(key);
                            }
                        }
                    }

                    console.log('=== DATA YANG DIKIRIM ===');
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    const response = await fetch(`/rb-general/${id}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const result = await response.json();
                    console.log('Response update:', result);

                    if (result.success) {
                        closeModal('editModal');
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        if (result.errors) {
                            let errorMessage = 'Terjadi kesalahan:\n';
                            for (const field in result.errors) {
                                errorMessage += `- ${result.errors[field][0]}\n`;
                            }
                            alert(errorMessage);
                        } else {
                            alert('Gagal memperbarui data: ' + result.message);
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui data: ' + error.message);
                } finally {
                    isSubmitting = false;
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        }
    });
</script>