@extends('layouts.app')

@section('title', 'SIMORGAN')

@section('content')
<div class="flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow sticky top-0 z-30">
        <div class="flex justify-between items-center py-4 px-6 md:px-8">
            <h1 class="text-xl md:text-2xl font-semibold flex items-center gap-2">
                <i class="fas fa-user-circle text-primary text-2xl"></i>
                <span class="hidden sm:inline">Profile</span>
            </h1>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex-1 content-body p-6 bg-gray-50">
        <div class="profile-container max-w-4xl mx-auto">

            <!-- Tabs -->
            <div class="card bg-white rounded-xl shadow border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="tab-buttons flex border-b border-gray-200 mb-5">
                    <button class="tab-button py-3.5 px-6 bg-transparent border-none cursor-pointer font-medium text-slate-500 relative transition-all duration-300 flex items-center gap-2 text-base active" onclick="openTab('profile-tab', event)">
                        <i class="fas fa-user text-lg"></i>Profile
                    </button>
                    <button class="tab-button py-3.5 px-6 bg-transparent border-none cursor-pointer font-medium text-slate-500 relative transition-all duration-300 flex items-center gap-2 text-base" onclick="openTab('password-tab', event)">
                        <i class="fas fa-key text-lg"></i> Ubah Kata Sandi
                    </button>
                </div>

                <div class="tab-content active" id="profile-tab">
                    @include('opd.profile.partials.update-profile-form')
                </div>

                <div class="tab-content" id="password-tab">
                    @include('opd.profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</div>

<script>
    // Tab functionality
    function openTab(tabId, event) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        const selectedTab = document.getElementById(tabId);
        if(selectedTab) selectedTab.classList.add('active');
        if(event && event.currentTarget) event.currentTarget.classList.add('active');
    }
</script>

<style>
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .tab-button.active { color: #1d4ed8; font-weight: 600; }
</style>
@endsection