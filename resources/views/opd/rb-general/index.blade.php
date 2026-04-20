@extends('layouts.app')

@section('title', 'SIMORGAN')

@section('content')
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    <span class="hidden sm:inline">RB General</span>
                </h1>
                <div class="relative group">
                    <button class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-user-circle text-xl md:text-2xl text-blue-600"></i>
                        <span class="text-sm md:text-base">Admin OPD</span>
                    </button>
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

        <main class="flex-1 px-4 md:px-8 py-6 bg-[#F8FAFC]">
            
            @if($isInspektorat)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-400 mr-3 text-xl"></i>
                        <div>
                            <p class="text-sm text-blue-700">
                                <span class="font-bold">Mode Inspektorat:</span> 
                                Anda hanya dapat melihat data dan mengisi catatan evaluasi/perbaikan.
                                Tidak dapat menambah/mengubah/menghapus data utama.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($canAccess) && !$canAccess)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <span class="font-bold">Informasi Akses:</span> Akses RB General sedang ditutup oleh admin. Anda hanya dapat melihat data, tidak dapat melakukan perubahan.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center px-4 md:px-6 py-4 gap-4 md:gap-0">
                <div class="flex items-center gap-3">
                    <label class="font-semibold text-gray-700 text-sm md:text-base">Tahun:</label>
                    <form method="GET">
                        <select name="year" id="yearFilter" class="py-2 px-3 rounded border border-gray-300 hover:border-blue-500 transition focus:outline-none focus:ring-2 focus:ring-blue-200 text-sm md:text-base" onchange="this.form.submit()">
                            @foreach(range(2024, now()->year) as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="flex gap-2">
                    @if(!$isInspektorat && isset($canAccess) && $canAccess)
                        <button 
                            class="flex items-center gap-2 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm md:text-base"
                            onclick="openAddModal()"
                        >
                            <span>Tambah</span>
                        </button>
                    @else
                        <button 
                            class="flex items-center gap-2 bg-gray-400 text-white py-2 px-4 rounded cursor-not-allowed text-sm md:text-base"
                            @if($isInspektorat) title="Inspektorat tidak dapat menambah data" @else title="Akses sedang ditutup" @endif
                            disabled
                        >
                            <i class="fas fa-plus"></i>
                            <span>Tambah</span>
                        </button>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow rounded-lg mt-6 overflow-hidden border border-gray-200">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">Daftar RB General Tahun {{ $selectedYear }}</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">No</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">OPD/Unit Kerja</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Sasaran Strategis</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Indikator</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Target</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Capaian</th>
                                <th class="py-3 px-4 text-center font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($rbData as $index => $item)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-gray-900 text-sm">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="font-semibold text-blue-600">{{ $item->unit_kerja }}</span>
                                        @if($item->pelaksana)
                                            <br><span class="text-xs text-gray-500">({{ $item->pelaksana }})</span>
                                        @endif
                                    </td>
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
                                            <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-200" title="Lihat" onclick="openDetailModal({{ $item->id }})">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                            
                                            @if($isInspektorat)
                                                <button class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-200" title="Edit Catatan" onclick="openEditModal({{ $item->id }})">
                                                    <i class="fas fa-pen text-sm"></i>
                                                </button>
                                                <button class="p-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed" title="Inspektorat tidak dapat menghapus data" disabled>
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            @else
                                                @if(isset($canAccess) && $canAccess)
                                                    <button class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-200" title="Edit Data" onclick="openEditModal({{ $item->id }})">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </button>
                                                    <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-200" title="Hapus" onclick="openHapusModal({{ $item->id }})">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                @else
                                                    <button class="p-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed" title="Akses ditutup" disabled>
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </button>
                                                    <button class="p-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed" title="Akses ditutup" disabled>
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                            <p class="text-lg">Tidak ada data untuk tahun {{ $selectedYear }}</p>
                                            @if(!$isInspektorat && isset($canAccess) && $canAccess)
                                                <p class="text-sm mt-1">Klik tombol "Tambah" untuk menambahkan data baru</p>
                                            @elseif($isInspektorat)
                                                <p class="text-sm mt-1">Belum ada data dari OPD lain</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        @include('components.opd.tambah-modal-rb-general')
        @include('components.opd.ubah-modal-rb-general')
        @include('components.opd.detail-modal-rb-general')
        @include('components.opd.hapus-modal-rb-general')

        @include('components.footer')
    </div>

    <script>
        const isInspektorat = @json($isInspektorat);
        const canAccess = @json($canAccess ?? false);

        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function setValue(elementId, value) {
            const el = document.getElementById(elementId);
            if (el) el.value = value || '';
        }

        async function openEditModal(id) {
            try {
                openModal('editModal');
                const response = await fetch(`/rb-general/${id}/edit`);
                const result = await response.json();

                if (result.success) {
                    const data = result.data;
                    const isUserInspektorat = data.isInspektorat || false;

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

                    if (isUserInspektorat) {
                        const readonlyFields = [
                            'editNo', 'editSasaranStrategi', 'editIndikator', 'editTarget', 
                            'editSatuan', 'editTargetTahun', 'editRencanaAksi', 'editSatuanOutput',
                            'editIndikatorOutput', 'editRenaksiTw1Target', 'editTw1Rp',
                            'editRenaksiTw2Target', 'editTw2Rp', 'editRenaksiTw3Target', 'editTw3Rp',
                            'editRenaksiTw4Target', 'editTw4Rp', 'editAnggaranTahun',
                            'editRealisasiTw1Target', 'editRealisasiTw1Rp', 'editRealisasiTw2Target',
                            'editRealisasiTw2Rp', 'editRealisasiTw3Target', 'editRealisasiTw3Rp',
                            'editRealisasiTw4Target', 'editRealisasiTw4Rp', 'editRumus',
                            'editUnitKerja', 'editPelaksana'
                        ];

                        readonlyFields.forEach(fieldId => {
                            const field = document.getElementById(fieldId);
                            if (field) {
                                field.readOnly = true;
                                field.classList.add('bg-gray-100', 'cursor-not-allowed');
                            }
                        });

                        const catatanFields = ['editCatatanEvaluasi', 'editCatatanPerbaikan'];
                        catatanFields.forEach(fieldId => {
                            const field = document.getElementById(fieldId);
                            if (field) {
                                field.readOnly = false;
                                field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                            }
                        });

                        const modalTitle = document.querySelector('#editModal h3');
                        if (modalTitle) {
                            modalTitle.innerHTML = '<i class="fas fa-pen"></i> Edit Catatan RB General';
                        }
                    } else {
                        const allFields = document.querySelectorAll('#editModal input, #editModal select, #editModal textarea');
                        allFields.forEach(field => {
                            field.readOnly = false;
                            field.disabled = false;
                            field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                        });

                        const modalTitle = document.querySelector('#editModal h3');
                        if (modalTitle) {
                            modalTitle.innerHTML = '<i class="fas fa-edit"></i> Ubah Rencana Aksi RB General';
                        }
                    }
                } else {
                    closeModal('editModal');
                    alert('Gagal mengambil data untuk edit: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                closeModal('editModal');
                alert('Terjadi kesalahan saat mengambil data');
            }
        }

        async function openDetailModal(id) {
            try {
                openModal('detailModal');
                const response = await fetch(`/rb-general/${id}`);
                const result = await response.json();

                if (result.success) {
                    const data = result.data;
                    setValue('detailNo', data.no || '');
                    setValue('detailSasaranStrategi', data.sasaranStrategi || '');
                    setValue('detailIndikator', data.indikator || '');
                    setValue('detailTarget', data.target || '');
                    setValue('detailSatuan', data.satuan || '');
                    setValue('detailTargetTahun', data.targetTahun || '');
                    setValue('detailRencanaAksi', data.rencanaAksi || '');
                    setValue('detailSatuanOutput', data.satuanOutput || '');
                    setValue('detailIndikatorOutput', data.indikatorOutput || '');
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
                    setValue('detailPelaksana', data.pelaksana || '');
                } else {
                    alert('Gagal mengambil data detail');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            }
        }

        function openHapusModal(id) {
            const form = document.getElementById("hapusForm");
            if (form) {
                form.action = `/rb-general/${id}`;
            }
            openModal("hapusModal");
        }

        function openAddModal() {
            openModal('addModal');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const editForm = document.getElementById('editRenaksiRB');
            let isSubmitting = false;

            if (editForm) {
                editForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    if (isSubmitting) return;
                    isSubmitting = true;

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;

                    try {
                        const id = document.getElementById('editId').value;
                        const formData = new FormData(this);
                        const hasReadonlyFields = document.querySelectorAll('#editModal input[readonly], #editModal select[readonly]').length > 0;
                        
                        if (hasReadonlyFields) {
                            const fieldsToDelete = [
                                'no', 'sasaran_strategi', 'indikator_capaian', 'target', 'satuan',
                                'target_tahun', 'rencana_aksi', 'satuan_output', 'indikator_output',
                                'renaksi_tw1_target', 'tw1_rp', 'renaksi_tw2_target', 'tw2_rp',
                                'renaksi_tw3_target', 'tw3_rp', 'renaksi_tw4_target', 'tw4_rp',
                                'anggaran_tahun', 'realisasi_tw1_target', 'realisasi_tw1_rp',
                                'realisasi_tw2_target', 'realisasi_tw2_rp', 'realisasi_tw3_target',
                                'realisasi_tw3_rp', 'realisasi_tw4_target', 'realisasi_tw4_rp',
                                'rumus', 'unit_kerja', 'pelaksana'
                            ];
                            fieldsToDelete.forEach(field => formData.delete(field));
                        }

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
                            setTimeout(() => window.location.reload(), 500);
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
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memperbarui data');
                    } finally {
                        isSubmitting = false;
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }

            const hapusForm = document.getElementById('hapusForm');
            if (hapusForm) {
                hapusForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: new FormData(this),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            closeModal('hapusModal');
                            setTimeout(() => window.location.reload(), 500);
                        } else {
                            alert(result.message || 'Gagal menghapus data');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus data');
                    } finally {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }
        });

        window.openModal = openModal;
        window.closeModal = closeModal;
        window.openDetailModal = openDetailModal;
        window.openEditModal = openEditModal;
        window.openHapusModal = openHapusModal;
        window.openAddModal = openAddModal;
    </script>
@endsection