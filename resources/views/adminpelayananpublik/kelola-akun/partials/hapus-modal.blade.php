<div id="hapusModal"
     class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50">

  <div class="bg-white rounded-xl shadow-xl w-full max-w-md animate-fadeIn overflow-hidden">

    <!-- Header -->
    <div class="flex justify-between items-center border-b px-5 py-4 bg-red-50">
      <h3 class="text-lg font-semibold flex items-center gap-2 text-red-600">
        <i class="fas fa-trash"></i> Konfirmasi Hapus
      </h3>
    </div>

    <!-- Body -->
    <div class="p-6 text-center">
      <p class="text-gray-700">
        Apakah kamu yakin ingin menghapus akun ini?
      </p>
    </div>

    <!-- Footer -->
    <div class="flex justify-end gap-3 px-5 py-4 bg-gray-50">
      <button type="button"
              onclick="closeModal('hapusModal')"
              class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">
        Batal
      </button>

      <button type="button"
              onclick="confirmDelete()"
              class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg transition">
        Hapus
      </button>
    </div>

  </div>
</div>