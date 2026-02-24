<!-- Ubah Modal RB Tematik -->
<div id="editModal" class="fixed inset-0 z-[9999] hidden">
    <div class="flex items-center justify-center w-full h-full bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header - STICKY TOP -->
            <div class="flex justify-between items-center bg-amber-600 text-white p-6 rounded-t-lg flex-shrink-0">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    Ubah Rencana Aksi RB Tematik <span
                        id="editTahunHeader">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                </h3>
            </div>

            <!-- Modal Body - SCROLLABLE AREA -->
            <div class="flex-1 overflow-y-auto p-6">
                <form id="editRenaksiRB" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="edit_no" name="no">
                    <input type="hidden" name="tahun" value="{{ $selectedYear ?? $currentYear ?? date('Y') }}"
                        id="editTahunAnggaran">

                    <!-- ===== INFORMASI DASAR ===== -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-amber-500">
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
                                    PERMASALAHAN <span class="text-red-500">*</span>
                                </label>
                                <select id="edit_permasalahan" name="permasalahan" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Permasalahan</option>
                                    <option value="Penanggulangan Kemiskinan">Penanggulangan Kemiskinan</option>
                                    <option value="Peningkatan Investasi">Peningkatan Investasi</option>
                                    <option value="Mendukung Ketahanan Pangan Nasional">Mendukung Ketahanan Pangan Nasional</option>
                                    <option value="Pengelolaan Sumber Daya dan Hilirisasi">Pengelolaan Sumber Daya dan Hilirisasi</option>
                                    <option value="Peningkatan Kualitas dan Akses Layanan Kesehatan">Peningkatan Kualitas dan Akses Layanan Kesehatan</option>
                                    <option value="Peningkatan Akses, Kualitas dan Mutu Layanan Pendidikan">Peningkatan Akses, Kualitas dan Mutu Layanan Pendidikan</option>
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

                        <div class="mt-4">
                            <label for="edit_rencana_aksi" class="block text-sm font-medium text-gray-700 mb-1">RENCANA AKSI</label>
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

                    <!-- ===== RENCANA AKSI DAN ANGGARAN PER TRIWULAN ===== -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                            Rencana Aksi dan Anggaran per Triwulan
                        </h4>

                        @foreach (['1', '2', '3', '4'] as $tw)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                <div>
                                    <label for="edit_tw{{ $tw }}_target"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $tw }} - Target
                                    </label>
                                    <input type="text" id="edit_tw{{ $tw }}_target" name="renaksi_tw{{ $tw }}_target"
                                        maxlength="255" placeholder="Masukkan target TW{{ $tw }}"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                                </div>
                                <div>
                                    <label for="edit_tw{{ $tw }}_rp" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $tw }} - Anggaran (Rp)
                                    </label>
                                    <input type="text" id="edit_tw{{ $tw }}_rp" name="renaksi_tw{{ $tw }}_rp" maxlength="255"
                                        placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 rupiah-input" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Anggaran Tahun -->
                    <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
                        <label for="edit_anggaran_tahun" class="block text-sm font-medium text-gray-700 mb-1">
                            ANGGARAN TAHUN <span class="anggaranYearText">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                        </label>
                        <input type="text" id="edit_anggaran_tahun" name="anggaran_tahun"
                            placeholder="Masukkan anggaran {{ $selectedYear ?? $currentYear ?? date('Y') }}"
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 rupiah-input">
                    </div>

                    <!-- ===== KOORDINATOR DAN PELAKSANA ===== -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-users text-green-600"></i>
                            Koordinator dan Pelaksana
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_koordinator"
                                    class="block text-sm font-medium text-gray-700 mb-1">KOORDINATOR</label>
                                <select id="edit_koordinator" name="koordinator"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Koordinator</option>
                                    <option value="Baperlitbang">Baperlitbang</option>
                                    <option value="DPMPTSP">DPMPTSP</option>
                                    <option value="Dinas Pengendalian Penduduk KB, PP dan PA">Dinas Pengendalian Penduduk KB, PP dan PA</option>
                                    <option value="DISNAKERIN">DISNAKERIN</option>
                                    <option value="Diskominfo">Diskominfo</option>
                                    <option value="Inspektorat Daerah">Inspektorat Daerah</option>
                                    <option value="Bagian Organisasi Sekda">Bagian Organisasi Sekda</option>
                                    <option value="BKPSDM">BKPSDM</option>
                                </select>
                            </div>
                            <div>
                                <label for="edit_pelaksana"
                                    class="block text-sm font-medium text-gray-700 mb-1">PELAKSANA</label>
                                <select id="edit_pelaksana" name="pelaksana"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Pelaksana</option>
                                    <option value="Dinas PUPR">Dinas PUPR</option>
                                    <option value="Dinas Pendidikan dan Kebudayaan">Dinas Pendidikan dan Kebudayaan</option>
                                    <option value="Dinas Perhubungan">Dinas Perhubungan</option>
                                    <option value="Dinas Perkim">Dinas Perkim</option>
                                    <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                                    <option value="Dinas Koperasi, Usaha Mikro, Perdagangan dan ESDM">Dinas Koperasi, Usaha Mikro, Perdagangan dan ESDM</option>
                                    <option value="Dinas Pertanian">Dinas Pertanian</option>
                                    <option value="Dinas Sosial">Dinas Sosial</option>
                                    <option value="Dinas Perikanan">Dinas Perikanan</option>
                                    <option value="Disnaker">Disnaker</option>
                                    <option value="Seluruh Perangkat Daerah">Seluruh Perangkat Daerah</option>
                                    <option value="Unit Pelayanan Publik">Unit Pelayanan Publik</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer - STICKY BOTTOM -->
            <div class="flex-shrink-0 bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-6 py-2 bg-gray-500 text-black rounded-md hover:bg-gray-600 transition flex items-center justify-center gap-2">
                        Batal
                    </button>
                    <button type="submit" form="editRenaksiRB"
                        class="px-6 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition flex items-center justify-center gap-2">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Format Rupiah untuk input
    document.addEventListener('keyup', function(e) {
        if (e.target.classList.contains('rupiah-input')) {
            let value = e.target.value.replace(/\./g, '');
            value = value.replace(/\D/g, '');
            
            if (value !== '') {
                value = parseInt(value).toString();
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                e.target.value = value;
            }
        }
    });
</script>