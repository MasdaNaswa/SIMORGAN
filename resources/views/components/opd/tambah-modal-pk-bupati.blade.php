<div id="addModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-[9999] p-">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-y-auto ">
            <!-- Modal Header -->
            <div class="flex justify-between items-center bg-blue-600 text-white p-6 rounded-t-lg sticky top-0 z-10">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Tambah Data PK Bupati
                </h3>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="pkForm" method="POST" action="{{ route('pk-bupati.store') }}">
                    @csrf
                    <input type="hidden" id="editIndex" />
                    <input type="hidden" name="tahun" id="tahunInput" value="{{ $tahun ?? date('Y') }}">
                    <input type="hidden" name="semester" id="semesterInput" value="{{ $semester ?? '1' }}">

                    <!-- Informasi Dasar -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                        <h3 class="text-md font-semibold text-blue-800 mb-4">Informasi Dasar</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NO <span
                                        class="text-red-500">*</span></label>
                                <input type="number" id="no" name="no" required readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Nomor akan terisi otomatis</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sasaran Strategis <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="sasaranStrategis" name="sasaranStrategis" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan sasaran strategis">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Indikator Kinerja <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="indikatorKinerja" name="indikatorKinerja" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan indikator kinerja">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target {{ $tahun ?? date('Y') }}
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="target2025" name="target2025" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Contoh: 100 atau 85.5 atau 75%">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="satuan" name="satuan" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Contoh: Miliar Rupiah, Persen, Orang, dll">
                            </div>
                        </div>
                    </div>

                    <!-- Target dan Realisasi per Triwulan -->
                    <div class="mb-6 p-6 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <h3 class="text-md font-semibold text-green-800 mb-4">Target dan Realisasi per Triwulan</h3>

                        <div class="flex gap-2 border-b pb-2 mb-4">
                            <button type="button" class="tablinks px-4 py-2 rounded-md bg-gray-200"
                                onclick="openFormTab(event, 'triwulan1')">Triwulan I</button>
                            <button type="button" class="tablinks px-4 py-2 rounded-md bg-gray-100"
                                onclick="openFormTab(event, 'triwulan2')">Triwulan II</button>
                            <button type="button" class="tablinks px-4 py-2 rounded-md bg-gray-100"
                                onclick="openFormTab(event, 'triwulan3')">Triwulan III</button>
                            <button type="button" class="tablinks px-4 py-2 rounded-md bg-gray-100"
                                onclick="openFormTab(event, 'triwulan4')">Triwulan IV</button>
                        </div>

                        <!-- Triwulan 1 -->
                        <div id="triwulan1" class="tabcontent">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target TW I</label>
                                    <input type="text" id="targetTW1" name="targetTW1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi TW I</label>
                                    <input type="text" id="realisasiTW1" name="realisasiTW1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran TW
                                        I</label>
                                    <input type="text" id="paguAnggaranTW1" name="paguAnggaranTW1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran TW
                                        I</label>
                                    <input type="text" id="realisasiAnggaranTW1" name="realisasiAnggaranTW1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                            </div>
                        </div>

                        <!-- Triwulan 2 -->
                        <div id="triwulan2" class="tabcontent hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target TW II</label>
                                    <input type="text" id="targetTW2" name="targetTW2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi TW II</label>
                                    <input type="text" id="realisasiTW2" name="realisasiTW2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran TW
                                        II</label>
                                    <input type="text" id="paguAnggaranTW2" name="paguAnggaranTW2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran TW
                                        II</label>
                                    <input type="text" id="realisasiAnggaranTW2" name="realisasiAnggaranTW2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                            </div>
                        </div>

                        <!-- Triwulan 3 -->
                        <div id="triwulan3" class="tabcontent hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target TW III</label>
                                    <input type="text" id="targetTW3" name="targetTW3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi TW III</label>
                                    <input type="text" id="realisasiTW3" name="realisasiTW3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran TW
                                        III</label>
                                    <input type="text" id="paguAnggaranTW3" name="paguAnggaranTW3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran TW
                                        III</label>
                                    <input type="text" id="realisasiAnggaranTW3" name="realisasiAnggaranTW3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                            </div>
                        </div>

                        <!-- Triwulan 4 -->
                        <div id="triwulan4" class="tabcontent hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target TW IV</label>
                                    <input type="text" id="targetTW4" name="targetTW4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi TW IV</label>
                                    <input type="text" id="realisasiTW4" name="realisasiTW4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran TW
                                        IV</label>
                                    <input type="text" id="paguAnggaranTW4" name="paguAnggaranTW4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran TW
                                        IV</label>
                                    <input type="text" id="realisasiAnggaranTW4" name="realisasiAnggaranTW4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Input manual (angka/huruf/persen)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program dan Analisis -->
                    <div class="mb-6 p-6 bg-amber-50 rounded-lg border-l-4 border-amber-500">
                        <h3 class="text-md font-semibold text-amber-800 mb-4">Program dan Analisis</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                            <input type="text" id="program" name="program"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Input program">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penjelasan Analisis dan
                                Evaluasi</label>
                            <textarea id="analisisEvaluasi" name="analisisEvaluasi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Input analisis dan evaluasi"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab <span
                                    class="text-red-500">*</span></label>
                            <select id="penanggungJawab" name="penanggungJawab" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Penanggung Jawab</option>
                                <option value="Dinas Penanaman Modal dan Pelayanan Satu Pintu">Dinas Penanaman Modal dan
                                    Pelayanan Satu Pintu</option>
                                <option value="Dinas Pangan dan Pertanian">Dinas Pangan dan Pertanian</option>
                                <option value="Dinas Perikanan">Dinas Perikanan</option>
                                <option value="Badan Pendapatan Daerah">Badan Pendapatan Daerah</option>
                                <option value="Dinas Pekerjaan Umum dan Penataan Ruang">Dinas Pekerjaan Umum dan
                                    Penataan Ruang</option>
                                <option value="Dinas Perhubungan">Dinas Perhubungan</option>
                                <option value="Dinas Kesehatan">Dinas Kesehatan</option>
                                <option value="Dinas Pendidikan dan Kebudayaan">Dinas Pendidikan dan Kebudayaan</option>
                                <option
                                    value="Dinas Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan dan Perlindungan Anak">
                                    Dinas Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan dan
                                    Perlindungan Anak</option>
                                <option value="Dinas Kepemudaan dan Olahraga">Dinas Kepemudaan dan Olahraga</option>
                                <option value="Dinas Sosial">Dinas Sosial</option>
                                <option value="Dinas Tenaga Kerja dan Periindustrian">Dinas Tenaga Kerja und
                                    Periindustrian</option>
                                <option value="Dinas Lingkungan Hidup">Dinas Lingkungan Hidup</option>
                                <option value="Bagian Tata Pembangunan">Bagian Tata Pembangunan</option>
                                <option value="Bagian Organisasi">Bagian Organisasi</option>
                                <option value="Baperlitbang Kabupaten Karimun">Baperlitbang Kabupaten Karimun</option>
                                <option value="Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu">Dinas Penanaman
                                    Modal dan Pelayanan Terpadu Satu Pintu</option>
                                <option value="Dinas Kependudukan dan Pencatatan Sipil">Dinas Kependudukan dan
                                    Pencatatan Sipil</option>
                                <option value="RSUD M.SANI">RSUD M.SANI</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeModal('addModal')"
                            class="px-6 py-2 bg-gray-500 text-black rounded-md hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk beralih tab form
    function openFormTab(evt, tabId) {
        document.querySelectorAll('#addModal .tabcontent').forEach(tab => {
            tab.classList.add('hidden');
        });

        document.getElementById(tabId).classList.remove('hidden');

        document.querySelectorAll('#addModal .tablinks').forEach(btn => {
            btn.classList.remove('bg-gray-200');
            btn.classList.add('bg-gray-100');
        });

        evt.currentTarget.classList.remove('bg-gray-100');
        evt.currentTarget.classList.add('bg-gray-200');
    }
</script>
