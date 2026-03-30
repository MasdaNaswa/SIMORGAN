<div id="unggahModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 px-4 py-6">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">

        <!-- Header -->
        <div class="flex items-center gap-3 mb-5">
            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                <i class="fas fa-cloud-upload-alt text-2xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700">
                Unggah Dokumen
            </h2>
        </div>

        <!-- Form Upload -->
        <form action="{{ route('petajab.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama OPD</label>
                <input type="text" name="opd" value="{{ Auth::user()?->nama_opd }}"
                    class="w-full border border-gray-300 p-2.5 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed"
                    readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Pilih File Dokumen</label>
                <input id="fileInput" type="file" name="laporan" accept=".pdf,.doc,.docx,.xls,.xlsx"
                    class="w-full border border-gray-300 p-2.5 rounded-lg bg-gray-50" required>
                <p class="text-xs text-gray-500 mt-1">
                    Format: PDF, DOC, DOCX, XLS, XLSX (Maks 10MB)
                </p>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModal('unggahModal')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition">
                    Unggah
                </button>
            </div>
        </form>

    </div>
</div>