{{-- resources/views/opd/rb-general/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Monitoring Bagor')

@section('content')
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    <span class="hidden sm:inline">RB General</span>
                </h1>
                <!-- Bagian tombol Admin OPD -->
                <div class="relative group">
                    <button class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-user-circle text-xl md:text-2xl text-blue-600"></i>
                        <span class="text-sm md:text-base">Admin OPD</span>
                    </button>

                    <!-- Dropdown profil -->
                    <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-opacity duration-200 z-50">
                        <ul class="py-2 text-gray-700 text-sm">
                            <li>
                                <a href="{{ route('opd.profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Profil Saya</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="flex-1 px-4 md:px-8 py-6 bg-[#F8FAFC]">
            
            <!-- Info Banner jika akses ditutup -->
            @if(isset($canAccess) && !$canAccess && isset($accessMessage))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <span class="font-bold">Informasi Akses:</span> {{ $accessMessage }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center px-4 md:px-6 py-4 gap-4 md:gap-0">
                <div class="flex items-center gap-3">
                    <label class="font-semibold text-gray-700 text-sm md:text-base">Tahun:</label>
                    @php
                        $startYear = 2024;
                        $currentYear = now()->year;
                        $selectedYear = request()->get('year', $currentYear);
                        $years = range($startYear, $currentYear);
                    @endphp
                    <form method="GET">
                        <select name="year" id="yearFilter" class="py-2 px-3 rounded border border-gray-300 hover:border-blue-500 transition focus:outline-none focus:ring-2 focus:ring-blue-200 text-sm md:text-base" onchange="this.form.submit()">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="flex gap-2">
                    <!-- Tombol Tambah dengan pengecekan akses -->
                    <button 
                        class="flex items-center gap-2 py-2 px-4 rounded transition focus:outline-none focus:ring-2 text-sm md:text-base
                        @if(isset($canAccess) && $canAccess)
                            bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-300"
                            onclick="openAddModal()"
                        @else
                            bg-gray-400 text-gray-200 cursor-not-allowed"
                            onclick="openInfoAksesModal()"
                        @endif
                    >
                        <span>Tambah</span>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow rounded-lg mt-6 overflow-hidden border border-gray-200">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">Daftar RB General Tahun {{ $selectedYear }}</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">No</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Sasaran Strategis</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Indikator</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Target</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Capaian</th>
                                <th class="py-3 px-4 text-center font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @if($rbData->count() > 0)
                                @foreach($rbData as $index => $item)
                                    <tr class="hover:bg-blue-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-900 text-sm">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-4 text-sm max-w-xs">
                                            <div class="truncate" title="{{ $item->sasaran_strategi }}">
                                                {{ $item->sasaran_strategi }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-sm">{{ $item->indikator_capaian }}</td>
                                        <td class="py-3 px-4 text-sm">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                                {{ $item->target }} ({{ $item->satuan }})
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            <span class="font-semibold">{{ $item->target_tahun ?? '-' }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex justify-center gap-1">
                                                <!-- Detail - selalu bisa dilihat -->
                                                <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-200" title="Lihat" onclick="openDetailModal({{ $item->id }})">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </button>
                                                
                                                <!-- Edit - hanya jika akses dibuka -->
                                                @if(isset($canAccess) && $canAccess)
                                                <button class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-200" title="Edit" onclick="openEditModal({{ $item->id }})">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </button>
                                                @else
                                                <button class="p-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed" title="Akses ditutup" disabled onclick="openInfoAksesModal()">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </button>
                                                @endif
                                                
                                                <!-- Hapus - hanya jika akses dibuka -->
                                                @if(isset($canAccess) && $canAccess)
                                                <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-200" title="Hapus" onclick="openHapusModal({{ $item->id }})">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                                @else
                                                <button class="p-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed" title="Akses ditutup" disabled onclick="openInfoAksesModal()">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                            <p class="text-lg">Tidak ada data untuk tahun {{ $selectedYear }}</p>
                                            <p class="text-sm mt-1">Klik tombol "Tambah" untuk menambahkan data baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($rbData->count() > 0)
                <div class="px-4 md:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <div class="text-xs md:text-sm text-gray-700">
                        Menampilkan <span class="font-semibold">1-{{ $rbData->count() }}</span> dari <span class="font-semibold">{{ $rbData->count() }}</span> entri
                    </div>
                    <div class="flex space-x-1">
                        <button class="px-2 py-1.5 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1.5 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm">1</button>
                        <button class="px-2 py-1.5 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </main>

        <!-- Modals -->
        @include('components.opd.tambah-modal-rb-general')
        @include('components.opd.info-akses-rb-general')
        @include('components.opd.ubah-modal-rb-general')
        @include('components.opd.detail-modal-rb-general')
        @include('components.opd.hapus-modal-rb-general')

        <!-- Footer -->
        @include('components.footer')
    </div>

    <script>
        // Data akses dari server
        const canAccess = @json($canAccess ?? false);
        const accessMessage = @json($accessMessage ?? 'Akses RB General sedang ditutup.');
        const aksesData = @json($akses ?? null);

        // Simpan tahun yang dipilih ke localStorage
        document.addEventListener('DOMContentLoaded', function () {
            const yearFilter = document.getElementById('yearFilter');

            if (yearFilter) {
                const selectedYear = yearFilter.value;
                localStorage.setItem('selectedYear', selectedYear);
                updateModalYearHeaders(selectedYear);

                yearFilter.addEventListener('change', function () {
                    const newYear = this.value;
                    localStorage.setItem('selectedYear', newYear);
                    updateModalYearHeaders(newYear);
                });
            }

            function updateModalYearHeaders(year) {
                const addModalHeader = document.querySelector('#addModal h2');
                if (addModalHeader) {
                    addModalHeader.textContent = `RENCANA AKSI RB GENERAL TAHUN ${year}`;
                }

                const editModalHeader = document.querySelector('#editModal h2');
                if (editModalHeader) {
                    editModalHeader.textContent = `UBAH RENCANA AKSI RB GENERAL TAHUN ${year}`;
                }

                const detailYearHeader = document.getElementById('detailTahunHeader');
                if (detailYearHeader) {
                    detailYearHeader.textContent = year;
                }
            }

            const savedYear = localStorage.getItem('selectedYear') || '2025';
            updateModalYearHeaders(savedYear);
        });

        function setValue(elementId, value) {
            const element = document.getElementById(elementId);
            if (element) {
                element.value = value || '';
            }
        }

        // FUNGSI MODAL UMUM
        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden');
        }

        // FUNGSI MODAL TAMBAH
        function openAddModal() {
            if (!canAccess) {
                openInfoAksesModal();
                return;
            }
            
            openModal('addModal');
            
            const yearFilter = document.getElementById('yearFilter');
            const currentYear = yearFilter ? yearFilter.value : localStorage.getItem('selectedYear') || '2025';
            
            const yearSpans = document.querySelectorAll('.targetYearText, .renaksiYearText, .anggaranYearText, .realisasiYearText, #modalYear');
            yearSpans.forEach(span => {
                span.textContent = currentYear;
            });
            
            const tahunHiddenInput = document.getElementById('addTahun');
            if (tahunHiddenInput) {
                tahunHiddenInput.value = currentYear;
            }
        }

        // FUNGSI MODAL INFO AKSES
        function openInfoAksesModal() {
            const messageEl = document.getElementById('infoAksesMessage');
            if (messageEl) {
                messageEl.textContent = accessMessage;
            }
            
            if (aksesData && aksesData.end_date) {
                const deadlineEl = document.getElementById('infoAksesDeadline');
                if (deadlineEl) {
                    deadlineEl.textContent = aksesData.end_date;
                }
            }
            
            if (aksesData && aksesData.start_date && new Date(aksesData.start_date) > new Date()) {
                const startDateEl = document.getElementById('infoAksesStartDate');
                if (startDateEl) {
                    startDateEl.textContent = aksesData.start_date;
                }
            }
            
            openModal('infoAksesModal');
        }

        // MODAL DETAIL
        async function openDetailModal(id) {
            try {
                openModal('detailModal');

                const response = await fetch(`/rb-general/${id}`);
                const result = await response.json();

                if (!result.success) {
                    console.error('Gagal mengambil data detail');
                    return;
                }

                const data = result.data;

                setValue('detailNo', data.no || data.id || '');
                setValue('detailSasaranStrategi', data.sasaranStrategi || '');
                setValue('detailIndikator', data.indikator || '');
                setValue('detailTarget', data.target || '');
                setValue('detailSatuan', data.satuan || '');
                setValue('detailTargetTahun', data.targetTahun || '');
                setValue('detailRencanaAksi', data.rencanaAksi || '');
                setValue('detailSatuanOutput', data.satuanOutput || data.satuan || '');
                setValue('detailIndikatorOutput', data.indikatorOutput || data.indikator || '');
                setValue('detailRenaksiTw1Target', data.renaksiTw1Target || '');
                setValue('detailTw1Rp', data.tw1Rp || '');
                setValue('detailRenaksiTw2Target', data.renaksiTw2Target || '');
                setValue('detailTw2Rp', data.tw2Rp || '');
                setValue('detailRenaksiTw3Target', data.renaksiTw3Target || '');
                setValue('detailTw3Rp', data.tw3Rp || '');
                setValue('detailRenaksiTw4Target', data.renaksiTw4Target || '');
                setValue('detailTw4Rp', data.tw4Rp || '');
                setValue('detailAnggaranTahun', data.anggaran_tahun || '');
                setValue('detailRealisasiTw1Target', data.realisasiTw1Target || '');
                setValue('detailRealisasiTw1Rp', data.realisasiTw1Rp || '');
                setValue('detailRealisasiTw2Target', data.realisasiTw2Target || '');
                setValue('detailRealisasiTw2Rp', data.realisasiTw2Rp || '');
                setValue('detailRealisasiTw3Target', data.realisasiTw3Target || '');
                setValue('detailRealisasiTw3Rp', data.realisasiTw3Rp || '');
                setValue('detailRealisasiTw4Target', data.realisasiTw4Target || '');
                setValue('detailRealisasiTw4Rp', data.realisasiTw4Rp || '');
                setValue('detailRumus', data.rumus || '');
                setValue('detailCatatanEvaluasi', data.catatanEvaluasi || '');
                setValue('detailCatatanPerbaikan', data.catatanPerbaikan || '');
                setValue('detailUnitKerja', data.unitKerja || '');
                setValue('detailPelaksana', data.pelaksana || data.unitKerja || '');

            } catch (error) {
                console.error('Error fetching detail data:', error);
            }
        }

        // MODAL EDIT
        async function openEditModal(id) {
            if (!canAccess) {
                openInfoAksesModal();
                return;
            }
            
            try {
                openModal('editModal');

                const response = await fetch(`/rb-general/${id}/edit`);
                const result = await response.json();

                if (!result.success) {
                    console.error('Gagal mengambil data untuk edit');
                    closeModal('editModal');
                    return;
                }

                const data = result.data;

                setValue('editId', data.id || '');
                setValue('editNo', data.no || '');
                setValue('editSasaranStrategi', data.sasaran_strategi || '');
                setValue('editIndikator', data.indikator_capaian || '');
                setValue('editTarget', data.target || '');
                setValue('editSatuan', data.satuan || '');
                setValue('editTargetTahun', data.target_tahun || '');
                setValue('editRencanaAksi', data.rencana_aksi || '');
                setValue('editSatuanOutput', data.satuan_output || '');
                setValue('editIndikatorOutput', data.indikator_output || '');
                setValue('editRenaksiTw1Target', data.renaksi_tw1_target || '');
                setValue('editTw1Rp', data.tw1_rp || '');
                setValue('editRenaksiTw2Target', data.renaksi_tw2_target || '');
                setValue('editTw2Rp', data.tw2_rp || '');
                setValue('editRenaksiTw3Target', data.renaksi_tw3_target || '');
                setValue('editTw3Rp', data.tw3_rp || '');
                setValue('editRenaksiTw4Target', data.renaksi_tw4_target || '');
                setValue('editTw4Rp', data.tw4_rp || '');
                setValue('editAnggaranTahun', data.anggaran_tahun || '');
                setValue('editRealisasiTw1Target', data.realisasi_tw1_target || '');
                setValue('editRealisasiTw1Rp', data.realisasi_tw1_rp || '');
                setValue('editRealisasiTw2Target', data.realisasi_tw2_target || '');
                setValue('editRealisasiTw2Rp', data.realisasi_tw2_rp || '');
                setValue('editRealisasiTw3Target', data.realisasi_tw3_target || '');
                setValue('editRealisasiTw3Rp', data.realisasi_tw3_rp || '');
                setValue('editRealisasiTw4Target', data.realisasi_tw4_target || '');
                setValue('editRealisasiTw4Rp', data.realisasi_tw4_rp || '');
                setValue('editRumus', data.rumus || '');
                setValue('editCatatanEvaluasi', data.catatan_evaluasi || '');
                setValue('editCatatanPerbaikan', data.catatan_perbaikan || '');
                setValue('editUnitKerja', data.unit_kerja || '');
                setValue('editPelaksana', data.pelaksana || '');

            } catch (error) {
                console.error('Error fetching edit data:', error);
                closeModal('editModal');
            }
        }

        // MODAL HAPUS
        function openHapusModal(id) {
            if (!canAccess) {
                openInfoAksesModal();
                return;
            }
            
            const form = document.getElementById("hapusForm");
            if (form) {
                form.action = `/rb-general/${id}`;
            }
            openModal("hapusModal");
        }

        // EVENT LISTENER UNTUK FORM EDIT
        document.addEventListener('DOMContentLoaded', function () {
            const editForm = document.getElementById('editRenaksiRB');
            let isEditing = false;

            if (editForm) {
                editForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    if (isEditing) return;
                    isEditing = true;

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                    submitBtn.disabled = true;

                    try {
                        const id = document.getElementById('editId').value;
                        const formData = new FormData(this);
                        formData.append('_method', 'PUT');

                        const response = await fetch(`/rb-general/${id}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            closeModal('editModal');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            if (result.errors) {
                                let errorMessage = 'Terjadi kesalahan:\n';
                                for (const field in result.errors) {
                                    errorMessage += `- ${result.errors[field][0]}\n`;
                                }
                                alert(errorMessage);
                            } else {
                                alert(result.message || 'Gagal memperbarui data');
                            }
                            isEditing = false;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memperbarui data: ' + error.message);
                        isEditing = false;
                    } finally {
                        if (!isEditing) {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    }
                });
            }
        });

        window.openModal = openModal;
        window.closeModal = closeModal;
        window.openAddModal = openAddModal;
        window.openInfoAksesModal = openInfoAksesModal;
        window.openDetailModal = openDetailModal;
        window.openEditModal = openEditModal;
        window.openHapusModal = openHapusModal;
    </script>
@endsection