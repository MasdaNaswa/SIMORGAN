<div id="hapusModal{{ $item['id'] }}" 
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                Konfirmasi Hapus
            </h3>
        </div>

        <!-- Body -->
        <div class="flex flex-col items-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-3"></i>
            <p class="text-gray-700 text-center w-full px-2">
                Apakah Anda yakin ingin menghapus 
                <strong>{{ $item['nama'] }}</strong> beserta indikatornya?
            </p>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" 
                onclick="closeModal('hapusModal{{ $item['id'] }}')" 
                class="px-4 py-2 rounded-lg bg-gray-200 text-black-700 hover:bg-gray-300 transition">
                Batal
            </button>

            <form method="POST" action="#">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                    Hapus
                </button>
            </form>
        </div>

    </div>
</div>
