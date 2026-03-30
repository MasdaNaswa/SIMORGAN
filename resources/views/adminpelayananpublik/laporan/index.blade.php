@extends('layouts.adminpelayananpublik')

@section('title', 'SIMORGAN - Manajemen Laporan OPD')

@section('content')
    <div class="flex flex-col min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="material-icons text-blue-600">assignment</i>
                    <span class="hidden sm:inline">Manajemen Laporan OPD</span>
                </h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 px-4 md:px-8 py-6">
            <!-- Flash Message -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                <!-- TABS KATEGORI LAPORAN -->
                <div class="border-b border-gray-100 bg-gray-50">
                    <div class="px-4 overflow-x-auto">
                        <nav class="flex flex-nowrap min-w-full gap-1 py-2">
                            @php
                                $categories = [
                                    'Semua' => ['label' => 'Semua Laporan', 'color' => 'gray'],
                                    'Data Lain Lainnya' => ['label' => 'Data Lainnya', 'color' => 'gray'],
                                    'Inovasi OPD' => ['label' => 'Inovasi OPD', 'color' => 'orange'],
                                    'Kode Etik' => ['label' => 'Kode Etik', 'color' => 'red'],
                                    'Laporan SKM' => ['label' => 'Laporan SKM', 'color' => 'purple'],
                                    'Laporan FKP' => ['label' => 'Laporan FKP', 'color' => 'blue'],
                                    'Probis' => ['label' => 'Probis', 'color' => 'yellow'],
                                    'SOP' => ['label' => 'SOP', 'color' => 'green'],
                                    'SK Tim Kerja' => ['label' => 'SK Tim Kerja', 'color' => 'indigo'],
                                    'Tindak Lanjut FKP' => ['label' => 'Tindak Lanjut FKP', 'color' => 'teal'],
                                ];
                            @endphp
                            
                            @foreach($categories as $key => $cat)
                                @php
                                    // Hitung jumlah per kategori
                                    $count = \App\Models\Laporan::whereNotIn('kategori', ['Petajab', 'Anjab & ABK', 'Evajab']);
                                    if ($key !== 'semua') {
                                        if ($key === 'Data Lain Lainnya') {
                                            $mainCategories = ['Laporan SKM', 'Laporan FKP', 'SOP', 'Probis', 'SK Tim Kerja', 'Kode Etik', 'Inovasi OPD', 'Tindak Lanjut FKP'];
                                            $count = $count->whereNotIn('kategori', $mainCategories);
                                        } else {
                                            $count = $count->where('kategori', $key);
                                        }
                                    }
                                    $countValue = $count->count();
                                    
                                    $colorClasses = [
                                        'purple' => ['active' => 'border-purple-600 text-purple-700 bg-purple-50', 'inactive' => 'border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300'],
                                        'blue' => ['active' => 'border-blue-600 text-blue-700 bg-blue-50', 'inactive' => 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-300'],
                                        'green' => ['active' => 'border-green-600 text-green-700 bg-green-50', 'inactive' => 'border-transparent text-gray-500 hover:text-green-600 hover:border-green-300'],
                                        'yellow' => ['active' => 'border-yellow-600 text-yellow-700 bg-yellow-50', 'inactive' => 'border-transparent text-gray-500 hover:text-yellow-600 hover:border-yellow-300'],
                                        'indigo' => ['active' => 'border-indigo-600 text-indigo-700 bg-indigo-50', 'inactive' => 'border-transparent text-gray-500 hover:text-indigo-600 hover:border-indigo-300'],
                                        'red' => ['active' => 'border-red-600 text-red-700 bg-red-50', 'inactive' => 'border-transparent text-gray-500 hover:text-red-600 hover:border-red-300'],
                                        'orange' => ['active' => 'border-orange-600 text-orange-700 bg-orange-50', 'inactive' => 'border-transparent text-gray-500 hover:text-orange-600 hover:border-orange-300'],
                                        'teal' => ['active' => 'border-teal-600 text-teal-700 bg-teal-50', 'inactive' => 'border-transparent text-gray-500 hover:text-teal-600 hover:border-teal-300'],
                                        'gray' => ['active' => 'border-gray-600 text-gray-700 bg-gray-50', 'inactive' => 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300']
                                    ];
                                    $classes = $selectedKategori == $key ? $colorClasses[$cat['color']]['active'] : $colorClasses[$cat['color']]['inactive'];
                                @endphp
                                <a href="{{ route('adminpelayananpublik.laporan.index', ['kategori' => $key]) }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium whitespace-nowrap rounded-lg transition-all duration-200 border {{ $classes }}">
                                    <span>{{ $cat['label'] }}</span>
                                    @if($countValue > 0)
                                        <span class="ml-1 px-2 py-0.5 text-xs font-medium rounded-full 
                                            {{ $selectedKategori == $key ? 'bg-white/50 text-gray-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $countValue }}
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    @if($laporans->count() > 0)
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="py-4 px-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">No</th>
                                    <th class="py-4 px-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Nama OPD</th>
                                    <th class="py-4 px-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Nama Dokumen</th>
                                    <th class="py-4 px-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Kategori</th>
                                    <th class="py-4 px-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Tanggal Upload</th>
                                    <th class="py-4 px-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Status</th>
                                    <th class="py-4 px-4 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Catatan Admin</th>
                                    <th class="py-4 px-4 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider">Aksi</th>
                                 </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($laporans as $index => $laporan)
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                                        <td class="py-3.5 px-4 font-medium text-gray-500">
                                            {{ ($laporans->currentPage() - 1) * $laporans->perPage() + $index + 1 }}
                                         </td>
                                        <td class="py-3.5 px-4">
                                            <span class="font-medium text-gray-700">{{ $laporan->user->nama_opd ?? $laporan->user->name ?? '-' }}</span>
                                         </td>
                                        <td class="py-3.5 px-4">
                                            <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                                <i class="fas fa-file-pdf text-red-500 text-sm"></i>
                                                <span class="truncate max-w-[200px]">{{ $laporan->judul }}</span>
                                            </a>
                                         </td>
                                        <td class="py-3.5 px-4">
                                            @php
                                                $badgeColors = [
                                                    'SKM' => 'bg-purple-100 text-purple-700',
                                                    'Laporan SKM' => 'bg-purple-100 text-purple-700',
                                                    'Laporan FKP' => 'bg-blue-100 text-blue-700',
                                                    'FKP' => 'bg-blue-100 text-blue-700',
                                                    'SOP' => 'bg-green-100 text-green-700',
                                                    'Probis' => 'bg-yellow-100 text-yellow-700',
                                                    'SK Tim Kerja' => 'bg-indigo-100 text-indigo-700',
                                                    'Kode Etik' => 'bg-red-100 text-red-700',
                                                    'Inovasi OPD' => 'bg-orange-100 text-orange-700',
                                                    'Tindak Lanjut FKP' => 'bg-teal-100 text-teal-700',
                                                ];
                                                $badgeClass = $badgeColors[$laporan->kategori] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                {{ $laporan->kategori }}
                                            </span>
                                         </td>
                                        <td class="py-3.5 px-4 text-gray-500">
                                            <span>{{ \Carbon\Carbon::parse($laporan->tanggal_upload)->translatedFormat('d M Y') }}</span>
                                         </td>
                                        <td class="py-3.5 px-4">
                                            @php
                                                $statusClasses = [
                                                    'Disetujui' => 'bg-green-100 text-green-700',
                                                    'Revisi' => 'bg-yellow-100 text-yellow-700',
                                                    'Diproses' => 'bg-blue-100 text-blue-700',
                                                    'Ditolak' => 'bg-red-100 text-red-700',
                                                ];
                                                $statusClass = $statusClasses[$laporan->status] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ $laporan->status ?? 'Diproses' }}
                                            </span>
                                         </td>
                                        <td class="py-3.5 px-4 max-w-xs">
                                            @if($laporan->catatan)
                                                <div class="relative group">
                                                    <div class="flex items-center gap-1 text-gray-500 text-xs truncate">
                                                        <span class="truncate">{{ $laporan->catatan }}</span>
                                                    </div>
                                                    <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block z-10">
                                                        <div class="bg-gray-800 text-white text-xs rounded-lg py-2 px-3 max-w-xs shadow-lg">
                                                            {{ $laporan->catatan }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                         </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <div class="flex justify-center items-center gap-2">
                                                <!-- Download -->
                                                <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                                    class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-all duration-200 hover:scale-105"
                                                    title="Download">
                                                    <i class="fas fa-download text-sm"></i>
                                                </a>

                                                <!-- Update Status / Catatan -->
                                                <button onclick="openStatusModal({{ $laporan->id_laporan }}, '{{ $laporan->status ?? '' }}', '{{ addslashes($laporan->catatan ?? '') }}')"
                                                    class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center hover:bg-green-100 transition-all duration-200 hover:scale-105"
                                                    title="Update Status & Catatan">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </button>

                                                <!-- Hapus -->
                                                <button onclick="openModal('hapusLaporanModal{{ $laporan->id_laporan }}')"
                                                    class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition-all duration-200 hover:scale-105"
                                                    title="Hapus">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </div>

                                            <!-- Include Modals -->
                                            @include('components.adminpelayananpublik.ubah-modal-laporan', ['laporan' => $laporan])
                                            @include('components.adminpelayananpublik.hapus-modal-laporan', ['laporan' => $laporan])
                                         </td>
                                     </tr>
                                @endforeach
                            </tbody>
                         </table>
                        
                        <!-- Pagination -->
                        @if ($laporans->total() > $laporans->perPage())
                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                                {{ $laporans->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16 px-4">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-folder-open text-gray-400 text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-700 mb-1">Belum ada dokumen</h3>
                            <p class="text-gray-500 text-sm">Belum ada dokumen dari OPD untuk kategori ini</p>
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
        
        function openStatusModal(id, status = '', catatan = '') {
            const modal = document.getElementById(`editModal${id}`);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                const statusSelect = modal.querySelector('select[name="status"]');
                const catatanTextarea = modal.querySelector('textarea[name="catatan"]');

                if (statusSelect) statusSelect.value = status;
                if (catatanTextarea) catatanTextarea.value = catatan;
            }
        }
    </script>
@endsection