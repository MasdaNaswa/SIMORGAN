@extends('layouts.app')

@section('title', 'Survei KemenPAN - SIMORGAN')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-100 to-gray-50">
    
    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-30">
        <div class="flex justify-between items-center py-4 px-6 md:px-8">
            <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-600"></i>
                <span class="hidden sm:inline">Survei KemenPAN - {{ Auth::user()->nama_opd }}</span>
            </h1>
            <div class="relative group">
                <button class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-user-circle text-xl md:text-2xl text-blue-600"></i>
                    <span class="text-sm md:text-base">Admin OPD</span>
                </button>
            </div>
        </div>
    </header>

    <main class="px-6 md:px-8 py-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <!-- Tab Utama Navigation -->
            <div class="border-b border-gray-200 bg-gray-50">
                <nav class="flex flex-wrap gap-1 px-6 pt-4" aria-label="Tabs">
                    <button onclick="showMainTab('mainTab1')" id="btn-mainTab1" class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition-all duration-200 flex items-center gap-2 bg-blue-600 text-white shadow-md">
                        <i class="fas fa-building"></i> DIMENSI STRUKTUR
                    </button>
                    <button onclick="showMainTab('mainTab2')" id="btn-mainTab2" class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition-all duration-200 flex items-center gap-2 bg-white text-gray-600 hover:bg-gray-100 border border-gray-300">
                        <i class="fas fa-chart-line"></i> DIMENSI PROSES
                    </button>
                </nav>
            </div>

            <!-- Form Container -->
            <form action="{{ route('kematangan.kemenpan.submit') }}" method="POST" class="bg-white">
                @csrf

                <input type="hidden" name="nama_opd" value="{{ $user->nama_opd }}">
                <input type="hidden" name="email" value="{{ $user->email }}">

                <!-- ==================== MAIN TAB 1: DIMENSI STRUKTUR ==================== -->
                <div id="mainTab1" class="p-4">
                    <!-- Sub Tab Navigation untuk Struktur -->
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="flex flex-wrap gap-1">
                            <button onclick="showSubTab('subTab1_1')" id="btn-subTab1_1" class="px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-blue-600 text-white shadow-md">
                                Kompleksitas (1-14)
                            </button>
                            <button onclick="showSubTab('subTab1_2')" id="btn-subTab1_2" class="px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-white text-gray-600 hover:bg-gray-100 border border-gray-300">
                                Formalisasi (15-21)
                            </button>
                            <button onclick="showSubTab('subTab1_3')" id="btn-subTab1_3" class="px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-white text-gray-600 hover:bg-gray-100 border border-gray-300">
                                Sentralisasi (22-32)
                            </button>
                        </nav>
                    </div>

                    <!-- Sub Tab 1.1: Kompleksitas (pertanyaan 1-14) -->
                    <div id="subTab1_1" class="sub-tab-content">
                        @include('components.opd.kemenpan-struktur', ['questions' => $questionsStruktur, 'start' => 1, 'end' => 14, 'title' => '1. Subdimensi Kompleksitas', 'isOPDTanpaUPTD' => $isOPDTanpaUPTD])
                    </div>

                    <!-- Sub Tab 1.2: Formalisasi (pertanyaan 15-21) -->
                    <div id="subTab1_2" class="sub-tab-content" style="display: none;">
                        @include('components.opd.kemenpan-struktur', ['questions' => $questionsStruktur, 'start' => 15, 'end' => 21, 'title' => '2. Subdimensi Formalisasi', 'isOPDTanpaUPTD' => $isOPDTanpaUPTD])
                    </div>

                    <!-- Sub Tab 1.3: Sentralisasi (pertanyaan 22-32) -->
                    <div id="subTab1_3" class="sub-tab-content" style="display: none;">
                        @include('components.opd.kemenpan-struktur', ['questions' => $questionsStruktur, 'start' => 22, 'end' => 32, 'title' => '3. Subdimensi Sentralisasi', 'isOPDTanpaUPTD' => $isOPDTanpaUPTD])
                    </div>
                </div>

                <!-- ==================== MAIN TAB 2: DIMENSI PROSES ==================== -->
                <div id="mainTab2" class="p-4" style="display: none;">
                    <!-- Sub Tab Navigation untuk Proses -->
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="flex flex-wrap gap-1">
                            <button onclick="showSubTab('subTab2_1')" id="btn-subTab2_1" class="px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-blue-600 text-white shadow-md">
                                Keselarasan (1-8)
                            </button>
                            <button onclick="showSubTab('subTab2_2')" id="btn-subTab2_2" class="px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-white text-gray-600 hover:bg-gray-100 border border-gray-300">
                                Tata Kelola (9-15)
                            </button>
                            <button onclick="showSubTab('subTab2_3')" id="btn-subTab2_3" class="px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-white text-gray-600 hover:bg-gray-100 border border-gray-300">
                                Perbaikan Proses (16-19)
                            </button>
                            <button onclick="showSubTab('subTab2_4')" id="btn-subTab2_4" class="px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-white text-gray-600 hover:bg-gray-100 border border-gray-300">
                                Manajemen Risiko (20-25)
                            </button>
                            <button onclick="showSubTab('subTab2_5')" id="btn-subTab2_5" class="px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-white text-gray-600 hover:bg-gray-100 border border-gray-300">
                                Teknologi Informasi (26-30)
                            </button>
                        </nav>
                    </div>

                    <!-- Sub Tab 2.1: Keselarasan (pertanyaan 1-8) -->
                    <div id="subTab2_1" class="sub-tab-content">
                        @include('components.opd.kemenpan-proses', ['questions' => $questionsProses, 'start' => 1, 'end' => 8, 'title' => '1. Subdimensi Keselarasan'])
                    </div>

                    <!-- Sub Tab 2.2: Tata Kelola (pertanyaan 9-15) -->
                    <div id="subTab2_2" class="sub-tab-content" style="display: none;">
                        @include('components.opd.kemenpan-proses', ['questions' => $questionsProses, 'start' => 9, 'end' => 15, 'title' => '2. Subdimensi Tata Kelola dan Kepatuhan'])
                    </div>

                    <!-- Sub Tab 2.3: Perbaikan Proses (pertanyaan 16-19) -->
                    <div id="subTab2_3" class="sub-tab-content" style="display: none;">
                        @include('components.opd.kemenpan-proses', ['questions' => $questionsProses, 'start' => 16, 'end' => 19, 'title' => '3. Subdimensi Perbaikan dan Peningkatan Proses'])
                    </div>

                    <!-- Sub Tab 2.4: Manajemen Risiko (pertanyaan 20-25) -->
                    <div id="subTab2_4" class="sub-tab-content" style="display: none;">
                        @include('components.opd.kemenpan-proses', ['questions' => $questionsProses, 'start' => 20, 'end' => 25, 'title' => '4. Subdimensi Manajemen Risiko'])
                    </div>

                    <!-- Sub Tab 2.5: Teknologi Informasi (pertanyaan 26-30) -->
                    <div id="subTab2_5" class="sub-tab-content" style="display: none;">
                        @include('components.opd.kemenpan-proses', ['questions' => $questionsProses, 'start' => 26, 'end' => 30, 'title' => '5. Subdimensi Teknologi Informasi'])
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-4 px-6 py-6 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('kematangan.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 font-medium shadow-sm flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-medium shadow-sm flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Survei
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('components.footer')
</div>

<script>
// Fungsi untuk toggle Main Tab (Struktur vs Proses)
function showMainTab(tabId) {
    // Sembunyikan semua main tab
    document.getElementById('mainTab1').style.display = 'none';
    document.getElementById('mainTab2').style.display = 'none';
    
    // Tampilkan main tab yang dipilih
    document.getElementById(tabId).style.display = 'block';
    
    // Update style tombol main tab
    const btn1 = document.getElementById('btn-mainTab1');
    const btn2 = document.getElementById('btn-mainTab2');
    
    btn1.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
    btn1.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-300');
    btn2.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
    btn2.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-300');
    
    if (tabId === 'mainTab1') {
        btn1.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-300');
        btn1.classList.add('bg-blue-600', 'text-white', 'shadow-md');
        
        // Reset ke sub tab pertama
        showSubTab('subTab1_1');
    } else {
        btn2.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-300');
        btn2.classList.add('bg-blue-600', 'text-white', 'shadow-md');
        
        // Reset ke sub tab pertama
        showSubTab('subTab2_1');
    }
}

// Fungsi untuk toggle Sub Tab
function showSubTab(subTabId) {
    // Tentukan main tab mana yang aktif
    const isStruktur = document.getElementById('mainTab1').style.display !== 'none';
    
    if (isStruktur) {
        // Sembunyikan semua sub tab struktur
        for (let i = 1; i <= 3; i++) {
            const el = document.getElementById(`subTab1_${i}`);
            if (el) el.style.display = 'none';
        }
        // Tampilkan yang dipilih
        document.getElementById(subTabId).style.display = 'block';
        
        // Update style tombol sub tab struktur
        for (let i = 1; i <= 3; i++) {
            const btn = document.getElementById(`btn-subTab1_${i}`);
            if (btn) {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-300');
            }
        }
        const activeBtn = document.getElementById(`btn-${subTabId}`);
        if (activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-300');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-md');
        }
    } else {
        // Sembunyikan semua sub tab proses
        for (let i = 1; i <= 5; i++) {
            const el = document.getElementById(`subTab2_${i}`);
            if (el) el.style.display = 'none';
        }
        // Tampilkan yang dipilih
        document.getElementById(subTabId).style.display = 'block';
        
        // Update style tombol sub tab proses
        for (let i = 1; i <= 5; i++) {
            const btn = document.getElementById(`btn-subTab2_${i}`);
            if (btn) {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-300');
            }
        }
        const activeBtn = document.getElementById(`btn-${subTabId}`);
        if (activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-300');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-md');
        }
    }
}

// Inisialisasi: tampilkan sub tab pertama dari main tab pertama
document.addEventListener('DOMContentLoaded', function() {
    showMainTab('mainTab1');
});
</script>

@endsection