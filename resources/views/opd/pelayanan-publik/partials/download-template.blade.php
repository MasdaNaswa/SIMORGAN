<div class="bg-white rounded-lg shadow-sm p-6 mb-5">
    <h2 class="text-lg font-medium mb-4 pb-3 border-b border-gray-200 flex items-center gap-2">
        <i class="fas fa-file-download text-primary"></i> Template Dokumen
    </h2>
    <p class="mb-4">Silakan pilih kategori laporan:</p>

    <!-- Category Tabs -->
    <div class="category-tabs flex border-b border-gray-200 mb-6 overflow-x-auto">
        @foreach($kategories as $kategori)
            <div class="category-tab py-2 px-5 cursor-pointer border-b-2 border-transparent transition-all text-gray-600 whitespace-nowrap"
                data-category="kategori-{{ $kategori->id_kategori }}">
                {{ $kategori->nama_kategori }}
            </div>
        @endforeach
    </div>

    <!-- Category Content -->
    <div class="category-contents">
        @foreach($kategories as $kategori)
            <div id="kategori-{{ $kategori->id_kategori }}" class="category-content hidden">
                @php
                    $templateKategori = $templates->where('id_kategori', $kategori->id_kategori);
                @endphp

                <div class="template-list grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    @if ($templateKategori->count() > 0)
                        @foreach ($templateKategori as $template)
                            <div
                                class="template-item flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-primary hover:shadow-md">
                                <div class="template-icon mr-4 text-primary text-3xl">
                                    <i class="fas fa-file-word"></i>
                                </div>
                                <div class="template-info flex-1">
                                    <div class="template-name font-medium mb-1">
                                        {{ $template->nama_template }}
                                    </div>
                                    <div class="template-desc text-sm text-gray-500 mb-3">
                                        Template laporan kategori {{ $kategori->nama_kategori }}.
                                    </div>
                                    <a href="{{ Storage::url($template->file_path) }}"
                                        class="btn-primary bg-primary text-white py-2 px-4 rounded text-sm hover:bg-primary-dark"
                                        download>
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">Belum ada template yang dibuat untuk kategori ini.</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-lg font-medium mb-4 pb-3 border-b border-gray-200 flex items-center gap-2">
        <i class="fas fa-info-circle text-primary"></i> Petunjuk Pengisian
    </h2>
    <ol class="list-decimal pl-5 space-y-2">
        <li>Pilih kategori laporan yang sesuai</li>
        <li>Klik pada template untuk melihat preview</li>
        <li>Isi laporan sesuai periode menggunakan template</li>
        <li>Download template</li>
        <li>Simpan dokumen dengan format: <strong>NAMA_DOKUMEN_WAKTU_PELAKSANAN_NAMA_OPD</strong></li>
        <li>Unggah dokumen melalui menu Unggah Dokumen</li>
    </ol>
</div>