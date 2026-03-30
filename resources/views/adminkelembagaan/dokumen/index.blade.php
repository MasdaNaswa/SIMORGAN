@extends('layouts.adminkelembagaan')

@section('title', 'SIMORGAN')

@section('content')
    <div class="flex flex-col min-h-screen bg-[#F8FAFC]">

        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="material-icons text-blue-600">list</i>
                    <span class="hidden sm:inline">Semua Laporan OPD</span>
                </h1>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="flex-1 px-4 md:px-8 py-6">
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200 mt-4">
                <!-- Table Header -->
                <div
                    class="px-4 md:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800">Daftar Laporan OPD</h2>
                </div>

                <!-- Table -->
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
                                    Kategori
                                </th>

                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Tanggal Upload</th>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Status</th>
                                <th
                                    class="py-3 px-4 font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Catatan</th>
                                <th
                                    class="py-3 px-4 text-center font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($dokumen as $index => $item)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-gray-900">
                                        {{ ($dokumen->currentPage() - 1) * $dokumen->perPage() + $index + 1 }}
                                    </td>

                                    <td class="py-3 px-4">{{ $item->user->nama_opd ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $item->judul }}</td>
                                    <td class="py-3 px-4">{{ $item->kategori }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->tanggal_upload)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span
    class="px-2 py-1 rounded text-white
        {{ $item->status == 'Disetujui' ? 'bg-green-600' : ($item->status == 'Revisi' ? 'bg-yellow-500' : ($item->status == 'Diproses' ? 'bg-red-500' : '')) }}">
    {{ $item->status ?? 'Belum diperiksa' }}
</span>
                                    </td>
                                    <td class="py-3 px-4">{{ $item->catatan ?? '-' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <!-- Delete Icon -->
                                            <a href="javascript:void(0);" class="text-red-600 hover:text-red-800"
                                                title="Hapus Dokumen" onclick="hapusDokumenModal({{ $item->id_laporan }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </a>
                                            <!-- Preview Icon -->
                                            <a href="{{ route('adminkelembagaan.dokumen.preview', $item->judul) }}"
                                                class="text-blue-600 hover:text-blue-800" title="Preview Dokumen">
                                                <i class="fas fa-eye text-lg"></i>
                                            </a>

                                            <!-- Update Status & Catatan Icon -->
                                            <a href="javascript:void(0);" class="text-green-600 hover:text-green-800"
                                                title="Update Status & Catatan"
                                                onclick="openStatusModal({{ $item->id_laporan }}, '{{ $item->status ?? '' }}', '{{ $item->catatan ?? '' }}')">
                                                <i class="fas fa-edit text-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Include Modal Hapus --}}
                                @include('components.adminkelembagaan.hapus-modal-dokumen', ['item' => $item])

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center p-4 text-gray-500">Belum ada dokumen dari OPD</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    @if ($dokumen->total() > $dokumen->perPage())
                        <div class="mt-6 flex justify-center">
                            <nav class="inline-flex items-center space-x-1">
                                {{-- Previous Page Link --}}
                                @if ($dokumen->onFirstPage())
                                    <span
                                        class="px-3 py-2 text-gray-400 bg-gray-200 rounded-l-lg cursor-not-allowed select-none">Prev</span>
                                @else
                                    <a href="{{ $dokumen->previousPageUrl() }}"
                                        class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-blue-50 hover:text-blue-600 transition">
                                        Prev
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($dokumen->getUrlRange(1, $dokumen->lastPage()) as $page => $url)
                                    @if ($page == $dokumen->currentPage())
                                        <span
                                            class="px-3 py-2 bg-blue-600 text-white border border-blue-600 rounded transition">{{ $page }}</span>
                                    @elseif($page == 1 || $page == $dokumen->lastPage() || ($page >= $dokumen->currentPage() - 1 && $page <= $dokumen->currentPage() + 1))
                                        <a href="{{ $url }}"
                                            class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded hover:bg-blue-50 hover:text-blue-600 transition">{{ $page }}</a>
                                    @elseif($page == $dokumen->currentPage() - 2 || $page == $dokumen->currentPage() + 2)
                                        <span class="px-3 py-2 text-gray-400 select-none">...</span>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($dokumen->hasMorePages())
                                    <a href="{{ $dokumen->nextPageUrl() }}"
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

        {{-- Footer --}}
        @include('components.footer')

    </div>

    {{-- Include Modal Ubah Status --}}
    @include('components.adminkelembagaan.ubah-modal-dokumen')

@endsection

@push('scripts')
    <script>
        function hapusDokumenModal(id) {
            const modal = document.getElementById(`hapusDokumenModal${id}`);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeHapusModal(id) {
            const modal = document.getElementById(`hapusDokumenModal${id}`);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function openStatusModal(id, status, catatan) {
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');

            // Set form action ke route update yang benar
            form.action = `/adminkelembagaan/dokumen/${id}`; // pastikan prefix sesuai route

            // Set nilai input status & catatan
            document.getElementById('statusInput').value = status ? status.toLowerCase() : '';
            document.getElementById('catatanInput').value = catatan ?? '';

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

    </script>
@endpush