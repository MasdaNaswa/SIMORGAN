@extends('layouts.adminrb')

@section('title', 'SIMORGAN')

@section('content')
    <div class="flex flex-col min-h-screen">

        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    <span class="hidden sm:inline">PK Bupati</span>
                </h1>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="flex-1 px-4 md:px-8 py-6 bg-[#F8FAFC]">

            <!-- Filter Tahun & Semester -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 md:gap-6 px-4 md:px-6 py-4">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <form method="GET" action="{{ route('adminrb.pk-bupati.index') }}"
                        class="flex flex-wrap items-center gap-3">
                        <label class="font-semibold text-gray-700 text-sm md:text-base">Tahun:</label>
                        <select name="year" id="yearFilter"
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
                    <!-- Tombol Tambah -->
                    <button
                        class="flex items-center gap-2 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm md:text-base"
                        onclick="openAddModal()">
                        <span>Tambah</span>
                    </button>

                    <button
                        class="flex items-center gap-2 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-300 text-sm md:text-base"
                        onclick="openModal('unduhModal')">
                        <i class="fas fa-download"></i>
                        <span>Unduh</span>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white shadow rounded-lg mt-6 overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full" id="pkBupatiTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    NO</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Sasaran Strategis</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Indikator Kinerja</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Target 2025</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Satuan</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Penanggung Jawab</th>
                                <th
                                    class="py-3 px-4 text-center font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Aksi</th>
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
                                                PK Bupati {{ $selectedYear }} Semester
                                                {{ $selectedSemester == '1' ? 'I' : 'II' }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach($pkData as $item)
                                    <tr class="hover:bg-blue-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-900 text-sm">{{ $item->no }}</td>
                                        <td class="py-3 px-4 text-sm max-w-xs truncate" title="{{ $item->sasaran_strategis }}">
                                            {{ $item->sasaran_strategis }}
                                        </td>
                                        <td class="py-3 px-4 text-sm max-w-xs truncate" title="{{ $item->indikator_kinerja }}">
                                            {{ $item->indikator_kinerja }}
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">{{ $item->target_2025 }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-sm font-semibold">{{ $item->satuan }}</td>
                                        <td class="py-3 px-4 text-sm max-w-xs truncate" title="{{ $item->penanggung_jawab }}">
                                            {{ $item->penanggung_jawab }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex justify-center gap-1">
                                                <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors"
                                                    title="Lihat Detail" onclick="showDetail({{ $item->id }})">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </button>
                                                <button class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors"
                                                    title="Edit" onclick="editData({{ $item->id }})">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </button>
                                                <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                    title="Hapus" onclick="deleteData({{ $item->id }})">
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
                    <div
                        class="px-4 md:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $pkData->firstItem() }}</span> - <span
                                class="font-medium">{{ $pkData->lastItem() }}</span> dari <span
                                class="font-medium">{{ $pkData->total() }}</span> entri
                        </div>
                        <div class="flex gap-2">
                            @if($pkData->onFirstPage())
                                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed"><i
                                        class="fas fa-chevron-left"></i></span>
                            @else
                                <a href="{{ $pkData->previousPageUrl() }}"
                                    class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50"><i
                                        class="fas fa-chevron-left"></i></a>
                            @endif

                            @foreach($pkData->getUrlRange(max(1, $pkData->currentPage() - 2), min($pkData->lastPage(), $pkData->currentPage() + 2)) as $page => $url)
                                @if($page == $pkData->currentPage())
                                    <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($pkData->hasMorePages())
                                <a href="{{ $pkData->nextPageUrl() }}"
                                    class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50"><i
                                        class="fas fa-chevron-right"></i></a>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed"><i
                                        class="fas fa-chevron-right"></i></span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <!-- Modals -->
        @include('components.adminrb.tambah-modal-pk-bupati')
        @include('components.adminrb.detail-modal-pk-bupati')
        @include('components.adminrb.ubah-modal-pk-bupati')
        @include('components.adminrb.hapus-modal-pk-bupati')
        @include('components.adminrb.unduh-modal-pk-bupati')

        @include('components.footer')
    </div>

    <script>
        // Data indikator dari backend
        const indikatorData = @json($indikatorData);
        const currentYear = '{{ $selectedYear }}';
        const currentSemester = '{{ $selectedSemester }}';

        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Fungsi untuk mendapatkan nomor otomatis
        function setAutoNumber() {
            // Cari semua baris di tabel
            const table = document.getElementById('pkBupatiTable');
            let maxNo = 0;
            
            if (table) {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const firstCell = row.querySelector('td:first-child');
                    if (firstCell) {
                        const noText = firstCell.textContent.trim();
                        const noValue = parseInt(noText);
                        if (!isNaN(noValue) && noValue > maxNo) {
                            maxNo = noValue;
                        }
                    }
                });
            }
            
            const nextNumber = maxNo + 1;
            const noInput = document.getElementById('no');
            if (noInput) {
                noInput.value = nextNumber;
            }
        }

        // Fungsi untuk membuka modal tambah
        function openAddModal() {
            const form = document.getElementById('pkForm');
            if (form) form.reset();

            const indikatorSelect = document.getElementById('indikatorKinerja');
            if (indikatorSelect) {
                indikatorSelect.innerHTML = '<option value="">Pilih Indikator Kinerja</option>';
            }

            const tahunFilter = document.getElementById('yearFilter');
            const semesterFilter = document.getElementById('semesterFilter');

            if (tahunFilter) {
                let tahunInput = document.querySelector('#pkForm input[name="tahun"]');
                if (!tahunInput) {
                    tahunInput = document.createElement('input');
                    tahunInput.type = 'hidden';
                    tahunInput.name = 'tahun';
                    form.appendChild(tahunInput);
                }
                tahunInput.value = tahunFilter.value;
            }

            if (semesterFilter) {
                let semesterInput = document.querySelector('#pkForm input[name="semester"]');
                if (!semesterInput) {
                    semesterInput = document.createElement('input');
                    semesterInput.type = 'hidden';
                    semesterInput.name = 'semester';
                    form.appendChild(semesterInput);
                }
                semesterInput.value = semesterFilter.value === '1' ? 'I' : 'II';
            }

            openModal('addModal');
            
            // Set nomor otomatis setelah modal terbuka
            setTimeout(setAutoNumber, 500);
        }

        function openFormTab(evt, tabId) {
            document.querySelectorAll('#addModal .tabcontent').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
            document.querySelectorAll('#addModal .tablinks').forEach(btn => {
                btn.classList.remove('bg-gray-200');
                btn.classList.add('bg-gray-100');
            });
            evt.currentTarget.classList.remove('bg-gray-100');
            evt.currentTarget.classList.add('bg-gray-200');
        }

        function openDetailTab(event, tabId) {
            var tabContents = document.getElementsByClassName("tabcontent");
            for (var i = 0; i < tabContents.length; i++) tabContents[i].classList.add('hidden');
            var tabLinks = document.getElementsByClassName("tablinks");
            for (var i = 0; i < tabLinks.length; i++) {
                tabLinks[i].classList.remove('active', 'bg-white', 'border-b-2', 'border-green-600', 'text-green-700');
                tabLinks[i].classList.add('bg-gray-100', 'text-gray-700');
            }
            document.getElementById(tabId).classList.remove('hidden');
            event.currentTarget.classList.remove('bg-gray-100', 'text-gray-700');
            event.currentTarget.classList.add('active', 'bg-white', 'border-b-2', 'border-green-600', 'text-green-700');
        }

        function openEditTab(event, tabId) {
            var tabContents = document.getElementsByClassName("tabcontent-edit");
            for (var i = 0; i < tabContents.length; i++) tabContents[i].classList.add('hidden');
            var tabLinks = document.getElementsByClassName("tablinks-edit");
            for (var i = 0; i < tabLinks.length; i++) {
                tabLinks[i].classList.remove('active', 'bg-white', 'border-b-2', 'border-amber-600', 'text-amber-700');
                tabLinks[i].classList.add('bg-gray-100', 'text-gray-700');
            }
            document.getElementById(tabId).classList.remove('hidden');
            event.currentTarget.classList.remove('bg-gray-100', 'text-gray-700');
            event.currentTarget.classList.add('active', 'bg-white', 'border-b-2', 'border-amber-600', 'text-amber-700');
        }

        async function showDetail(id) {
            try {
                const response = await fetch(`/adminrb/pk-bupati/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (result.success) {
                    const data = result.data;
                    document.getElementById('detailNo').innerHTML = data.no || '-';
                    document.getElementById('detailTahun').innerHTML = data.tahun || '-';
                    document.getElementById('detailTahunHeader').innerHTML = data.tahun || '-';
                    document.getElementById('detailSasaranStrategis').innerHTML = data.sasaranStrategis || '-';
                    document.getElementById('detailIndikatorKinerja').innerHTML = data.indikatorKinerja || '-';
                    document.getElementById('detailTarget2025').innerHTML = data.target2025 || '-';
                    document.getElementById('detailSatuan').innerHTML = data.satuan || '-';
                    document.getElementById('detailProgram').innerHTML = data.program || '-';
                    document.getElementById('detailAnalisisEvaluasi').innerHTML = data.analisisEvaluasi || '-';
                    document.getElementById('detailPenanggungJawab').innerHTML = data.penanggungJawab || '-';
                    document.getElementById('detailTargetTW1').innerHTML = data.targetTW1 || '-';
                    document.getElementById('detailRealisasiTW1').innerHTML = data.realisasiTW1 || '-';
                    document.getElementById('detailPaguTW1').innerHTML = data.paguAnggaranTW1 || '-';
                    document.getElementById('detailRealisasiAnggaranTW1').innerHTML = data.realisasiAnggaranTW1 || '-';
                    document.getElementById('detailTargetTW2').innerHTML = data.targetTW2 || '-';
                    document.getElementById('detailRealisasiTW2').innerHTML = data.realisasiTW2 || '-';
                    document.getElementById('detailPaguTW2').innerHTML = data.paguAnggaranTW2 || '-';
                    document.getElementById('detailRealisasiAnggaranTW2').innerHTML = data.realisasiAnggaranTW2 || '-';
                    document.getElementById('detailTargetTW3').innerHTML = data.targetTW3 || '-';
                    document.getElementById('detailRealisasiTW3').innerHTML = data.realisasiTW3 || '-';
                    document.getElementById('detailPaguTW3').innerHTML = data.paguAnggaranTW3 || '-';
                    document.getElementById('detailRealisasiAnggaranTW3').innerHTML = data.realisasiAnggaranTW3 || '-';
                    document.getElementById('detailTargetTW4').innerHTML = data.targetTW4 || '-';
                    document.getElementById('detailRealisasiTW4').innerHTML = data.realisasiTW4 || '-';
                    document.getElementById('detailPaguTW4').innerHTML = data.paguAnggaranTW4 || '-';
                    document.getElementById('detailRealisasiAnggaranTW4').innerHTML = data.realisasiAnggaranTW4 || '-';
                    openModal('detailModal');
                } else {
                    alert('Gagal memuat data: ' + result.message);
                }
            } catch (error) {
                console.error('Error detail:', error);
                alert('Gagal memuat data detail');
            }
        }

        async function editData(id) {
            try {
                openModal('editModal');
                const response = await fetch(`/adminrb/pk-bupati/${id}/edit`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (result.success) {
                    const data = result.data;
                    function setValue(elementId, value) {
                        const el = document.getElementById(elementId);
                        if (el) el.value = value || '';
                    }
                    setValue('editId', data.id);
                    setValue('editNo', data.no);
                    setValue('editTarget2025', data.target2025);
                    setValue('editSatuan', data.satuan);
                    setValue('editProgram', data.program || '');
                    setValue('editAnalisisEvaluasi', data.analisisEvaluasi || '');
                    setValue('editSasaranStrategis', data.sasaranStrategis); 
                    setValue('editIndikatorKinerja', data.indikatorKinerja); 
                    setValue('editTargetTW1', data.targetTW1 || '');
                    setValue('editRealisasiTW1', data.realisasiTW1 || '');
                    setValue('editPaguAnggaranTW1', data.paguAnggaranTW1 || '');
                    setValue('editRealisasiAnggaranTW1', data.realisasiAnggaranTW1 || '');
                    setValue('editTargetTW2', data.targetTW2 || '');
                    setValue('editRealisasiTW2', data.realisasiTW2 || '');
                    setValue('editPaguAnggaranTW2', data.paguAnggaranTW2 || '');
                    setValue('editRealisasiAnggaranTW2', data.realisasiAnggaranTW2 || '');
                    setValue('editTargetTW3', data.targetTW3 || '');
                    setValue('editRealisasiTW3', data.realisasiTW3 || '');
                    setValue('editPaguAnggaranTW3', data.paguAnggaranTW3 || '');
                    setValue('editRealisasiAnggaranTW3', data.realisasiAnggaranTW3 || '');
                    setValue('editTargetTW4', data.targetTW4 || '');
                    setValue('editRealisasiTW4', data.realisasiTW4 || '');
                    setValue('editPaguAnggaranTW4', data.paguAnggaranTW4 || '');
                    setValue('editRealisasiAnggaranTW4', data.realisasiAnggaranTW4 || '');

                    const pjSelect = document.getElementById('editPenanggungJawab');
                    if (pjSelect && data.penanggungJawab) {
                        for (let i = 0; i < pjSelect.options.length; i++) {
                            if (pjSelect.options[i].value === data.penanggungJawab) {
                                pjSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }

                    const firstTab = document.querySelector('.tablinks-edit');
                    if (firstTab) firstTab.click();
                } else {
                    alert('Gagal memuat data: ' + (result.message || 'Unknown error'));
                    closeModal('editModal');
                }
            } catch (error) {
                console.error('Error edit:', error);
                alert('Gagal memuat data untuk diedit: ' + error.message);
                closeModal('editModal');
            }
        }

        function deleteData(id) {
            const hapusForm = document.getElementById('hapusForm');
            if (hapusForm) hapusForm.action = `/adminrb/pk-bupati/${id}`;
            const hapusItem = document.getElementById('hapusItem');
            if (hapusItem) {
                const rows = document.querySelectorAll('tbody tr');
                for (let row of rows) {
                    const detailButton = row.querySelector('button[onclick*="showDetail"]');
                    if (detailButton && detailButton.getAttribute('onclick').includes(id)) {
                        const sasaranCell = row.querySelector('td:nth-child(2)');
                        if (sasaranCell) {
                            const sasaranText = sasaranCell.textContent.trim();
                            hapusItem.textContent = `"${sasaranText.substring(0, 50)}${sasaranText.length > 50 ? '...' : ''}"`;
                        }
                        break;
                    }
                }
            }
            openModal('hapusModal');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const addForm = document.getElementById('pkForm');
            if (addForm) {
                addForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    try {
                        const formData = new FormData(this);
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        const result = await response.json();
                        if (result.success) {
                            closeModal('addModal');
                            location.reload();
                        } else {
                            if (result.errors) {
                                let errorMessage = 'Validasi gagal:\n';
                                for (const field in result.errors) {
                                    errorMessage += `• ${result.errors[field][0]}\n`;
                                }
                                alert(errorMessage);
                            } else {
                                alert('Gagal menyimpan: ' + (result.message || 'Unknown error'));
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan data');
                    } finally {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }

            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const id = document.getElementById('editId').value;
                    const formData = new FormData(this);
                    formData.append('_method', 'PUT');
                    try {
                        const response = await fetch(`/adminrb/pk-bupati/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        const result = await response.json();
                        if (result.success) {
                            closeModal('editModal');
                            location.reload();
                        } else {
                            alert('Gagal menyimpan data: ' + (result.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan data');
                    }
                });
            }

            const hapusForm = document.getElementById('hapusForm');
            if (hapusForm) {
                hapusForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    try {
                        const url = this.action;
                        const formData = new FormData(this);
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        const result = await response.json();
                        if (result.success) {
                            closeModal('hapusModal');
                            location.reload();
                        } else {
                            alert('Gagal menghapus data: ' + (result.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus data');
                    }
                });
            }
        });
    </script>
@endsection