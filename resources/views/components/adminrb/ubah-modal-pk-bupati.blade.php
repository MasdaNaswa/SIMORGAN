<div id="editModal" class="fixed inset-0 z-[9999] hidden">
    <div class="flex items-center justify-center w-full h-full bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header - STICKY TOP -->
            <div class="flex justify-between items-center bg-amber-600 text-white p-6 rounded-t-lg flex-shrink-0">
                <h3 class="text-xl font-semibold flex items-center gap-2">
                    <i class="fas fa-edit"></i> Ubah Data PK Bupati
                </h3>
            </div>

            <!-- Modal Body - SCROLLABLE AREA -->
            <div class="flex-1 overflow-y-auto p-6">
                 <div class="text-center mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-amber-700">
                            UBAH RENCANA AKSI PK BUPATI TAHUN {{  $selectedYear }}
                        </h2>
                    </div>
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
                                <label for="editSasaranStrategis" class="block text-sm font-medium text-gray-700 mb-1">Sasaran Strategis</label>
                                <input type="text" id="editSasaranStrategis" name="sasaranStrategis" required 
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan sasaran strategis">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label for="editIndikatorKinerja" class="block text-sm font-medium text-gray-700 mb-1">Indikator Kinerja</label>
                                <input type="text" id="editIndikatorKinerja" name="indikatorKinerja" required 
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Masukkan indikator kinerja">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editTarget2025" class="block text-sm font-medium text-gray-700 mb-1">Target 2025</label>
                                <input type="text" id="editTarget2025" name="target2025" required 
                                       class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label for="editSatuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                <input type="text" id="editSatuan" name="satuan" required 
                                       class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                        </div>
                    </div>

                    <!-- Rencana Aksi dan Anggaran per Triwulan (sama seperti sebelumnya) -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                            Rencana Aksi dan Anggaran per Triwulan
                        </h4>

                        <!-- Tab navigation -->
                        <div class="flex border border-gray-300 bg-gray-100 rounded-t-md overflow-hidden mb-4">
                            <button type="button" class="tablinks-edit px-4 py-2 bg-white font-medium active border-b-2 border-amber-600 text-amber-700"
                                onclick="openEditTab(event, 'editTriwulan1')">Triwulan I</button>
                            <button type="button" class="tablinks-edit px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openEditTab(event, 'editTriwulan2')">Triwulan II</button>
                            <button type="button" class="tablinks-edit px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openEditTab(event, 'editTriwulan3')">Triwulan III</button>
                            <button type="button" class="tablinks-edit px-4 py-2 bg-gray-100 font-medium text-gray-700 hover:bg-gray-200"
                                onclick="openEditTab(event, 'editTriwulan4')">Triwulan IV</button>
                        </div>

                        @for($i = 1; $i <= 4; $i++)
                        <div id="editTriwulan{{ $i }}" class="tabcontent-edit {{ $i > 1 ? 'hidden' : '' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="editTargetTW{{ $i }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $i }} - Target
                                    </label>
                                    <input type="text" id="editTargetTW{{ $i }}" name="targetTW{{ $i }}" 
                                           placeholder="Masukkan target TW{{ $i }}"
                                           class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiTW{{ $i }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $i }} - Realisasi
                                    </label>
                                    <input type="text" id="editRealisasiTW{{ $i }}" name="realisasiTW{{ $i }}"
                                           placeholder="Masukkan realisasi TW{{ $i }}"
                                           class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editPaguAnggaranTW{{ $i }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $i }} - Pagu Anggaran
                                    </label>
                                    <input type="text" id="editPaguAnggaranTW{{ $i }}" name="paguAnggaranTW{{ $i }}"
                                           placeholder="0"
                                           class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                                <div>
                                    <label for="editRealisasiAnggaranTW{{ $i }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        TW{{ $i }} - Realisasi Anggaran
                                    </label>
                                    <input type="text" id="editRealisasiAnggaranTW{{ $i }}" name="realisasiAnggaranTW{{ $i }}"
                                           placeholder="0"
                                           class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <!-- Program & Evaluasi -->
                    <div class="mb-6 p-6 bg-gray-50 rounded-lg border-l-4 border-green-500">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-green-600"></i>
                            Program dan Evaluasi
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="editProgram" class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                                <input type="text" id="editProgram" name="program" 
                                       class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            
                            <div>
                                <label for="editPenanggungJawab" class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                                <select id="editPenanggungJawab" name="penanggungJawab" required 
                                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Pilih Penanggung Jawab</option>
                                    @foreach($penanggungJawabOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="editAnalisisEvaluasi" class="block text-sm font-medium text-gray-700 mb-1">Penjelasan Analisis dan Evaluasi</label>
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
</script>