{{-- resources/views/opd/pelayanan-publik/partials/skm-list.blade.php --}}

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-lg font-medium mb-6 pb-3 border-b border-gray-200 flex items-center gap-2">
        <i class="fas fa-history text-primary"></i> Laporan SKM yang Telah Digenerate
    </h2>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Alert untuk laporan revisi -->
    @php
        $hasRevision = false;
        foreach ($skmLaporans as $laporan) {
            if ($laporan->status == 'Revisi') {
                $hasRevision = true;
                break;
            }
        }
    @endphp

    @if($hasRevision)
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-yellow-700 font-medium">Ada laporan yang memerlukan revisi!</p>
                    <p class="text-yellow-600 mt-1">Silakan periksa catatan dari admin pada laporan yang berstatus "Revisi".
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Daftar Laporan -->
    @if($skmLaporans->count() > 0)
        <div class="space-y-4">
            @foreach($skmLaporans as $laporan)
                <div id="laporan-{{ $laporan->id_laporan }}" class="border border-gray-200 rounded-lg p-4 hover:shadow transition-shadow
                                        {{ session('new_skm_id') == $laporan->id_laporan ? 'bg-green-50 border-green-300' : '' }}
                                        {{ $laporan->status == 'Revisi' ? 'bg-yellow-50 border-yellow-300' : '' }}
                                        {{ $laporan->status == 'Ditolak' ? 'bg-red-50 border-red-300' : '' }}">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 pt-1">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col md:flex-row md:items-center gap-2 mb-2">
                                        <h4 class="font-medium text-gray-800">{{ $laporan->judul }}</h4>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-0.5 rounded text-xs font-medium 
                                                            {{ $laporan->status == 'Disetujui' ? 'bg-green-100 text-green-800' :
                    ($laporan->status == 'Diproses' ? 'bg-yellow-100 text-yellow-800' :
                        ($laporan->status == 'Ditolak' ? 'bg-red-100 text-red-800' :
                            ($laporan->status == 'Revisi' ? 'bg-yellow-100 text-yellow-800 font-semibold' : 'bg-gray-100 text-gray-800'))) }}">
                                                {{ $laporan->status }}
                                            </span>

                                            @if($laporan->status == 'Revisi')
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs font-medium">
                                                    <i class="fas fa-exclamation-circle"></i> Perlu Revisi
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-2">
                                        <span class="flex items-center gap-1">
                                            <i class="far fa-calendar"></i>
                                            @if($laporan->tanggal_upload)
                                                {{ $laporan->tanggal_upload->format('d M Y H:i') }}
                                            @else
                                                Tanggal tidak tersedia
                                            @endif
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-clock"></i>
                                            Triwulan {{ $laporan->periode_triwulan ?? '-' }}
                                            {{ $laporan->periode_tahun ?? '-' }}
                                        </span>
                                        @if($laporan->updated_at && $laporan->created_at && $laporan->updated_at->notEqualTo($laporan->created_at))
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-history"></i>
                                                Diperbarui: {{ $laporan->updated_at->format('d M Y H:i') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Tampilkan Catatan dari Admin jika ada -->
                                    @if($laporan->catatan)
                                                    <div class="mt-3 p-3 
                                                                                    {{ $laporan->status == 'Revisi' ? 'bg-yellow-50 border border-yellow-200' :
                                        ($laporan->status == 'Ditolak' ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200') }} 
                                                                                    rounded-md">
                                                        <div class="flex items-start gap-2">
                                                            <div class="flex-shrink-0 pt-0.5">
                                                                <i class="fas fa-comment-alt 
                                                                                                {{ $laporan->status == 'Revisi' ? 'text-yellow-600' :
                                        ($laporan->status == 'Ditolak' ? 'text-red-600' : 'text-gray-600') }}"></i>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-2 mb-1">
                                                                    <h5 class="font-medium 
                                                                                                    {{ $laporan->status == 'Revisi' ? 'text-yellow-800' :
                                        ($laporan->status == 'Ditolak' ? 'text-red-800' : 'text-gray-800') }}">
                                                                        @if($laporan->status == 'Revisi')
                                                                            Catatan Revisi dari Admin:
                                                                        @elseif($laporan->status == 'Ditolak')
                                                                            Alasan Penolakan:
                                                                        @else
                                                                            Catatan dari Admin:
                                                                        @endif
                                                                    </h5>
                                                                    @if($laporan->updated_at)
                                                                                                <span
                                                                                                    class="text-xs 
                                                                                                                                                            {{ $laporan->status == 'Revisi' ? 'text-yellow-600 bg-yellow-100' :
                                                                        ($laporan->status == 'Ditolak' ? 'text-red-600 bg-red-100' : 'text-gray-600 bg-gray-100') }} 
                                                                                                                                                            px-2 py-0.5 rounded">
                                                                                                    {{ $laporan->updated_at->format('d M Y H:i') }}
                                                                                                </span>
                                                                    @endif
                                                                </div>
                                                                <p class="whitespace-pre-line 
                                                                                                {{ $laporan->status == 'Revisi' ? 'text-yellow-700' :
                                        ($laporan->status == 'Ditolak' ? 'text-red-700' : 'text-gray-700') }}">
                                                                    {{ $laporan->catatan }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                    @endif

                                    <!-- Tampilkan informasi jika sudah disetujui -->
                                    @if($laporan->status == 'Disetujui')
                                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-check-circle text-green-600"></i>
                                                <p class="text-green-700 font-medium">Laporan telah disetujui oleh admin</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <!-- Lihat -->
                            <a href="{{ route('laporan.view', $laporan->id_laporan) }}" target="_blank"
                                class="px-4 py-2 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Hapus -->
                            <button type="button" onclick="openHapusModal('{{ route('skm.destroy', $laporan->id_laporan) }}')"
                                class="px-3 py-2 bg-red-50 text-red-600 rounded hover:bg-red-100 flex items-center">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>

                    @if(session('new_skm_id') == $laporan->id_laporan)
                        <div class="mt-3 text-sm text-green-600 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <span>Laporan baru berhasil dibuat</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($skmLaporans->hasPages())
            <div class="mt-6">
                {{ $skmLaporans->links() }}
            </div>
        @endif
    @else
        <!-- Jika belum ada laporan -->
        <div class="text-center py-12">
            <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada laporan SKM</h3>
            <p class="text-gray-500 mb-6">Buat laporan SKM pertama Anda di tab "Form SKM".</p>
            <a href="javascript:void(0);" onclick="switchToSkmTab()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark">
                <i class="fas fa-plus"></i> Buat Laporan SKM
            </a>
        </div>
    @endif
</div>

<script>
    // Fungsi untuk pindah ke tab Form SKM
    function switchToSkmTab() {
        const skmTab = document.querySelector('.tab[data-tab="skm"]');
        if (skmTab) {
            skmTab.click();
        }
    }

    // Remove highlight after 5 seconds
    @if(session('new_skm_id'))
        setTimeout(() => {
            const newReport = document.getElementById('laporan-{{ session('new_skm_id') }}');
            if (newReport) {
                newReport.classList.remove('bg-green-50', 'border-green-300');
            }
        }, 5000);
    @endif

        function openHapusModal(actionUrl) {
            const modal = document.getElementById('hapusModal');
            const form = document.getElementById('hapusForm');

            form.action = actionUrl;
            modal.classList.remove('hidden');
        }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>