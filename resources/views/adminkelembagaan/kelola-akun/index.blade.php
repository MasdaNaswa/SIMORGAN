@extends('layouts.adminkelembagaan')

@section('title', 'SIMORGAN')

@section('menu-dashboard', 'text-blue-600 font-bold')

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
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200 mt-4">
                <div
                    class="px-4 md:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">Daftar Akun OPD</h2>
                </div>

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
                                    <td class="py-3 px-4 text-sm">{{ ucfirst($item->role) }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center gap-2">
                                            <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg"
                                                onclick="openHapus({{ $item->id_user }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-3 px-4 text-center text-gray-500">Belum ada data akun OPD</td>
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
    @include('adminkelembagaan.kelola-akun.partials.add-modal')
    @include('adminkelembagaan.kelola-akun.partials.hapus-modal')
    @include('adminkelembagaan.kelola-akun.partials.duplicate-email-modal')

@endsection

@push('scripts')
<script>
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
                
                // Reset pesan error
                const emailError = document.getElementById('emailError');
                if (emailError) emailError.classList.add('hidden');
                
                const passwordError = document.getElementById('passwordError');
                if (passwordError) passwordError.classList.add('hidden');
            }
        }
    }

    function openHapus(id) {
        const form = document.getElementById('deleteForm');
        if (form) {
            form.action = `/adminkelembagaan/kelola-akun/${id}`;
        }
        openModal('hapusModal');
    }

    // ========== VALIDASI CLIENT-SIDE ==========
    function validateForm() {
        const nama_opd = document.getElementById('nama_opd').value.trim();
        const email = document.getElementById('emailInput').value.trim();
        const password = document.getElementById('password').value;
        
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        
        if (emailError) emailError.classList.add('hidden');
        if (passwordError) passwordError.classList.add('hidden');
        
        // Validasi field tidak kosong
        if (!nama_opd || !email || !password) {
            alert('Semua field harus diisi!');
            return false;
        }
        
        // Validasi format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            if (emailError) {
                emailError.textContent = 'Format email tidak valid! Contoh: nama@domain.com';
                emailError.classList.remove('hidden');
            }
            return false;
        }
        
        // Validasi password minimal 8 karakter
        if (password.length < 8) {
            if (passwordError) {
                passwordError.textContent = 'Password minimal 8 karakter!';
                passwordError.classList.remove('hidden');
            }
            return false;
        }
        
        // Validasi password mengandung huruf besar
        if (!/[A-Z]/.test(password)) {
            if (passwordError) {
                passwordError.textContent = 'Password harus mengandung huruf besar (A-Z)!';
                passwordError.classList.remove('hidden');
            }
            return false;
        }
        
        // Validasi password mengandung huruf kecil
        if (!/[a-z]/.test(password)) {
            if (passwordError) {
                passwordError.textContent = 'Password harus mengandung huruf kecil (a-z)!';
                passwordError.classList.remove('hidden');
            }
            return false;
        }
        
        // Validasi password mengandung angka
        if (!/[0-9]/.test(password)) {
            if (passwordError) {
                passwordError.textContent = 'Password harus mengandung angka (0-9)!';
                passwordError.classList.remove('hidden');
            }
            return false;
        }
        
        // Validasi password mengandung simbol
        if (!/[@$!%*?&#]/.test(password)) {
            if (passwordError) {
                passwordError.textContent = 'Password harus mengandung simbol (@$!%*?&#)!';
                passwordError.classList.remove('hidden');
            }
            return false;
        }
        
        return true;
    }

    // ========== CEK EMAIL SEBELUM SUBMIT ==========
    async function cekEmailSebelumSubmit() {
        if (!validateForm()) {
            return;
        }
        
        const email = document.getElementById('emailInput').value.trim();
        
        try {
            const url = `/adminkelembagaan/kelola-akun/check-email?email=${encodeURIComponent(email)}`;
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.duplicate === true) {
                // Email sudah ada, tampilkan modal duplicate
                closeModal('addOPDModal');
                
                // Set data ke modal duplicate
                document.getElementById('duplicateEmailMessage').textContent = data.message || 'Email sudah digunakan';
                document.getElementById('existingAccountName').textContent = data.existing_user?.nama_opd || '-';
                document.getElementById('existingAccountEmail').textContent = data.existing_user?.email || '-';
                document.getElementById('existingAccountRole').textContent = data.existing_user?.role || '-';
                document.getElementById('existingAccountCreatedBy').textContent = data.existing_user?.created_by || '-';
                document.getElementById('existingAccountCreatedAt').textContent = data.existing_user?.created_at || '-';
                
                openModal('duplicateEmailModal');
            } 
            else if (data.duplicate === false) {
                // Email tersedia, lanjutkan simpan
                await simpanAkunBaru();
            }
            else {
                alert('Terjadi kesalahan: Format response tidak valid');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memeriksa email: ' + (error.message || 'Silakan coba lagi.'));
        }
    }

    // ========== SIMPAN AKUN BARU ==========
    async function simpanAkunBaru() {
        const form = document.getElementById('formTambahAkun');
        const formData = new FormData(form);
        
        const url = '/adminkelembagaan/kelola-akun';
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.reload();
            } else {
                let errorMsg = 'Gagal menyimpan data:\n';
                if (data.errors) {
                    if (Array.isArray(data.errors)) {
                        data.errors.forEach(err => {
                            errorMsg += '- ' + err + '\n';
                        });
                    } else {
                        Object.values(data.errors).forEach(err => {
                            errorMsg += '- ' + err + '\n';
                        });
                    }
                } else {
                    errorMsg += data.message || 'Terjadi kesalahan';
                }
                alert(errorMsg);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data: ' + (error.message || 'Silakan coba lagi.'));
        }
    }

    // ========== HANDLER HAPUS ==========
    function initHapusFormHandler() {
        const hapusForm = document.getElementById('deleteForm');
        if (hapusForm) {
            const newForm = hapusForm.cloneNode(true);
            hapusForm.parentNode.replaceChild(newForm, hapusForm);
            newForm.addEventListener('submit', handleHapusSubmit);
        } else {
            setTimeout(initHapusFormHandler, 500);
        }
    }

    async function handleHapusSubmit(e) {
        e.preventDefault();
        
        const form = e.currentTarget;
        
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
            
            if (result.success) {
                closeModal('hapusModal');
                window.location.reload();
            } else {
                alert('Gagal menghapus: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        }
    }

    // Event listener saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        const btnTambah = document.getElementById('btnTambahAkun');
        if (btnTambah) {
            btnTambah.onclick = cekEmailSebelumSubmit;
        }
        
        initHapusFormHandler();
    });
</script>
@endpush