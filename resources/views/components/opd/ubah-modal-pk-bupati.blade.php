<div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-[9999] p-4">
    <div class="flex items-center justify-center w-full h-full">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header - STICKY TOP -->
            <div class="flex justify-between items-center bg-amber-600 text-white p-6 rounded-t-lg flex-shrink-0">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <i class="fas fa-edit"></i> Ubah Data PK Bupati
                </h3>
            </div>

            <!-- Modal Body - SCROLLABLE AREA -->
            <div class="flex-1 overflow-y-auto p-6">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    <input type="hidden" name="tahun" id="editTahun" value="{{ $tahun ?? date('Y') }}">
                    <input type="hidden" name="semester" id="editSemester" value="{{ $semester ?? '1' }}">

                    <!-- Informasi Dasar -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-amber-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-amber-600"></i>
                            Informasi Dasar
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="editNo" class="block text-sm font-medium text-gray-700 mb-1">NO</label>
                                <input type="text" id="editNo" name="no" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label for="editSasaranStrategis"
                                    class="block text-sm font-medium text-gray-700 mb-1">Sasaran Strategis</label>
                                <input type="text" id="editSasaranStrategis" name="sasaranStrategis" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan sasaran strategis">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label for="editIndikatorKinerja"
                                    class="block text-sm font-medium text-gray-700 mb-1">Indikator Kinerja</label>
                                <input type="text" id="editIndikatorKinerja" name="indikatorKinerja" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan indikator kinerja">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editTarget2025" class="block text-sm font-medium text-gray-700 mb-1">Target
                                    2025</label>
                                <input type="text" id="editTarget2025" name="target2025" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label for="editSatuan"
                                    class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                <input type="text" id="editSatuan" name="satuan" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                        </div>
                    </div>

                    <!-- Rencana Aksi dan Anggaran per Triwulan -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                            Rencana Aksi dan Anggaran per Triwulan
                        </h4>

                        <!-- Tab navigation -->
                        <div class="tab flex border border-gray-300 bg-gray-100 rounded-t-md overflow-hidden mb-4">
                            <button type="button"
                                class="tablinks-edit px-4 py-2 bg-white font-medium active border-b-2 border-amber-600 text-amber-700"
                                onclick="openEditTab(event, 'editTriwulan1')">Triwulan I</button>
                            <button type="button"
                                class="tablinks-edit px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openEditTab(event, 'editTriwulan2')">Triwulan II</button>
                            <button type="button"
                                class="tablinks-edit px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openEditTab(event, 'editTriwulan3')">Triwulan III</button>
                            <button type="button"
                                class="tablinks-edit px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openEditTab(event, 'editTriwulan4')">Triwulan IV</button>
                        </div>

                        <!-- Tab content - Triwulan 1 -->
                        <div id="editTriwulan1" class="tabcontent-edit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="editTargetTW1" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW1 - Target
                                    </label>
                                    <input type="text" id="editTargetTW1" name="targetTW1"
                                        placeholder="Masukkan target TW1"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiTW1" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW1 - Realisasi
                                    </label>
                                    <input type="text" id="editRealisasiTW1" name="realisasiTW1"
                                        placeholder="Masukkan realisasi TW1"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editPaguAnggaranTW1"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW1 - Pagu Anggaran
                                    </label>
                                    <input type="text" id="editPaguAnggaranTW1" name="paguAnggaranTW1" placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiAnggaranTW1"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW1 - Realisasi Anggaran
                                    </label>
                                    <input type="text" id="editRealisasiAnggaranTW1" name="realisasiAnggaranTW1"
                                        placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                        </div>

                        <!-- Tab content - Triwulan 2 -->
                        <div id="editTriwulan2" class="tabcontent-edit hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="editTargetTW2" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW2 - Target
                                    </label>
                                    <input type="text" id="editTargetTW2" name="targetTW2"
                                        placeholder="Masukkan target TW2"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiTW2" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW2 - Realisasi
                                    </label>
                                    <input type="text" id="editRealisasiTW2" name="realisasiTW2"
                                        placeholder="Masukkan realisasi TW2"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editPaguAnggaranTW2"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW2 - Pagu Anggaran
                                    </label>
                                    <input type="text" id="editPaguAnggaranTW2" name="paguAnggaranTW2" placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiAnggaranTW2"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW2 - Realisasi Anggaran
                                    </label>
                                    <input type="text" id="editRealisasiAnggaranTW2" name="realisasiAnggaranTW2"
                                        placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                        </div>

                        <!-- Tab content - Triwulan 3 -->
                        <div id="editTriwulan3" class="tabcontent-edit hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="editTargetTW3" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW3 - Target
                                    </label>
                                    <input type="text" id="editTargetTW3" name="targetTW3"
                                        placeholder="Masukkan target TW3"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiTW3" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW3 - Realisasi
                                    </label>
                                    <input type="text" id="editRealisasiTW3" name="realisasiTW3"
                                        placeholder="Masukkan realisasi TW3"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editPaguAnggaranTW3"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW3 - Pagu Anggaran
                                    </label>
                                    <input type="text" id="editPaguAnggaranTW3" name="paguAnggaranTW3" placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiAnggaranTW3"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW3 - Realisasi Anggaran
                                    </label>
                                    <input type="text" id="editRealisasiAnggaranTW3" name="realisasiAnggaranTW3"
                                        placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                        </div>

                        <!-- Tab content - Triwulan 4 -->
                        <div id="editTriwulan4" class="tabcontent-edit hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="editTargetTW4" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW4 - Target
                                    </label>
                                    <input type="text" id="editTargetTW4" name="targetTW4"
                                        placeholder="Masukkan target TW4"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiTW4" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW4 - Realisasi
                                    </label>
                                    <input type="text" id="editRealisasiTW4" name="realisasiTW4"
                                        placeholder="Masukkan realisasi TW4"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editPaguAnggaranTW4"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW4 - Pagu Anggaran
                                    </label>
                                    <input type="text" id="editPaguAnggaranTW4" name="paguAnggaranTW4" placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiAnggaranTW4"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        TW4 - Realisasi Anggaran
                                    </label>
                                    <input type="text" id="editRealisasiAnggaranTW4" name="realisasiAnggaranTW4"
                                        placeholder="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program & Evaluasi -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-green-600"></i>
                            Program dan Evaluasi
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="editProgram"
                                    class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                                <input type="text" id="editProgram" name="program"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>

                            <div>
                                <label for="editPenanggungJawab"
                                    class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                                <select id="editPenanggungJawab" name="penanggungJawab" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Penanggung Jawab</option>

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

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="editAnalisisEvaluasi"
                                    class="block text-sm font-medium text-gray-700 mb-1">Penjelasan Analisis dan
                                    Evaluasi</label>
                                <textarea id="editAnalisisEvaluasi" name="analisisEvaluasi" rows="3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"></textarea>
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
                    <button type="submit" form="editForm"
                        class="px-6 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition flex items-center justify-center gap-2">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk membuka tab di modal edit
    function openEditTab(event, tabId) {
        var tabContents = document.getElementsByClassName("tabcontent-edit");
        for (var i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.add('hidden');
        }

        var tabLinks = document.getElementsByClassName("tablinks-edit");
        for (var i = 0; i < tabLinks.length; i++) {
            tabLinks[i].classList.remove('active', 'bg-white', 'border-b-2', 'border-amber-600', 'text-amber-700');
            tabLinks[i].classList.add('bg-gray-100', 'text-gray-700');
        }

        document.getElementById(tabId).classList.remove('hidden');

        event.currentTarget.classList.remove('bg-gray-100', 'text-gray-700');
        event.currentTarget.classList.add('active', 'bg-white', 'border-b-2', 'border-amber-600', 'text-amber-700');
    }

    // Fungsi untuk menutup modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>