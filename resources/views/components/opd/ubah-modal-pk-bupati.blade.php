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
                                <select id="editSasaranStrategis" name="sasaranStrategis" required
                                    onchange="updateEditIndikator()"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Sasaran Strategis</option>
                                    <?php foreach ($sasaranOptions as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label for="editIndikatorKinerja"
                                    class="block text-sm font-medium text-gray-700 mb-1">Indikator Kinerja</label>
                                <select id="editIndikatorKinerja" name="indikatorKinerja" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Sasaran Strategis terlebih dahulu</option>
                                </select>
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

                    <!-- Rencana Aksi dan Anggaran per Triwulan - DIPERBAIKI MENGGUNAKAN TAB -->
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
                            <!-- Kolom Kiri: Program -->
                            <div>
                                <label for="editProgram"
                                    class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                                <input type="text" id="editProgram" name="program"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>

                            <!-- Kolom Kanan: Penanggung Jawab -->
                            <div>
                                <label for="editPenanggungJawab"
                                    class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                                <select id="editPenanggungJawab" name="penanggungJawab" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Penanggung Jawab</option>
                                    <?php foreach ($penanggungJawabOptions as $option): ?>
                                    <option value="<?= $option ?>"><?= $option ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Analisis Evaluasi (tetap satu baris penuh) -->
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
        // Sembunyikan semua tab content
        var tabContents = document.getElementsByClassName("tabcontent-edit");
        for (var i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.add('hidden');
        }

        // Hapus class active dari semua tab links
        var tabLinks = document.getElementsByClassName("tablinks-edit");
        for (var i = 0; i < tabLinks.length; i++) {
            tabLinks[i].classList.remove('active', 'bg-white', 'border-b-2', 'border-amber-600', 'text-amber-700');
            tabLinks[i].classList.add('bg-gray-100', 'text-gray-700');
        }

        // Tampilkan tab yang dipilih
        document.getElementById(tabId).classList.remove('hidden');

        // Set class active pada tab yang diklik
        event.currentTarget.classList.remove('bg-gray-100', 'text-gray-700');
        event.currentTarget.classList.add('active', 'bg-white', 'border-b-2', 'border-amber-600', 'text-amber-700');
    }

    // Fungsi untuk membuka tab di modal detail
    function openDetailTab(event, tabId) {
        // Sembunyikan semua tab content
        var tabContents = document.getElementsByClassName("tabcontent");
        for (var i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.add('hidden');
        }

        // Hapus class active dari semua tab links
        var tabLinks = document.getElementsByClassName("tablinks");
        for (var i = 0; i < tabLinks.length; i++) {
            tabLinks[i].classList.remove('active', 'bg-white', 'border-b-2', 'border-green-600', 'text-green-700');
            tabLinks[i].classList.add('bg-gray-100', 'text-gray-700');
        }

        // Tampilkan tab yang dipilih
        document.getElementById(tabId).classList.remove('hidden');

        // Set class active pada tab yang diklik
        event.currentTarget.classList.remove('bg-gray-100', 'text-gray-700');
        event.currentTarget.classList.add('active', 'bg-white', 'border-b-2', 'border-green-600', 'text-green-700');
    }

    // Fungsi untuk menutup modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Fungsi untuk update indikator (anda perlu mengimplementasikan ini sesuai kebutuhan)
    function updateEditIndikator() {
        // Implementasi untuk mengisi dropdown indikator berdasarkan sasaran strategis yang dipilih
        console.log('Update indikator based on selected sasaran strategis');
    }
</script>