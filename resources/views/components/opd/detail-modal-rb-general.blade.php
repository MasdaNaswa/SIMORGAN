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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">NO</label>
                            <input type="text" id="detailNo" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">SASARAN STRATEGI</label>
                            <input type="text" id="detailSasaranStrategi" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">INDIKATOR</label>
                            <input type="text" id="detailIndikator" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TARGET</label>
                            <input type="text" id="detailTarget" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">SATUAN</label>
                            <input type="text" id="detailSatuan" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">
                                TARGET TAHUN <span class="detailTargetYearText">2025</span>
                            </label>
                            <input type="text" id="detailTargetTahun" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Rencana Aksi -->
                <div class="mb-6 p-6 bg-green-50 rounded-lg border-l-4 border-green-500">
                    <label class="block text-sm font-medium text-gray-600">RENCANA AKSI</label>
                    <textarea id="detailRencanaAksi" readonly rows="3"
                        class="w-full p-2 border border-gray-300 rounded-md bg-gray-100"></textarea>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">SATUAN OUTPUT</label>
                            <input type="text" id="detailSatuanOutput" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">INDIKATOR OUTPUT</label>
                            <input type="text" id="detailIndikatorOutput" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Renaksi Tahun -->
                <div class="mb-6 p-6 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                    <h3 class="text-md font-semibold text-purple-800 mb-4">
                        Renaksi Tahun <span class="detailRenaksiYearText">2025</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW1 Target</label>
                            <input type="text" id="detailRenaksiTw1Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW1 Rp</label>
                            <input type="text" id="detailTw1Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW2 Target</label>
                            <input type="text" id="detailRenaksiTw2Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW2 Rp</label>
                            <input type="text" id="detailTw2Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW3 Target</label>
                            <input type="text" id="detailRenaksiTw3Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW3 Rp</label>
                            <input type="text" id="detailTw3Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW4 Target</label>
                            <input type="text" id="detailRenaksiTw4Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW4 Rp</label>
                            <input type="text" id="detailTw4Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Anggaran Total -->
                <div class="mb-6 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <label class="text-md font-medium text-gray-600 mb-4">
                        Anggaran Tahun <span class="detailAnggaranYearText">{{$selectedYear}}</span>
                    </label>
                    <input type="text" id="detailAnggaranTahun" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                </div>

                <!-- Realisasi Renaksi Tahun -->
                <div class="mb-6 p-6 bg-teal-50 rounded-lg border-l-4 border-teal-500">
                    <h3 class="text-md font-semibold text-teal-800 mb-4">
                        Realisasi Renaksi Tahun <span class="detailRealisasiYearText">{{ $selectedYear }}</span>
                    </h3>

                    <!-- TW1 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW1 Target</label>
                            <input type="text" id="detailRealisasiTw1Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW1 Rp</label>
                            <input type="text" id="detailRealisasiTw1Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <!-- TW2 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW2 Target</label>
                            <input type="text" id="detailRealisasiTw2Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW2 Rp</label>
                            <input type="text" id="detailRealisasiTw2Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <!-- TW3 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW3 Target</label>
                            <input type="text" id="detailRealisasiTw3Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW3 Rp</label>
                            <input type="text" id="detailRealisasiTw3Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>

                    <!-- TW4 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW4 Target</label>
                            <input type="text" id="detailRealisasiTw4Target" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TW4 Rp</label>
                            <input type="text" id="detailRealisasiTw4Rp" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Rumus -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600">RUMUS</label>
                    <input type="text" id="detailRumus" readonly
                        class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 font-mono" />
                </div>

                <!-- Catatan -->
                <div class="mb-6 p-6 bg-amber-50 rounded-lg border-l-4 border-amber-500">
                    <h3 class="text-md font-semibold text-amber-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-clipboard-check"></i> Catatan Inspektorat
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Catatan Evaluasi</label>
                            <input type="text" id="detailCatatanEvaluasi" readonly
                                class="w-full p-2 border border-amber-300 rounded-md bg-amber-100 text-amber-800" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Catatan Perbaikan</label>
                            <input type="text" id="detailCatatanPerbaikan" readonly
                                class="w-full p-2 border border-amber-300 rounded-md bg-amber-100 text-amber-800" />
                        </div>
                    </div>
                </div>

                <!-- Unit Kerja -->
                <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">UNIT KERJA</label>
                            <input type="text" id="detailUnitKerja" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">PELAKSANA</label>
                            <input type="text" id="detailPelaksana" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100" />
                        </div>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-3 mt-6 pt-6">
                    <button type="button"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                        onclick="closeModal('detailModal')">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fungsi untuk update semua teks tahun di modal detail
        function updateDetailModalYears(year) {
            // Update semua span dengan class tertentu di modal detail
            const detailYearElements = document.querySelectorAll('#detailModal .detailTargetYearText, #detailModal .detailRenaksiYearText, #detailModal .detailAnggaranYearText, #detailModal .detailRealisasiYearText, #detailModalYear');
            detailYearElements.forEach(element => {
                element.textContent = year;
            });
            
            // Simpan tahun ke localStorage
            localStorage.setItem('selectedYear', year);
        }

        // Override fungsi openModal untuk modal detail
        const originalOpenModal = window.openModal;
        window.openModal = function(id) {
            if (id === 'detailModal') {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.classList.remove('hidden');
                    
                    // Ambil tahun dari localStorage atau dropdown
                    const currentYear = localStorage.getItem('selectedYear') || 
                                       document.getElementById('yearFilter')?.value || 
                                       '2025';
                    
                    // Update semua teks tahun di modal detail
                    updateDetailModalYears(currentYear);
                }
            } else {
                originalOpenModal(id);
            }
        };
    });
</script>