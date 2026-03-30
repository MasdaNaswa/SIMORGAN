@extends('layouts.adminpelayananpublik')

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
        <h2 class="text-2xl font-semibold text-gray-800">Selamat Datang, Admin Pelayanan Publik</h2>
      </div>

      <!-- Tombol Connect & Disconnect Gmail -->
      <div class="px-6 md:px-8 mb-8 flex items-center gap-4">
        @if(Auth::check())
          @if(!Auth::user()->gmail_token)
            <a href="{{ route('gmail.connect') }}"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
              Connect Gmail
            </a>
          @else
            <p class="text-green-600 font-semibold mb-0">Gmail sudah terhubung ✅</p>

            <!-- Tombol Disconnect di samping -->
            <form action="{{ route('gmail.disconnect') }}" method="POST">
              @csrf
              <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                Disconnect Gmail
              </button>
            </form>
          @endif
        @endif
      </div>
      
      <!-- Statistik Cards -->
      <div class="stats-container flex flex-wrap justify-center gap-6 px-6 md:px-8 mb-8">

        <!-- Card 1: Jumlah Akun Terdaftar -->
        <div
          class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-all duration-200 w-full sm:w-[280px]">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-gray-700 font-semibold">Jumlah Akun Terdaftar</h3>
            <i class="fas fa-users text-blue-500 text-2xl"></i>
          </div>
          <div class="text-4xl font-bold text-gray-800">{{ $totalAkun ?? 0 }}</div>
          <p class="text-gray-500 text-sm mt-1">Total akun OPD yang terdaftar</p>
        </div>

        <!-- Card 2: Jumlah Dokumen -->
        <div
          class="relative bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-all duration-200 group w-full sm:w-[280px]">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-gray-700 font-semibold flex items-center gap-2">
              Jumlah Dokumen
              <i class="fas fa-info-circle text-green-500 cursor-pointer"></i>
            </h3>
            <i class="fas fa-file-alt text-green-500 text-2xl"></i>
          </div>
          <div class="text-4xl font-bold text-gray-800">{{ $totalDokumen ?? 0 }}</div>
          <p class="text-gray-500 text-sm mt-1">Total dokumen yang dikelola</p>

          <!-- Tooltip Status Dokumen -->
          <div
            class="absolute right-0 top-10 translate-y-1 bg-slate-800 text-white text-xs rounded-lg shadow-lg px-4 py-3 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 invisible group-hover:visible transition-all duration-300 ease-out z-50 w-60">
            <div class="absolute -top-2 right-6 w-3 h-3 bg-slate-800 rotate-45"></div>
            <p class="font-semibold mb-2 text-slate-100 border-b border-slate-600 pb-1">Status Dokumen:</p>
            <ul class="space-y-1.5 text-[13px]">
              <li class="flex justify-between">
                <span>Proses</span>
                <span class="text-blue-400">{{ $dokumenProses ?? 0 }}</span>
              </li>
              <li class="flex justify-between">
                <span>Revisi</span>
                <span class="text-yellow-400">{{ $dokumenRevisi ?? 0 }}</span>
              </li>
              <li class="flex justify-between">
                <span>Disetujui</span>
                <span class="text-green-400">{{ $dokumenDisetujui ?? 0 }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>

    <!-- Footer -->
    @include('components.footer')

  </div>

@endsection