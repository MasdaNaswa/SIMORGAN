@extends('layouts.app')

@section('title', 'PK Bupati - OPD')

@section('content')
<div class="flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-30">
        <div class="flex justify-between items-center py-4 px-6 md:px-8">
            <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-600"></i>
                <span class="hidden sm:inline">PK Bupati</span>
            </h1>
            <div class="relative group">
                <button class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-user-circle text-xl md:text-2xl text-blue-600"></i>
                    <span class="text-sm md:text-base">Admin OPD</span>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-opacity duration-200 z-50">
                    <ul class="py-2 text-gray-700 text-sm">
                        <li>
                            <a href="{{ route('opd.profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">
                                Profil Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-1 px-4 md:px-8 py-6 bg-[#F8FAFC]">

        <!-- Filter Tahun & Semester + Tombol Tambah -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 md:gap-6 px-4 md:px-6 py-4">
            <div class="flex flex-col md:flex-row items-center gap-4">
                @php
                    $startYear = 2024;
                    $currentYear = now()->year;
                    $selectedYear = request()->get('tahun', $currentYear);
                    $selectedSemester = request()->get('semester', '1');
                    $years = range($startYear, $currentYear);
                @endphp

                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <label class="font-semibold text-gray-700 text-sm md:text-base">Tahun:</label>
                    <select name="tahun" id="yearFilter"
                        class="py-2 px-3 rounded border border-gray-300 hover:border-primary transition focus:outline-none focus:ring-2 focus:ring-blue-200 text-sm md:text-base"
                        onchange="this.form.submit()">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    <label class="font-semibold text-gray-700 text-sm md:text-base">Semester:</label>
                    <select name="semester" id="semesterFilter"
                        class="py-2 px-3 rounded border border-gray-300 hover:border-primary transition focus:outline-none focus:ring-2 focus:ring-blue-200 text-sm md:text-base"
                        onchange="this.form.submit()">
                        <option value="1" {{ $selectedSemester == '1' ? 'selected' : '' }}>I</option>
                        <option value="2" {{ $selectedSemester == '2' ? 'selected' : '' }}>II</option>
                    </select>
                </form>
            </div>

            <div class="flex gap-2">
                <button class="flex items-center gap-2 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm md:text-base"
                    onclick="openModal('addModal')">
                    <span>Tambah</span>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg mt-6 overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">NO</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Sasaran Strategis</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Indikator Kinerja</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Target 2025</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Satuan</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Penanggung Jawab</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($pkData->isEmpty())
                            <tr>
                                <td colspan="7" class="py-12 px-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-file-alt text-5xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500 text-base mb-1">Tidak ada data</p>
                                        <p class="text-gray-400 text-sm">
                                            PK Bupati {{ $selectedYear }} Semester {{ $selectedSemester == '1' ? 'I' : 'II' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($pkData as $item)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-gray-900 text-sm">{{ $item->no }}</td>
                                    <td class="py-3 px-4 text-sm max-w-xs truncate" title="{{ $item->sasaran_strategis }}">{{ $item->sasaran_strategis }}</td>
                                    <td class="py-3 px-4 text-sm max-w-xs truncate" title="{{ $item->indikator_kinerja }}">{{ $item->indikator_kinerja }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">{{ $item->target_2025 }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-semibold">{{ $item->satuan }}</td>
                                    <td class="py-3 px-4 text-sm max-w-xs truncate" title="{{ $item->penanggung_jawab }}">{{ $item->penanggung_jawab }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center gap-1">
                                            <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors" 
                                                title="Lihat Detail" 
                                                onclick="showDetail({{ $item->id }})">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                            <button class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" 
                                                title="Edit" 
                                                onclick="editData({{ $item->id }})">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors" 
                                                title="Hapus" 
                                                onclick="deleteData({{ $item->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($pkData instanceof \Illuminate\Pagination\LengthAwarePaginator && $pkData->total() > 0)
            <div class="px-4 md:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-700">
                    Menampilkan 
                    <span class="font-medium">{{ $pkData->firstItem() }}</span>
                    -
                    <span class="font-medium">{{ $pkData->lastItem() }}</span>
                    dari 
                    <span class="font-medium">{{ $pkData->total() }}</span>
                    entri
                </div>
                <div class="flex gap-2">
                    @if($pkData->onFirstPage())
                            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed"><i class="fas fa-chevron-left"></i></span></span>
                    @else
                        <a href="{{ $pkData->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50"></a>
                    @endif
                    
                    @foreach($pkData->getUrlRange(max(1, $pkData->currentPage() - 2), min($pkData->lastPage(), $pkData->currentPage() + 2)) as $page => $url)
                        @if($page == $pkData->currentPage())
                            <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($pkData->hasMorePages())
                        <a href="{{ $pkData->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50"></a>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </main>

    @include('components.footer')
</div>

<!-- Include Modals -->
@include('components.opd.tambah-modal-pk-bupati')
@include('components.opd.ubah-modal-pk-bupati')
@include('components.opd.detail-modal-pk-bupati')
@include('components.opd.hapus-modal-pk-bupati')

@endsection

@push('scripts')
<script>
    // Data indikator dari backend
    const indikatorData = @json($indikatorData);
    const currentYear = '{{ $tahun }}';
    const currentSemester = '{{ $semester }}';

    // Fungsi untuk membuka/tutup modal
    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Fungsi untuk update dropdown indikator (Tambah)
    function updateIndikator() {
        const sasaranSelect = document.getElementById("sasaranStrategis");
        const indikatorSelect = document.getElementById("indikatorKinerja");
        const selectedSasaran = sasaranSelect.value;

        indikatorSelect.innerHTML = '<option value="">Pilih Indikator Kinerja</option>';

        if (selectedSasaran && indikatorData[selectedSasaran]) {
            indikatorData[selectedSasaran].forEach((indikator) => {
                const option = document.createElement("option");
                option.value = indikator;
                option.textContent = indikator;
                indikatorSelect.appendChild(option);
            });
        }
    }

    // Fungsi untuk update dropdown indikator (Edit)
    function updateEditIndikator() {
        const sasaranSelect = document.getElementById("editSasaranStrategis");
        const indikatorSelect = document.getElementById("editIndikatorKinerja");
        const selectedSasaran = sasaranSelect.value;

        indikatorSelect.innerHTML = '<option value="">Pilih Indikator Kinerja</option>';

        if (selectedSasaran && indikatorData[selectedSasaran]) {
            indikatorData[selectedSasaran].forEach((indikator) => {
                const option = document.createElement("option");
                option.value = indikator;
                option.textContent = indikator;
                indikatorSelect.appendChild(option);
            });
        }
    }

    // Fungsi untuk menampilkan detail data
    function showDetail(id) {
        fetch(`/pk-bupati/show/${id}`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const data = result.data;
                    
                    document.getElementById('detailNo').textContent = data.no || '-';
                    document.getElementById('detailTahun').textContent = data.tahun || '-';
                    document.getElementById('detailTahunHeader').textContent = data.tahun || '-';
                    document.getElementById('detailSasaranStrategis').textContent = data.sasaranStrategis || '-';
                    document.getElementById('detailIndikatorKinerja').textContent = data.indikatorKinerja || '-';
                    document.getElementById('detailTarget2025').textContent = data.target2025 || '-';
                    document.getElementById('detailSatuan').textContent = data.satuan || '-';
                    document.getElementById('detailProgram').textContent = data.program || '-';
                    document.getElementById('detailAnalisisEvaluasi').textContent = data.analisisEvaluasi || '-';
                    document.getElementById('detailPenanggungJawab').textContent = data.penanggungJawab || '-';
                    
                    document.getElementById('detailTargetTW1').textContent = data.targetTW1 || '-';
                    document.getElementById('detailRealisasiTW1').textContent = data.realisasiTW1 || '-';
                    document.getElementById('detailPaguTW1').textContent = data.paguAnggaranTW1 || '-';
                    document.getElementById('detailRealisasiAnggaranTW1').textContent = data.realisasiAnggaranTW1 || '-';
                    
                    document.getElementById('detailTargetTW2').textContent = data.targetTW2 || '-';
                    document.getElementById('detailRealisasiTW2').textContent = data.realisasiTW2 || '-';
                    document.getElementById('detailPaguTW2').textContent = data.paguAnggaranTW2 || '-';
                    document.getElementById('detailRealisasiAnggaranTW2').textContent = data.realisasiAnggaranTW2 || '-';
                    
                    document.getElementById('detailTargetTW3').textContent = data.targetTW3 || '-';
                    document.getElementById('detailRealisasiTW3').textContent = data.realisasiTW3 || '-';
                    document.getElementById('detailPaguTW3').textContent = data.paguAnggaranTW3 || '-';
                    document.getElementById('detailRealisasiAnggaranTW3').textContent = data.realisasiAnggaranTW3 || '-';
                    
                    document.getElementById('detailTargetTW4').textContent = data.targetTW4 || '-';
                    document.getElementById('detailRealisasiTW4').textContent = data.realisasiTW4 || '-';
                    document.getElementById('detailPaguTW4').textContent = data.paguAnggaranTW4 || '-';
                    document.getElementById('detailRealisasiAnggaranTW4').textContent = data.realisasiAnggaranTW4 || '-';
                    
                    openModal('detailModal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Fungsi untuk mengisi form edit
    function editData(id) {
        fetch(`/pk-bupati/show/${id}`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const data = result.data;
                    
                    document.getElementById('editId').value = data.id;
                    document.getElementById('editNo').value = data.no;
                    document.getElementById('editTarget2025').value = data.target2025;
                    document.getElementById('editSatuan').value = data.satuan;
                    document.getElementById('editProgram').value = data.program || '';
                    document.getElementById('editAnalisisEvaluasi').value = data.analisisEvaluasi || '';
                    
                    const penanggungJawabSelect = document.getElementById('editPenanggungJawab');
                    if (penanggungJawabSelect && data.penanggungJawab) {
                        for (let i = 0; i < penanggungJawabSelect.options.length; i++) {
                            if (penanggungJawabSelect.options[i].value === data.penanggungJawab) {
                                penanggungJawabSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                    
                    const sasaranSelect = document.getElementById('editSasaranStrategis');
                    const sasaranText = data.sasaranStrategis;
                    for (let i = 0; i < sasaranSelect.options.length; i++) {
                        if (sasaranSelect.options[i].text === sasaranText) {
                            sasaranSelect.value = sasaranSelect.options[i].value;
                            break;
                        }
                    }
                    
                    updateEditIndikator();
                    
                    setTimeout(() => {
                        const indikatorSelect = document.getElementById('editIndikatorKinerja');
                        for (let i = 0; i < indikatorSelect.options.length; i++) {
                            if (indikatorSelect.options[i].value === data.indikatorKinerja) {
                                indikatorSelect.value = data.indikatorKinerja;
                                break;
                            }
                        }
                    }, 100);
                    
                    document.getElementById('editTargetTW1').value = data.targetTW1 || '';
                    document.getElementById('editRealisasiTW1').value = data.realisasiTW1 || '';
                    document.getElementById('editPaguAnggaranTW1').value = data.paguAnggaranTW1 || '';
                    document.getElementById('editRealisasiAnggaranTW1').value = data.realisasiAnggaranTW1 || '';
                    
                    document.getElementById('editTargetTW2').value = data.targetTW2 || '';
                    document.getElementById('editRealisasiTW2').value = data.realisasiTW2 || '';
                    document.getElementById('editPaguAnggaranTW2').value = data.paguAnggaranTW2 || '';
                    document.getElementById('editRealisasiAnggaranTW2').value = data.realisasiAnggaranTW2 || '';
                    
                    document.getElementById('editTargetTW3').value = data.targetTW3 || '';
                    document.getElementById('editRealisasiTW3').value = data.realisasiTW3 || '';
                    document.getElementById('editPaguAnggaranTW3').value = data.paguAnggaranTW3 || '';
                    document.getElementById('editRealisasiAnggaranTW3').value = data.realisasiAnggaranTW3 || '';
                    
                    document.getElementById('editTargetTW4').value = data.targetTW4 || '';
                    document.getElementById('editRealisasiTW4').value = data.realisasiTW4 || '';
                    document.getElementById('editPaguAnggaranTW4').value = data.paguAnggaranTW4 || '';
                    document.getElementById('editRealisasiAnggaranTW4').value = data.realisasiAnggaranTW4 || '';
                    
                    openModal('editModal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Fungsi untuk konfirmasi hapus
    function deleteData(id) {
        document.getElementById('hapusId').value = id;
        document.getElementById('hapusItem').textContent = '';
        openModal('hapusModal');
    }

    // Submit form tambah
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('pkForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    no: document.getElementById('no').value,
                    sasaranStrategis: document.getElementById('sasaranStrategis').options[document.getElementById('sasaranStrategis').selectedIndex].text,
                    indikatorKinerja: document.getElementById('indikatorKinerja').value,
                    target2025: document.getElementById('target2025').value,
                    satuan: document.getElementById('satuan').value,
                    targetTW1: document.getElementById('targetTW1').value || null,
                    realisasiTW1: document.getElementById('realisasiTW1').value || null,
                    targetTW2: document.getElementById('targetTW2').value || null,
                    realisasiTW2: document.getElementById('realisasiTW2').value || null,
                    targetTW3: document.getElementById('targetTW3').value || null,
                    realisasiTW3: document.getElementById('realisasiTW3').value || null,
                    targetTW4: document.getElementById('targetTW4').value || null,
                    realisasiTW4: document.getElementById('realisasiTW4').value || null,
                    paguAnggaranTW1: document.getElementById('paguAnggaranTW1').value || null,
                    realisasiAnggaranTW1: document.getElementById('realisasiAnggaranTW1').value || null,
                    paguAnggaranTW2: document.getElementById('paguAnggaranTW2').value || null,
                    realisasiAnggaranTW2: document.getElementById('realisasiAnggaranTW2').value || null,
                    paguAnggaranTW3: document.getElementById('paguAnggaranTW3').value || null,
                    realisasiAnggaranTW3: document.getElementById('realisasiAnggaranTW3').value || null,
                    paguAnggaranTW4: document.getElementById('paguAnggaranTW4').value || null,
                    realisasiAnggaranTW4: document.getElementById('realisasiAnggaranTW4').value || null,
                    program: document.getElementById('program').value || null,
                    analisisEvaluasi: document.getElementById('analisisEvaluasi').value || null,
                    penanggungJawab: document.getElementById('penanggungJawab').value,
                    tahun: currentYear,
                    semester: currentSemester
                };

                fetch('{{ route("pk-bupati.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('addModal');
                        form.reset();
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }

        // Form Edit
        const editForm = document.getElementById('editForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const id = document.getElementById('editId').value;
                
                const formData = {
                    no: document.getElementById('editNo').value,
                    sasaranStrategis: document.getElementById('editSasaranStrategis').options[document.getElementById('editSasaranStrategis').selectedIndex].text,
                    indikatorKinerja: document.getElementById('editIndikatorKinerja').value,
                    target2025: document.getElementById('editTarget2025').value,
                    satuan: document.getElementById('editSatuan').value,
                    targetTW1: document.getElementById('editTargetTW1').value || null,
                    realisasiTW1: document.getElementById('editRealisasiTW1').value || null,
                    targetTW2: document.getElementById('editTargetTW2').value || null,
                    realisasiTW2: document.getElementById('editRealisasiTW2').value || null,
                    targetTW3: document.getElementById('editTargetTW3').value || null,
                    realisasiTW3: document.getElementById('editRealisasiTW3').value || null,
                    targetTW4: document.getElementById('editTargetTW4').value || null,
                    realisasiTW4: document.getElementById('editRealisasiTW4').value || null,
                    paguAnggaranTW1: document.getElementById('editPaguAnggaranTW1').value || null,
                    realisasiAnggaranTW1: document.getElementById('editRealisasiAnggaranTW1').value || null,
                    paguAnggaranTW2: document.getElementById('editPaguAnggaranTW2').value || null,
                    realisasiAnggaranTW2: document.getElementById('editRealisasiAnggaranTW2').value || null,
                    paguAnggaranTW3: document.getElementById('editPaguAnggaranTW3').value || null,
                    realisasiAnggaranTW3: document.getElementById('editRealisasiAnggaranTW3').value || null,
                    paguAnggaranTW4: document.getElementById('editPaguAnggaranTW4').value || null,
                    realisasiAnggaranTW4: document.getElementById('editRealisasiAnggaranTW4').value || null,
                    program: document.getElementById('editProgram').value || null,
                    analisisEvaluasi: document.getElementById('editAnalisisEvaluasi').value || null,
                    penanggungJawab: document.getElementById('editPenanggungJawab').value
                };

                fetch(`/pk-bupati/update/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('editModal');
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }

        // Form Hapus
        const hapusForm = document.getElementById('hapusForm');
        if (hapusForm) {
            hapusForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const id = document.getElementById('hapusId').value;
                
                fetch(`/pk-bupati/destroy/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('hapusModal');
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    });

    // Fungsi untuk beralih tab form
    function openFormTab(evt, tabId) {
        const formContainer = evt.currentTarget.closest('.bg-green-50');
        formContainer.querySelectorAll('.tabcontent').forEach(tab => {
            tab.classList.add('hidden');
        });

        document.getElementById(tabId).classList.remove('hidden');

        formContainer.querySelectorAll('.tablinks').forEach(btn => {
            btn.classList.remove('bg-gray-200');
            btn.classList.add('bg-gray-100');
        });

        evt.currentTarget.classList.remove('bg-gray-100');
        evt.currentTarget.classList.add('bg-gray-200');
    }

    // Fungsi untuk membuka tab di detail modal
    function openDetailTab(evt, tabId) {
        document.querySelectorAll('#detailModal .tabcontent').forEach(tab => {
            tab.classList.add('hidden');
        });

        document.getElementById(tabId).classList.remove('hidden');

        document.querySelectorAll('#detailModal .tablinks').forEach(btn => {
            btn.classList.remove('bg-gray-200');
            btn.classList.add('bg-gray-100');
        });

        evt.currentTarget.classList.remove('bg-gray-100');
        evt.currentTarget.classList.add('bg-gray-200');
    }
</script>
@endpush