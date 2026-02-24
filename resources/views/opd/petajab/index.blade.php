{{-- resources/views/kelembagaan/petajab.blade.php --}}
@extends('layouts.app')

@section('title', 'Peta Jabatan')

@section('content')
    <div class="min-h-screen bg-[#F8FAFC] flex flex-col">

        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-map-marked-alt text-blue-600"></i>
                    <span class="hidden sm:inline">Peta Jabatan</span>
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

        {{-- Content --}}
        <div class="p-6 space-y-6 flex-1">

            {{-- Upload Section --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold flex items-center gap-2 border-b pb-2 mb-4">
                    <i class="fas fa-cloud-upload-alt text-blue-600"></i> Unggah Dokumen Peta Jabatan
                </h2>

                <div id="uploadArea" onclick="openModal('unggahModal')"
                    class="border-2 border-dashed border-gray-300 rounded p-12 text-center cursor-pointer hover:border-blue-600 hover:bg-blue-50 transition">

                    <i class="fas fa-cloud-upload-alt text-4xl text-blue-600 mb-3"></i>
                    <h3 class="text-lg font-medium mb-2">Seret file ke sini atau klik untuk memilih</h3>
                    <p class="text-gray-500 mb-1">Format file yang didukung:.pdf, .doc, .docx, .pdf, .xls, .xlsx</p>
                    <p class="text-gray-500">Maksimal ukuran file: 10MB</p>
                </div>
            </div>

            {{-- Recent Documents --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold flex items-center gap-2 border-b pb-2 mb-4">
                    <i class="fas fa-history text-blue-600"></i> Dokumen Terbaru
                </h2>

                <ul class="space-y-4">
                    @forelse ($laporan as $item)
                                    <li class="flex items-center justify-between p-4 border rounded hover:shadow-md transition">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 flex items-center justify-center rounded bg-blue-100 text-blue-600">
                                                <i class="fas fa-file"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ $item->judul }}</div>
                                                <div class="text-sm text-gray-500">
                                                    Diunggah pada {{ \Carbon\Carbon::parse($item->tanggal_upload )->format('d-m-Y') }}
                                                    - {{ $item->kategori }}
                                                </div>


                                                {{-- Catatan Admin --}}
                                                @if($item->catatan)
    @php
        $maxLength = 30;
        $fullCatatan = $item->catatan;
        $shortCatatan = strlen($fullCatatan) > $maxLength
            ? substr($fullCatatan, 0, $maxLength) . '...'
            : $fullCatatan;
    @endphp

    <div class="relative inline-block group text-xs italic text-gray-700 cursor-pointer mt-1">
        Catatan Admin: {{ $shortCatatan }}

        @if(strlen($fullCatatan) > $maxLength)
            <div
                class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-64 max-w-xs bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-normal break-words shadow-lg">
                {{ $fullCatatan }}
            </div>
        @endif
    </div>
@endif

                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded
                        {{ $item->status == 'Diproses' ? 'bg-yellow-100 text-yellow-700' : ($item->status == 'Disetujui' ? 'bg-green-100 text-green-600' : ($item->status == 'Direvisi' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-700')) }}">
                                                {{ $item->status }}
                                            </span>


                                            <a href="{{ route('petajab.download', $item->id_laporan) }}">
                                                <i class="material-icons text-blue-600 cursor-pointer" title="Download">download</i>
                                            </a>

                                            <form action="{{ route('petajab.destroy', $item->id_laporan) }}" method="POST"
                                                onsubmit="return confirm('Hapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="openHapusModal({{ $item->id_laporan }}, '{{ route('petajab.destroy', $item->id_laporan) }}')">
                                                    <i class="material-icons text-red-600 cursor-pointer" title="Hapus">delete</i>
                                                </button>


                                            </form>
                                        </div>
                                    </li>
                    @empty
                        <p class="text-gray-500">Belum ada laporan diunggah.</p>
                    @endforelse
                </ul>
                <!-- Pagination -->
                @if ($laporan->total() > $laporan->perPage())
                    <div class="mt-6 flex justify-center">
                        <nav class="inline-flex items-center space-x-1">
                            {{-- Previous Page Link --}}
                            @if ($laporan->onFirstPage())
                                <span
                                    class="px-3 py-2 text-gray-400 bg-gray-200 rounded-l-lg cursor-not-allowed select-none">Prev</span>
                            @else
                                <a href="{{ $laporan->previousPageUrl() }}"
                                    class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-blue-50 hover:text-blue-600 transition">
                                    Prev
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($laporan->getUrlRange(1, $laporan->lastPage()) as $page => $url)
                                @if ($page == $laporan->currentPage())
                                    <span
                                        class="px-3 py-2 bg-blue-600 text-white border border-blue-600 rounded transition">{{ $page }}</span>
                                @elseif($page == 1 || $page == $laporan->lastPage() || ($page >= $laporan->currentPage() - 1 && $page <= $laporan->currentPage() + 1))
                                    <a href="{{ $url }}"
                                        class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded hover:bg-blue-50 hover:text-blue-600 transition">{{ $page }}</a>
                                @elseif($page == $laporan->currentPage() - 2 || $page == $laporan->currentPage() + 2)
                                    <span class="px-3 py-2 text-gray-400 select-none">...</span>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($laporan->hasMorePages())
                                <a href="{{ $laporan->nextPageUrl() }}"
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
    </div>

    @include('components.footer')
    {{-- Modal hapus --}}
    @include('components.opd.hapus-modal-petajab')

    {{-- Modal unggah --}}
    @include('components.opd.unggah-modal-petajab')
    <script>
        // Fungsi buka/tutup modal
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
            }
        }

        // Fungsi buka modal hapus
        function openHapusModal(id, url) {
            const modal = document.getElementById('hapusModal');
            const form = document.getElementById('hapusForm');

            form.action = url; // set action form hapus ke route yang benar

            modal.classList.remove('hidden'); // tampilkan modal
        }
    </script>
@endsection