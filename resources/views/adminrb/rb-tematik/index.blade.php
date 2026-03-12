@extends('layouts.adminrb')

@section('title', 'Monitoring Bagor - RB Tematik')

@section('content')
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    <span class="hidden sm:inline">RB Tematik</span>
                </h1>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="flex-1 px-4 md:px-8 py-6 bg-[#F8FAFC]">
            <div class="flex flex-col md:flex-row justify-between items-center px-4 md:px-6 py-4 gap-4 md:gap-0">
                <div class="flex items-center gap-3">
                    <label class="font-semibold text-gray-700 text-sm md:text-base">Tahun:</label>
                    <form method="GET" action="{{ route('adminrb.rb-tematik.index') }}">
                        <select name="year" id="yearFilter"
                            class="py-2 px-3 rounded border border-gray-300 hover:border-blue-500 transition focus:outline-none focus:ring-2 focus:ring-blue-200 text-sm md:text-base"
                            onchange="this.form.submit()">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="flex gap-2">
                    <button
                        class="flex items-center gap-2 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-300 text-sm md:text-base"
                        onclick="openModal('unduhModal')">
                        <i class="fas fa-download"></i>
                        <span>Unduh</span>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow rounded-lg mt-6 overflow-hidden border border-gray-200">
                <div
                    class="px-4 md:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">Daftar RB Tematik</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="rbTematikTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    No</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Permasalahan</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Sasaran Tematik</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Indikator</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Target</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Anggaran</th>
                                <th
                                    class="py-3 px-4 text-center font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider whitespace-nowrap border-b border-gray-200">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($rbTematik as $index => $item)
                                <tr class="hover:bg-blue-50 transition-colors" data-id="{{ $item->id }}">
                                    <td class="py-3 px-4 font-medium text-gray-900 text-sm">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4 text-sm max-w-xs">
                                        <div class="truncate" title="{{ $item->permasalahan }}">{{ $item->permasalahan }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm max-w-xs">
                                        <div class="truncate" title="{{ $item->sasaran_tematik }}">
                                            {{ $item->sasaran_tematik ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm">{{ $item->indikator ?? '-' }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                            {{ $item->target ?? '0' }} ({{ $item->satuan ?? '-' }})
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="font-semibold text-green-600">
                                            Rp
                                            {{ $item->anggaran_tahun ? number_format((float) str_replace(['.', ','], '', $item->anggaran_tahun), 0, ',', '.') : '0' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center gap-1">
                                            <!-- Detail -->
                                            <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg"
                                                onclick="showDetail({{ $item->id }})">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>

                                            <!-- Edit -->
                                            <button class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg"
                                                onclick="showEdit({{ $item->id }})">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>

                                            <!-- Hapus -->
                                            <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg"
                                                onclick="openHapusModal({{ $item->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-500">
                                        <i class="fas fa-folder-open text-4xl mb-2 text-gray-400"></i>
                                        <p>Tidak ada data RB Tematik untuk tahun {{ $selectedYear }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($rbTematik instanceof \Illuminate\Pagination\LengthAwarePaginator && $rbTematik->total() > 0)
                    <div
                        class="px-4 md:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-700">
                            Menampilkan
                            <span class="font-medium">{{ $rbTematik->firstItem() }}</span>
                            -
                            <span class="font-medium">{{ $rbTematik->lastItem() }}</span>
                            dari
                            <span class="font-medium">{{ $rbTematik->total() }}</span>
                            entri
                        </div>
                        <div class="flex gap-2">
                            @if($rbTematik->onFirstPage())
                                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $rbTematik->previousPageUrl() }}"
                                    class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                    Sebelumnya
                                </a>
                            @endif

                            @foreach($rbTematik->getUrlRange(max(1, $rbTematik->currentPage() - 2), min($rbTematik->lastPage(), $rbTematik->currentPage() + 2)) as $page => $url)
                                @if($page == $rbTematik->currentPage())
                                    <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($rbTematik->hasMorePages())
                                <a href="{{ $rbTematik->nextPageUrl() }}"
                                    class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                    Selanjutnya
                                </a>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Modals -->
            @include('components.adminrb.detail-modal-rb-tematik')
            @include('components.adminrb.ubah-modal-rb-tematik')
            @include('components.adminrb.hapus-modal-rb-tematik')
            @include('components.adminrb.unduh-modal-rb-tematik')
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>

@push('scripts')
    <script>
        // ================ FUNGSI DASAR MODAL ================
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';

                // Reset form jika ada
                if (id === 'editModal') {
                    const form = document.getElementById('editRenaksiRB');
                    if (form) form.reset();
                }
            }
        }

        // ================ FORMAT RUPIAH ================
        function formatRupiah(angka) {
            if (!angka || angka === '-' || angka === '0') return '0';
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function cleanRupiah(value) {
            if (!value) return '0';
            return value.toString().replace(/\./g, '');
        }

        // ================ DETAIL DATA DENGAN REALISASI ================
        async function showDetail(id) {
            try {
                const response = await fetch(`/adminrb/rb-tematik/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    const data = result.data;

                    // Set data ke form detail
                    document.getElementById('detailNo').value = data.id || '-';
                    document.getElementById('detailPermasalahan').value = data.permasalahan || '-';
                    document.getElementById('detailSasaranTematik').value = data.sasaran_tematik || '-';
                    document.getElementById('detailIndikator').value = data.indikator || '-';
                    document.getElementById('detailTarget').value = data.target || '-';
                    document.getElementById('detailTargetTahun').value = data.target_tahun || '-';
                    document.getElementById('detailSatuan').value = data.satuan || '-';
                    document.getElementById('detailRencanaAksi').value = data.rencana_aksi || '-';
                    document.getElementById('detailSatuanOutput').value = data.satuan_output || '-';
                    document.getElementById('detailIndikatorOutput').value = data.indikator_output || '-';
                    document.getElementById('detailKoordinator').value = data.koordinator || '-';
                    document.getElementById('detailPelaksana').value = data.pelaksana || '-';
                    document.getElementById('detailAnggaranTahun').value = data.anggaran_tahun ? 'Rp ' + formatRupiah(data.anggaran_tahun) : 'Rp 0';

                    // Set nilai TW Rencana Aksi
                    document.getElementById('detailTw1Target').innerHTML = data.renaksi_tw1_target || '-';
                    document.getElementById('detailTw1Rp').innerHTML = data.renaksi_tw1_rp ? 'Rp ' + formatRupiah(data.renaksi_tw1_rp) : 'Rp 0';
                    
                    document.getElementById('detailTw2Target').innerHTML = data.renaksi_tw2_target || '-';
                    document.getElementById('detailTw2Rp').innerHTML = data.renaksi_tw2_rp ? 'Rp ' + formatRupiah(data.renaksi_tw2_rp) : 'Rp 0';
                    
                    document.getElementById('detailTw3Target').innerHTML = data.renaksi_tw3_target || '-';
                    document.getElementById('detailTw3Rp').innerHTML = data.renaksi_tw3_rp ? 'Rp ' + formatRupiah(data.renaksi_tw3_rp) : 'Rp 0';
                    
                    document.getElementById('detailTw4Target').innerHTML = data.renaksi_tw4_target || '-';
                    document.getElementById('detailTw4Rp').innerHTML = data.renaksi_tw4_rp ? 'Rp ' + formatRupiah(data.renaksi_tw4_rp) : 'Rp 0';
                    
                    document.getElementById('detailRumus').value = data.rumus || '-';

                    // Set nilai REALISASI
                    const realisasiTw1Target = document.getElementById('detailRealisasiTw1Target');
                    if (realisasiTw1Target) {
                        realisasiTw1Target.innerHTML = data.realisasi_renaksi_tw1_target || '-';
                    }
                    
                    const realisasiTw1Rp = document.getElementById('detailRealisasiTw1Rp');
                    if (realisasiTw1Rp) {
                        realisasiTw1Rp.innerHTML = data.realisasi_renaksi_tw1_rp ? 'Rp ' + formatRupiah(data.realisasi_renaksi_tw1_rp) : 'Rp 0';
                    }

                    const realisasiTw2Target = document.getElementById('detailRealisasiTw2Target');
                    if (realisasiTw2Target) {
                        realisasiTw2Target.innerHTML = data.realisasi_renaksi_tw2_target || '-';
                    }
                    
                    const realisasiTw2Rp = document.getElementById('detailRealisasiTw2Rp');
                    if (realisasiTw2Rp) {
                        realisasiTw2Rp.innerHTML = data.realisasi_renaksi_tw2_rp ? 'Rp ' + formatRupiah(data.realisasi_renaksi_tw2_rp) : 'Rp 0';
                    }

                    const realisasiTw3Target = document.getElementById('detailRealisasiTw3Target');
                    if (realisasiTw3Target) {
                        realisasiTw3Target.innerHTML = data.realisasi_renaksi_tw3_target || '-';
                    }
                    
                    const realisasiTw3Rp = document.getElementById('detailRealisasiTw3Rp');
                    if (realisasiTw3Rp) {
                        realisasiTw3Rp.innerHTML = data.realisasi_renaksi_tw3_rp ? 'Rp ' + formatRupiah(data.realisasi_renaksi_tw3_rp) : 'Rp 0';
                    }

                    const realisasiTw4Target = document.getElementById('detailRealisasiTw4Target');
                    if (realisasiTw4Target) {
                        realisasiTw4Target.innerHTML = data.realisasi_renaksi_tw4_target || '-';
                    }
                    
                    const realisasiTw4Rp = document.getElementById('detailRealisasiTw4Rp');
                    if (realisasiTw4Rp) {
                        realisasiTw4Rp.innerHTML = data.realisasi_renaksi_tw4_rp ? 'Rp ' + formatRupiah(data.realisasi_renaksi_tw4_rp) : 'Rp 0';
                    }

                    // Set tahun
                    const tahun = data.tahun || '{{ $currentYear }}';
                    const tahunHeader = document.getElementById('detailTahunHeader');
                    if (tahunHeader) tahunHeader.textContent = tahun;

                    openModal('detailModal');
                }
            } catch (error) {
                console.error('Error detail:', error);
                alert('Gagal memuat data detail');
            }
        }

        // ================ EDIT DATA ================
        async function showEdit(id) {
            try {
                const response = await fetch(`/adminrb/rb-tematik/${id}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    const data = result.data;

                    // Set form action
                    const formEdit = document.getElementById('editRenaksiRB');
                    if (formEdit) {
                        formEdit.action = `/adminrb/rb-tematik/${id}`;
                    }

                    // Set values ke form edit
                    document.getElementById('edit_id').value = data.id || '';
                    document.getElementById('edit_no').value = data.id || '';
                    document.getElementById('edit_permasalahan').value = data.permasalahan || '';
                    document.getElementById('edit_sasaran_tematik').value = data.sasaran_tematik || '';
                    document.getElementById('edit_indikator').value = data.indikator || '';
                    document.getElementById('edit_target').value = data.target || '';
                    document.getElementById('edit_target_tahun').value = data.target_tahun || '';
                    document.getElementById('edit_satuan').value = data.satuan || '';
                    document.getElementById('edit_rencana_aksi').value = data.rencana_aksi || '';
                    document.getElementById('edit_satuan_output').value = data.satuan_output || '';
                    document.getElementById('edit_indikator_output').value = data.indikator_output || '';
                    document.getElementById('edit_anggaran_tahun').value = data.anggaran_tahun ? 'Rp ' + formatRupiah(data.anggaran_tahun) : '';
                    document.getElementById('edit_koordinator').value = data.koordinator || '';
                    document.getElementById('edit_pelaksana').value = data.pelaksana || '';

                    // Set TW values (Rencana Aksi)
                    document.getElementById('edit_tw1_target').value = data.renaksi_tw1_target || '';
                    document.getElementById('edit_tw1_rp').value = data.renaksi_tw1_rp ? formatRupiah(data.renaksi_tw1_rp) : '';
                    document.getElementById('edit_tw2_target').value = data.renaksi_tw2_target || '';
                    document.getElementById('edit_tw2_rp').value = data.renaksi_tw2_rp ? formatRupiah(data.renaksi_tw2_rp) : '';
                    document.getElementById('edit_tw3_target').value = data.renaksi_tw3_target || '';
                    document.getElementById('edit_tw3_rp').value = data.renaksi_tw3_rp ? formatRupiah(data.renaksi_tw3_rp) : '';
                    document.getElementById('edit_tw4_target').value = data.renaksi_tw4_target || '';
                    document.getElementById('edit_tw4_rp').value = data.renaksi_tw4_rp ? formatRupiah(data.renaksi_tw4_rp) : '';
                    document.getElementById('edit_rumus').value = data.rumus || '';

                    // Set REALISASI values untuk form edit
                    const editRealisasiTw1Target = document.getElementById('edit_realisasi_tw1_target');
                    if (editRealisasiTw1Target) {
                        editRealisasiTw1Target.value = data.realisasi_renaksi_tw1_target || '';
                    }
                    
                    const editRealisasiTw1Rp = document.getElementById('edit_realisasi_tw1_rp');
                    if (editRealisasiTw1Rp) {
                        editRealisasiTw1Rp.value = data.realisasi_renaksi_tw1_rp ? formatRupiah(data.realisasi_renaksi_tw1_rp) : '';
                    }

                    const editRealisasiTw2Target = document.getElementById('edit_realisasi_tw2_target');
                    if (editRealisasiTw2Target) {
                        editRealisasiTw2Target.value = data.realisasi_renaksi_tw2_target || '';
                    }
                    
                    const editRealisasiTw2Rp = document.getElementById('edit_realisasi_tw2_rp');
                    if (editRealisasiTw2Rp) {
                        editRealisasiTw2Rp.value = data.realisasi_renaksi_tw2_rp ? formatRupiah(data.realisasi_renaksi_tw2_rp) : '';
                    }

                    const editRealisasiTw3Target = document.getElementById('edit_realisasi_tw3_target');
                    if (editRealisasiTw3Target) {
                        editRealisasiTw3Target.value = data.realisasi_renaksi_tw3_target || '';
                    }
                    
                    const editRealisasiTw3Rp = document.getElementById('edit_realisasi_tw3_rp');
                    if (editRealisasiTw3Rp) {
                        editRealisasiTw3Rp.value = data.realisasi_renaksi_tw3_rp ? formatRupiah(data.realisasi_renaksi_tw3_rp) : '';
                    }

                    const editRealisasiTw4Target = document.getElementById('edit_realisasi_tw4_target');
                    if (editRealisasiTw4Target) {
                        editRealisasiTw4Target.value = data.realisasi_renaksi_tw4_target || '';
                    }
                    
                    const editRealisasiTw4Rp = document.getElementById('edit_realisasi_tw4_rp');
                    if (editRealisasiTw4Rp) {
                        editRealisasiTw4Rp.value = data.realisasi_renaksi_tw4_rp ? formatRupiah(data.realisasi_renaksi_tw4_rp) : '';
                    }

                    // Set tahun
                    const tahun = data.tahun || '{{ $currentYear }}';
                    const editTahunHeader = document.getElementById('editTahunHeader');
                    if (editTahunHeader) editTahunHeader.textContent = tahun;

                    // Hitung total anggaran
                    let totalAnggaran = 0;
                    ['renaksi_tw1_rp', 'renaksi_tw2_rp', 'renaksi_tw3_rp', 'renaksi_tw4_rp'].forEach(field => {
                        const value = data[field];
                        if (value) totalAnggaran += parseInt(value);
                    });

                    const editAnggaranTotal = document.getElementById('editAnggaranTotal');
                    if (editAnggaranTotal) {
                        editAnggaranTotal.value = 'Rp ' + totalAnggaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }

                    // Buka modal
                    openModal('editModal');
                }
            } catch (error) {
                console.error('Error edit:', error);
                alert('Gagal memuat data untuk diedit');
            }
        }

        // ================ HAPUS DATA ================
        function openHapusModal(id) {
            const form = document.getElementById("hapusForm");
            if (form) {
                form.action = `/adminrb/rb-tematik/${id}`;
            }
            openModal("hapusModal");
        }

        // ================ HANDLE EDIT FORM SUBMIT ================
        document.addEventListener('DOMContentLoaded', function () {
            // Format Rupiah untuk input
            document.addEventListener('keyup', function (e) {
                if (e.target.classList.contains('rupiah-input')) {
                    let value = e.target.value.replace(/\./g, '');
                    value = value.replace(/\D/g, '');

                    if (value !== '') {
                        value = parseInt(value).toString();
                        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        e.target.value = value;
                    }
                }
            });

            // Handle form edit
            const formEdit = document.getElementById('editRenaksiRB');
            if (formEdit) {
                formEdit.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                    submitBtn.disabled = true;

                    try {
                        const formData = new FormData(this);
                        
                        // PASTIKAN INI ADA - mengirim method PUT
                        formData.append('_method', 'PUT');

                        // Clean rupiah fields (Rencana Aksi)
                        const rupiahFields = [
                            'edit_tw1_rp', 'edit_tw2_rp', 'edit_tw3_rp', 'edit_tw4_rp', 
                            'edit_anggaran_tahun', 'edit_realisasi_tw1_rp', 'edit_realisasi_tw2_rp', 
                            'edit_realisasi_tw3_rp', 'edit_realisasi_tw4_rp'
                        ];
                        
                        rupiahFields.forEach(field => {
                            const element = document.getElementById(field);
                            if (element) {
                                const value = element.value;
                                if (value) {
                                    formData.set(field, cleanRupiah(value));
                                }
                            }
                        });

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const url = this.action;

                        const response = await fetch(url, {
                            method: 'POST', // Tetap POST karena kita pakai _method PUT
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            closeModal('editModal');
                            // Reload halaman atau update data
                            window.location.reload();
                        } else {
                            alert('Gagal menyimpan data: ' + (result.message || 'Unknown error'));
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

            // Handle form hapus
            const hapusForm = document.getElementById('hapusForm');
            if (hapusForm) {
                hapusForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
                    submitBtn.disabled = true;

                    try {
                        const formData = new FormData(this);
                        formData.append('_method', 'DELETE');

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const url = this.action;

                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            closeModal('hapusModal');
                            window.location.reload();
                        } else {
                            alert('Gagal menghapus data: ' + (result.message || 'Unknown error'));
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
    </script>
@endpush
@endsection