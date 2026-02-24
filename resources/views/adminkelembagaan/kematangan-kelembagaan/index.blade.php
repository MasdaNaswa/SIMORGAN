@extends('layouts.adminkelembagaan')

@section('title', 'Hasil Survei Kematangan Kelembagaan')
@section('page-title', 'Hasil Survei Kematangan Kelembagaan')
@section('menu-kematangan', 'text-blue-600 font-bold')

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
                <div class="relative">
                    <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select name="opd" onchange="this.form.submit()"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                        <option value="">Semua OPD</option>
                        @foreach($listOPD as $opd)
                            <option value="{{ $opd }}" {{ request('opd') == $opd ? 'selected' : '' }}>
                                {{ $opd }}
                            </option>
                        @endforeach
                    </select>
                </div>


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
                            <p class="text-2xl font-bold text-yellow-600">{{ $stats['avg_kemenpan'] }}</p>
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
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['avg_kemendagri'] }}</p>
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
                                            {{ $index + 1 + (($evaluasiKemenpan->currentPage() - 1) * $evaluasiKemenpan->perPage()) }}
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
                                        <td class="py-3 px-4 text-center flex justify-center gap-3">
                                           <button
    x-data
    @click="$dispatch('open-modal-kemenpan', {{ $evaluasi->id}})"
    class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition"
    title="Lihat Detail">
    <i class="fas fa-eye text-sm"></i>
</button>


                                            <button type="button"
                                                class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition"
                                                onclick="openHapusModal({{ $evaluasi->id}}, 'kemenpan', '{{ $evaluasi->nama_opd }}')"
                                                title="Hapus Data">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
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
                    @if($evaluasiKemenpan->hasPages())
                        <div class="px-4 py-3 border-t border-gray-200">
                            {{ $evaluasiKemenpan->appends(request()->except('kemenpan_page'))->links() }}
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
                                            {{ $index + 1 + (($evaluasiKemendagri->currentPage() - 1) * $evaluasiKemendagri->perPage()) }}
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
                                        <td class="py-3 px-4 text-center flex justify-center gap-3">
                                           <!-- Di index.blade.php, ganti tombol: -->
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
                    @if($evaluasiKemendagri->hasPages())
                        <div class="px-4 py-3 border-t border-gray-200">
                            {{ $evaluasiKemendagri->appends(request()->except('kemendagri_page'))->links() }}
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
{{-- Di bagian sebelum @endsection --}}
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
    
    // FUNGSI UNTUK MENDAPATKAN STATUS MATURITAS BERDASARKAN LEVEL P-1 s/d P-5
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
    
    // FUNGSI UNTUK MENDAPATKAN WARNA BERDASARKAN LEVEL
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
    
    // FUNGSI UNTUK MEMBUKA MODAL KEMENDAGRI
    window.openDetailModalKemendagri = async function(id) {
        console.log('Opening Kemendagri modal for id:', id);
        
        const modal = document.getElementById('detailModalKemendagri');
        
        if (!modal) {
            console.error('Modal element not found!');
            alert('Modal tidak ditemukan');
            return;
        }
        
        // Tampilkan modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Tampilkan loading state di dalam modal
        showLoadingState();
        
        try {
            // Load data via AJAX
            const response = await fetch(`/adminkelembagaan/kematangan-kelembagaan/kemendagri/${id}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            console.log('Data loaded successfully:', data);
            
            // Populate modal dengan data
            populateKemendagriModal(data);
            
        } catch (error) {
            console.error('Error loading Kemendagri data:', error);
            showErrorState(error.message);
        }
    }

    // FUNGSI UNTUK MENAMPILKAN LOADING STATE
    function showLoadingState() {
        // Jawaban section
        const jawabanContainer = document.getElementById('jawabanKemendagri');
        if (jawabanContainer) {
            jawabanContainer.innerHTML = `
                <tr>
                    <td colspan="3" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mb-4"></div>
                            <p class="text-sm text-gray-600 font-medium">Memuat data survei...</p>
                            <p class="text-xs text-gray-400 mt-1">Harap tunggu sebentar</p>
                        </div>
                    </td>
                </tr>
            `;
        }
        
        // Perhitungan section (jika ada elemen dengan id ini)
        const perhitunganContainer = document.getElementById('perhitunganKemendagri');
        if (perhitunganContainer) {
            perhitunganContainer.innerHTML = `
                <tr>
                    <td colspan="3" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-purple-600 mb-4"></div>
                            <p class="text-sm text-gray-600 font-medium">Memuat detail perhitungan...</p>
                            <p class="text-xs text-gray-400 mt-1">Harap tunggu sebentar</p>
                        </div>
                    </td>
                </tr>
            `;
        }
        
        // Reset header data
        const totalSkorElement = document.getElementById('modalTotalSkorKemendagri');
        const tingkatElement = document.getElementById('modalTingkatMaturitasKemendagri');
        const badgeElement = document.getElementById('badgeMaturitasKemendagri');
        const scorePercentage = document.getElementById('scorePercentage');
        const scoreProgressBar = document.getElementById('scoreProgressBar');
        
        if (totalSkorElement) totalSkorElement.textContent = '0';
        
        if (tingkatElement) {
            tingkatElement.textContent = 'P-0';
            tingkatElement.className = 'text-3xl font-bold text-gray-500 leading-tight';
        }
        
        if (badgeElement) {
            badgeElement.textContent = 'Memuat...';
            badgeElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600';
        }
        
        if (scorePercentage) scorePercentage.textContent = '0%';
        if (scoreProgressBar) {
            scoreProgressBar.style.width = '0%';
            scoreProgressBar.className = 'bg-gradient-to-r from-gray-300 to-gray-400 h-2.5 rounded-full';
        }
        
        const opdSpan = document.querySelector('#modalOpdKemendagri span');
        if (opdSpan) opdSpan.textContent = 'Memuat...';
        
        const tglSpan = document.querySelector('#modalTanggalKemendagri span');
        if (tglSpan) tglSpan.textContent = 'Memuat...';
    }

    // FUNGSI UNTUK MENAMPILKAN ERROR STATE
    function showErrorState(errorMessage) {
        // Jawaban section
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
                            <p class="text-xs text-gray-500 mt-1">${errorMessage || 'Terjadi kesalahan'}</p>
                            <button onclick="window.location.reload()" class="mt-3 px-4 py-2 bg-blue-100 text-blue-600 rounded-lg text-sm hover:bg-blue-200 transition">
                                <i class="fas fa-redo mr-1"></i> Coba Lagi
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }
        
        // Perhitungan section
        const perhitunganContainer = document.getElementById('perhitunganKemendagri');
        if (perhitunganContainer) {
            perhitunganContainer.innerHTML = `
                <tr>
                    <td colspan="3" class="py-10 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-red-600">Gagal memuat perhitungan</p>
                            <p class="text-xs text-gray-500 mt-1">Data tidak tersedia</p>
                        </div>
                    </td>
                </tr>
            `;
        }
        
        // Update header untuk error state
        const tingkatElement = document.getElementById('modalTingkatMaturitasKemendagri');
        const badgeElement = document.getElementById('badgeMaturitasKemendagri');
        
        if (tingkatElement) {
            tingkatElement.textContent = 'P-?';
            tingkatElement.className = 'text-3xl font-bold text-gray-400 leading-tight';
        }
        
        if (badgeElement) {
            badgeElement.textContent = 'Error Loading';
            badgeElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600';
        }
    }

    // FUNGSI UTAMA UNTUK MENGISI MODAL DENGAN DATA
    function populateKemendagriModal(data) {
        try {
            console.log('Populating modal with data:', data);
            
            // 1. ISI DATA HEADER
            // OPD
            const opdEl = document.getElementById('modalOpdKemendagri');
            if (opdEl) {
                const spanEl = opdEl.querySelector('span');
                if (spanEl) {
                    spanEl.textContent = data.evaluasi?.nama_opd || '-';
                    spanEl.title = data.evaluasi?.nama_opd || '';
                }
            }
            
            // Tanggal
            const tglEl = document.getElementById('modalTanggalKemendagri');
            if (tglEl) {
                const spanEl = tglEl.querySelector('span');
                if (spanEl) {
                    spanEl.textContent = data.evaluasi?.created_at || '-';
                }
            }
            
            // 2. ISI SKOR UTAMA
            const totalSkor = parseFloat(data.evaluasi?.total_skor) || 0;
            const totalSkorElement = document.getElementById('modalTotalSkorKemendagri');
            if (totalSkorElement) {
                totalSkorElement.textContent = totalSkor.toFixed(2);
            }
            
            // 3. TINGKAT MATURITAS DAN STATUS
            const tingkatElement = document.getElementById('modalTingkatMaturitasKemendagri');
            const badgeElement = document.getElementById('badgeMaturitasKemendagri');
            
            // Ambil tingkat maturitas dari data
            // Asumsi data bisa dalam format: "P-3" atau "TINGKAT 3" atau angka 3
            let maturityLevel = data.evaluasi?.tingkat_maturitas || 'P-1';
            
            // Normalisasi format ke "P-1", "P-2", dst
            if (typeof maturityLevel === 'number') {
                maturityLevel = `P-${maturityLevel}`;
            } else if (maturityLevel.includes('TINGKAT')) {
                const levelNum = maturityLevel.match(/\d+/)?.[0] || '1';
                maturityLevel = `P-${levelNum}`;
            } else if (maturityLevel.includes('Tingkat')) {
                const levelNum = maturityLevel.match(/\d+/)?.[0] || '1';
                maturityLevel = `P-${levelNum}`;
            } else if (!maturityLevel.startsWith('P-')) {
                // Coba konversi dari "SANGAT TINGGI" ke "P-5", dll
                const levelMap = {
                    'SANGAT RENDAH': 'P-1',
                    'RENDAH': 'P-2',
                    'SEDANG': 'P-3',
                    'TINGGI': 'P-4',
                    'SANGAT TINGGI': 'P-5'
                };
                maturityLevel = levelMap[maturityLevel] || 'P-1';
            }
            
            // Dapatkan status dan warna
            const maturityStatus = getMaturityStatus(maturityLevel);
            const maturityColor = getMaturityColor(maturityLevel);
            
            // Update tingkat maturitas (format: P-3)
            if (tingkatElement) {
                tingkatElement.textContent = maturityLevel;
                tingkatElement.className = `text-3xl font-bold ${maturityColor.text} leading-tight`;
            }
            
            // Update status maturitas (badge)
            if (badgeElement) {
                badgeElement.textContent = maturityStatus;
                badgeElement.className = `inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r ${maturityColor.bg} ${maturityColor.text} border ${maturityColor.border}`;
            }
            
            // 4. PROGRESS BAR
            const scorePercentage = document.getElementById('scorePercentage');
            const scoreProgressBar = document.getElementById('scoreProgressBar');
            
            // Hitung persentase berdasarkan total skor (skala 11-55)
            // Skor minimum: 11, maksimum: 55
            const minScore = 11;
            const maxScore = 55;
            
            let percentage = 0;
            if (totalSkor >= minScore) {
                percentage = Math.min(100, ((totalSkor - minScore) / (maxScore - minScore)) * 100);
            }
            
            if (scorePercentage) {
                scorePercentage.textContent = `${percentage.toFixed(1)}%`;
            }
            
            if (scoreProgressBar) {
                scoreProgressBar.style.width = `${percentage}%`;
                
                // Warna progress bar berdasarkan persentase
                if (percentage >= 80) {
                    scoreProgressBar.className = 'bg-gradient-to-r from-emerald-500 to-green-600 h-2.5 rounded-full';
                } else if (percentage >= 60) {
                    scoreProgressBar.className = 'bg-gradient-to-r from-blue-500 to-cyan-600 h-2.5 rounded-full';
                } else if (percentage >= 40) {
                    scoreProgressBar.className = 'bg-gradient-to-r from-yellow-500 to-amber-600 h-2.5 rounded-full';
                } else if (percentage >= 20) {
                    scoreProgressBar.className = 'bg-gradient-to-r from-orange-500 to-red-500 h-2.5 rounded-full';
                } else {
                    scoreProgressBar.className = 'bg-gradient-to-r from-red-600 to-pink-600 h-2.5 rounded-full';
                }
            }
            
            // 5. ISI JAWABAN SURVEI
            const jawabanContainer = document.getElementById('jawabanKemendagri');
            if (jawabanContainer) {
                jawabanContainer.innerHTML = '';
                
                if (data.jawaban && Object.keys(data.jawaban).length > 0) {
                    let rowIndex = 0;
                    Object.entries(data.jawaban).forEach(([variabel, jawabanData]) => {
                        const row = document.createElement('tr');
                        row.className = `hover:bg-gray-50 transition-colors duration-150 ${rowIndex % 2 === 0 ? 'bg-gray-50/30' : ''}`;
                        
                        // Format file pendukung
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
                                        <i class="fas fa-external-link-alt text-gray-400 text-xs ml-auto"></i>
                                    </a>
                                `;
                            });
                        } else {
                            filesHtml += `
                                <span class="text-gray-400 text-sm italic">
                                    <i class="fas fa-times-circle mr-1"></i> Tidak ada file
                                </span>
                            `;
                        }
                        filesHtml += '</div>';
                        
                        // Format tingkat jawaban
                        let tingkatJawaban = jawabanData.tingkat || 'Belum dinilai';
                        if (tingkatJawaban.includes('Tingkat')) {
                            // Konversi "Tingkat III" ke "P-3"
                            const tingkatMap = {
                                'Tingkat I': 'P-1',
                                'Tingkat II': 'P-2',
                                'Tingkat III': 'P-3',
                                'Tingkat IV': 'P-4',
                                'Tingkat V': 'P-5'
                            };
                            tingkatJawaban = tingkatMap[tingkatJawaban] || tingkatJawaban;
                        }
                        
                        row.innerHTML = `
                            <td class="py-4 px-4 font-medium text-gray-900 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-semibold">${rowIndex + 1}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">${variabel}</span>
                                        <p class="text-xs text-gray-500 mt-0.5">Variabel ${variabel.split(' ')[1] || rowIndex + 1}</p>
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
                                    <i class="fas fa-chart-line mr-1.5"></i>
                                    ${tingkatJawaban}
                                </span>
                            </td>
                            <td class="py-4 px-4 border-b border-gray-200">
                                ${filesHtml}
                            </td>
                        `;
                        
                        jawabanContainer.appendChild(row);
                        rowIndex++;
                    });
                } else {
                    // Jika tidak ada data jawaban
                    jawabanContainer.innerHTML = `
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-database text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-sm font-medium text-gray-500">Data jawaban tidak tersedia</p>
                                    <p class="text-xs text-gray-400 mt-1">Survei ini belum memiliki data jawaban</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }
            
            // 6. ISI DETAIL PERHITUNGAN (jika ada elemen dengan id ini)
            const perhitunganContainer = document.getElementById('perhitunganKemendagri');
            if (perhitunganContainer) {
                perhitunganContainer.innerHTML = '';
                
                if (data.detailPerhitungan && Object.keys(data.detailPerhitungan).length > 0) {
                    let rowIndex = 0;
                    let totalSkorMentah = 0;
                    
                    Object.entries(data.detailPerhitungan).forEach(([variabel, perhitungan]) => {
                        const row = document.createElement('tr');
                        row.className = `hover:bg-gray-50 transition-colors duration-150 ${rowIndex % 2 === 0 ? 'bg-gray-50/30' : ''}`;
                        
                        const skorMentah = parseInt(perhitungan.skor_mentah) || 0;
                        totalSkorMentah += skorMentah;
                        
                        row.innerHTML = `
                            <td class="py-4 px-4 font-medium text-gray-900 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center mr-3">
                                        <span class="text-purple-600 font-semibold">${rowIndex + 1}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">${variabel}</span>
                                        <p class="text-xs text-gray-500 mt-0.5">Variabel ${variabel.split(' ')[1] || rowIndex + 1}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center border-b border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    ${perhitungan.tingkat === 'Tingkat V' ? 'bg-emerald-100 text-emerald-800' :
                                      perhitungan.tingkat === 'Tingkat IV' ? 'bg-blue-100 text-blue-800' :
                                      perhitungan.tingkat === 'Tingkat III' ? 'bg-yellow-100 text-yellow-800' :
                                      perhitungan.tingkat === 'Tingkat II' ? 'bg-orange-100 text-orange-800' :
                                      'bg-red-100 text-red-800'}">
                                    ${perhitungan.tingkat || '-'}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center border-b border-gray-200">
                                <div class="flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-lg ${skorMentah >= 4 ? 'bg-emerald-100 text-emerald-700' : 
                                                                       skorMentah >= 3 ? 'bg-blue-100 text-blue-700' : 
                                                                       skorMentah >= 2 ? 'bg-yellow-100 text-yellow-700' : 
                                                                       'bg-red-100 text-red-700'} flex items-center justify-center font-bold">
                                        ${skorMentah}
                                    </div>
                                    <span class="text-xs text-gray-500 ml-2">/ 5</span>
                                </div>
                            </td>
                        `;
                        
                        perhitunganContainer.appendChild(row);
                        rowIndex++;
                    });
                    
                    // Tambahkan baris total
                    const totalRow = document.createElement('tr');
                    totalRow.className = 'bg-gradient-to-r from-gray-50 to-gray-100';
                    
                    totalRow.innerHTML = `
                        <td class="py-4 px-4 font-bold text-gray-900">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center mr-3">
                                    <span class="text-white font-bold">∑</span>
                                </div>
                                <div>
                                    <span class="font-bold">TOTAL SKOR MENTAH</span>
                                    <p class="text-xs text-gray-500 mt-0.5">Jumlah dari semua variabel</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-center font-bold text-gray-900">
                            <span class="px-3 py-1 bg-gray-800 text-white rounded-full text-xs">
                                ${rowIndex} Variabel
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 flex items-center justify-center font-bold text-white text-lg">
                                    ${totalSkorMentah}
                                </div>
                                <div class="ml-3 text-left">
                                    <span class="text-xs text-gray-500 block">dari maksimal</span>
                                    <span class="font-bold text-gray-900">${rowIndex * 5}</span>
                                </div>
                            </div>
                        </td>
                    `;
                    
                    perhitunganContainer.appendChild(totalRow);
                    
                } else if (perhitunganContainer) {
                    // Jika tidak ada data perhitungan
                    perhitunganContainer.innerHTML = `
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calculator text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-sm font-medium text-gray-500">Data perhitungan tidak tersedia</p>
                                    <p class="text-xs text-gray-400 mt-1">Detail perhitungan belum dihitung</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }
            
            console.log('Modal populated successfully');
            
        } catch (error) {
            console.error('Error in populateKemendagriModal:', error);
            showErrorState(error.message);
            
            // Fallback: tutup modal jika error parah
            setTimeout(() => {
                alert(`Terjadi kesalahan: ${error.message}. Modal akan ditutup.`);
                closeDetailModalKemendagri();
            }, 1000);
        }
    }

    // FUNGSI UNTUK MENUTUP MODAL KEMENDAGRI
    window.closeDetailModalKemendagri = function() {
        const modal = document.getElementById('detailModalKemendagri');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // TUTUP MODAL SAAT KLIK DI LUAR KONTEN
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('detailModalKemendagri');
        if (e.target === modal) {
            closeDetailModalKemendagri();
        }
    });

    // TUTUP MODAL DENGAN ESC KEY
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModalKemendagri();
        }
    });

    // ===== DELETE MODAL FUNCTIONS =====
    window.openHapusModal = function(id, type, opdName) {
        const modal = document.getElementById('hapusModalSurvei');
        const form = document.getElementById('hapusFormSurvei');
        const opdSpan = document.getElementById('opdNameSurvei');
        
        if (form) {
            form.action = `/adminkelembagaan/kematangan-kelembagaan/delete/${id}?type=${type}`;
        }
        
        if (opdSpan) {
            opdSpan.textContent = opdName;
        }
        
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
    
    // EVENT LISTENERS SAAT DOM LOADED
    document.addEventListener('DOMContentLoaded', function () {
        // Modal hapus
        const hapusModal = document.getElementById('hapusModalSurvei');
        if (hapusModal) {
            hapusModal.addEventListener('click', function (e) {
                if (e.target === hapusModal) closeHapusModal();
            });
        }
        
        // Event listener untuk modal Kemenpan (Alpine.js)
        document.addEventListener('open-modal-kemenpan', function(e) {
            console.log('Kemenpan modal event received for id:', e.detail);
        });
        
        // Debug: cek apakah elemen modal ada
        const kemendagriModal = document.getElementById('detailModalKemendagri');
        console.log('Kemendagri modal element exists:', !!kemendagriModal);
        
        if (kemendagriModal) {
            console.log('Modal structure verified');
        }
    });
</script>
@endpush