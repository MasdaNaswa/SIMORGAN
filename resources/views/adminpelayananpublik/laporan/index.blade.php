@extends('layouts.adminpelayananpublik')

@section('title', 'SIMORGAN')

@section('content')
    <div class="flex flex-col min-h-screen bg-[#F8FAFC]">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="material-icons text-blue-600">assignment</i>
                    <span>Daftar Laporan OPD</span>
                </h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 px-4 md:px-8 py-6">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">Laporan Masuk</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    No</th>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Nama OPD</th>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Nama Dokumen</th>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Kategori</th>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Tanggal Upload</th>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Status</th>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Catatan Admin</th>
                                <th
                                    class="py-3 px-4 text-center font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($laporans as $index => $laporan)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-gray-900">
                                        {{ ($laporans->currentPage() - 1) * $laporans->perPage() + $index + 1 }}
                                    </td>

                                    <td class="py-3 px-4">{{ $laporan->user->nama_opd ?? $laporan->user->name ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <a href="{{ Storage::url($laporan->file_path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ $laporan->judul }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4">{{ $laporan->kategori }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($laporan->tanggal_upload)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="px-2 py-1 rounded  
                                                        {{ $laporan->status == 'Disetujui' ? 'bg-green-600' : ($laporan->status == 'Revisi' ? 'bg-yellow-500' : ($laporan->status == 'Diproses' ? 'bg-red-500' : '')) }}">
                                            {{ $laporan->status ?? 'Belum diperiksa' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">{{ $laporan->catatan ?? '-' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <!-- Hapus -->
                                            <a href="javascript:void(0);" class="text-red-600 hover:text-red-800" title="Hapus"
                                                onclick="openModal('hapusLaporanModal{{ $laporan->id_laporan }}')">
                                                <i class="fas fa-trash text-sm"></i>
                                            </a>

                                            <!-- Download -->
                                            <a href="{{ asset('storage/laporan/' . $laporan->file_path) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800" title="Download">
                                                <i class="fas fa-download text-lg"></i>
                                            </a>


                                            <!-- Update Status / Catatan -->
                                            <a href="javascript:void(0);" class="text-green-600 hover:text-green-800"
                                                title="Update Status & Catatan"
                                                onclick="openStatusModal({{ $laporan->id_laporan }}, '{{ $laporan->status ?? '' }}', '{{ $laporan->catatan ?? '' }}')">
                                                <i class="fas fa-edit text-lg"></i>
                                            </a>

                                        </div>

                                        <!-- Include Modals -->
                                        @include('components.adminpelayananpublik.ubah-modal-laporan', ['laporan' => $laporan])
                                        @include('components.adminpelayananpublik.hapus-modal-laporan', ['laporan' => $laporan])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center p-4 text-gray-500">Belum ada dokumen dari OPD</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    @if ($laporans->total() > $laporans->perPage())
                        <div class="mt-6 flex justify-center">
                            <nav class="inline-flex items-center space-x-1">
                                {{-- Previous Page Link --}}
                                @if ($laporans->onFirstPage())
                                    <span
                                        class="px-3 py-2 text-gray-400 bg-gray-200 rounded-l-lg cursor-not-allowed select-none">Prev</span>
                                @else
                                    <a href="{{ $laporans->previousPageUrl() }}"
                                        class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-blue-50 hover:text-blue-600 transition">
                                        Prev
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($laporans->getUrlRange(1, $laporans->lastPage()) as $page => $url)
                                    @if ($page == $laporans->currentPage())
                                        <span
                                            class="px-3 py-2 bg-blue-600 text-white border border-blue-600 rounded transition">{{ $page }}</span>
                                    @elseif($page == 1 || $page == $laporans->lastPage() || ($page >= $laporans->currentPage() - 1 && $page <= $laporans->currentPage() + 1))
                                        <a href="{{ $url }}"
                                            class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded hover:bg-blue-50 hover:text-blue-600 transition">{{ $page }}</a>
                                    @elseif($page == $laporans->currentPage() - 2 || $page == $laporans->currentPage() + 2)
                                        <span class="px-3 py-2 text-gray-400 select-none">...</span>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($laporans->hasMorePages())
                                    <a href="{{ $laporans->nextPageUrl() }}"
                                        class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-r-lg hover:bg-blue-50 hover:text-blue-600 transition">
                                        Next
                                    </a>
                                @else
                                    <span
                                        class="px-3 py-2 text-gray-400 bg-gray-200 rounded-r-lg cursor-not-allowed select-none">Next</span>
                                @endif
                            </nav>
                        </div>
                    @endif

                </div>
            </div>
        </main>

        @include('components.footer')
    </div>

    <!-- Script untuk modal -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        function openStatusModal(id, status = '', catatan = '') {
            // buka modal
            const modal = document.getElementById(`editModal${id}`);
            if (modal) {
                modal.classList.remove('hidden');

                // isi field status dan catatan
                const statusInput = modal.querySelector('select[name="status"]') || modal.querySelector('input[name="status"]');
                const catatanInput = modal.querySelector('textarea[name="catatan"]');

                if (statusInput) statusInput.value = status;
                if (catatanInput) catatanInput.value = catatan;
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.add('hidden');
        }
    </script>
@endsection