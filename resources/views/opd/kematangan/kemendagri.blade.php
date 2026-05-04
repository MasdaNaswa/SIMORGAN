@extends('layouts.app')

@section('title', 'Survei Kemendagri - SIMORGAN')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-100 to-gray-50">
    
    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-30">
        <div class="flex justify-between items-center py-4 px-6 md:px-8">
            <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                <i class="fas fa-chart-line text-green-600"></i>
                <span class="hidden sm:inline">Survei Kemendagri - {{ Auth::user()->nama_opd }}</span>
            </h1>
            <div class="relative group">
                <button class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-user-circle text-xl md:text-2xl text-green-600"></i>
                    <span class="text-sm md:text-base">Admin OPD</span>
                </button>
            </div>
        </div>
    </header>

    <main class="px-6 md:px-8 py-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <!-- Tab Navigation untuk 11 Variabel -->
            <div class="border-b border-gray-200 bg-gray-50">
                <nav class="flex flex-wrap gap-1 px-6 pt-4" aria-label="Tabs">
                    @php
                        $variabelTabs = [
                            'I' => 'Variabel I',
                            'II' => 'Variabel II',
                            'III' => 'Variabel III',
                            'IV' => 'Variabel IV',
                            'V' => 'Variabel V',
                            'VI' => 'Variabel VI',
                            'VII' => 'Variabel VII',
                            'VIII' => 'Variabel VIII',
                            'IX' => 'Variabel IX',
                            'X' => 'Variabel X',
                            'XI' => 'Variabel XI',
                        ];
                    @endphp
                    
                    @foreach($variabelTabs as $key => $label)
                        <button onclick="showTab('tab{{ $key }}')" id="btn-tab{{ $key }}" 
                            class="tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 {{ $loop->first ? 'bg-green-600 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-300' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Form Container -->
            <form action="{{ route('kematangan.kemendagri.submit') }}" method="POST" class="bg-white" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="nama_opd" value="{{ $user->nama_opd }}">
                <input type="hidden" name="email" value="{{ $user->email }}">

                <!-- 11 Tab Content -->
                @foreach($variabelData as $key => $data)
                <div id="tab{{ $key }}" class="tab-content p-6" style="display: {{ $loop->first ? 'block' : 'none' }};">
                    <!-- Header Variabel -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 mb-6 border border-green-100">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-chart-line text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">VARIABEL {{ $key }} - {{ $data['title'] }}</h3>
                                <p class="text-sm text-gray-600">Pilih salah satu tingkat kematangan yang sesuai</p>
                            </div>
                        </div>
                    </div>

                    <!-- Skor Kematangan Info -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-6 border border-blue-100">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="fas fa-chart-simple text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700">SKOR TINGKAT KEMATANGAN ORGANISASI</h4>
                                <div class="flex flex-wrap gap-3 mt-1">
                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Tingkat I: 10-19</span>
                                    <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded">Tingkat II: 19,1-28</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Tingkat III: 28,1-37</span>
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Tingkat IV: 37,1-46</span>
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Tingkat V: 46,1-55</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Radio Options untuk Tingkat -->
                    <div class="space-y-4">
                        @foreach($data['tingkat'] as $tingkat => $deskripsi)
                            @php
                                $colorClass = match($tingkat) {
                                    'Tingkat I' => 'border-red-200 hover:border-red-300 hover:bg-red-50',
                                    'Tingkat II' => 'border-orange-200 hover:border-orange-300 hover:bg-orange-50',
                                    'Tingkat III' => 'border-yellow-200 hover:border-yellow-300 hover:bg-yellow-50',
                                    'Tingkat IV' => 'border-blue-200 hover:border-blue-300 hover:bg-blue-50',
                                    'Tingkat V' => 'border-green-200 hover:border-green-300 hover:bg-green-50',
                                    default => 'border-gray-200 hover:border-gray-300 hover:bg-gray-50',
                                };
                            @endphp
                            <label class="flex items-start p-5 rounded-lg border-2 cursor-pointer transition-all duration-200 bg-white {{ $colorClass }} group">
                                <div class="flex-shrink-0 mt-1 mr-4">
                                    <input type="radio" name="variabel_{{ strtolower($key) }}" value="{{ $tingkat }}" required
                                        class="mt-1 h-5 w-5 text-green-600">
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700 font-bold text-sm">
                                            {{ substr($tingkat, -1) }}
                                        </span>
                                        <span class="font-semibold text-gray-800">{{ $tingkat }}</span>
                                    </div>
                                    <p class="text-gray-700 text-sm leading-relaxed">{{ $deskripsi }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Upload File -->
                    <div class="mt-6 bg-blue-50 rounded-xl p-5 border border-blue-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="fas fa-cloud-upload-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800">Upload Data Pendukung</h5>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                    Maksimal ukuran file 10 MB dan maksimal 3 file (format JPG, JPEG, PNG, PDF)
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <input type="file" 
                                   name="variabel_{{ strtolower($key) }}_files[]" 
                                   id="fileInput_variabel_{{ strtolower($key) }}"
                                   multiple
                                   accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/jpg,image/png,application/pdf"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <ul id="fileList_{{ strtolower($key) }}" class="mt-2 text-sm text-gray-700"></ul>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                <span>Upload maksimal 3 file. Format: JPG, JPEG, PNG, PDF. Max 10 MB per file.</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Footer -->
                <div class="flex justify-end gap-4 px-6 py-6 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('kematangan.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 font-medium shadow-sm flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-lg hover:from-green-700 hover:to-emerald-800 transition-all duration-200 font-medium shadow-sm flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('components.footer')
</div>

<script>
function showTab(tabId) {
    // Sembunyikan semua tab content
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Sembunyikan style aktif semua tombol
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-green-600', 'text-white', 'shadow-md');
        btn.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-300');
    });
    
    // Tampilkan tab yang dipilih
    document.getElementById(tabId).style.display = 'block';
    
    // Aktifkan tombol yang dipilih
    const activeBtn = document.getElementById(`btn-${tabId}`);
    if (activeBtn) {
        activeBtn.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-300');
        activeBtn.classList.add('bg-green-600', 'text-white', 'shadow-md');
    }
}

// Inisialisasi FileManager untuk setiap variabel
class FileManager {
    constructor(variable) {
        this.variable = variable;
        this.files = [];
        this.init();
    }
    
    init() {
        const fileInput = document.getElementById(`fileInput_variabel_${this.variable}`);
        const fileListContainer = document.getElementById(`fileList_${this.variable}`);
        
        if (fileInput && fileListContainer) {
            fileInput.addEventListener('change', (e) => this.handleFileSelect(e));
            this.updatePreview();
        }
    }
    
    handleFileSelect(e) {
        const newFiles = Array.from(e.target.files);
        
        if (this.files.length + newFiles.length > 3) {
            alert('Maksimal 3 file per variabel');
            e.target.value = '';
            return;
        }
        
        newFiles.forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                alert(`File "${file.name}" melebihi 10 MB`);
                return;
            }
            
            const ext = file.name.toLowerCase().split('.').pop();
            const validExt = ['jpg', 'jpeg', 'png', 'pdf'];
            
            if (!validExt.includes(ext)) {
                alert(`File "${file.name}" harus JPG, JPEG, PNG, atau PDF`);
                return;
            }
            
            this.files.push(file);
        });
        
        this.updatePreview();
        e.target.value = '';
    }
    
    removeFile(index) {
        this.files.splice(index, 1);
        this.updatePreview();
    }
    
    updatePreview() {
        const container = document.getElementById(`fileList_${this.variable}`);
        if (!container) return;
        
        container.innerHTML = '';
        
        if (this.files.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm italic">Belum ada file yang diupload</p>';
            return;
        }
        
        this.files.forEach((file, index) => {
            const fileElement = document.createElement('div');
            fileElement.className = 'flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg mb-2';
            
            const fileName = file.name.length > 30 ? file.name.substring(0, 27) + '...' : file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            
            let icon = 'fa-file';
            let iconColor = 'text-blue-600';
            let bgColor = 'bg-blue-50';
            
            if (file.type.includes('image')) {
                icon = 'fa-file-image';
                iconColor = 'text-green-600';
                bgColor = 'bg-green-50';
            } else if (file.type.includes('pdf')) {
                icon = 'fa-file-pdf';
                iconColor = 'text-red-600';
                bgColor = 'bg-red-50';
            }
            
            fileElement.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 ${bgColor} rounded-lg flex items-center justify-center">
                        <i class="fas ${icon} ${iconColor} text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">${fileName}</p>
                        <span class="text-xs text-gray-500">${fileSize}</span>
                    </div>
                </div>
                <button type="button" onclick="window.fileManagers['${this.variable}'].removeFile(${index})"
                        class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(fileElement);
        });
    }
    
    getFiles() {
        return this.files;
    }
}

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    window.fileManagers = {};
    const variables = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x', 'xi'];
    
    variables.forEach(v => {
        window.fileManagers[v] = new FileManager(v);
    });
    
    // Handle form submission
    const form = document.querySelector('form[enctype="multipart/form-data"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Hapus semua file input yang ada
            document.querySelectorAll('input[type="file"]').forEach(input => {
                input.remove();
            });
            
            // Tambahkan file dari setiap manager
            variables.forEach(v => {
                const manager = window.fileManagers[v];
                if (manager && manager.getFiles().length > 0) {
                    const dataTransfer = new DataTransfer();
                    manager.getFiles().forEach(file => {
                        dataTransfer.items.add(file);
                    });
                    
                    const newInput = document.createElement('input');
                    newInput.type = 'file';
                    newInput.name = `variabel_${v}_files[]`;
                    newInput.multiple = true;
                    newInput.style.display = 'none';
                    newInput.files = dataTransfer.files;
                    form.appendChild(newInput);
                }
            });
        });
    }
});
</script>

@endsection