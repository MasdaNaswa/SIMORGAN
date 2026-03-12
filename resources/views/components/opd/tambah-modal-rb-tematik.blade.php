<div id="addModalTematik" class="fixed inset-0 z-[9999] hidden">
    <div class="flex items-center justify-center w-full h-full bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">

            <!-- FORM -->
            <form id="formTambahTematik" method="POST" action="{{ route('rb-tematik.store') }}"
                class="flex flex-col flex-1 overflow-hidden">
                @csrf

                <!-- Modal Header -->
                <div class="flex justify-between items-center bg-indigo-600 text-white p-6 rounded-t-lg flex-shrink-0">
                    <h3 class="text-xl font-semibold flex items-center gap-2">
                        Tambah Rencana Aksi RB Tematik 
                    </h3>
                </div>

                <!-- Modal Body - SCROLLABLE -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Header Tahun -->
                    <div class="text-center mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-indigo-700">
                            RENCANA AKSI RB TEMATIK TAHUN {{ $selectedYear }}
                        </h2>
                    </div>

                    <!-- Informasi Dasar -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-indigo-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-indigo-600"></i>
                            Informasi Dasar
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NO</label>
                                <input type="text" value="{{ ($dataForYearCount ?? 0) + 1 }}"
                                    class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" readonly />
                                <input type="hidden" name="tahun" value="{{ $currentYear ?? date('Y') }}">
                            </div>
                            <div>
                                <label for="permasalahan" class="block text-sm font-medium text-gray-700 mb-1">
                                    PERMASALAHAN <span class="text-red-500"></span>
                                </label>
                                <select id="permasalahan" name="permasalahan" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
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
                                <label for="sasaran_tematik"
                                    class="block text-sm font-medium text-gray-700 mb-1">SASARAN TEMATIK</label>
                                <input type="text" id="sasaran_tematik" name="sasaran_tematik" maxlength="100"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label for="indikator"
                                    class="block text-sm font-medium text-gray-700 mb-1">INDIKATOR</label>
                                <input type="text" id="indikator" name="indikator" maxlength="100"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="target" class="block text-sm font-medium text-gray-700 mb-1">TARGET</label>
                                <input type="text" id="target" name="target" maxlength="100"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">SATUAN</label>
                                <input type="text" id="satuan" name="satuan" maxlength="100"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                        </div>

                        <!-- TARGET TAHUN  -->
                        <div class="mt-4">
                            <label for="target_tahun" class="block text-sm font-medium text-gray-700 mb-1">
                                TARGET TAHUN {{ $selectedYear }}
                            </label>
                            <input type="text" id="target_tahun" name="target_tahun" maxlength="100"
                                placeholder="Masukkan target tahun {{ $selectedYear ?? date('Y') }}"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                required />
                        </div>

                        <div class="mt-4">
                            <label for="rencana_aksi" class="block text-sm font-medium text-gray-700 mb-1">RENCANA
                                AKSI</label>
                            <textarea id="rencana_aksi" name="rencana_aksi" rows="3"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="satuan_output" class="block text-sm font-medium text-gray-700 mb-1">SATUAN
                                    OUTPUT</label>
                                <input type="text" id="satuan_output" name="satuan_output" maxlength="255"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label for="indikator_output"
                                    class="block text-sm font-medium text-gray-700 mb-1">INDIKATOR OUTPUT</label>
                                <input type="text" id="indikator_output" name="indikator_output" maxlength="255"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                        </div>
                    </div>

                    <!-- Rencana Aksi Triwulan -->
                    <div class="mb-6 p-6 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                        <h4 class="text-md font-semibold text-purple-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-purple-600"></i>
                            Rencana Aksi dan Anggaran per Triwulan
                        </h4>

                        @foreach (['1', '2', '3', '4'] as $tw)
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                <div>
                                    <label for="tw{{ $tw }}_target" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $tw }} Target
                                    </label>
                                    <input type="text" id="tw{{ $tw }}_target" name="tw{{ $tw }}_target" maxlength="255"
                                        placeholder="Masukkan target TW{{ $tw }}"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                <div>
                                    <label for="tw{{ $tw }}_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $tw }} Anggaran (Rp)
                                    </label>
                                    <input type="text" id="tw{{ $tw }}_rp" name="tw{{ $tw }}_rp" maxlength="255"
                                        placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 rupiah-input" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Anggaran Tahun -->
                    <div class="mb-6 p-4 bg-indigo-50 rounded-lg border-l-4 border-purple-500">
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
                                    placeholder="Masukkan anggaran {{ $selectedYear ?? $currentYear ?? date('Y') }}"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
                            </div>
                        </div>
                    </div>

                    <!-- REALISASI RENAKSI -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                        <h4 class="text-md font-semibold text-blue-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-check-circle text-blue-600"></i>
                            REALISASI RENAKSI TAHUN <span
                                class="realisasiYearText">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                        </h4>

                        <!-- TW1 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="realisasi_tw1_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW1 Realisasi Target
                                </label>
                                <input type="text" id="realisasi_tw1_target" name="realisasi_tw1_target"
                                    placeholder="Masukkan realisasi target TW1"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="realisasi_tw1_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW1 Realisasi Anggaran (Rp)
                                </label>
                                <input type="text" id="realisasi_tw1_rp" name="realisasi_tw1_rp"
                                    placeholder="Masukkan realisasi anggaran TW1"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
                            </div>
                        </div>

                        <!-- TW2 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="realisasi_tw2_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW2 Realisasi Target
                                </label>
                                <input type="text" id="realisasi_tw2_target" name="realisasi_tw2_target"
                                    placeholder="Masukkan realisasi target TW2"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="realisasi_tw2_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW2 Realisasi Anggaran (Rp)
                                </label>
                                <input type="text" id="realisasi_tw2_rp" name="realisasi_tw2_rp"
                                    placeholder="Masukkan realisasi anggaran TW2"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
                            </div>
                        </div>

                        <!-- TW3 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label for="realisasi_tw3_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW3 Realisasi Target
                                </label>
                                <input type="text" id="realisasi_tw3_target" name="realisasi_tw3_target"
                                    placeholder="Masukkan realisasi target TW3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="realisasi_tw3_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW3 Realisasi Anggaran (Rp)
                                </label>
                                <input type="text" id="realisasi_tw3_rp" name="realisasi_tw3_rp"
                                    placeholder="Masukkan realisasi anggaran TW3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
                            </div>
                        </div>

                        <!-- TW4 Realisasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="realisasi_tw4_target" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW4 Realisasi Target
                                </label>
                                <input type="text" id="realisasi_tw4_target" name="realisasi_tw4_target"
                                    placeholder="Masukkan realisasi target TW4"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="form-group">
                                <label for="realisasi_tw4_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                    TW4 Realisasi Anggaran (Rp)
                                </label>
                                <input type="text" id="realisasi_tw4_rp" name="realisasi_tw4_rp"
                                    placeholder="Masukkan realisasi anggaran TW4"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition rupiah-input">
                            </div>
                        </div>
                    </div>

                    <!-- RUMUS OPD - New Section Added -->
                    <div class="mb-6 p-4 bg-gray-100 rounded-lg border-l-4 border-gray-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-calculator text-gray-600"></i>
                            Rumus Perhitungan OPD
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="rumus" class="block text-sm font-medium text-gray-700 mb-1">
                                    RUMUS <span class="text-gray-500 text-xs">(Isi manual sesuai kebutuhan OPD)</span>
                                </label>
                                <input type="text" id="rumus" name="rumus" 
                                    placeholder=""
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition">
                               
                            </div>
                        </div>
                    </div>

                    <!-- Koordinator & Pelaksana -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-users text-green-600"></i>
                            Unit Kerja/Satuan Kerja
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="koordinator"
                                    class="block text-sm font-medium text-gray-700 mb-1">KOORDINATOR</label>
                                <select id="koordinator" name="koordinator"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Pilih Koordinator</option>
                                    <option value="Inspektorat Daerah">Inspektorat Daerah</option>
                                    <option value="Bappeda">Bappeda</option>
                                    <option value="Bagian Organisasi Sekda">Bagian Organisasi Sekda</option>
                                </select>
                            </div>
                            <div>
                                <label for="pelaksana"
                                    class="block text-sm font-medium text-gray-700 mb-1">PELAKSANA</label>
                                <select id="pelaksana" name="pelaksana"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
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
                                    <option value="dinas_koperasi">Dinas Koperasi, Usaha Mikro, Perdagangan, dan ESDM
                                    </option>
                                    <option value="dinas_lingkungan_hidup">Dinas Lingkungan Hidup</option>
                                    <option value="dinas_pangan">Dinas Pangan</option>
                                    <option value="dinas_pangan_dan_pertanian">Dinas Pangan dan Pertanian</option>
                                    <option value="dinas_pemberdayaan_masyarakat_desa">Dinas Pemberdayaan Masyarakat
                                        Desa</option>
                                    <option value="dinas_pengendalian_penduduk">Dinas Pengendalian Penduduk, KB, PP, dan
                                        PA</option>
                                    <option value="dinas_perhubungan">Dinas Perhubungan</option>
                                    <option value="dinas_perikanan">Dinas Perikanan</option>
                                    <option value="dinas_perumahan_rakyat_dan_kawasan_permukiman">Dinas Perumahan Rakyat
                                        dan Kawasan Pemukiman</option>
                                    <option value="dinas_pertanian">Dinas Pertanian</option>
                                    <option value="dinas_pendidikan_dan_kebudayaan">Dinas Pendidikan dan Kebudayaan
                                    </option>
                                    <option value="dinas_pupr">Dinas Pekerjaan Umum dan Penataan Ruang</option>
                                    <option value="dinas_sosial">Dinas Sosial</option>
                                    <option value="dpmptsp">DPMPTSP</option>
                                    <option value="kecamatan_belat">Kecamatan Belat</option>
                                    <option value="kecamatan_buru">Kecamatan Buru</option>
                                    <option value="kecamatan_durai">Kecamatan Durai</option>
                                    <option value="kecamatan_karimun">Kecamatan Karimun</option>
                                    <option value="kecamatan_kundur">Kecamatan Kundur</option>
                                    <option value="kecamatan_kundur_barat">Kecamatan Kundur Barat</option>
                                    <option value="kecamatan_kundur_utara">Kecamatan Kundur Utara</option>
                                    <option value="kecamatan_meral">Kecamatan Meral</option>
                                    <option value="kecamatan_meral_barat">Kecamatan Meral Barat</option>
                                    <option value="kecamatan_moro">Kecamatan Moro</option>
                                    <option value="kecamatan_selat_gelam">Kecamatan Selat Gelam</option>
                                    <option value="kecamatan_sugie_besar">Kecamatan Sugie Besar</option>
                                    <option value="kecamatan_tebing">Kecamatan Tebing</option>
                                    <option value="kecamatan_unggar">Kecamatan Unggar</option>
                                    <option value="ptsp">PTSP</option>
                                    <option value="rsud">RSUD M.SANI</option>
                                    <option value="rsud_tj_batu_kundur">RSUD Tanjung Batu Kundur</option>
                                    <option value="satpolpp">Satpol PP</option>
                                    <option value="sekda">Sekretariat Daerah</option>
                                    <option value="sekretariat_dprd">Sekretariat DPRD</option>
                                    <option value="ukpbj">UKPBJ</option>
                                    <option value="unit_pelayan_publik">Unit Pelayan Publik</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex-shrink-0 bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200">
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('addModalTematik')"
                            class="px-6 py-2 bg-gray-500 text-black rounded-md hover:bg-gray-600 transition flex items-center justify-center gap-2">
                            Batal
                        </button>
                        <button type="submit" id="submitTambahTematik"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                            Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>