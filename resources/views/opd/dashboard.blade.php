@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  @include('components.header')

  <div class="content-body px-6 md:px-8 py-6">

    <!-- Welcome Section -->
    <div class="welcome-container mb-8">
      <div class="welcome-text">
        <h2 class="text-2xl font-semibold text-gray-800">
          Selamat Datang, {{ $nama_opd }} Kabupaten Karimun
        </h2>
      </div>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-container grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- Card 1: Laporan Kelembagaan -->
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-all duration-200 relative group">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-gray-700 font-semibold flex items-center gap-2">
            Laporan Kelembagaan
            <i class="fas fa-info-circle text-blue-500 cursor-pointer"></i>
          </h3>
          <i class="fas fa-file-alt text-blue-500 text-2xl"></i>
        </div>
        <div class="text-4xl font-bold text-gray-800">{{ $totalDokumenKelembagaan ?? 0 }}</div>
        <p class="text-gray-500 text-sm mt-1">Total dokumen laporan kelembagaan</p>

        <!-- Tooltip -->
        <div class="absolute right-0 top-10 translate-y-1 bg-slate-800 text-white text-xs rounded-lg shadow-lg px-4 py-3 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 invisible group-hover:visible transition-all duration-300 ease-out z-50 w-60">
          <div class="absolute -top-2 right-6 w-3 h-3 bg-slate-800 rotate-45"></div>
          <p class="font-semibold mb-2 border-b border-slate-600 pb-1">Rincian Status:</p>
          <ul class="space-y-1 text-[13px]">
            <li class="flex justify-between"><span>Diproses</span><span class="text-blue-400">{{ $dokumenProsesKelembagaan ?? 0 }}</span></li>
            <li class="flex justify-between"><span>Direvisi</span><span class="text-yellow-400">{{ $dokumenDirevisiKelembagaan ?? 0 }}</span></li>
            <li class="flex justify-between"><span>Disetujui</span><span class="text-green-400">{{ $dokumenDisetujuiKelembagaan ?? 0 }}</span></li>
          </ul>
        </div>
      </div>

      <!-- Card 2: Dokumen Pelayanan Publik -->
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-all duration-200 relative group">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-gray-700 font-semibold flex items-center gap-2">
            Dokumen Pelayanan Publik
            <i class="fas fa-info-circle text-green-500 cursor-pointer"></i>
          </h3>
          <i class="fas fa-file-alt text-green-500 text-2xl"></i>
        </div>
        <div class="text-4xl font-bold text-gray-800">
          {{ $totalDokumenPublik ?? 0 }}
        </div>
        <p class="text-gray-500 text-sm mt-1">Total dokumen pelayanan publik</p>

        <!-- Tooltip -->
        <div class="absolute right-0 top-10 translate-y-1 bg-slate-800 text-white text-xs rounded-lg shadow-lg px-4 py-3 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 invisible group-hover:visible transition-all duration-300 ease-out z-50 w-60">
          <div class="absolute -top-2 right-6 w-3 h-3 bg-slate-800 rotate-45"></div>
          <p class="font-semibold mb-2 border-b border-slate-600 pb-1">Rincian Status:</p>
          <ul class="space-y-1 text-[13px]">
            <li class="flex justify-between"><span>Diproses</span><span class="text-blue-400">{{ $dokumenProsesPublik ?? 0 }}</span></li>
            <li class="flex justify-between"><span>Direvisi</span><span class="text-yellow-400">{{ $dokumenDirevisiPublik ?? 0 }}</span></li>
            <li class="flex justify-between"><span>Disetujui</span><span class="text-green-400">{{ $dokumenDisetujuiPublik ?? 0 }}</span></li>
          </ul>
        </div>
      </div>

      <!-- Card 3: Status Akses RB -->
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-400 hover:shadow-lg transition-all duration-200 relative group">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-gray-700 font-semibold flex items-center gap-2">
            Status Akses RB
            <i class="fas fa-info-circle text-yellow-500 cursor-pointer"></i>
          </h3>
          <i class="fas fa-clock text-yellow-400 text-2xl"></i>
        </div>
        <div class="text-4xl font-bold text-gray-800">{{ $statusUmum ?? 'Aktif' }}</div>
        <p class="text-gray-500 text-sm mt-1">Keterangan akses & deadline RB</p>

        <!-- Tooltip Detail -->
        <div class="absolute right-0 top-10 translate-y-1 bg-slate-800 text-white text-xs rounded-lg shadow-lg px-4 py-3 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 invisible group-hover:visible transition-all duration-300 ease-out z-50 w-72">
          <div class="absolute -top-2 right-6 w-3 h-3 bg-slate-800 rotate-45"></div>
          <p class="font-semibold mb-2 border-b border-slate-600 pb-1">Status Akses RB:</p>
          
          <!-- RB General -->
          <div class="mb-2 pb-2 border-b border-slate-700">
            <div class="flex justify-between items-center mb-1">
              <span class="font-medium">RB General</span>
              <span class="px-2 py-0.5 rounded-full text-xs
                {{ $statusGeneral == 'Dibuka' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                {{ $statusGeneral }}
              </span>
            </div>
            <div class="flex justify-between text-[11px] text-gray-300">
              <span>Deadline:</span>
              <span class="font-semibold">{{ $deadlineGeneral }}</span>
            </div>
          </div>

          <!-- RB Tematik -->
          <div class="mb-2 pb-2 border-b border-slate-700">
            <div class="flex justify-between items-center mb-1">
              <span class="font-medium">RB Tematik</span>
              <span class="px-2 py-0.5 rounded-full text-xs
                {{ $statusTematik == 'Dibuka' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                {{ $statusTematik }}
              </span>
            </div>
            <div class="flex justify-between text-[11px] text-gray-300">
              <span>Deadline:</span>
              <span class="font-semibold">{{ $deadlineTematik }}</span>
            </div>
          </div>

          <!-- PK Bupati -->
          <div>
            <div class="flex justify-between items-center mb-1">
              <span class="font-medium">PK Bupati</span>
              <span class="px-2 py-0.5 rounded-full text-xs
                {{ $statusPK == 'Dibuka' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                {{ $statusPK }}
              </span>
            </div>
            <div class="flex justify-between text-[11px] text-gray-300">
              <span>Deadline:</span>
              <span class="font-semibold">{{ $deadlinePK }}</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  @include('components.footer')
@endsection