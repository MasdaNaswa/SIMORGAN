<div id="editModal" class="fixed inset-0 z-[9999] hidden">
    <div class="flex items-center justify-center w-full h-full bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-amber-600 text-white p-6 rounded-t-lg flex-shrink-0">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <i class="fas fa-edit mr-2"></i>
                    Ubah Rencana Aksi RB Tematik <span id="editTahunHeader"></span>
                </h3>
            </div>

            <!-- Modal Body dengan Form -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Bagian Header -->
                <div class="text-center mb-6 pb-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-amber-700">
                        UBAH RENCANA AKSI RB TEMATIK TAHUN {{ $selectedYear }}
                    </h2>
                </div>
                <form id="editRenaksiRB" method="POST" action="" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" name="tahun" value="{{ $selectedYear ?? $currentYear ?? date('Y') }}"
                        id="editTahunAnggaran">

                    <!-- INFORMASI DASAR -->
                    <div class="p-6 bg-gray-50 rounded-lg border-l-4 border-amber-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-amber-600"></i>
                            Informasi Dasar
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NO</label>
                                <input type="text" id="edit_no" readonly
                                    class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                            </div>
                            <div>
                                <label for="edit_permasalahan" class="block text-sm font-medium text-gray-700 mb-1">
                                    PERMASALAHAN <span class="text-red-500"></span>
                                </label>
                                <select id="edit_permasalahan" name="permasalahan" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Permasalahan</option>
                                    <option value="Penanggulangan Kemiskinan">Penanggulangan Kemiskinan</option>
                                    <option value="Peningkatan Investasi">Peningkatan Investasi</option>
                                    <option value="Mendukung Ketahanan Pangan Nasional">Mendukung Ketahanan Pangan
                                        Nasional</option>
                                    <option value="Pengelolaan Sumber Daya dan Hilirisasi">Pengelolaan Sumber Daya dan
                                        Hilirisasi</option>
                                    <option value="Peningkatan Kualitas dan Akses Layanan Kesehatan">Peningkatan
                                        Kualitas dan Akses Layanan Kesehatan</option>
                                    <option value="Peningkatan Akses, Kualitas dan Mutu Layanan Pendidikan">Peningkatan
                                        Akses, Kualitas dan Mutu Layanan Pendidikan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="edit_sasaran_tematik"
                                    class="block text-sm font-medium text-gray-700 mb-1">SASARAN TEMATIK</label>
                                <input type="text" id="edit_sasaran_tematik" name="sasaran_tematik" maxlength="100"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                            </div>
                            <div>
                                <label for="edit_indikator"
                                    class="block text-sm font-medium text-gray-700 mb-1">INDIKATOR</label>
                                <input type="text" id="edit_indikator" name="indikator" maxlength="100"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="edit_target"
                                    class="block text-sm font-medium text-gray-700 mb-1">TARGET</label>
                                <input type="text" id="edit_target" name="target" maxlength="100"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                            </div>
                            <div>
                                <label for="edit_satuan"
                                    class="block text-sm font-medium text-gray-700 mb-1">SATUAN</label>
                                <input type="text" id="edit_satuan" name="satuan" maxlength="100"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                            </div>
                        </div>

                        <!-- TARGET TAHUN -->
                        <div class="mt-4">
                            <label for="edit_target_tahun" class="block text-sm font-medium text-gray-700 mb-1">
                                TARGET TAHUN {{ $selectedYear }}
                            </label>
                            <input type="text" id="edit_target_tahun" name="target_tahun" maxlength="100"
                                placeholder="Masukkan target tahun {{ $selectedYear ?? $currentYear ?? date('Y') }}"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                required />
                        </div>

                        <div class="mt-4">
                            <label for="edit_rencana_aksi" class="block text-sm font-medium text-gray-700 mb-1">RENCANA
                                AKSI</label>
                            <textarea id="edit_rencana_aksi" name="rencana_aksi" rows="3"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="edit_satuan_output"
                                    class="block text-sm font-medium text-gray-700 mb-1">SATUAN OUTPUT</label>
                                <input type="text" id="edit_satuan_output" name="satuan_output" maxlength="255"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                            </div>
                            <div>
                                <label for="edit_indikator_output"
                                    class="block text-sm font-medium text-gray-700 mb-1">INDIKATOR OUTPUT</label>
                                <input type="text" id="edit_indikator_output" name="indikator_output" maxlength="255"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                            </div>
                        </div>
                    </div>

                    <!-- RENCANA AKSI DAN ANGGARAN PER TRIWULAN -->
                    <div class="p-6 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                            Rencana Aksi dan Anggaran per Triwulan
                        </h4>

                        @foreach (['1', '2', '3', '4'] as $tw)
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                <div>
                                    <label for="edit_tw{{ $tw }}_target"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $tw }} - Target
                                    </label>
                                    <input type="text" id="edit_tw{{ $tw }}_target" name="tw{{ $tw }}_target" {{--
                                        PERBAIKAN: gunakan tw1_target, bukan renaksi_tw1_target --}} maxlength="255"
                                        placeholder="Masukkan target TW{{ $tw }}"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                                </div>
                                <div>
                                    <label for="edit_tw{{ $tw }}_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $tw }} - Anggaran (Rp)
                                    </label>
                                    <input type="text" id="edit_tw{{ $tw }}_rp" name="tw{{ $tw }}_rp" maxlength="255"
                                        placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 rupiah-input" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- ANGGARAN TAHUN -->
                    <div class="p-4 bg-indigo-50 rounded-lg">
                        <label for="edit_anggaran_tahun" class="block text-sm font-medium text-gray-700 mb-1">
                            ANGGARAN TAHUN <span
                                class="anggaranYearText">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                        </label>
                        <input type="text" id="edit_anggaran_tahun" name="anggaran_tahun"
                            placeholder="Masukkan anggaran {{ $selectedYear ?? $currentYear ?? date('Y') }}"
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 rupiah-input">
                    </div>

                    <!-- REALISASI RENAKSI -->
                    <div class="p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-green-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-600"></i>
                            Realisasi Renaksi Tahun <span
                                class="realisasiYearText">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                        </h4>

                        <!-- TW1 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="edit_realisasi_tw1_target"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    TW1 Realisasi Target
                                </label>
                                <input type="text" id="edit_realisasi_tw1_target" name="realisasi_tw1_target"
                                    placeholder="Masukkan realisasi target TW1"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label for="edit_realisasi_tw1_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW1 Realisasi Anggaran (Rp)
                                </label>
                                <input type="text" id="edit_realisasi_tw1_rp" name="realisasi_tw1_rp"
                                    placeholder="Masukkan realisasi anggaran TW1"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 rupiah-input">
                            </div>
                        </div>

                        <!-- TW2 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="edit_realisasi_tw2_target"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    TW2 Realisasi Target
                                </label>
                                <input type="text" id="edit_realisasi_tw2_target" name="realisasi_tw2_target"
                                    placeholder="Masukkan realisasi target TW2"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label for="edit_realisasi_tw2_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW2 Realisasi Anggaran (Rp)
                                </label>
                                <input type="text" id="edit_realisasi_tw2_rp" name="realisasi_tw2_rp"
                                    placeholder="Masukkan realisasi anggaran TW2"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 rupiah-input">
                            </div>
                        </div>

                        <!-- TW3 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="edit_realisasi_tw3_target"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    TW3 Realisasi Target
                                </label>
                                <input type="text" id="edit_realisasi_tw3_target" name="realisasi_tw3_target"
                                    placeholder="Masukkan realisasi target TW3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label for="edit_realisasi_tw3_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW3 Realisasi Anggaran (Rp)
                                </label>
                                <input type="text" id="edit_realisasi_tw3_rp" name="realisasi_tw3_rp"
                                    placeholder="Masukkan realisasi anggaran TW3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 rupiah-input">
                            </div>
                        </div>

                        <!-- TW4 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_realisasi_tw4_target"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    TW4 Realisasi Target
                                </label>
                                <input type="text" id="edit_realisasi_tw4_target" name="realisasi_tw4_target"
                                    placeholder="Masukkan realisasi target TW4"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label for="edit_realisasi_tw4_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW4 Realisasi Anggaran (Rp)
                                </label>
                                <input type="text" id="edit_realisasi_tw4_rp" name="realisasi_tw4_rp"
                                    placeholder="Masukkan realisasi anggaran TW4"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 rupiah-input">
                            </div>
                        </div>
                    </div>

                    <!-- RUMUS OPD - New Section Added (mengikuti contoh dari detail modal) -->
                    <div class="p-6 bg-gray-50 rounded-lg border-l-4 border-gray-500 shadow-sm">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-calculator text-gray-600"></i>
                            Rumus Perhitungan OPD
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="edit_rumus" class="block text-sm font-medium text-gray-700 mb-1">
                                    RUMUS
                                </label>
                                <input type="text" id="edit_rumus" name="rumus" placeholder=""
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 font-mono">
                            </div>
                        </div>
                    </div>

                    <!-- KOORDINATOR DAN PELAKSANA -->
                    <div class="p-6 bg-gray-50 rounded-lg border-l-4 border-purple-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-users text-purple-600"></i>
                            UNIT KERJA / SATUAN KERJA PELAKSANAAN
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_koordinator"
                                    class="block text-sm font-medium text-gray-700 mb-1">KOORDINATOR</label>
                                <select id="edit_koordinator" name="koordinator"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Koordinator</option>
                                    <option value="Inspektorat Daerah">Inspektorat Daerah</option>
                                    <option value="Bappeda">Bappeda</option>
                                    <option value="Bagian Organisasi Setda">Bagian Organisasi Setda</option>
                                </select>
                            </div>
                            <div>
                                <label for="edit_pelaksana"
                                    class="block text-sm font-medium text-gray-700 mb-1">PELAKSANA</label>
                                <select id="edit_pelaksana" name="pelaksana"
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

                    <!-- Modal Footer (DI DALAM FORM) -->
                    <div class="flex-shrink-0 bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200">
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeModal('editModal')"
                                class="px-6 py-2 bg-gray-500 text-black rounded-md hover:bg-gray-600 transition flex items-center justify-center gap-2">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition flex items-center justify-center gap-2">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>