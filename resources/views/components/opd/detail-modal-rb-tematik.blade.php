<div id="detailModal" class="fixed inset-0 z-[9999] hidden">
    <div class="flex items-center justify-center w-full h-full bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-green-600 text-white p-6 rounded-t-lg flex-shrink-0">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    Detail Rencana Aksi RB Tematik 
                </h3>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Bagian Header -->
                <div class="text-center mb-6 pb-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-green-700">
                        DETAIL RENCANA AKSI RB TEMATIK TAHUN {{ $selectedYear}}
                    </h2>
                </div>
                <div class="bg-gray-50 rounded-lg p-6">
                    <!-- Informasi Dasar -->
                    <div class="mb-6 p-6 bg-white rounded-lg border-l-4 border-green-500 shadow-sm">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-green-600"></i>
                            Informasi Dasar
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NO</label>
                                <input type="text" id="detailNo" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PERMASALAHAN</label>
                                <input type="text" id="detailPermasalahan" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SASARAN TEMATIK</label>
                                <input type="text" id="detailSasaranTematik" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">INDIKATOR</label>
                                <input type="text" id="detailIndikator" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">TARGET</label>
                                <input type="text" id="detailTarget" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SATUAN</label>
                                <input type="text" id="detailSatuan" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                        </div>

                        <!-- TARGET TAHUN  -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">TARGET TAHUN
                                {{ $selectedYear }}</label>
                            <input type="text" id="detailTargetTahun" readonly
                                class="w-full p-2 border border-gray-200 rounded-md bg-gray-50 font-medium text-gray-700" />
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">RENCANA AKSI</label>
                            <textarea id="detailRencanaAksi" readonly rows="3"
                                class="w-full p-2 border border-gray-200 rounded-md bg-gray-50"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SATUAN OUTPUT</label>
                                <input type="text" id="detailSatuanOutput" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">INDIKATOR OUTPUT</label>
                                <input type="text" id="detailIndikatorOutput" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                        </div>
                    </div>

                    <!-- Anggaran Tahun -->
                    <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ANGGARAN TAHUN <span
                                class="anggaranYearText">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                        </label>
                        <input type="text" id="detailAnggaranTahun" readonly
                            class="w-full p-2 border border-gray-200 rounded-md bg-gray-50 text-black font-medium">
                    </div>

                    <!-- Rencana Aksi Triwulan -->
                    <div class="mb-6 p-6 bg-white rounded-lg border-l-4 border-blue-500 shadow-sm">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                            Rencana Aksi dan Anggaran per Triwulan
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['1', '2', '3', '4'] as $tw)
                                <div
                                    class="border rounded-lg p-4 {{ $tw == '1' ? 'bg-blue-50' : ($tw == '2' ? 'bg-indigo-50' : ($tw == '3' ? 'bg-purple-50' : 'bg-pink-50')) }}">
                                    <h5 class="font-semibold text-gray-700 mb-3">Triwulan {{ $tw }}</h5>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Target:</span>
                                            <span class="text-sm font-medium" id="detailTw{{ $tw }}Target"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Anggaran:</span>
                                            <span class="text-sm font-medium text-green-600"
                                                id="detailTw{{ $tw }}Rp"></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- REALISASI RENAKSI -->
                    <div class="mb-6 p-6 bg-white rounded-lg border-l-4 border-blue-500 shadow-sm">
                        <h4 class="text-md font-semibold text-blue-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-check-circle text-blue-600"></i>
                            Realisasi Renaksi Tahun <span
                                class="realisasiYearText">{{ $selectedYear ?? $currentYear ?? date('Y') }}</span>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- TW1 Realisasi -->
                            <div class="border rounded-lg p-4 bg-blue-50">
                                <h5 class="font-semibold text-gray-700 mb-3">Triwulan 1 - Realisasi</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Target:</span>
                                        <span class="text-sm font-medium" id="detailRealisasiTw1Target"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Anggaran:</span>
                                        <span class="text-sm font-medium text-green-600"
                                            id="detailRealisasiTw1Rp"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- TW2 Realisasi -->
                            <div class="border rounded-lg p-4 bg-indigo-50">
                                <h5 class="font-semibold text-gray-700 mb-3">Triwulan 2 - Realisasi</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Target:</span>
                                        <span class="text-sm font-medium" id="detailRealisasiTw2Target"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Anggaran:</span>
                                        <span class="text-sm font-medium text-green-600"
                                            id="detailRealisasiTw2Rp"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- TW3 Realisasi -->
                            <div class="border rounded-lg p-4 bg-purple-50">
                                <h5 class="font-semibold text-gray-700 mb-3">Triwulan 3 - Realisasi</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Target:</span>
                                        <span class="text-sm font-medium" id="detailRealisasiTw3Target"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Anggaran:</span>
                                        <span class="text-sm font-medium text-green-600"
                                            id="detailRealisasiTw3Rp"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- TW4 Realisasi -->
                            <div class="border rounded-lg p-4 bg-pink-50">
                                <h5 class="font-semibold text-gray-700 mb-3">Triwulan 4 - Realisasi</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Target:</span>
                                        <span class="text-sm font-medium" id="detailRealisasiTw4Target"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Anggaran:</span>
                                        <span class="text-sm font-medium text-green-600"
                                            id="detailRealisasiTw4Rp"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RUMUS OPD - New Section Added -->
                    <div class="mb-6 p-6 bg-white rounded-lg border-l-4 border-gray-500 shadow-sm">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    RUMUS 
                                </label>
                                <input type="text" id="detailRumus" readonly
                                    placeholder="-"
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50 font-mono">
                            </div>
                        </div>
                    </div>

                    <!-- Koordinator & Pelaksana -->
                    <div class="p-6 bg-white rounded-lg border-l-4 border-green-500 shadow-sm">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-users text-green-600"></i>
                            Koordinator dan Pelaksana
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">KOORDINATOR</label>
                                <input type="text" id="detailKoordinator" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PELAKSANA</label>
                                <input type="text" id="detailPelaksana" readonly
                                    class="w-full p-2 border border-gray-200 rounded-md bg-gray-50" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex-shrink-0 bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('detailModal')"
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center gap-2">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>