<div id="detailModal" class="fixed inset-0 z-[9999] hidden">
    <div class="flex items-center justify-center w-full h-full bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-green-600 text-white p-6 rounded-t-lg sticky top-0 z-10">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    Detail Rencana Aksi RB General
                </h3>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Bagian Header -->
                <div class="text-center mb-6 pb-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-green-700">
                        DETAIL RENCANA AKSI RB GENERAL TAHUN {{ $selectedYear }}
                    </h2>
                </div>

                <!-- Informasi Utama -->
                <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                    <h4 class="text-md font-semibold text-green-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-green-600"></i>
                        INFORMASI UTAMA
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                NO
                            </label>
                            <input type="text" id="detailNo" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                SASARAN STRATEGI
                            </label>
                            <input type="text" id="detailSasaranStrategi" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                INDIKATOR
                            </label>
                            <input type="text" id="detailIndikator" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TARGET
                            </label>
                            <input type="text" id="detailTarget" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                SATUAN
                            </label>
                            <input type="text" id="detailSatuan" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TARGET TAHUN <span class="detailTargetYearText">{{$selectedYear}}</span>
                            </label>
                            <input type="text" id="detailTarget2025" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Rencana Aksi -->
                <div class="mb-6 p-6 bg-green-50 rounded-lg border-l-4 border-green-500">
                    <h4 class="text-md font-semibold text-green-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-green-600"></i>
                        RENCANA AKSI
                    </h4>
                    <label class="block text-sm font-medium text-gray-600 flex items-center gap-1 mb-1">
                        RENCANA AKSI
                    </label>
                    <textarea id="detailRencanaAksi" readonly rows="3"
                        class="w-full p-2 border border-gray-300 rounded-md bg-gray-100"></textarea>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                SATUAN OUTPUT
                            </label>
                            <input type="text" id="detailSatuanOutput" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                INDIKATOR OUTPUT
                            </label>
                            <input type="text" id="detailIndikatorOutput" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Renaksi Tahun -->
                <div class="mb-6 p-6 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                    <h3 class="text-md font-semibold text-purple-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line text-purple-600"></i>
                        Renaksi Tahun <span class="detailRenaksiYearText">{{$selectedYear}}</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW1 Target
                            </label>
                            <input type="text" id="detailTw1Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW1 Rp
                            </label>
                            <input type="text" id="detailTw1Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW2 Target
                            </label>
                            <input type="text" id="detailTw2Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW2 Rp
                            </label>
                            <input type="text" id="detailTw2Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW3 Target
                            </label>
                            <input type="text" id="detailTw3Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW3 Rp
                            </label>
                            <input type="text" id="detailTw3Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW4 Target
                            </label>
                            <input type="text" id="detailTw4Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW4 Rp
                            </label>
                            <input type="text" id="detailTw4Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Anggaran Total -->
                <div class="mb-6 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <label class="text-md font-medium text-gray-600 mb-4 flex items-center gap-2">
                        <i class="fas fa-money-check-alt text-blue-600"></i>
                        ANGGARAN TAHUN <span class="detailAnggaranYearText">{{$selectedYear}}</span>
                    </label>
                    <input type="text" id="detailAnggaran" readonly
                        class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                </div>

                <!-- Realisasi Renaksi Tahun -->
                <div class="mb-6 p-6 bg-teal-50 rounded-lg border-l-4 border-teal-500">
                    <h3 class="text-md font-semibold text-teal-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-check-circle text-teal-600"></i>
                        Realisasi Renaksi Tahun <span class="detailRealisasiYearText">{{$selectedYear}}</span>
                    </h3>

                    <!-- TW1 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW1 Target
                            </label>
                            <input type="text" id="detailRealisasiTw1Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                <i class="fas fa-money-bill-wave text-green-600"></i> TW1 Rp
                            </label>
                            <input type="text" id="detailRealisasiTw1Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <!-- TW2 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW2 Target
                            </label>
                            <input type="text" id="detailRealisasiTw2Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW2 Rp
                            </label>
                            <input type="text" id="detailRealisasiTw2Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <!-- TW3 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW3 Target
                            </label>
                            <input type="text" id="detailRealisasiTw3Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW3 Rp
                            </label>
                            <input type="text" id="detailRealisasiTw3Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <!-- TW4 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW4 Target
                            </label>
                            <input type="text" id="detailRealisasiTw4Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                TW4 Rp
                            </label>
                            <input type="text" id="detailRealisasiTw4Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Rumus -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <label class="block text-sm font-medium text-gray-600 flex items-center gap-2 mb-1">
                        <i class="fas fa-calculator text-amber-600"></i> RUMUS
                    </label>
                    <input type="text" id="detailRumus" readonly
                        class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 font-mono" />
                </div>

                <!-- Catatan -->
                <div class="mb-6 p-6 bg-amber-50 rounded-lg border-l-4 border-amber-500">
                    <h3 class="text-md font-semibold text-amber-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-amber-600"></i> Catatan Inspektorat
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                Catatan Evaluasi
                            </label>
                            <input type="text" id="detailCatatanEvaluasi" readonly
                                class="w-full p-2 border border-amber-300 rounded-md bg-amber-100 text-amber-800" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                Catatan Perbaikan
                            </label>
                            <input type="text" id="detailCatatanPerbaikan" readonly
                                class="w-full p-2 border border-amber-300 rounded-md bg-amber-100 text-amber-800" />
                        </div>
                    </div>
                </div>

                <!-- Unit Kerja -->
                <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                    <!-- Judul Section -->
                    <h4 class="text-md font-semibold text-green-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-building text-green-600"></i> UNIT KERJA / SATUAN KERJA PELAKSANAAN
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                KOORDINATOR
                            </label>
                            <input type="text" id="detailUnitKerja" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 flex items-center gap-1">
                                PELAKSANA
                            </label>
                            <input type="text" id="detailPelaksana" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-3 mt-6 pt-6">
                    <button type="button"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center gap-2"
                        onclick="closeModal('detailModal')">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fungsi untuk update semua teks tahun di modal detail
        function updateDetailModalYears(year) {
            const detailYearElements = document.querySelectorAll('#detailModal .detailTargetYearText, #detailModal .detailRenaksiYearText, #detailModal .detailAnggaranYearText, #detailModal .detailRealisasiYearText, #detailTahunHeader');
            detailYearElements.forEach(element => {
                element.textContent = year;
            });

            localStorage.setItem('selectedYear', year);
        }

        // Override fungsi openModal untuk modal detail
        const originalOpenModal = window.openModal;
        window.openModal = function (id) {
            if (id === 'detailModal') {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.classList.remove('hidden');

                    const currentYear = localStorage.getItem('selectedYear') ||
                        document.getElementById('yearFilter')?.value ||
                        '2025';

                    updateDetailModalYears(currentYear);
                }
            } else if (originalOpenModal) {
                originalOpenModal(id);
            }
        };
    });
</script>