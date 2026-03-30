@extends('layouts.app')

@section('title', 'SIMORGAN')

@section('content')

<script src="https://unpkg.com/alpinejs" defer></script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div class="min-h-screen bg-gradient-to-b from-gray-100 to-gray-50"
     x-data="{ 
         modalKemenpan: false, 
         modalKemendagri: false,
         modalInfoSurvei: false
     }"
     @close-kemenpan.window="modalKemenpan = false"
     @close-kemendagri.window="modalKemendagri = false"
     @close-info-survei.window="modalInfoSurvei = false"
>

    <!-- Header -->
<header class="bg-white shadow sticky top-0 z-30">
    <div class="flex justify-between items-center py-4 px-6 md:px-8">
        <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-600"></i>
            <span class="hidden sm:inline">Kematangan Kelembagaan</span>
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

    <!-- Content -->
    <main class="px-6 md:px-8 py-10">
        <div class="flex flex-wrap gap-4">

            {{-- CARD 1 – KemenPAN RB --}}
            <div class="bg-white border border-gray-300 rounded-2xl shadow-sm p-6 hover:shadow-md transition flex-shrink-0"
                 style="max-width: 300px; flex: 1 0 auto;">
                 
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-4">
                    <i class="fas fa-external-link-alt text-blue-600 text-xl"></i>
                </div>

                <h2 class="text-lg font-bold text-gray-900 mb-2">Survei KemenPAN</h2>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                    OPD dapat menilai kematangan kelembagaan oleh Kemenpan.
                </p>

                <button
                    @click="
                        @if($evaluasiKemenpan)
                            modalInfoSurvei = true
                        @else
                            modalKemenpan = true
                        @endif
                    "
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold py-2 rounded-lg shadow hover:opacity-90 transition"
                >
                    Isi Survei
                </button>
            </div>

            {{-- CARD 2 – Kemendagri --}}
            <div class="bg-white border border-gray-300 rounded-2xl shadow-sm p-6 hover:shadow-md transition flex-shrink-0"
                 style="max-width: 300px; flex: 1 0 auto;">
                 
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mb-4">
                    <i class="fas fa-external-link-alt text-green-600 text-xl"></i>
                </div>

                <h2 class="text-lg font-bold text-gray-900 mb-2">Survei Kemendagri</h2>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                    Form ini digunakan untuk penilaian kematangan kelembagaan oleh Kemendagri.
                </p>

                <button
                    @click="
                        @if($evaluasiKemendagri)
                            modalInfoSurvei = true
                        @else
                            modalKemendagri = true
                        @endif
                    "
                    class="w-full bg-gradient-to-r from-green-500 to-green-700 text-white font-semibold py-2 rounded-lg shadow hover:opacity-90 transition"
                >
                    Isi Survei
                </button>
            </div>

        </div>
    </main>

    {{-- Modal KemenPAN-RB --}}
    <div 
        x-show="modalKemenpan"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 p-4"
    >
        <div class="bg-white rounded-2xl w-full max-w-6xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
                <h3 class="text-xl font-bold">Survei KemenPAN</h3>
                
            </div>
            
            <!-- Konten komponen kemenpan -->
            @include('components.opd.kemenpan', [
                'action' => route('kematangan.kemenpan.submit'),
                'cancelUrl' => 'javascript:voids(0)',
                'user' => Auth::user()
            ])
        </div>
    </div>

    {{-- Modal Kemendagri --}}
    <div 
        x-show="modalKemendagri"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 p-4"
    >
        <div class="bg-white rounded-2xl w-full max-w-6xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
                <h3 class="text-xl font-bold">Survei Kemendagri</h3>
            </div>
            
            <!-- Konten komponen kemendagri -->
            @include('components.opd.kemendagri', [
                'action' => route('kematangan.kemendagri.submit'),
                'cancelUrl' => 'javascript:void(0)',
                'user' => Auth::user()
            ])
        </div>
    </div>

    {{-- Modal Info Survei --}}
    <div 
        x-show="modalInfoSurvei"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-200 mx-4">
            <div class="flex justify-between items-center border-b border-gray-200 pb-3 mb-4">
                <h3 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-info-circle text-yellow-500"></i>
                    Informasi Survei
                </h3>
                <button 
                    @click="modalInfoSurvei = false" 
                    class="text-gray-500 hover:text-gray-700 text-2xl"
                >
                    &times;
                </button>
            </div>

            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-5xl mb-4"></i>
                <p class="text-gray-800 font-medium mb-2 text-lg">
                    Anda sudah mengisi survei ini.
                </p>
                <p class="text-gray-500 text-sm">
                    Jika ingin mengisi ulang, hasil survei sebelumnya harus dihapus terlebih dahulu oleh admin.
                </p>
            </div>

            <div class="mt-6 flex justify-center">
                <button 
                    @click="modalInfoSurvei = false" 
                    class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold rounded-lg shadow-lg hover:from-yellow-600 hover:to-yellow-700 transition"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

@include('components.footer')
@endsection