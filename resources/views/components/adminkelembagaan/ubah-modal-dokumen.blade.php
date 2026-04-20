<div id="statusModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 px-4 py-6">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="flex justify-between items-center bg-green-50 px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-green-600">
                <i class="fas fa-edit"></i> Update Status & Catatan
            </h5>
        </div>

        <form method="POST" action="" class="px-6 py-4 space-y-5" id="statusForm">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-2">
                <label for="status" class="text-gray-700 text-sm">Status</label>
                <select name="status" id="statusInput"
                    class="border border-gray-300 rounded p-2 text-sm w-full focus:ring-2 focus:ring-green-400 focus:outline-none">
                    <option value="">-- Pilih Status --</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Revisi">Revisi</option>
                </select>
            </div>

            <div class="flex flex-col gap-2">
                <label for="catatan" class="text-gray-700 text-sm">Catatan</label>
                <textarea name="catatan" id="catatanInput" rows="3" placeholder="Tambahkan catatan..."
                    class="border border-gray-300 rounded p-2 text-sm w-full focus:ring-2 focus:ring-green-400 focus:outline-none"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-gray-200">
                <button type="button" onclick="closeStatusModal()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const statusSelect = document.getElementById('statusInput');
    const catatanInput = document.getElementById('catatanInput');

    function toggleCatatan() {
        const val = statusSelect.value.toLowerCase();

        if (val === "Revisi") {
            catatanInput.disabled = false;
            catatanInput.style.background = "white";
        } else {
            catatanInput.disabled = true;
            catatanInput.style.background = "#f1f1f1";
            catatanInput.value = ""; // reset catatan
        }
    }

    // Jalan saat status berubah
    statusSelect.addEventListener('change', toggleCatatan);

    // Jalan saat modal dibuka (mengisi nilai awal)
    toggleCatatan();
});
</script>
