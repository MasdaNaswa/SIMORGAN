@extends('layouts.adminpelayananpublik')

@section('title', 'SIMORGAN')

@section('menu-kelola-akun', 'text-blue-600 font-bold')

@section('content')
    <div class="flex flex-col min-h-screen bg-[#F8FAFC]">

        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-users text-blue-600"></i>
                    <span class="hidden sm:inline">Akun OPD</span>
                </h1>
            </div>
        </header>

        <!-- Tombol Tambah Akun -->
        <div class="px-4 md:px-6 py-4 flex justify-end">
            <button
                class="flex items-center gap-2 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-300 text-sm md:text-base"
                onclick="openModal('addOPDModal')">
                Tambah 
            </button>
        </div>

        <!-- Konten Utama -->
        <main class="flex-1 px-4 md:px-8 py-6">
            <!-- Table Container -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                              <tr>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">No</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">Nama OPD</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">Email</th>
                                <th class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">Role</th>
                                <th class="py-3 px-4 text-center font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">Aksi</th>
                              </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($akun as $index => $item)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-gray-900 text-sm">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 text-sm">{{ $item->nama_opd }}</td>
                                    <td class="py-3 px-4 text-sm">{{ $item->email }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                            {{ ucfirst($item->role) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center gap-2">
                                            <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition"
                                                onclick="openHapus({{ $item->id_user }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                                        <p>Belum ada data akun OPD</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        @include('components.footer')

    </div>

    {{-- Modals --}}
    @include('adminpelayananpublik.kelola-akun.partials.add-modal')
    @include('adminpelayananpublik.kelola-akun.partials.duplicate-email-modal')
    @include('adminpelayananpublik.kelola-akun.partials.hapus-modal')

@endsection

@push('scripts')
<script>
    let currentDeleteId = null;

    // Fungsi Modal
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
            // Reset form jika modal add ditutup
            if (id === 'addOPDModal') {
                const form = document.getElementById('formTambahAkun');
                if (form) form.reset();
                const emailError = document.getElementById('emailError');
                const passwordError = document.getElementById('passwordError');
                if (emailError) emailError.classList.add('hidden');
                if (passwordError) passwordError.classList.add('hidden');
            }
        }
    }

    function openHapus(id) {
        currentDeleteId = id;
        openModal('hapusModal');
    }

    // Fungsi delete tanpa loading
    async function confirmDelete() {
        if (!currentDeleteId) {
            alert('ID akun tidak ditemukan');
            return;
        }
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch(`/adminpelayananpublik/kelola-akun/${currentDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}'
                }
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                closeModal('hapusModal');
                window.location.reload();
            } else {
                alert(result.message || 'Gagal menghapus akun');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + (error.message || 'Gagal menghapus akun'));
        }
    }

    // Fungsi cek email
    async function cekEmailSebelumSubmit() {
        const emailInput = document.getElementById('emailInput');
        const email = emailInput ? emailInput.value.trim() : '';
        
        if (!email) {
            alert('Masukkan email terlebih dahulu');
            if (emailInput) emailInput.focus();
            return;
        }
        
        try {
            const response = await fetch(`/adminpelayananpublik/kelola-akun/check-email?email=${encodeURIComponent(email)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.exists) {
                showDuplicateModal(result.data);
            } else {
                submitForm();
            }
        } catch (error) {
            console.error('Error checking email:', error);
            alert('Terjadi kesalahan saat mengecek email');
        }
    }

    // Fungsi submit form tanpa loading
    async function submitForm() {
        const form = document.getElementById('formTambahAkun');
        
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        
        if (emailError) emailError.classList.add('hidden');
        if (passwordError) passwordError.classList.add('hidden');
        
        const formData = new FormData(form);
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch('/adminpelayananpublik/kelola-akun', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}'
                }
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                closeModal('addOPDModal');
                window.location.reload();
            } else {
                if (result.errors) {
                    if (result.errors.email && emailError) {
                        emailError.textContent = result.errors.email[0];
                        emailError.classList.remove('hidden');
                    }
                    if (result.errors.password && passwordError) {
                        passwordError.textContent = result.errors.password[0];
                        passwordError.classList.remove('hidden');
                    }
                } else {
                    alert(result.message || 'Gagal menambah akun');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + (error.message || 'Gagal menambah akun'));
        }
    }
    
    // Fungsi menampilkan modal duplikat email
    function showDuplicateModal(data) {
        const existingAccountName = document.getElementById('existingAccountName');
        const existingAccountEmail = document.getElementById('existingAccountEmail');
        const existingAccountCreatedBy = document.getElementById('existingAccountCreatedBy');
        const existingAccountCreatedAt = document.getElementById('existingAccountCreatedAt');
        const existingAccountRole = document.getElementById('existingAccountRole');
        const duplicateEmailMessage = document.getElementById('duplicateEmailMessage');
        
        if (existingAccountName) existingAccountName.textContent = data.nama_opd || '-';
        if (existingAccountEmail) existingAccountEmail.textContent = data.email || '-';
        if (existingAccountCreatedBy) existingAccountCreatedBy.textContent = data.created_by || '-';
        if (existingAccountCreatedAt) existingAccountCreatedAt.textContent = data.created_at || '-';
        if (existingAccountRole) existingAccountRole.textContent = data.role || 'OPD';
        if (duplicateEmailMessage) duplicateEmailMessage.innerHTML = `Email <strong>${data.email}</strong> sudah digunakan oleh akun lain.`;
        
        openModal('duplicateEmailModal');
    }
    
    function closeDuplicateModal() {
        closeModal('duplicateEmailModal');
    }
    
    function continueSubmit() {
        closeModal('duplicateEmailModal');
        submitForm();
    }
</script>
@endpush