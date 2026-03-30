@extends('layouts.adminkelembagaan')

@section('title', 'SIMORGAN')

@section('content')
    <div class="flex flex-col min-h-screen bg-[#F8FAFC]">

        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-600"></i>
                    <span class="hidden sm:inline">Hasil Survei Kematangan Kelembagaan</span>
                </h1>

                <!-- Filter OPD -->
                <form method="GET" action="{{ route('adminkelembagaan.kematangan-kelembagaan.index') }}" class="relative">
                    <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select name="opd" onchange="this.form.submit()"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua OPD</option>
                        @foreach($listOPD as $opd)
                            <option value="{{ $opd }}" {{ request('opd') == $opd ? 'selected' : '' }}>
                                {{ $opd }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </header>

        <!-- Statistik -->
        <div class="px-4 md:px-8 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Kemenpan -->
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Survei KemenPAN</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['total_kemenpan'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Kemendagri -->
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Survei Kemendagri</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['total_kemendagri'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-chart-bar text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Kemenpan -->
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Rata-rata Skor KemenPAN</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['avg_kemenpan'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-star text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Kemendagri -->
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Rata-rata Skor Kemendagri</p>
                            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['avg_kemendagri'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-star-half-alt text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <main class="flex-1 px-4 md:px-8 py-6">

            <!-- Tab Kemenpan / Kemendagri -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button id="tabKemenpan"
                            class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Hasil Survei KemenPAN
                        </button>
                        <button id="tabKemendagri"
                            class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Hasil Survei Kemendagri
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Konten Kemenpan -->
            <div id="contentKemenpan" class="tab-content">
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">No</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Nama OPD</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Tanggal Pengisian</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Skor Struktur</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Skor Proses</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Total Skor</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Tingkat Kematangan</th>
                                    <th class="py-3 px-4 text-center font-semibold border-b border-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($evaluasiKemenpan as $index => $evaluasi)
                                    @php $interpretasi = $evaluasi->getInterpretasi(); @endphp
                                    <tr class="hover:bg-blue-50 transition-colors">
                                        <td class="py-3 px-4">
                                            {{ $evaluasiKemenpan->firstItem() + $index }}
                                         </td>
                                        <td class="py-3 px-4 font-medium text-gray-900">{{ $evaluasi->nama_opd }}</td>
                                        <td class="py-3 px-4">{{ date('d M Y', strtotime($evaluasi->created_at)) }}</td>
                                        <td class="py-3 px-4">{{ $evaluasi->skor_struktur }}</td>
                                        <td class="py-3 px-4">{{ $evaluasi->skor_proses }}</td>
                                        <td class="py-3 px-4 font-bold">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $interpretasi['warna'] }}">
                                                {{ $evaluasi->total_skor }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $interpretasi['warna'] }}">
                                                {{ $interpretasi['level'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex justify-center gap-3">
                                                <button
                                                    x-data
                                                    @click="$dispatch('open-modal-kemenpan', {{ $evaluasi->id }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </button>
                                                <button type="button"
                                                    class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition"
                                                    onclick="openHapusModal({{ $evaluasi->id }}, 'kemenpan', '{{ $evaluasi->nama_opd }}')"
                                                    title="Hapus Data">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                     </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center p-6 text-gray-500">
                                            <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                                            <p>Belum ada hasil survei KemenPAN</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Info Footer dan Pagination Kemenpan -->
                    @if($evaluasiKemenpan->count() > 0)
                        <div class="px-4 md:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600">
                            <div>
                                Menampilkan
                                <span class="font-semibold">{{ $evaluasiKemenpan->firstItem() }}</span>
                                -
                                <span class="font-semibold">{{ $evaluasiKemenpan->lastItem() }}</span>
                                dari
                                <span class="font-semibold">{{ $evaluasiKemenpan->total() }}</span>
                                entri
                            </div>

                            <div class="flex gap-2 mt-2 sm:mt-0">
                                @if($evaluasiKemenpan->onFirstPage())
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @else
                                    <a href="{{ $evaluasiKemenpan->previousPageUrl() . '&' . http_build_query(request()->except('kemenpan_page')) }}"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                        Sebelumnya
                                    </a>
                                @endif

                                @foreach($evaluasiKemenpan->getUrlRange(max(1, $evaluasiKemenpan->currentPage() - 2), min($evaluasiKemenpan->lastPage(), $evaluasiKemenpan->currentPage() + 2)) as $page => $url)
                                    @if($page == $evaluasiKemenpan->currentPage())
                                        <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url . '&' . http_build_query(request()->except('kemenpan_page')) }}"
                                            class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if($evaluasiKemenpan->hasMorePages())
                                    <a href="{{ $evaluasiKemenpan->nextPageUrl() . '&' . http_build_query(request()->except('kemenpan_page')) }}"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                        Selanjutnya
                                    </a>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Konten Kemendagri -->
            <div id="contentKemendagri" class="tab-content hidden">
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">No</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Nama OPD</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Tanggal Pengisian</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Total Skor</th>
                                    <th class="py-3 px-4 font-semibold border-b border-gray-200">Tingkat Maturitas</th>
                                    <th class="py-3 px-4 text-center font-semibold border-b border-gray-200">Aksi</th>
                                 </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($evaluasiKemendagri as $index => $evaluasi)
                                    <tr class="hover:bg-green-50 transition-colors">
                                        <td class="py-3 px-4">
                                            {{ $evaluasiKemendagri->firstItem() + $index }}
                                        </td>
                                        <td class="py-3 px-4 font-medium text-gray-900">{{ $evaluasi->nama_opd }}</td>
                                        <td class="py-3 px-4">{{ date('d M Y', strtotime($evaluasi->created_at)) }}</td>
                                        <td class="py-3 px-4 font-bold text-green-600">{{ $evaluasi->total_skor }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 rounded-full text-xs 
                                                @if($evaluasi->tingkat_maturitas == 'SANGAT TINGGI') bg-green-100 text-green-800
                                                @elseif($evaluasi->tingkat_maturitas == 'TINGGI') bg-blue-100 text-blue-800
                                                @elseif($evaluasi->tingkat_maturitas == 'SEDANG') bg-yellow-100 text-yellow-800
                                                @elseif($evaluasi->tingkat_maturitas == 'RENDAH') bg-orange-100 text-orange-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $evaluasi->tingkat_maturitas }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex justify-center gap-3">
                                                <button
                                                    onclick="openDetailModalKemendagri({{ $evaluasi->id }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </button>
                                                <button type="button"
                                                    class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition"
                                                    onclick="openHapusModal({{ $evaluasi->id }}, 'kemendagri', '{{ $evaluasi->nama_opd }}')"
                                                    title="Hapus Data">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-6 text-gray-500">
                                            <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                                            <p>Belum ada hasil survei Kemendagri</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Info Footer dan Pagination Kemendagri -->
                    @if($evaluasiKemendagri->count() > 0)
                        <div class="px-4 md:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600">
                            <div>
                                Menampilkan
                                <span class="font-semibold">{{ $evaluasiKemendagri->firstItem() }}</span>
                                -
                                <span class="font-semibold">{{ $evaluasiKemendagri->lastItem() }}</span>
                                dari
                                <span class="font-semibold">{{ $evaluasiKemendagri->total() }}</span>
                                entri
                            </div>

                            <div class="flex gap-2 mt-2 sm:mt-0">
                                @if($evaluasiKemendagri->onFirstPage())
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @else
                                    <a href="{{ $evaluasiKemendagri->previousPageUrl() . '&' . http_build_query(request()->except('kemendagri_page')) }}"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                        Sebelumnya
                                    </a>
                                @endif

                                @foreach($evaluasiKemendagri->getUrlRange(max(1, $evaluasiKemendagri->currentPage() - 2), min($evaluasiKemendagri->lastPage(), $evaluasiKemendagri->currentPage() + 2)) as $page => $url)
                                    @if($page == $evaluasiKemendagri->currentPage())
                                        <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url . '&' . http_build_query(request()->except('kemendagri_page')) }}"
                                            class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if($evaluasiKemendagri->hasMorePages())
                                    <a href="{{ $evaluasiKemendagri->nextPageUrl() . '&' . http_build_query(request()->except('kemendagri_page')) }}"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                        Selanjutnya
                                    </a>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md text-sm cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <!-- Modal Hapus -->
        @include('components.adminkelembagaan.hapus-modal-survei')

        <!-- Modal Detail Kemenpan -->
        @include('components.adminkelembagaan.detail-modal-survei-kemenpan', [
            'evaluasi' => null,
            'nama_opd' => null,
            'jawaban' => null,
            'interpretasi' => null,
            'detailPerhitungan' => null
        ])

        <!-- Modal Detail Kemendagri -->
        @include('components.adminkelembagaan.detail-modal-survei-kemendagri', [
            'evaluasi' => null,
            'jawaban' => [],
            'detailPerhitungan' => [],
            'totalMentah' => 0,
            'skorNormalized' => 0
        ])

        {{-- Footer --}}
        @include('components.footer')

    </div>
@endsection

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    // Tab functionality
    document.getElementById('tabKemenpan').addEventListener('click', function () {
        setActiveTab('kemenpan');
    });
    document.getElementById('tabKemendagri').addEventListener('click', function () {
        setActiveTab('kemendagri');
    });
    
    function setActiveTab(tabName) {
        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => {
            tab.classList.remove('border-blue-500', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        const activeTab = document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-blue-500', 'text-blue-600');

        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.add('hidden'));
        document.getElementById('content' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).classList.remove('hidden');
    }
    
    // Fungsi untuk mendapatkan status maturitas berdasarkan level
    function getMaturityStatus(level) {
        const statusMap = {
            'P-1': 'Sangat Tidak Efektif',
            'P-2': 'Tidak Efektif', 
            'P-3': 'Cukup Efektif',
            'P-4': 'Efektif',
            'P-5': 'Sangat Efektif'
        };
        return statusMap[level] || 'Belum Dinilai';
    }
    
    // Fungsi untuk mendapatkan warna berdasarkan level
    function getMaturityColor(level) {
        const colorMap = {
            'P-1': { bg: 'from-red-100 to-red-50', text: 'text-red-700', border: 'border-red-200' },
            'P-2': { bg: 'from-orange-100 to-amber-50', text: 'text-orange-700', border: 'border-orange-200' },
            'P-3': { bg: 'from-yellow-100 to-yellow-50', text: 'text-yellow-700', border: 'border-yellow-200' },
            'P-4': { bg: 'from-blue-100 to-cyan-50', text: 'text-blue-700', border: 'border-blue-200' },
            'P-5': { bg: 'from-green-100 to-emerald-50', text: 'text-green-700', border: 'border-green-200' }
        };
        return colorMap[level] || { bg: 'from-gray-100 to-gray-50', text: 'text-gray-700', border: 'border-gray-200' };
    }
    
    // Fungsi untuk membuka modal Kemendagri
    window.openDetailModalKemendagri = async function(id) {
        console.log('Opening Kemendagri modal for id:', id);
        
        const modal = document.getElementById('detailModalKemendagri');
        if (!modal) return;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Tampilkan loading state
        showLoadingState();
        
        try {
            const response = await fetch(`/adminkelembagaan/kematangan-kelembagaan/kemendagri/${id}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            if (data.error) throw new Error(data.error);
            populateKemendagriModal(data);
        } catch (error) {
            console.error('Error:', error);
            showErrorState(error.message);
        }
    }

    // Fungsi untuk menampilkan loading state
    function showLoadingState() {
        const jawabanContainer = document.getElementById('jawabanKemendagri');
        if (jawabanContainer) {
            jawabanContainer.innerHTML = `
                <tr>
                    <td colspan="3" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mb-4"></div>
                            <p class="text-sm text-gray-600">Memuat data survei...</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    // Fungsi untuk menampilkan error state
    function showErrorState(errorMessage) {
        const jawabanContainer = document.getElementById('jawabanKemendagri');
        if (jawabanContainer) {
            jawabanContainer.innerHTML = `
                <tr>
                    <td colspan="3" class="py-10 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-red-600">Gagal memuat data</p>
                            <p class="text-xs text-gray-500 mt-1">${errorMessage}</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    // Fungsi untuk mengisi modal dengan data
    function populateKemendagriModal(data) {
        // Update header
        const opdEl = document.querySelector('#modalOpdKemendagri span');
        if (opdEl) opdEl.textContent = data.evaluasi?.nama_opd || '-';
        
        const tglEl = document.querySelector('#modalTanggalKemendagri span');
        if (tglEl) tglEl.textContent = data.evaluasi?.created_at || '-';
        
        const totalSkorElement = document.getElementById('modalTotalSkorKemendagri');
        if (totalSkorElement) totalSkorElement.textContent = parseFloat(data.evaluasi?.total_skor || 0).toFixed(2);
        
        // Update tingkat maturitas
        const tingkatElement = document.getElementById('modalTingkatMaturitasKemendagri');
        const badgeElement = document.getElementById('badgeMaturitasKemendagri');
        let maturityLevel = data.evaluasi?.tingkat_maturitas || 'P-1';
        
        const maturityStatus = getMaturityStatus(maturityLevel);
        const maturityColor = getMaturityColor(maturityLevel);
        
        if (tingkatElement) {
            tingkatElement.textContent = maturityLevel;
            tingkatElement.className = `text-3xl font-bold ${maturityColor.text} leading-tight`;
        }
        
        if (badgeElement) {
            badgeElement.textContent = maturityStatus;
            badgeElement.className = `inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r ${maturityColor.bg} ${maturityColor.text} border ${maturityColor.border}`;
        }
        
        // Update progress bar
        const totalSkor = parseFloat(data.evaluasi?.total_skor || 0);
        const minScore = 11;
        const maxScore = 55;
        let percentage = totalSkor >= minScore ? ((totalSkor - minScore) / (maxScore - minScore)) * 100 : 0;
        percentage = Math.min(100, percentage);
        
        const scorePercentage = document.getElementById('scorePercentage');
        if (scorePercentage) scorePercentage.textContent = `${percentage.toFixed(1)}%`;
        
        const scoreProgressBar = document.getElementById('scoreProgressBar');
        if (scoreProgressBar) {
            scoreProgressBar.style.width = `${percentage}%`;
            if (percentage >= 80) scoreProgressBar.className = 'bg-gradient-to-r from-emerald-500 to-green-600 h-2.5 rounded-full';
            else if (percentage >= 60) scoreProgressBar.className = 'bg-gradient-to-r from-blue-500 to-cyan-600 h-2.5 rounded-full';
            else if (percentage >= 40) scoreProgressBar.className = 'bg-gradient-to-r from-yellow-500 to-amber-600 h-2.5 rounded-full';
            else if (percentage >= 20) scoreProgressBar.className = 'bg-gradient-to-r from-orange-500 to-red-500 h-2.5 rounded-full';
            else scoreProgressBar.className = 'bg-gradient-to-r from-red-600 to-pink-600 h-2.5 rounded-full';
        }
        
        // Update jawaban survei
        const jawabanContainer = document.getElementById('jawabanKemendagri');
        if (jawabanContainer && data.jawaban) {
            jawabanContainer.innerHTML = '';
            let rowIndex = 0;
            Object.entries(data.jawaban).forEach(([variabel, jawabanData]) => {
                const row = document.createElement('tr');
                row.className = `hover:bg-gray-50 transition-colors duration-150 ${rowIndex % 2 === 0 ? 'bg-gray-50/30' : ''}`;
                
                let filesHtml = '<div class="flex flex-col gap-1">';
                if (jawabanData.files && jawabanData.files.length > 0) {
                    jawabanData.files.forEach((file, fileIndex) => {
                        const fileName = file.name || `File ${fileIndex + 1}`;
                        const fileUrl = file.url || '#';
                        filesHtml += `
                            <a href="${fileUrl}" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition text-sm text-gray-700 no-underline group">
                                <i class="fas ${fileName.toLowerCase().endsWith('.pdf') ? 'fa-file-pdf text-red-500' : 'fa-file text-blue-500'}"></i>
                                <span class="truncate max-w-[180px] group-hover:text-blue-600">${fileName}</span>
                            </a>
                        `;
                    });
                } else {
                    filesHtml += `<span class="text-gray-400 text-sm italic">Tidak ada file</span>`;
                }
                filesHtml += '</div>';
                
                let tingkatJawaban = jawabanData.tingkat || 'Belum dinilai';
                const tingkatMap = { 'Tingkat I': 'P-1', 'Tingkat II': 'P-2', 'Tingkat III': 'P-3', 'Tingkat IV': 'P-4', 'Tingkat V': 'P-5' };
                tingkatJawaban = tingkatMap[tingkatJawaban] || tingkatJawaban;
                
                row.innerHTML = `
                    <td class="py-4 px-4 font-medium text-gray-900 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-semibold">${rowIndex + 1}</span>
                            </div>
                            <div>
                                <span class="font-semibold">${variabel}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-center border-b border-gray-200">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            ${tingkatJawaban === 'P-5' ? 'bg-emerald-100 text-emerald-800' :
                              tingkatJawaban === 'P-4' ? 'bg-blue-100 text-blue-800' :
                              tingkatJawaban === 'P-3' ? 'bg-yellow-100 text-yellow-800' :
                              tingkatJawaban === 'P-2' ? 'bg-orange-100 text-orange-800' :
                              'bg-red-100 text-red-800'}">
                            ${tingkatJawaban}
                        </span>
                    </td>
                    <td class="py-4 px-4 border-b border-gray-200">${filesHtml}</td>
                `;
                jawabanContainer.appendChild(row);
                rowIndex++;
            });
        }
    }

    // Fungsi menutup modal
    window.closeDetailModalKemendagri = function() {
        const modal = document.getElementById('detailModalKemendagri');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Modal hapus
    window.openHapusModal = function(id, type, opdName) {
        const modal = document.getElementById('hapusModalSurvei');
        const form = document.getElementById('hapusFormSurvei');
        const opdSpan = document.getElementById('opdNameSurvei');
        if (form) form.action = `/adminkelembagaan/kematangan-kelembagaan/delete/${id}?type=${type}`;
        if (opdSpan) opdSpan.textContent = opdName;
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };
    
    window.closeHapusModal = function() {
        const modal = document.getElementById('hapusModalSurvei');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };
    
    // Event listener untuk modal
    document.addEventListener('DOMContentLoaded', function () {
        const hapusModal = document.getElementById('hapusModalSurvei');
        if (hapusModal) {
            hapusModal.addEventListener('click', function (e) {
                if (e.target === hapusModal) closeHapusModal();
            });
        }
        
        // Tutup modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDetailModalKemendagri();
        });
        
        // Klik di luar modal
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('detailModalKemendagri');
            if (e.target === modal) closeDetailModalKemendagri();
        });
    });
</script>
@endpush