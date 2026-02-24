<div id="hapusModal" 
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]">
    
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                Konfirmasi Hapus Laporan SKM
            </h3>
            <button onclick="closeModal('hapusModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="text-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-3"></i>
            <p class="text-gray-700 mb-1">
                Apakah Anda yakin ingin menghapus laporan SKM:
            </p>
            <p id="hapusJudul" class="font-semibold text-gray-900"></p>
            <p class="text-sm text-red-600 mt-2">
                Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" 
                onclick="closeModal('hapusModal')" 
                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                Batal
            </button>

            <form id="hapusForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
