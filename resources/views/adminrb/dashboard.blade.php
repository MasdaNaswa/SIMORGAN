@extends('layouts.adminrb')

@section('title', 'SIMORGAN')

@section('menu-dashboard', 'text-blue-600 font-bold')

@section('content')

<div class="flex flex-col min-h-screen">

    <div class="flex-1">

        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-30">
            <div class="flex justify-between items-center py-4 px-6 md:px-8">
                <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                    <i class="material-icons text-blue-600">dashboard</i>
                    <span class="hidden sm:inline">Dashboard</span>
                </h1>
            </div>
        </header>

        <!-- Welcome -->
        <div class="welcome-container mt-8 mb-8 px-6 md:px-8">
            <h2 class="text-2xl font-semibold text-gray-800">Selamat Datang, Admin RB</h2>
        </div>

        <!-- Tombol Connect & Disconnect Gmail -->
        <div class="px-6 md:px-8 mb-8 flex items-center gap-4">
            @if(Auth::check())
                @if(!Auth::user()->gmail_token)
                    <a href="{{ route('gmail.connect') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Connect Gmail
                    </a>
                @else
                    <p class="text-green-600 font-semibold mb-0">Gmail sudah terhubung ✅</p>

                    <form action="{{ route('gmail.disconnect') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Disconnect Gmail
                        </button>
                    </form>
                @endif
            @endif
        </div>

        <!-- Statistik -->
        <div class="stats-container grid grid-cols-1 md:grid-cols-3 gap-6 px-6 md:px-8 mb-8">

            <!-- Card 1: Jumlah Akun -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-gray-700 font-semibold">Jumlah Akun</h3>
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
                <div class="text-4xl font-bold text-gray-800">{{ $totalAkun ?? 0 }}</div>
                <p class="text-gray-500 text-sm mt-1">Total akun OPD terdaftar</p>
            </div>

            <!-- Card 2: Data RB Masuk + Tooltip -->
            <div class="relative bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-all duration-200 group">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-gray-700 font-semibold flex items-center gap-2">
                        Data RB Masuk
                        <i class="fas fa-info-circle text-green-500 cursor-pointer"></i>
                    </h3>
                    <i class="fas fa-database text-green-500 text-2xl"></i>
                </div>
                <div class="text-4xl font-bold text-gray-800">{{ $totalRB ?? 0 }}</div>
                <p class="text-gray-500 text-sm mt-1">Total laporan RB yang diterima</p>

                <!-- Tooltip -->
                <div class="absolute right-0 top-10 translate-y-1 bg-slate-800 text-white text-xs rounded-lg shadow-lg px-4 py-3
                            opacity-0 group-hover:opacity-100 group-hover:translate-y-0 invisible group-hover:visible
                            transition-all duration-300 ease-out z-50 w-64">
                    <div class="absolute -top-2 right-6 w-3 h-3 bg-slate-800 rotate-45"></div>
                    <p class="font-semibold mb-2 text-slate-100 border-b border-slate-600 pb-1">Rincian Data RB:</p>
                    <ul class="space-y-1.5 text-[13px]">
                        <li class="flex justify-between">
                            <span>RB General</span>
                            <span class="text-green-400 font-semibold">{{ $totalRBGeneral ?? 0 }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span>RB Tematik</span>
                            <span class="text-blue-400 font-semibold">{{ $totalRBTematik ?? 0 }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span>PK Bupati</span>
                            <span class="text-yellow-400 font-semibold">{{ $totalRBPK ?? 0 }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Card 3: Status Akses RB -->
            <div class="relative bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-400 hover:shadow-lg transition-all duration-200 group">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-gray-700 font-semibold flex items-center gap-2">
                        Status Akses RB
                        <i class="fas fa-info-circle text-yellow-500 cursor-pointer"></i>
                    </h3>
                    <i class="fas fa-clock text-yellow-400 text-2xl"></i>
                </div>
                <div class="text-4xl font-bold text-gray-800">{{ $statusUmum ?? 'Aktif' }}</div>
                <p class="text-gray-500 text-sm mt-1">Keterangan akses pengisian RB</p>

                <!-- Tooltip -->
                <div class="absolute right-0 top-10 translate-y-1 bg-slate-800 text-white text-xs rounded-lg shadow-lg px-4 py-3
                            opacity-0 group-hover:opacity-100 group-hover:translate-y-0 invisible group-hover:visible
                            transition-all duration-300 ease-out z-50 w-60">
                    <div class="absolute -top-2 right-6 w-3 h-3 bg-slate-800 rotate-45"></div>
                    <p class="font-semibold mb-2 text-slate-100 border-b border-slate-600 pb-1">Rincian Status:</p>
                    <ul class="space-y-1.5 text-[13px]">
                        <li class="flex justify-between">
                            <span>RB General</span>
                            <span class="{{ ($statusGeneral ?? 'Dibuka') === 'Dibuka' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $statusGeneral ?? 'Dibuka' }}
                            </span>
                        </li>
                        <li class="flex justify-between">
                            <span>RB Tematik</span>
                            <span class="{{ ($statusTematik ?? 'Ditutup') === 'Dibuka' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $statusTematik ?? 'Ditutup' }}
                            </span>
                        </li>
                        <li class="flex justify-between">
                            <span>PK Bupati</span>
                            <span class="{{ ($statusPK ?? 'Dibuka') === 'Dibuka' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $statusPK ?? 'Dibuka' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </div>

    @include('components.footer')

</div>

@endsection
