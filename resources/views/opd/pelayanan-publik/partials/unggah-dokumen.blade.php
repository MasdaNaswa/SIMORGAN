<div class="bg-white rounded-lg shadow-sm p-6 mb-5">
    <h2 class="text-lg font-medium mb-4 pb-3 border-b border-gray-200 flex items-center gap-2">
        <i class="fas fa-cloud-upload-alt text-primary"></i> Unggah Dokumen
    </h2>

    <div id="uploadArea"
        class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-8 text-center my-5 cursor-pointer hover:border-primary">
        <i class="fas fa-cloud-upload-alt text-primary text-4xl mb-4"></i>
        <h3 class="font-medium mb-2">Seret file ke sini atau klik untuk memilih</h3>
        <p class="text-gray-500 mb-1">Format: .doc, .docx, .pdf</p>
        <p class="text-gray-500">Maksimal 10MB</p>
    </div>

    {{-- Recent Documents --}}
    <div class="bg-white p-6 rounded-lg shadow mt-4">
        <h2 class="text-lg font-semibold flex items-center gap-2 border-b pb-2 mb-4">
            <i class="fas fa-history text-blue-600"></i> Dokumen Terbaru (Upload Manual)
        </h2>

        @if($laporans->count() > 0)
            <ul class="space-y-4">
                @foreach ($laporans as $doc)
                    <li
                        class="flex items-center justify-between p-4 border rounded hover:shadow-md transition overflow-visible">
                        <div class="flex items-center gap-4">
                            {{-- File Icon --}}
                            <div @class([
                                'w-10 h-10 flex items-center justify-center rounded',
                                'bg-red-100 text-red-600' => Str::endsWith($doc->file_path, '.pdf'),
                                'bg-blue-100 text-blue-600' => !Str::endsWith($doc->file_path, '.pdf')
                            ])>
                        <i class="fas fa-file"></i>
                            </div>

                            {{-- Document Info --}}
                            <div class="flex flex-col">
                                <div class="font-medium">{{ $doc->judul }}</div>
                                <div class="text-sm text-gray-500 mb-1">
                                    Diunggah pada {{ $doc->tanggal_upload_formatted }} - {{ $doc->kategori }}
                                </div>

                                @if($doc->catatan)
                                    @php
                                        $maxLength = 30;
                                        $fullCatatan = $doc->catatan;
                                        $shortCatatan = strlen($fullCatatan) > $maxLength
                                            ? substr($fullCatatan, 0, $maxLength) . '...'
                                            : $fullCatatan;
                                    @endphp

                                    <div class="relative inline-block group text-xs italic text-gray-700 cursor-pointer">
                                        Catatan Admin: {{ $shortCatatan }}

                                        @if(strlen($fullCatatan) > $maxLength)
                                            <div class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-64 max-w-xs bg-blue-600 text-white text-sm rounded-md py-2 px-3 opacity-0 group-hover:opacity-100 transition-opacity z-[9999] whitespace-normal break-words shadow-lg pointer-events-none">
    {{ $fullCatatan }}
</div>

                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons & Status --}}
                        <div class="flex items-center gap-2">
                            @php
                                $statusText = strtolower($doc->status);
                                if (str_contains($statusText, 'revisi')) {
                                    $badgeClass = 'bg-yellow-100 text-yellow-700';
                                } elseif ($statusText === 'disetujui' || $statusText === 'terverifikasi') {
                                    $badgeClass = 'bg-green-100 text-green-600';
                                } elseif ($statusText === 'diproses') {
                                    $badgeClass = 'bg-red-100 text-red-600';
                                }
                            @endphp

                            <span class="px-2 py-1 text-xs font-medium rounded {{ $badgeClass }}">
                                {{ $doc->status }}
                            </span>

                            <a href="{{ route('laporan.download', $doc->id_laporan) }}" title="Download">
                                <i class="material-icons text-blue-600 cursor-pointer hover:text-blue-800">download</i>
                            </a>

                            @if($doc->status != 'Terverifikasi')
                                <form action="{{ route('laporan.hapus', $doc->id_laporan) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="openDeleteModal('hapusModal', '{{ route('laporan.hapus', $doc->id_laporan) }}')"
                                        title="Hapus">
                                        <i class="material-icons text-red-600 cursor-pointer hover:text-red-800">delete</i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach

            </ul>

            {{-- Pagination --}}
            @if ($laporans->total() > $laporans->perPage())
                <div class="mt-6 flex justify-center">
                    {{ $laporans->links('vendor.pagination.simple-tailwind') }}
                </div>
            @endif
        @else
            <p class="text-gray-500 text-center py-4">Belum ada dokumen yang diunggah manual.</p>
        @endif
    </div>
</div>