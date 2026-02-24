@extends('layouts.app')

@section('title', 'Analisis Jabatan & Beban Kerja')

@section('content')

    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-30">
        <div class="flex justify-between items-center py-4 px-6 md:px-8">
            <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-600"></i>
                <span class="hidden sm:inline">Analisis Jabatan & Beban Kerja</span>
            </h1>

            <div class="relative group">
                <button
                    class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-user-circle text-xl md:text-2xl text-blue-600"></i>
                    <span class="text-sm md:text-base">Admin OPD</span>
                </button>

                <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md opacity-0 
                    group-hover:opacity-100 invisible group-hover:visible transition-opacity duration-200 z-50">
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
    <div class="p-6 space-y-6 flex-1 bg-[#F8FAFC]">

        <!-- Upload Section -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold flex items-center gap-2 border-b pb-2 mb-4">
                <i class="fas fa-cloud-upload-alt text-blue-600"></i> Unggah Dokumen Analisis Jabatan & Beban Kerja
            </h2>

            <div id="uploadArea" onclick="openModal('unggahModal')"
                class="border-2 border-dashed border-gray-300 rounded p-12 text-center cursor-pointer hover:border-blue-600 hover:bg-blue-50 transition">

                <i class="fas fa-cloud-upload-alt text-4xl text-blue-600 mb-3"></i>
                <h3 class="text-lg font-medium mb-2">Seret file ke sini atau klik untuk memilih</h3>

                <p class="text-gray-500 mb-1">Format:.pdf, .doc, .docx, .xls, .xlsx</p>
                <p class="text-gray-500">Maks ukuran: 10MB</p>
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold flex items-center gap-2 border-b pb-2 mb-4">
                <i class="fas fa-history text-blue-600"></i> Dokumen Terbaru
            </h2>

            <ul class="space-y-4">
                @forelse ($laporan as $item)
                    <li class="flex items-center justify-between p-4 border rounded hover:shadow-md transition">

                        <!-- Left -->
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 flex items-center justify-center rounded bg-blue-100 text-blue-600">
                                <i class="fas fa-file"></i>
                            </div>

                            <div>
                                <div class="font-medium">{{ $item->judul }}</div>

                                <div class="text-sm text-gray-500">
                                    Diunggah pada {{ $item->tanggal_upload_formatted }} - {{ $item->kategori }}
                                </div>

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

                        <!-- Right -->
                        <div class="flex items-center gap-2">

                            {{-- Status Badge --}}
                            @php
                                $statusText = strtolower($item->status ?? '');

                                if (str_contains($statusText, 'revisi')) {
                                    $badge = 'bg-yellow-100 text-yellow-700';
                                } elseif ($statusText === 'disetujui' || $statusText === 'terverifikasi') {
                                    $badge = 'bg-green-100 text-green-600';
                                } elseif ($statusText === 'diproses') {
                                    $badge = 'bg-red-100 text-red-600';
                                } else {
                                    $badge = 'bg-gray-200 text-gray-600';
                                }
                            @endphp

                            <span class="px-2 py-1 text-xs font-medium rounded {{ $badge }}">
                                {{ $item->status ?? 'Tidak ada status' }}
                            </span>

                            {{-- Download --}}
                            <a href="{{ route('anjab-abk.download', $item->id_laporan) }}">
                                <i class="material-icons text-blue-600 cursor-pointer" title="Download">download</i>
                            </a>

                            {{-- Delete --}}
                            <button
                                onclick="openHapusModal({{ $item->id_laporan }}, '{{ route('anjab-abk.destroy', $item->id_laporan) }}')">
                                <i class="material-icons text-red-600 cursor-pointer" title="Hapus">delete</i>
                            </button>
                        </div>

                    </li>
                @empty
                    <p class="text-gray-500">Belum ada laporan diunggah.</p>
                @endforelse
            </ul>

            {{-- Pagination --}}
            @if ($laporan->total() > $laporan->perPage())
                <div class="mt-6 flex justify-center">
                    <nav class="inline-flex items-center space-x-1">

                        {{-- Prev --}}
                        @if ($laporan->onFirstPage())
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded-l">Prev</span>
                        @else
                            <a href="{{ $laporan->previousPageUrl() }}"
                                class="px-3 py-2 bg-white border rounded-l hover:bg-blue-50 hover:text-blue-600">
                                Prev
                            </a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach ($laporan->getUrlRange(1, $laporan->lastPage()) as $page => $url)
                            @if ($page == $laporan->currentPage())
                                <span class="px-3 py-2 bg-blue-600 text-white border rounded">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-2 bg-white border text-gray-700 rounded hover:bg-blue-50 hover:text-blue-600">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($laporan->hasMorePages())
                            <a href="{{ $laporan->nextPageUrl() }}"
                                class="px-3 py-2 bg-white border rounded-r hover:bg-blue-50 hover:text-blue-600">
                                Next
                            </a>
                        @else
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded-r">Next</span>
                        @endif

                    </nav>
                </div>
            @endif

        </div>
    </div>

    @include('components.footer')
    {{-- Modal Hapus --}}
    @include('components.opd.hapus-modal-anjab')

    {{-- Modal Unggah --}}
    @include('components.opd.unggah-modal-anjab')


    {{-- Scripts --}}
    <script>
        const uploadArea = document.getElementById("uploadArea");

        uploadArea.addEventListener("dragover", e => {
            e.preventDefault();
            uploadArea.classList.add("border-blue-600", "bg-blue-50");
        });

        uploadArea.addEventListener("dragleave", e => {
            uploadArea.classList.remove("border-blue-600", "bg-blue-50");
        });

        uploadArea.addEventListener("drop", e => {
            e.preventDefault();
            uploadArea.classList.remove("border-blue-600", "bg-blue-50");

            const files = e.dataTransfer.files;

            if (files.length > 0) {
                openModal('unggahModal');
                document.querySelector("#unggahModal input[name='laporan']").files = files;
            }
        });

        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden');
        }

        function openHapusModal(id, actionUrl) {
            const modal = document.getElementById('hapusModal');
            const form = document.getElementById('hapusForm');

            if (modal && form) {
                form.action = actionUrl;
                modal.classList.remove('hidden');
            }
        }
    </script>

@endsection