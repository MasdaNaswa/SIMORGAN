<div id="hapusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                Konfirmasi Hapus
            </h3>
        </div>

        <!-- Body -->
        <div class="text-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-3"></i>
            <p class="text-gray-700">
                Apakah kamu yakin ingin menghapus akun ini?
            </p>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeModal('hapusModal')"
                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                Batal
            </button>

            {{-- Hapus onsubmit dari form --}}
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>