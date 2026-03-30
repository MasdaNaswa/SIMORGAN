@extends('layouts.adminrb')

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
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">
                                    No</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">
                                    Nama OPD</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">
                                    Email</th>
                                <th
                                    class="py-3 px-4 text-left font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">
                                    Role</th>
                                <th
                                    class="py-3 px-4 text-center font-semibold text-gray-700 text-xs md:text-sm uppercase tracking-wider border-b border-gray-200">
                                    Aksi</th>
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
    @include('adminrb.kelola-akun.partials.add-modal')
    @include('adminrb.kelola-akun.partials.hapus-modal')
    @include('adminrb.kelola-akun.partials.duplicate-email-modal')

@endsection

@push('scripts')
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Reset form jika modal add ditutup
            if (id === 'addOPDModal') {
                document.getElementById('formTambahAkun').reset();

                // Reset pesan error
                const emailError = document.getElementById('emailError');
                if (emailError) emailError.classList.add('hidden');

                const passwordError = document.getElementById('passwordError');
                if (passwordError) passwordError.classList.add('hidden');
            }
        }

        function openHapus(id) {
            const form = document.getElementById('deleteForm');
            if (form) {
                form.action = `/adminrb/kelola-akun/${id}`;
                console.log('Form action diatur ke:', form.action);
            }
            openModal('hapusModal');
        }

        // ========== Handler untuk form hapus ==========
        function initHapusFormHandler() {
            const hapusForm = document.getElementById('deleteForm');
            if (hapusForm) {
                console.log('Form hapus ditemukan, memasang event listener');

                const newForm = hapusForm.cloneNode(true);
                hapusForm.parentNode.replaceChild(newForm, hapusForm);
                newForm.addEventListener('submit', handleHapusSubmit);
            } else {
                console.log('Form hapus belum ditemukan, mencoba lagi...');
                setTimeout(initHapusFormHandler, 500);
            }
        }

        async function handleHapusSubmit(e) {
            e.preventDefault();
            console.log('Form hapus disubmit');

            const form = e.currentTarget;
            const submitBtn = form.querySelector('button[type="submit"]');

            if (!submitBtn) return;

            // HAPUS loading indicator - langsung disable tombol saja
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

                if (result.success) {
                    closeModal('hapusModal');
                    window.location.reload();
                } else {
                    alert('Gagal menghapus: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data: ' + error.message);
            } finally {
                submitBtn.disabled = false;
            }
        }
        // ========== VALIDASI CLIENT-SIDE (DI SINI TEMPATNYA) ==========
        function validateForm() {
            // Ambil nilai dari input form
            const nama_opd = document.getElementById('nama_opd').value.trim();
            const email = document.getElementById('emailInput').value.trim();
            const password = document.getElementById('password').value;

            // Ambil elemen untuk menampilkan error
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');

            // Reset pesan error (sembunyikan dulu)
            if (emailError) emailError.classList.add('hidden');
            if (passwordError) passwordError.classList.add('hidden');

            // ===== VALIDASI 1: Field tidak boleh kosong =====
            if (!nama_opd || !email || !password) {
                alert('Semua field harus diisi!');
                return false;
            }

            // ===== VALIDASI 2: Format email harus valid =====
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                if (emailError) {
                    emailError.textContent = 'Format email tidak valid! Contoh: nama@domain.com';
                    emailError.classList.remove('hidden');
                } else {
                    alert('Format email tidak valid!');
                }
                return false;
            }

            // ===== VALIDASI 3: Password minimal 8 karakter =====
            if (password.length < 8) {
                if (passwordError) {
                    passwordError.textContent = 'Password minimal 8 karakter!';
                    passwordError.classList.remove('hidden');
                } else {
                    alert('Password minimal 8 karakter!');
                }
                return false;
            }

            // ===== VALIDASI 4: Password harus mengandung huruf besar =====
            const hasUpper = /[A-Z]/.test(password);
            if (!hasUpper) {
                if (passwordError) {
                    passwordError.textContent = 'Password harus mengandung huruf besar (A-Z)!';
                    passwordError.classList.remove('hidden');
                } else {
                    alert('Password harus mengandung huruf besar (A-Z)!');
                }
                return false;
            }

            // ===== VALIDASI 5: Password harus mengandung huruf kecil =====
            const hasLower = /[a-z]/.test(password);
            if (!hasLower) {
                if (passwordError) {
                    passwordError.textContent = 'Password harus mengandung huruf kecil (a-z)!';
                    passwordError.classList.remove('hidden');
                } else {
                    alert('Password harus mengandung huruf kecil (a-z)!');
                }
                return false;
            }

            // ===== VALIDASI 6: Password harus mengandung angka =====
            const hasNumber = /[0-9]/.test(password);
            if (!hasNumber) {
                if (passwordError) {
                    passwordError.textContent = 'Password harus mengandung angka (0-9)!';
                    passwordError.classList.remove('hidden');
                } else {
                    alert('Password harus mengandung angka (0-9)!');
                }
                return false;
            }

            // ===== VALIDASI 7: Password harus mengandung simbol =====
            const hasSpecial = /[@$!%*?&#]/.test(password);
            if (!hasSpecial) {
                if (passwordError) {
                    passwordError.textContent = 'Password harus mengandung simbol (@$!%*?&#)!';
                    passwordError.classList.remove('hidden');
                } else {
                    alert('Password harus mengandung simbol (@$!%*?&#)!');
                }
                return false;
            }

            // Jika semua validasi lolos, return true
            return true;
        }

        // ========== CEK EMAIL SEBELUM SUBMIT ==========
        function cekEmailSebelumSubmit() {
            // PERTAMA: Jalankan validasi client-side
            if (!validateForm()) {
                return; // Jika validasi gagal, STOP, tidak lanjut ke server
            }

            const email = document.getElementById('emailInput').value.trim();
            const nama_opd = document.getElementById('nama_opd').value.trim();
            const password = document.getElementById('password').value;

            const btnTambah = document.querySelector('#addOPDModal button[onclick="cekEmailSebelumSubmit()"]');
            // HAPUS loading indicator - langsung disable tombol saja
            btnTambah.disabled = true;

            const url = `/adminrb/kelola-akun/check-email?email=${encodeURIComponent(email)}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response check email:', data);

                    if (data.duplicate === true) {
                        closeModal('addOPDModal');

                        document.getElementById('duplicateEmailMessage').textContent = data.message || 'Email sudah digunakan';

                        if (data.existing_user) {
                            document.getElementById('existingAccountName').textContent = data.existing_user.nama_opd || '-';
                            document.getElementById('existingAccountEmail').textContent = data.existing_user.email || '-';
                            document.getElementById('existingAccountRole').textContent = data.existing_user.role || '-';
                            document.getElementById('existingAccountCreatedBy').textContent = data.existing_user.created_by || '-';
                            document.getElementById('existingAccountCreatedAt').textContent = data.existing_user.created_at || '-';
                        }

                        openModal('duplicateEmailModal');
                    }
                    else if (data.duplicate === false) {
                        simpanAkunBaru(nama_opd, email, password);
                    }
                    else {
                        console.error('Response format tidak sesuai:', data);
                        alert('Terjadi kesalahan: Format response tidak valid');
                    }
                })
                .catch(error => {
                    console.error('Error detail:', error);
                    alert('Terjadi kesalahan saat memeriksa email. Silakan coba lagi.');
                })
                .finally(() => {
                    btnTambah.disabled = false;
                });
        }

        // ========== SIMPAN AKUN BARU ==========
        function simpanAkunBaru(nama_opd, email, password) {
            const btnTambah = document.querySelector('#addOPDModal button[onclick="cekEmailSebelumSubmit()"]');
            // HAPUS loading indicator - langsung disable tombol saja
            btnTambah.disabled = true;

            const url = '/adminrb/kelola-akun';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nama_opd: nama_opd,
                    email: email,
                    password: password
                })
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
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
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
                })
                .finally(() => {
                    btnTambah.disabled = false;
                });
        }

        // Inisialisasi saat DOM siap
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM loaded - Initializing...');
            initHapusFormHandler();
        });
    </script>
@endpush