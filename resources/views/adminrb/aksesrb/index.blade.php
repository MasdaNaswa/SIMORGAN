@extends('layouts.adminrb')

@section('title', 'Monitoring Bagor - Kontrol Akses RB')

@section('content')
    <div class="flex flex-col min-h-screen">

        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-lock text-blue-600"></i>
                    <span class="hidden sm:inline">Kontrol Akses RB</span>
                </h1>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="flex-1 px-4 md:px-8 py-6 bg-[#F8FAFC]">
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">Pengaturan Akses Menu RB</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="py-3 px-4 text-left text-xs md:text-sm uppercase font-semibold text-gray-700 border-b">
                                    No</th>
                                <th
                                    class="py-3 px-4 text-left text-xs md:text-sm uppercase font-semibold text-gray-700 border-b">
                                    Jenis RB</th>
                                <th
                                    class="py-3 px-4 text-center text-xs md:text-sm uppercase font-semibold text-gray-700 border-b">
                                    Status</th>
                                <th
                                    class="py-3 px-4 text-center text-xs md:text-sm uppercase font-semibold text-gray-700 border-b">
                                    Tanggal Mulai</th>
                                <th
                                    class="py-3 px-4 text-center text-xs md:text-sm uppercase font-semibold text-gray-700 border-b">
                                    Deadline</th>
                                <th
                                    class="py-3 px-4 text-center text-xs md:text-sm uppercase font-semibold text-gray-700 border-b">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($aksesRb as $index => $akses)
                                <tr>
                                    <td class="py-3 px-4 text-sm">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 text-sm font-semibold capitalize">{{ $akses->jenis_rb }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($akses->status === 'Dibuka')
                                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Dibuka</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Ditutup</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center text-sm">{{ $akses->start_date ?? '-' }}</td>
                                    <td class="py-3 px-4 text-center text-sm">{{ $akses->end_date ?? '-' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <button onclick="openModal('aksesModal-{{ $akses->id }}')"
                                            class="p-2 text-yellow-600 hover:bg-yellow-100 text-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-gray-500">
                                        Belum ada data akses menu RB
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal di-include di sini --}}
            @include('components.adminrb.ubah-modal-akses')
            <script>
                function openModal(id) {
                    const modal = document.getElementById(id);
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                }

                function closeModal(id) {
                    const modal = document.getElementById(id);
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                }
            </script>

        </main>

        @include('components.footer')
    </div>
@endsection