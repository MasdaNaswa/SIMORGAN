@extends('layouts.adminrb')

@section('title', 'Monitoring Bagor - Admin RB')

@section('content')
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    <span class="hidden sm:inline">RB General</span>
                </h1>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="flex-1 px-4 md:px-8 py-6 bg-[#F8FAFC]">
            <!-- Filter Tahun -->
            <div class="flex justify-between items-center px-4 md:px-6 py-4">
                <div class="flex items-center gap-3">
                    <label class="font-semibold text-gray-700 text-sm md:text-base">Tahun:</label>
                    @php
                        $startYear = 2024;
                        $currentYear = now()->year;
                        $years = range($startYear, $currentYear);
                    @endphp
                    <form method="GET" id="yearForm">
                        <select name="year" id="yearFilter" 
                                class="py-2 px-3 rounded border border-gray-300 hover:border-blue-500 transition focus:outline-none focus:ring-2 focus:ring-blue-200 text-sm md:text-base"
                                onchange="this.form.submit()">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="flex gap-2">
                    <button class="flex items-center gap-2 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-300 text-sm md:text-base"
                            onclick="openModal('unduhModal')">
                        <i class="fas fa-download"></i>
                        <span>Unduh</span>
                    </button>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white shadow rounded-lg mt-2 overflow-hidden border border-gray-200">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">
                        Daftar RB General Tahun {{ $selectedYear }}
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="dataTable">
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
                                        <span class="font-semibold text-black-600">{{ $item->unit_kerja }}</span>
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
                                            <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-200" 
                                                    title="Lihat Detail" 
                                                    onclick="openDetailModal({{ $item->id }})">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                            <button class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-200" 
                                                    title="Edit Data" 
                                                    onclick="openEditModal({{ $item->id }})">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-200" 
                                                    title="Hapus Data" 
                                                    onclick="openHapusModal({{ $item->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-database text-5xl text-gray-300 mb-3"></i>
                                            <p class="text-lg font-medium">Tidak ada data untuk tahun {{ $selectedYear }}</p>
                                            <p class="text-sm mt-1">Belum ada OPD yang menginput data RB General</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Info Footer -->
                @if($rbData->count() > 0)
                <div class="px-4 md:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600">
                    <div>
                        Menampilkan <span class="font-semibold">1-{{ $rbData->count() }}</span> dari <span class="font-semibold">{{ $rbData->count() }}</span> entri
                    </div>
                </div>
                @endif
            </div>
        </main>

        <!-- Modals -->
        @include('components.adminrb.ubah-modal-rb-general')
        @include('components.adminrb.detail-modal-rb-general')
        @include('components.adminrb.hapus-modal-rb-general')
        @include('components.adminrb.unduh-modal-rb-general')

        <!-- Footer -->
        @include('components.footer')
    </div>

    <!-- Scripts -->
    <script>
        // Fungsi Modal
        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden');
        }

        // Helper set value
        function setValue(elementId, value) {
            const el = document.getElementById(elementId);
            if (el) el.value = value || '';
        }

        // Fungsi Detail Modal - DIPERBAIKI
        async function openDetailModal(id) {
            try {
                console.log('Membuka detail modal untuk ID:', id);
                openModal('detailModal');
                
                const response = await fetch(`/adminrb/rb-general/${id}`);
                const result = await response.json();

                console.log('Response detail:', result); // DEBUG

                if (result.success) {
                    const d = result.data;
                    
                    // Isi data ke form detail - SESUAIKAN DENGAN RESPONSE DARI CONTROLLER
                    setValue('detailNo', d.no || '');
                    setValue('detailSasaranStrategi', d.sasaran_strategi || '');
                    setValue('detailIndikator', d.indikator_capaian || '');
                    setValue('detailTarget', d.target || '');
                    setValue('detailSatuan', d.satuan || '');
                    setValue('detailTarget2025', d.target_tahun || '');
                    setValue('detailRencanaAksi', d.rencana_aksi || '');
                    setValue('detailSatuanOutput', d.satuan_output || '');
                    setValue('detailIndikatorOutput', d.indikator_output || '');
                    setValue('detailTw1Target', d.renaksi_tw1_target || '');
                    setValue('detailTw1Rp', d.tw1_rp || '');
                    setValue('detailTw2Target', d.renaksi_tw2_target || '');
                    setValue('detailTw2Rp', d.tw2_rp || '');
                    setValue('detailTw3Target', d.renaksi_tw3_target || '');
                    setValue('detailTw3Rp', d.tw3_rp || '');
                    setValue('detailTw4Target', d.renaksi_tw4_target || '');
                    setValue('detailTw4Rp', d.tw4_rp || '');
                    setValue('detailAnggaran', d.anggaran_tahun || '');
                    setValue('detailRealisasiTw1Target', d.realisasi_tw1_target || '');
                    setValue('detailRealisasiTw1Rp', d.realisasi_tw1_rp || '');
                    setValue('detailRealisasiTw2Target', d.realisasi_tw2_target || '');
                    setValue('detailRealisasiTw2Rp', d.realisasi_tw2_rp || '');
                    setValue('detailRealisasiTw3Target', d.realisasi_tw3_target || '');
                    setValue('detailRealisasiTw3Rp', d.realisasi_tw3_rp || '');
                    setValue('detailRealisasiTw4Target', d.realisasi_tw4_target || '');
                    setValue('detailRealisasiTw4Rp', d.realisasi_tw4_rp || '');
                    setValue('detailRumus', d.rumus || '');
                    setValue('detailCatatanEvaluasi', d.catatan_evaluasi || '');
                    setValue('detailCatatanPerbaikan', d.catatan_perbaikan || '');
                    setValue('detailUnitKerja', d.unit_kerja || '');
                    setValue('detailPelaksana', d.pelaksana || '');
                    
                    // Update header tahun
                    const tahunSpan = document.getElementById('detailTahunHeader');
                    if (tahunSpan) tahunSpan.textContent = d.tahun || '{{ $selectedYear }}';
                } else {
                    console.error('Gagal mengambil data:', result.message);
                }
            } catch (error) {
                console.error('Error detail:', error);
                closeModal('detailModal');
            }
        }

        // Fungsi Edit Modal - DIPERBAIKI
        async function openEditModal(id) {
            try {
                console.log('Membuka edit modal untuk ID:', id);
                openModal('editModal');
                
                const response = await fetch(`/adminrb/rb-general/${id}/edit`);
                const result = await response.json();

                console.log('Response edit:', result); // DEBUG

                if (result.success) {
                    const d = result.data;
                    
                    // Isi form edit - SESUAIKAN DENGAN RESPONSE DARI CONTROLLER
                    setValue('editId', d.id || '');
                    setValue('editNo', d.no || '');
                    
                    // Select elements
                    const sasaranSelect = document.getElementById('editSasaranStrategi');
                    if (sasaranSelect) sasaranSelect.value = d.sasaran_strategi || '';
                    
                    setValue('editIndikator', d.indikator_capaian || '');
                    setValue('editTarget', d.target || '');
                    
                    const satuanSelect = document.getElementById('editSatuan');
                    if (satuanSelect) satuanSelect.value = d.satuan || '';
                    
                    setValue('editTargetTahun', d.target_tahun || '');
                    setValue('editRencanaAksi', d.rencana_aksi || '');
                    setValue('editSatuanOutput', d.satuan_output || '');
                    setValue('editIndikatorOutput', d.indikator_output || '');
                    setValue('editRenaksiTw1Target', d.renaksi_tw1_target || '');
                    setValue('editTw1Rp', d.tw1_rp || '');
                    setValue('editRenaksiTw2Target', d.renaksi_tw2_target || '');
                    setValue('editTw2Rp', d.tw2_rp || '');
                    setValue('editRenaksiTw3Target', d.renaksi_tw3_target || '');
                    setValue('editTw3Rp', d.tw3_rp || '');
                    setValue('editRenaksiTw4Target', d.renaksi_tw4_target || '');
                    setValue('editTw4Rp', d.tw4_rp || '');
                    setValue('editAnggaranTahun', d.anggaran_tahun || '');
                    setValue('editRealisasiTw1Target', d.realisasi_tw1_target || '');
                    setValue('editRealisasiTw1Rp', d.realisasi_tw1_rp || '');
                    setValue('editRealisasiTw2Target', d.realisasi_tw2_target || '');
                    setValue('editRealisasiTw2Rp', d.realisasi_tw2_rp || '');
                    setValue('editRealisasiTw3Target', d.realisasi_tw3_target || '');
                    setValue('editRealisasiTw3Rp', d.realisasi_tw3_rp || '');
                    setValue('editRealisasiTw4Target', d.realisasi_tw4_target || '');
                    setValue('editRealisasiTw4Rp', d.realisasi_tw4_rp || '');
                    setValue('editRumus', d.rumus || '');
                    setValue('editCatatanEvaluasi', d.catatan_evaluasi || '');
                    setValue('editCatatanPerbaikan', d.catatan_perbaikan || '');
                    
                    const unitSelect = document.getElementById('editUnitKerja');
                    if (unitSelect) unitSelect.value = d.unit_kerja || '';
                    
                    setValue('editPelaksana', d.pelaksana || '');
                } else {
                    console.error('Gagal mengambil data:', result.message);
                }
            } catch (error) {
                console.error('Error edit:', error);
                closeModal('editModal');
            }
        }

        // Fungsi Hapus Modal
        function openHapusModal(id) {
            const form = document.getElementById('hapusForm');
            if (form) form.action = `/adminrb/rb-general/${id}`;
            openModal('hapusModal');
        }

        // Handle submit form edit
        document.addEventListener('DOMContentLoaded', function() {
            const editForm = document.getElementById('editRenaksiRB');
            
            if (editForm) {
                editForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const id = document.getElementById('editId').value;
                    const formData = new FormData(this);
                    formData.append('_method', 'PUT');
                    
                    try {
                        const response = await fetch(`/adminrb/rb-general/${id}`, {
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
                            // Refresh halaman setelah 1 detik
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            alert('Gagal mengupdate data: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupdate data');
                    }
                });
            }
        });

        // Export functions global
        window.openModal = openModal;
        window.closeModal = closeModal;
        window.openDetailModal = openDetailModal;
        window.openEditModal = openEditModal;
        window.openHapusModal = openHapusModal;
    </script>
@endsection