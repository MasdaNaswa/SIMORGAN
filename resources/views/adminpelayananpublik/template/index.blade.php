@extends('layouts.adminpelayananpublik')

@section('title', 'SIMORGAN')

@section('content')
<div class="flex flex-col min-h-screen bg-[#F8FAFC]">

    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-30">
        <div class="flex justify-between items-center py-4 px-6 md:px-8">
            <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                 <i class="material-icons text-blue-600">description</i> 
                <span class="hidden sm:inline">Kelola Template Laporan</span>
            </h1>
        </div>
    </header>

    <!-- Tombol Tambah -->
    <div class="px-4 md:px-6 py-4 flex justify-end">
        <button
            class="flex items-center gap-2 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition text-sm md:text-base"
            onclick="openModal('tambahTemplateModal')">
             Tambah 
        </button>
    </div>

    <!-- Tabel -->
    <main class="flex-1 px-4 md:px-8 py-6">
        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200 mt-4">
            <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg md:text-xl font-semibold text-gray-800">Daftar Template Laporan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase border-b">No</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase border-b">Nama Template</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase border-b">Kategori</th>
                            <th class="py-3 px-4 text-center text-xs font-semibold text-gray-700 uppercase border-b">File</th>
                            <th class="py-3 px-4 text-center text-xs font-semibold text-gray-700 uppercase border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($templates as $template)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="py-3 px-4 text-sm">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4 text-sm">{{ $template->nama_template }}</td>
                            <td class="py-3 px-4 text-sm">{{ $template->kategori->nama_kategori }}</td>
                            <td class="py-3 px-4 text-center text-sm">
                                <a href="{{ asset($template->file_path) }}" target="_blank" 
                                   class="text-blue-600 hover:underline">
                                     {{ basename($template->file_path) }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-center flex justify-center gap-2">
                                <!-- Edit -->
                                <button onclick="openModal('editTemplateModal{{ $template->id_template }}')" 
                                    class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-lg">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete -->
                                <button onclick="openModal('hapusTemplateModal{{ $template->id_template }}')" 
                                    class="p-2 text-red-600 hover:bg-red-100 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Belum ada template</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    @include('components.footer')
</div>

{{-- Modals --}}
@include('components.adminpelayananpublik.tambah-modal-template', ['kategories' => $kategories])
@foreach($templates as $template)
    @include('components.adminpelayananpublik.ubah-modal-template', ['template' => $template, 'kategories' => $kategories])
    @include('components.adminpelayananpublik.hapus-modal-template', ['template' => $template])
@endforeach

<script>
    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
