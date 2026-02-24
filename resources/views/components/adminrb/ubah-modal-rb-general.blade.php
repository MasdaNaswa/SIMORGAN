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

                    <!-- Bagian Header -->
                    <div class="text-center mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-amber-700">
                            UBAH RENCANA AKSI RB GENERAL TAHUN <span id="editModalYear">{{ $selectedYear }}</span>
                        </h2>
                    </div>

                    <!-- Informasi Utama -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-amber-500">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="editNo" class="block text-sm font-medium text-gray-700 mb-1">NO</label>
                                <input type="text" id="editNo" name="no"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan nomor" />
                            </div>
                            <div class="form-group">
                                <label for="editSasaranStrategi"
                                    class="block text-sm font-medium text-gray-700 mb-1">SASARAN STRATEGI</label>
                                <select id="editSasaranStrategi" name="sasaran_strategi" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Sasaran Strategi</option>
                                    <option value="Terwujudnya Transformasi Digital">Terwujudnya Transformasi Digital</option>
                                    <option value="Terciptanya Aparatur Negara yang Kompeten dan Berkinerja Tinggi Berdasarkan Sistem Merit">Terciptanya Aparatur Negara yang Kompeten dan Berkinerja Tinggi Berdasarkan Sistem Merit</option>
                                    <option value="Terbangunnya Perilaku Birokrasi yang Beretika dan Inovatif">Terbangunnya Perilaku Birokrasi yang Beretika dan Inovatif</option>
                                    <option value="Terbangunnya Kapabilitas Kelembagaan Berkinerja Tinggi yang berbaris Jejaring dan Lincah">Terbangunnya Kapabilitas Kelembagaan Berkinerja Tinggi yang berbaris Jejaring dan Lincah</option>
                                    <option value="Terwujudnya Kebijakan dan Pelayanan Publik yang Berkualitas dan Inklusif">Terwujudnya Kebijakan dan Pelayanan Publik yang Berkualitas dan Inklusif</option>
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
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editTargetTahun" class="block text-sm font-medium text-gray-700 mb-1">
                                    TARGET TAHUN <span class="editTargetYearText">{{ $selectedYear }}</span>
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
                                    class="block text-sm font-medium text-gray-700 mb-1">SATUAN OUTPUT</label>
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
                                <label for="editTw1Rp" class="block text-sm font-medium text-gray-700 mb-1">TW1 Rp</label>
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
                                <label for="editTw2Rp" class="block text-sm font-medium text-gray-700 mb-1">TW2 Rp</label>
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
                                <label for="editTw3Rp" class="block text-sm font-medium text-gray-700 mb-1">TW3 Rp</label>
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
                                <label for="editTw4Rp" class="block text-sm font-medium text-gray-700 mb-1">TW4 Rp</label>
                                <input type="text" id="editTw4Rp" name="tw4_rp"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan anggaran TW4" />
                            </div>
                        </div>
                    </div>

                    <!-- Anggaran Tahun -->
                    <div class="mb-6 p-4 bg-amber-50 rounded-lg">
                        <label for="editAnggaranTahun" class="block text-sm font-medium text-amber-700 mb-1">
                            <i class="fas fa-money-bill-wave text-amber-600"></i>
                            ANGGARAN TAHUN <span class="editAnggaranYearText">{{ $selectedYear }}</span>
                        </label>
                        <input type="text" id="editAnggaranTahun" name="anggaran_tahun"
                            placeholder="Masukkan anggaran {{ $selectedYear }}"
                            class="w-full p-2 border border-amber-300 rounded-md focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-amber-50">
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
                                <label for="editRealisasiTw1Rp" class="block text-sm font-medium text-gray-700 mb-1">TW1 Rp</label>
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
                                <label for="editRealisasiTw2Rp" class="block text-sm font-medium text-gray-700 mb-1">TW2 Rp</label>
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
                                <label for="editRealisasiTw3Rp" class="block text-sm font-medium text-gray-700 mb-1">TW3 Rp</label>
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
                                <label for="editRealisasiTw4Rp" class="block text-sm font-medium text-gray-700 mb-1">TW4 Rp</label>
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
                                    class="w-full p-2 border border-amber-300 rounded-md bg-amber-100 text-amber-800 focus:ring-amber-500 focus:border-amber-500"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editCatatanPerbaikan"
                                    class="block text-sm font-medium text-amber-700 mb-1">Catatan Perbaikan</label>
                                <textarea id="editCatatanPerbaikan" name="catatan_perbaikan" rows="2"
                                    class="w-full p-2 border border-amber-300 rounded-md bg-amber-100 text-amber-800 focus:ring-amber-500 focus:border-amber-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Kerja -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-amber-500">
                        <h4 class="text-md font-semibold text-amber-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-building text-amber-600"></i>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="editUnitKerja" class="block text-sm font-medium text-amber-700 mb-1">KOORDINATOR</label>
                                <select id="editUnitKerja" name="unit_kerja" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Unit Kerja</option>
                                    <option value="Diskominfo">Diskominfo</option>
                                    <option value="Baperlitbang">Baperlitbang</option>
                                    <option value="BPKAD">BPKAD</option>
                                    <option value="Bagian Organisasi Sekda">Bagian Organisasi Sekda</option>
                                    <option value="Inspektorat Daerah">Inspektorat Daerah</option>
                                    <option value="BKPSDM">BKPSDM</option>
                                    <option value="Bagian Hukum Sekretariat Daerah">Bagian Hukum Sekretariat Daerah</option>
                                    <option value="Bagian PBJ Setda">Bagian PBJ Setda</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editPelaksana"
                                    class="block text-sm font-medium text-amber-700 mb-1">PELAKSANA</label>
                                <input type="text" id="editPelaksana" name="pelaksana"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan pelaksana" />
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="button"
                            class="px-4 py-2 bg-gray-500 text-black rounded-md hover:bg-gray-600 transition flex items-center justify-center gap-2"
                            onclick="closeModal('editModal')">Batal
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