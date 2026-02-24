<div id="detailModalKemendagri" class="hidden fixed inset-0 z-[99999] flex items-center justify-center bg-black/60">
    <div class="relative bg-white w-full max-w-6xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto m-4">

        <!-- Modal Header -->
        <div class="sticky top-0 z-10 bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-5 rounded-t-xl">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-white bg-opacity-20 rounded-lg">
                        <i class="fas fa-chart-bar text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">
                            Detail Survei Kemendagri
                        </h3>
                        <div class="flex items-center text-sm text-emerald-100 mt-1">
                            <span id="modalOpdKemendagri" class="flex items-center mr-4">
                                <i class="fas fa-building mr-2"></i>
                                <span class="truncate max-w-xs">-</span>
                            </span>
                            <span id="modalTanggalKemendagri" class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span>-</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Content Container -->
        <div class="p-6 space-y-6">
            <!-- Skor Utama Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Total Skor Card -->
                <div
                    class="bg-gradient-to-br from-emerald-50 to-green-100 border border-emerald-200 p-5 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center">
                        <div class="flex-grow">
                            <p class="text-emerald-700 text-sm font-medium mb-1">Total Skor</p>
                            <p class="text-3xl font-bold text-emerald-800" id="modalTotalSkorKemendagri">0</p>
                            <div class="flex items-center mt-3">
                                <div class="w-full bg-emerald-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-500 to-green-600 h-2.5 rounded-full"
                                        id="scoreProgressBar" style="width: 0%"></div>
                                </div>
                                <span class="text-emerald-700 text-xs font-medium ml-3" id="scorePercentage">0%</span>
                            </div>
                        </div>
                        <div class="p-3 bg-white bg-opacity-50 rounded-full ml-4">
                            <i class="fas fa-star text-emerald-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Tingkat Maturitas Card -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-cyan-100 border border-blue-200 p-5 rounded-xl shadow-sm flex flex-col justify-between">

                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-blue-700 text-sm font-medium mb-1">
                                Tingkat Maturitas
                            </p>

                            <p class="text-2xl font-bold text-blue-800 leading-tight"
                                id="modalTingkatMaturitasKemendagri">
                                -
                            </p>

                            <p class="text-xs text-blue-600 mt-1">
                                Berdasarkan hasil evaluasi Kemendagri
                            </p>
                        </div>

                        <div class="p-3 bg-white bg-opacity-60 rounded-full">
                            <i class="fas fa-layer-group text-blue-600 text-2xl"></i>
                        </div>
                    </div>

                    <!-- Badge Status -->
                    <div class="mt-4">
                        <span id="badgeMaturitasKemendagri"
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Status Maturitas
                        </span>
                    </div>
                </div>

            </div>

            <!-- Jawaban Survei Section -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 px-5 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-50 rounded-lg mr-3">
                                <i class="fas fa-list-check text-blue-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800">Jawaban Survei</h4>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="py-3.5 px-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider border-b border-gray-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-list-ol mr-2 text-gray-500"></i>
                                        Variabel
                                    </div>
                                </th>
                                <th
                                    class="py-3.5 px-4 text-center font-semibold text-gray-700 text-sm uppercase tracking-wider border-b border-gray-200 w-32">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-chart-line mr-2 text-gray-500"></i>
                                        Tingkat
                                    </div>
                                </th>
                                <th
                                    class="py-3.5 px-4 text-center font-semibold text-gray-700 text-sm uppercase tracking-wider border-b border-gray-200 w-40">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-file-alt mr-2 text-gray-500"></i>
                                        File Pendukung
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="jawabanKemendagri" class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                <td colspan="3" class="py-8 text-center text-gray-400">
                                    <i class="fas fa-database text-3xl mb-3 text-gray-300"></i>
                                    <p class="text-sm font-medium">Data tidak tersedia</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-xl">
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Survei Kemendagri - Evaluasi Kelembagaan
            </div>
            <button onclick="closeDetailModalKemendagri()"
                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-medium shadow-sm transition-all duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>