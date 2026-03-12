@extends('layouts.adminrb')

@section('title', 'Dashboard Admin RB')
@section('page-title', 'Dashboard')

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
                    <div class="relative w-full sm:w-auto">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" placeholder="Cari..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 w-full text-sm">
                    </div>
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
        }
    }

    function openHapus(id) {
        document.getElementById('deleteForm').action = `/adminrb/kelola-akun/${id}`;
        openModal('hapusModal');
    }

    function cekEmailSebelumSubmit() {
        const email = document.getElementById('emailInput').value;
        const nama_opd = document.getElementById('nama_opd').value;
        const password = document.getElementById('password').value;
        
        // Validasi sederhana
        if (!nama_opd || !email || !password) {
            alert('Semua field harus diisi!');
            return;
        }
        
        // Validasi format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Format email tidak valid!');
            return;
        }
        
        // Tampilkan loading pada button
        const btnTambah = event.target;
        const originalText = btnTambah.innerHTML;
        btnTambah.disabled = true;
        btnTambah.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memeriksa...';
        
        // Gunakan route check-email yang sudah ada
        fetch(`/adminrb/kelola-akun/check-email?email=${encodeURIComponent(email)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.duplicate) {
                // Email sudah ada, tampilkan modal peringatan
                closeModal('addOPDModal');
                
                // Isi data ke modal duplicate
                document.getElementById('duplicateEmailMessage').textContent = data.message;
                document.getElementById('existingAccountName').textContent = data.existing_user.nama_opd;
                document.getElementById('existingAccountEmail').textContent = data.existing_user.email;
                document.getElementById('existingAccountRole').textContent = data.existing_user.role;
                document.getElementById('existingAccountCreatedBy').textContent = data.existing_user.created_by;
                document.getElementById('existingAccountCreatedAt').textContent = data.existing_user.created_at;
                
                openModal('duplicateEmailModal');
            } else {
                // Email tersedia, lanjutkan simpan
                simpanAkunBaru(nama_opd, email, password);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memeriksa email. Silakan coba lagi.');
        })
        .finally(() => {
            btnTambah.disabled = false;
            btnTambah.innerHTML = originalText;
        });
    }

    function simpanAkunBaru(nama_opd, email, password) {
        const btnTambah = event.target;
        const originalText = btnTambah.innerHTML;
        btnTambah.disabled = true;
        btnTambah.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        
        fetch('{{ route("akun.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nama_opd: nama_opd,
                email: email,
                password: password,
                role: 'OPD'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Sukses - reload halaman
                window.location.reload();
            } else {
                // Tampilkan error validasi
                let errorMsg = 'Gagal menyimpan data:\n';
                if (data.errors) {
                    Object.values(data.errors).forEach(err => {
                        errorMsg += '- ' + err + '\n';
                    });
                } else {
                    errorMsg += data.message || 'Terjadi kesalahan';
                }
                alert(errorMsg);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        })
        .finally(() => {
            btnTambah.disabled = false;
            btnTambah.innerHTML = originalText;
        });
    }
</script>

@endpush