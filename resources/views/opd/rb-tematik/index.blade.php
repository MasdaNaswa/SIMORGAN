{{-- resources/views/opd/rb-tematik/index.blade.php --}}
@extends('layouts.app')

@section('title', 'SIMORGAN')

@section('content')
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    <span class="hidden sm:inline">RB Tematik</span>
                </h1>
                <div class="relative group">
                    <button
                        class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-user-circle text-xl md:text-2xl text-blue-600"></i>
                        <span class="text-sm md:text-base">Admin OPD</span>
                    </button>
                    <div
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-opacity duration-200 z-50">
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
            
            <!-- ========== TAMBAHAN: Info Banner jika akses ditutup ========== -->
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
            <!-- ========== END TAMBAHAN ========== -->

            <div class="flex flex-col md:flex-row justify-between items-center px-4 md:px-6 py-4 gap-4 md:gap-0">
                <div class="flex items-center gap-3">
                    <label class="font-semibold text-gray-700 text-sm md:text-base">Tahun:</label>
                    <form method="GET" action="{{ route('rb-tematik.index') }}">
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
                    <!-- ========== TAMBAHAN: Tombol Tambah dengan pengecekan akses ========== -->
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
                    <!-- ========== END TAMBAHAN ========== -->
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow rounded-lg mt-6 overflow-hidden border border-gray-200">
                <div
                    class="px-4 md:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">Daftar RB Tematik Tahun {{ $selectedYear }}</h2>
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
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center gap-1">
                                            <!-- Detail - selalu bisa dilihat -->
                                            <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg"
                                                onclick="showDetail({{ $item->id }})">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                            
                                            <!-- ========== TAMBAHAN: Edit - hanya jika akses dibuka ========== -->
                                            @if(isset($canAccess) && $canAccess)
                                            <button class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg"
                                                onclick="showEdit({{ $item->id }})">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>
                                            @else
                                            <button class="p-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed" 
                                                title="Akses ditutup" disabled onclick="openInfoAksesModal()">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>
                                            @endif
                                            <!-- ========== END TAMBAHAN ========== -->
                                            
                                            <!-- ========== TAMBAHAN: Hapus - hanya jika akses dibuka ========== -->
                                            @if(isset($canAccess) && $canAccess)
                                            <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg"
                                                onclick="openHapusModal({{ $item->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                            @else
                                            <button class="p-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed" 
                                                title="Akses ditutup" disabled onclick="openInfoAksesModal()">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                            @endif
                                            <!-- ========== END TAMBAHAN ========== -->
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500">
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
                <div class="px-4 md:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
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
                            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed"><i class="fas fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $rbTematik->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">Sebelumnya</a>
                        @endif
                        
                        @foreach($rbTematik->getUrlRange(max(1, $rbTematik->currentPage() - 2), min($rbTematik->lastPage(), $rbTematik->currentPage() + 2)) as $page => $url)
                            @if($page == $rbTematik->currentPage())
                                <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endforeach
                        
                        @if($rbTematik->hasMorePages())
                            <a href="{{ $rbTematik->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">Selanjutnya</a>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed"><i class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Modals -->
            @include('components.opd.tambah-modal-rb-tematik')
            @include('components.opd.info-akses-rb-tematik') {{-- Modal info akses --}}
            @include('components.opd.ubah-modal-rb-tematik')
            @include('components.opd.detail-modal-rb-tematik')
            @include('components.opd.hapus-modal-rb-tematik')
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>
@endsection

@push('scripts')
<script>
// ================ TAMBAHAN: Data akses dari server ================
const canAccess = @json($canAccess ?? false);
const accessMessage = @json($accessMessage ?? 'Akses RB Tematik sedang ditutup.');
const aksesData = @json($akses ?? null);
// ================ END TAMBAHAN ================

// ================ FUNGSI DASAR MODAL ================
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        console.log('Modal dibuka:', id);
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        console.log('Modal ditutup:', id);
        
        // Reset form jika perlu
        if (id === 'addModalTematik') {
            const form = document.getElementById('formTambahTematik');
            if (form) form.reset();
        } else if (id === 'editModal') {
            const form = document.getElementById('editRenaksiRB');
            if (form) form.reset();
        }
    }
}

// ================ TAMBAHAN: Fungsi untuk modal info akses ================
function openInfoAksesModal() {
    // Update pesan di modal info
    const messageEl = document.getElementById('infoAksesMessage');
    if (messageEl) {
        messageEl.textContent = accessMessage;
    }
    
    // Update deadline jika ada
    if (aksesData && aksesData.end_date) {
        const deadlineEl = document.getElementById('infoAksesDeadline');
        if (deadlineEl) {
            const date = new Date(aksesData.end_date);
            deadlineEl.textContent = date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
        document.getElementById('infoAksesDeadlineContainer')?.classList.remove('hidden');
    } else {
        document.getElementById('infoAksesDeadlineContainer')?.classList.add('hidden');
    }
    
    // Update tanggal buka jika ada
    if (aksesData && aksesData.start_date && new Date(aksesData.start_date) > new Date()) {
        const startDateEl = document.getElementById('infoAksesStartDate');
        if (startDateEl) {
            const date = new Date(aksesData.start_date);
            startDateEl.textContent = date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
        document.getElementById('infoAksesStartDateContainer')?.classList.remove('hidden');
    } else {
        document.getElementById('infoAksesStartDateContainer')?.classList.add('hidden');
    }
    
    openModal('infoAksesModal');
}

function openAddModal() {
    if (!canAccess) {
        openInfoAksesModal();
        return;
    }
    
    openModal('addModalTematik');
    
    // Update tahun di modal tambah
    const yearFilter = document.getElementById('yearFilter');
    const currentYear = yearFilter ? yearFilter.value : localStorage.getItem('selectedYearTematik') || '2025';
    
    const tahunHiddenInput = document.getElementById('addTahun');
    if (tahunHiddenInput) {
        tahunHiddenInput.value = currentYear;
    }
}
// ================ END TAMBAHAN ================

// ================ FORMAT RUPIAH ================
function formatRupiah(angka) {
    if (!angka || angka === '-' || angka === '0' || angka === 0) return '0';
    // Jika sudah dalam format number
    if (typeof angka === 'number') {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    // Hapus format yang sudah ada
    const angkaStr = angka.toString().replace(/\./g, '');
    return angkaStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function cleanRupiah(value) {
    if (!value) return '0';
    return value.toString().replace(/\./g, '');
}

// ================ FORMAT RUPIAH INPUT ================
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded - Initializing...');
    
    // Format Rupiah untuk input
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('rupiah-input')) {
            let value = e.target.value;
            // Hapus semua karakter non-digit
            value = value.replace(/[^\d]/g, '');
            
            if (value !== '') {
                // Format dengan titik setiap 3 digit
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                e.target.value = value;
            } else {
                e.target.value = '';
            }
        }
    });
    
    // Initialize form handlers
    initTambahFormHandler();
    initEditForm();
    
    // Handle form hapus
    const hapusForm = document.getElementById('hapusForm');
    if (hapusForm) {
        hapusForm.addEventListener('submit', handleHapusSubmit);
    }
    
    // Simpan tahun ke localStorage
    const yearFilter = document.getElementById('yearFilter');
    if (yearFilter) {
        localStorage.setItem('selectedYearTematik', yearFilter.value);
    }
});

// ================ INIT TAMBAH FORM HANDLER ================
function initTambahFormHandler() {
    const formTambah = document.getElementById('formTambahTematik');
    
    if (formTambah) {
        console.log('Form tambah ditemukan');
        
        // Hapus event listener lama dengan clone node
        const newForm = formTambah.cloneNode(true);
        formTambah.parentNode.replaceChild(newForm, formTambah);
        
        // Pasang event listener baru
        newForm.addEventListener('submit', handleTambahSubmit);
    } else {
        console.log('Form tambah belum ditemukan, mencoba lagi...');
        setTimeout(initTambahFormHandler, 500);
    }
}

// ================ HANDLE TAMBAH SUBMIT ================
async function handleTambahSubmit(e) {
    e.preventDefault();
    console.log('Tambah form submitted');
    
    const form = e.currentTarget;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    if (!submitBtn) return;
    
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    
    try {
        const formData = new FormData(form);
        
        // Clean rupiah fields
        const rupiahFields = [
            'tw1_rp', 'tw2_rp', 'tw3_rp', 'tw4_rp', 
            'anggaran_tahun',
            'realisasi_tw1_rp', 'realisasi_tw2_rp', 'realisasi_tw3_rp', 'realisasi_tw4_rp'
        ];
        
        rupiahFields.forEach(field => {
            const value = formData.get(field);
            if (value) {
                formData.set(field, cleanRupiah(value));
            }
        });
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token tidak ditemukan');
        }
        
        const response = await fetch('{{ route("rb-tematik.store") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });
        
        const result = await response.json();
        console.log('Response:', result);
        
        if (result.success) {
            closeModal('addModalTematik');
            window.location.reload();
        } else {
            if (result.errors) {
                let errorMessage = 'Terjadi kesalahan validasi:\n';
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
        alert('Terjadi kesalahan: ' + error.message);
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// ================ INIT EDIT FORM ================
function initEditForm() {
    const formEdit = document.getElementById('editRenaksiRB');
    
    if (formEdit) {
        console.log('Form edit ditemukan, memasang event listener');
        
        // Hapus event listener lama dengan clone node
        const newForm = formEdit.cloneNode(true);
        if (formEdit.parentNode) {
            formEdit.parentNode.replaceChild(newForm, formEdit);
        }
        
        // Pasang event listener baru
        newForm.addEventListener('submit', handleEditSubmit);
        console.log('Event listener terpasang pada form edit');
    } else {
        console.log('Form edit belum ditemukan, mencoba lagi...');
        setTimeout(initEditForm, 500);
    }
}

// ================ HANDLE EDIT SUBMIT ================
async function handleEditSubmit(e) {
    e.preventDefault();
    console.log('Edit form submitted');
    
    const form = e.currentTarget;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    if (!submitBtn) {
        console.error('Tombol submit tidak ditemukan');
        return;
    }
    
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    
    try {
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        
        // Debug: Log form data
        console.log('Form data yang akan dikirim:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        // Clean rupiah fields - PERUBAHAN: tambahkan renaksi_tw*_target ke list jika perlu
        const rupiahFields = [
            'tw1_rp', 'tw2_rp', 'tw3_rp', 'tw4_rp', 
            'anggaran_tahun',
            'realisasi_tw1_rp', 'realisasi_tw2_rp', 'realisasi_tw3_rp', 'realisasi_tw4_rp'
        ];
        
        rupiahFields.forEach(field => {
            const value = formData.get(field);
            if (value) {
                const cleanValue = cleanRupiah(value);
                formData.set(field, cleanValue);
                console.log(`Field ${field}: ${value} -> ${cleanValue}`);
            }
        });
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token tidak ditemukan');
        }
        
        const url = form.action;
        console.log('Mengirim ke URL:', url);
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });
        
        console.log('Response status:', response.status);
        
        const result = await response.json();
        console.log('Response data:', result);
        
        if (result.success) {
            closeModal('editModal');
            window.location.reload();
        } else {
            if (result.errors) {
                let errorMessage = 'Terjadi kesalahan validasi:\n';
                for (const field in result.errors) {
                    errorMessage += `• ${result.errors[field][0]}\n`;
                }
                alert(errorMessage);
            } else {
                alert('Gagal menyimpan: ' + (result.message || 'Unknown error'));
            }
        }
    } catch (error) {
        console.error('Error detail:', error);
        alert('Terjadi kesalahan saat menyimpan data: ' + error.message);
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// ================ SHOW EDIT ================
async function showEdit(id) {
    // ================ TAMBAHAN: Cek akses sebelum membuka modal edit ================
    if (!canAccess) {
        openInfoAksesModal();
        return;
    }
    // ================ END TAMBAHAN ================
    
    console.log('Edit clicked for ID:', id);
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const response = await fetch(`/rb-tematik/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Data diterima:', result);
        
        if (result.success) {
            const data = result.data;
            
            const formEdit = document.getElementById('editRenaksiRB');
            if (formEdit) {
                formEdit.action = `/rb-tematik/${id}`;
                console.log('Form action diatur ke:', formEdit.action);
            }
            
            // Set values ke form edit
            document.getElementById('edit_id').value = data.id || '';
            document.getElementById('edit_no').value = data.id || '';
            
            // Set select values
            const permasalahanSelect = document.getElementById('edit_permasalahan');
            if (permasalahanSelect && data.permasalahan) {
                permasalahanSelect.value = data.permasalahan;
            }
            
            // Set input values
            document.getElementById('edit_sasaran_tematik').value = data.sasaran_tematik || '';
            document.getElementById('edit_indikator').value = data.indikator || '';
            document.getElementById('edit_target').value = data.target || '';
            document.getElementById('edit_target_tahun').value = data.target_tahun || '';
            document.getElementById('edit_satuan').value = data.satuan || '';
            document.getElementById('edit_rencana_aksi').value = data.rencana_aksi || '';
            document.getElementById('edit_satuan_output').value = data.satuan_output || '';
            document.getElementById('edit_indikator_output').value = data.indikator_output || '';
            
            // Format Rupiah untuk anggaran tahun
            document.getElementById('edit_anggaran_tahun').value = data.anggaran_tahun ? formatRupiah(data.anggaran_tahun) : '';
            
            // ========== PERBAIKAN UTAMA ==========
            // Set TW values (Rencana Aksi) - Gunakan data.renaksi_tw*_target
            document.getElementById('edit_tw1_target').value = data.renaksi_tw1_target || '';
            document.getElementById('edit_tw1_rp').value = data.renaksi_tw1_rp ? formatRupiah(data.renaksi_tw1_rp) : '';
            document.getElementById('edit_tw2_target').value = data.renaksi_tw2_target || '';
            document.getElementById('edit_tw2_rp').value = data.renaksi_tw2_rp ? formatRupiah(data.renaksi_tw2_rp) : '';
            document.getElementById('edit_tw3_target').value = data.renaksi_tw3_target || '';
            document.getElementById('edit_tw3_rp').value = data.renaksi_tw3_rp ? formatRupiah(data.renaksi_tw3_rp) : '';
            document.getElementById('edit_tw4_target').value = data.renaksi_tw4_target || '';
            document.getElementById('edit_tw4_rp').value = data.renaksi_tw4_rp ? formatRupiah(data.renaksi_tw4_rp) : '';
            // ========== END PERBAIKAN ==========
            
            // Set REALISASI values
            document.getElementById('edit_realisasi_tw1_target').value = data.realisasi_renaksi_tw1_target || '';
            document.getElementById('edit_realisasi_tw1_rp').value = data.realisasi_renaksi_tw1_rp ? formatRupiah(data.realisasi_renaksi_tw1_rp) : '';
            document.getElementById('edit_realisasi_tw2_target').value = data.realisasi_renaksi_tw2_target || '';
            document.getElementById('edit_realisasi_tw2_rp').value = data.realisasi_renaksi_tw2_rp ? formatRupiah(data.realisasi_renaksi_tw2_rp) : '';
            document.getElementById('edit_realisasi_tw3_target').value = data.realisasi_renaksi_tw3_target || '';
            document.getElementById('edit_realisasi_tw3_rp').value = data.realisasi_renaksi_tw3_rp ? formatRupiah(data.realisasi_renaksi_tw3_rp) : '';
            document.getElementById('edit_realisasi_tw4_target').value = data.realisasi_renaksi_tw4_target || '';
            document.getElementById('edit_realisasi_tw4_rp').value = data.realisasi_renaksi_tw4_rp ? formatRupiah(data.realisasi_renaksi_tw4_rp) : '';
            document.getElementById('edit_rumus').value = data.rumus || '';
            
            // Set select values
            document.getElementById('edit_koordinator').value = data.koordinator || '';
            document.getElementById('edit_pelaksana').value = data.pelaksana || '';
            
            // Set tahun
            const tahun = data.tahun || '{{ $currentYear ?? date('Y') }}';
            const editTahunHeader = document.getElementById('editTahunHeader');
            if (editTahunHeader) editTahunHeader.textContent = tahun;
            
            const editTahunAnggaran = document.getElementById('editTahunAnggaran');
            if (editTahunAnggaran) editTahunAnggaran.value = tahun;
            
            // Buka modal
            openModal('editModal');
        } else {
            alert('Gagal memuat data: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal memuat data. Silakan coba lagi.');
    }
}

// ================ DETAIL DATA ================
async function showDetail(id) {
    console.log('Detail clicked for ID:', id);
    
    try {
        const response = await fetch(`/adminrb/rb-tematik/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        console.log('Detail data:', result);
        
        if (result.success) {
            const data = result.data;
            
            // Set data ke form detail
            document.getElementById('detailNo').value = data.id;
            document.getElementById('detailPermasalahan').value = data.permasalahan || '-';
            document.getElementById('detailSasaranTematik').value = data.sasaran_tematik || '-';
            document.getElementById('detailIndikator').value = data.indikator || '-';
            document.getElementById('detailTarget').value = data.target || '-';
            document.getElementById('detailTargetTahun').value = data.target_tahun || '-';
            document.getElementById('detailSatuan').value = data.satuan || '-';
            document.getElementById('detailRencanaAksi').value = data.rencana_aksi || '-';
            document.getElementById('detailRumus').value = data.rumus || '-';
            document.getElementById('detailSatuanOutput').value = data.satuan_output || '-';
            document.getElementById('detailIndikatorOutput').value = data.indikator_output || '-';
            document.getElementById('detailKoordinator').value = data.koordinator || '-';
            document.getElementById('detailPelaksana').value = data.pelaksana || '-';
            document.getElementById('detailAnggaranTahun').value = data.anggaran_tahun ? 'Rp ' + formatRupiah(data.anggaran_tahun) : 'Rp 0';
            
            // Set TW Rencana Aksi
            document.getElementById('detailTw1Target').textContent = data.renaksi_tw1_target || '-';
            document.getElementById('detailTw1Rp').textContent = data.renaksi_tw1_rp ? 'Rp ' + formatRupiah(data.renaksi_tw1_rp) : 'Rp 0';
            document.getElementById('detailTw2Target').textContent = data.renaksi_tw2_target || '-';
            document.getElementById('detailTw2Rp').textContent = data.renaksi_tw2_rp ? 'Rp ' + formatRupiah(data.renaksi_tw2_rp) : 'Rp 0';
            document.getElementById('detailTw3Target').textContent = data.renaksi_tw3_target || '-';
            document.getElementById('detailTw3Rp').textContent = data.renaksi_tw3_rp ? 'Rp ' + formatRupiah(data.renaksi_tw3_rp) : 'Rp 0';
            document.getElementById('detailTw4Target').textContent = data.renaksi_tw4_target || '-';
            document.getElementById('detailTw4Rp').textContent = data.renaksi_tw4_rp ? 'Rp ' + formatRupiah(data.renaksi_tw4_rp) : 'Rp 0';
            
            // Set REALISASI
            document.getElementById('detailRealisasiTw1Target').textContent = data.realisasi_renaksi_tw1_target || '-';
            document.getElementById('detailRealisasiTw1Rp').textContent = data.realisasi_renaksi_tw1_rp ? 'Rp ' + formatRupiah(data.realisasi_renaksi_tw1_rp) : 'Rp 0';
            document.getElementById('detailRealisasiTw2Target').textContent = data.realisasi_renaksi_tw2_target || '-';
            document.getElementById('detailRealisasiTw2Rp').textContent = data.realisasi_renaksi_tw2_rp ? 'Rp ' + formatRupiah(data.realisasi_renaksi_tw2_rp) : 'Rp 0';
            document.getElementById('detailRealisasiTw3Target').textContent = data.realisasi_renaksi_tw3_target || '-';
            document.getElementById('detailRealisasiTw3Rp').textContent = data.realisasi_renaksi_tw3_rp ? 'Rp ' + formatRupiah(data.realisasi_renaksi_tw3_rp) : 'Rp 0';
            document.getElementById('detailRealisasiTw4Target').textContent = data.realisasi_renaksi_tw4_target || '-';
            document.getElementById('detailRealisasiTw4Rp').textContent = data.realisasi_renaksi_tw4_rp ? 'Rp ' + formatRupiah(data.realisasi_renaksi_tw4_rp) : 'Rp 0';
            
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

// ================ HAPUS DATA ================
function openHapusModal(id) {
    // ================ TAMBAHAN: Cek akses sebelum membuka modal hapus ================
    if (!canAccess) {
        openInfoAksesModal();
        return;
    }
    // ================ END TAMBAHAN ================
    
    console.log('Hapus clicked for ID:', id);
    const form = document.getElementById("hapusForm");
    if (form) {
        form.action = `/adminrb/rb-tematik/${id}`;
    }
    openModal("hapusModal");
}

// ================ HANDLE HAPUS SUBMIT ================
async function handleHapusSubmit(e) {
    e.preventDefault();
    console.log('Hapus form submitted');
    
    const form = e.currentTarget;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    if (!submitBtn) return;
    
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    
    try {
        const formData = new FormData(form);
        formData.append('_method', 'DELETE');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const url = form.action;
        
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
        console.log('Hapus response:', result);
        
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
}
</script>
@endpush