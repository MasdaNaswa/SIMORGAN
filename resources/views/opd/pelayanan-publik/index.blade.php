@extends('layouts.app')

@section('title', 'SIMORGAN')

@section('content')
<div class="flex min-h-screen">
    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col transition-all duration-300" id="mainContent">
        <!-- HEADER -->
        <header class="bg-white shadow-sm py-4 px-6 sticky top-0 z-40 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <!-- Sidebar toggle button -->
                <button id="sidebarToggle" class="md:hidden p-2 rounded bg-gray-100 hover:bg-gray-200">
                    <i class="fas fa-bars text-gray-700"></i>
                </button>
                <h1 class="text-xl font-semibold flex items-center gap-2">
                    <i class="fas fa-file-alt text-primary"></i> Unggah Dokumen Pelayanan Publik
                </h1>
            </div>
            <div class="header-actions flex items-center gap-4">
                <div class="profile-dropdown relative">
                    <button
                        class="profile-btn flex items-center gap-2 bg-transparent border-none cursor-pointer text-gray-900 font-medium py-1 px-3 rounded-full transition-all hover:bg-primary-light">
                        <i class="fas fa-user-circle text-primary text-2xl"></i>
                        <span>Admin OPD</span>
                    </button>
                    <div
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md hidden profile-dropdown-content">
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

        <!-- CONTENT -->
        <div class="content-container flex-1 py-6 px-4 md:px-6 bg-[#F8FAFC]">
            <!-- Tabs Navigation -->
            <div class="tabs flex border-b border-gray-200 mb-6 overflow-x-auto">
                <div class="tab py-3 px-5 cursor-pointer border-b-2 border-transparent transition-all font-medium text-gray-600 hover:text-primary whitespace-nowrap"
                    data-tab="template">Unduh Template</div>
                <div class="tab py-3 px-5 cursor-pointer border-b-2 border-transparent transition-all font-medium text-gray-600 hover:text-primary whitespace-nowrap"
                    data-tab="upload">Unggah Laporan</div>
                <div class="tab py-3 px-5 cursor-pointer border-b-2 border-transparent transition-all font-medium text-gray-600 hover:text-primary whitespace-nowrap"
                    data-tab="skm">Form SKM</div>
    <div class="tab py-3 px-5 cursor-pointer border-b-2 border-transparent transition-all font-medium text-gray-600 hover:text-primary whitespace-nowrap"
        data-tab="skm-list">Laporan SKM Saya</div>
            </div>

            <!-- Tab Content -->
            <div class="tab-container">
                <!-- Tab 1: Download Template -->
                <div id="template" class="tab-content">
                    @include('opd.pelayanan-publik.partials.download-template', [
                        'kategories' => $kategories,
                        'templates' => $templates
                    ])
                </div>

                <!-- Tab 2: Unggah Dokumen -->
                <div id="upload" class="tab-content hidden">
                    @include('opd.pelayanan-publik.partials.unggah-dokumen', [
                        'laporans' => $laporans
                    ])
                </div>

                <!-- Tab 3: Form SKM -->
                <div id="skm" class="tab-content hidden">
                    @include('opd.pelayanan-publik.partials.form-skm')
                </div>

                <!-- Tab 4: Daftar Laporan SKM (BUAT BARU) -->
    <div id="skm-list" class="tab-content hidden">
        @include('opd.pelayanan-publik.partials.skm-list')
    </div>
            </div>
        </div>
    </div>
</div>

@include('components.footer')
@include('components.opd.unggah-modal-yanlik')
@include('components.opd.hapus-modal-yanlik')

<!-- SCRIPTS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== PROFILE DROPDOWN =====
    const profileBtn = document.querySelector('.profile-btn');
    const dropdownContent = document.querySelector('.profile-dropdown-content');
    if (profileBtn && dropdownContent) {
        profileBtn.addEventListener('click', e => { 
            e.stopPropagation(); 
            dropdownContent.classList.toggle('hidden'); 
        });
        document.addEventListener('click', e => { 
            if (!e.target.closest('.profile-dropdown')) dropdownContent.classList.add('hidden'); 
        });
    }

    // ===== TABS =====
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    function activateTab(tabName) {
        tabs.forEach(t => { 
            t.classList.remove('active', 'border-primary', 'text-primary'); 
            t.classList.add('text-gray-600'); 
        });
        tabContents.forEach(c => c.classList.add('hidden'));
        const activeTabElement = document.querySelector(`.tab[data-tab="${tabName}"]`);
        const activeContent = document.getElementById(tabName);
        if (activeTabElement && activeContent) {
            activeTabElement.classList.add('active', 'border-primary', 'text-primary');
            activeTabElement.classList.remove('text-gray-600');
            activeContent.classList.remove('hidden');
        }
    }

    // Klik manual tab
    tabs.forEach(tab => tab.addEventListener('click', () => {
        activateTab(tab.getAttribute('data-tab'));
    }));

    // ===== SET TAB SESUAI SESSION =====
    const sessionActiveTab = "{{ session('active_tab', 'template') }}"; // default template
    activateTab(sessionActiveTab);

    // ===== CATEGORY TABS (for template download) =====
    const categoryTabs = document.querySelectorAll('.category-tab');
    categoryTabs.forEach(tab => tab.addEventListener('click', () => {
        categoryTabs.forEach(t => { 
            t.classList.remove('active', 'border-primary', 'text-primary'); 
            t.classList.add('text-gray-600'); 
        });
        document.querySelectorAll('.category-content').forEach(c => c.classList.add('hidden'));
        tab.classList.add('active', 'border-primary', 'text-primary'); 
        tab.classList.remove('text-gray-600');
        document.getElementById(tab.getAttribute('data-category')).classList.remove('hidden');
    }));
    if (categoryTabs.length > 0) categoryTabs[0].click();

    // ===== MODAL HAPUS =====
    window.openDeleteModal = function (id, actionUrl = '#') {
        const modal = document.getElementById(id);
        const form = document.getElementById('hapusForm');
        if (form) form.action = actionUrl;
        if (modal) modal.classList.remove('hidden');
    }

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('hidden');
    }
    
    // ===== MODAL UNGGAH =====
    window.openUploadModal = function () {
        const modal = document.getElementById('unggahModal');
        if (modal) modal.classList.remove('hidden');
    }
    
    window.closeUploadModal = function () {
        const modal = document.getElementById('unggahModal');
        if (modal) modal.classList.add('hidden');
    }

    const uploadArea = document.getElementById('uploadArea');
    if (uploadArea) uploadArea.addEventListener('click', () => { 
        window.openUploadModal(); 
    });

});
</script>

@endsection