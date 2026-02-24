<div id="detailModal" class="fixed inset-0 z-[9999] hidden">
    <div class="flex items-center justify-center w-full h-full bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header - STICKY TOP -->
            <div class="flex justify-between items-center bg-green-600 text-white p-6 rounded-t-lg flex-shrink-0">
                <h3 class="text-xl font-semibold flex items-center gap-2"> Detail Data PK Bupati
                </h3>
            </div>

            <!-- Modal Body - SCROLLABLE AREA -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <!-- Header -->
                    <div class="text-center mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-green-700">
                            DETAIL PK BUPATI TAHUN <span id="detailTahunHeader">2025</span>
                        </h2>
                    </div>

                    <!-- Informasi Dasar -->
                    <div class="mb-6 p-6 bg-white rounded-lg border-l-4 border-green-500 shadow-sm">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-green-600"></i>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No</label>
                                <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailNo"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailTahun"></div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sasaran Strategis</label>
                                <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailSasaranStrategis"></div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Indikator Kinerja</label>
                                <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailIndikatorKinerja"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target 2025</label>
                                <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailTarget2025"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailSatuan"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Target & Realisasi per Triwulan -->
                    <div class="mb-6 p-6 bg-white rounded-lg border-l-4 border-blue-500 shadow-sm">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                            Target & Realisasi per Triwulan
                        </h3>

                        <div class="tab flex border border-gray-300 bg-gray-100 rounded-t-md overflow-hidden mb-4">
                            <button type="button" class="tablinks px-4 py-2 bg-white font-medium active border-b-2 border-green-600 text-green-700"
                                onclick="openDetailTab(event, 'detailTriwulan1')">Triwulan I</button>
                            <button type="button" class="tablinks px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openDetailTab(event, 'detailTriwulan2')">Triwulan II</button>
                            <button type="button" class="tablinks px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openDetailTab(event, 'detailTriwulan3')">Triwulan III</button>
                            <button type="button" class="tablinks px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openDetailTab(event, 'detailTriwulan4')">Triwulan IV</button>
                        </div>

                        <!-- Triwulan content -->
                        <div id="detailTriwulan1" class="tabcontent">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target TW1</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailTargetTW1"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi TW1</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailRealisasiTW1"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran TW1</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailPaguTW1"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran TW1</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailRealisasiAnggaranTW1"></div>
                                </div>
                            </div>
                        </div>

                        <div id="detailTriwulan2" class="tabcontent hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target TW2</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailTargetTW2"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi TW2</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailRealisasiTW2"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran TW2</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailPaguTW2"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran TW2</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailRealisasiAnggaranTW2"></div>
                                </div>
                            </div>
                        </div>

                        <div id="detailTriwulan3" class="tabcontent hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target TW3</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailTargetTW3"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi TW3</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailRealisasiTW3"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran TW3</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailPaguTW3"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran TW3</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailRealisasiAnggaranTW3"></div>
                                </div>
                            </div>
                        </div>

                        <div id="detailTriwulan4" class="tabcontent hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target TW4</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailTargetTW4"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi TW4</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailRealisasiTW4"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran TW4</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailPaguTW4"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran TW4</label>
                                    <div class="p-2 border border-gray-200 rounded-md bg-gray-50" id="detailRealisasiAnggaranTW4"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program dan Analisis -->
                    <div class="mb-6 p-6 bg-white rounded-lg border-l-4 border-green-500 shadow-sm">
                        <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-green-600"></i>
                            Program dan Analisis
                        </h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                            <div id="detailProgram" class="p-2 border border-gray-200 rounded-md bg-gray-50"></div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penjelasan Analisis dan Evaluasi</label>
                            <div id="detailAnalisisEvaluasi" class="p-2 border border-gray-200 rounded-md bg-gray-50"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                            <div id="detailPenanggungJawab" class="p-2 border border-gray-200 rounded-md bg-gray-50"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer - STICKY BOTTOM -->
            <div class="flex-shrink-0 bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200">
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('detailModal')"
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center gap-2">
                       Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>