<!-- Modal Unduh -->
<div id="unduhModal" class="hidden fixed inset-0 z-[9999] bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <!-- Header -->
        <div class="relative bg-white text-black p-4 rounded-t-lg">
            <h3 class="text-lg font-semibold text-center">Unduh</h3>
        </div>

        <!-- Konten -->
        <div class="p-6 flex flex-col items-center gap-6 bg-blue-100 rounded-b-lg">
            <!-- Tombol Excel & PDF -->
            <div class="flex gap-4">
                <button onclick="downloadExcel()"
                    class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition font-medium shadow">
                    Format Excel
                </button>
                <button onclick="downloadPdf()"
                    class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition font-medium shadow">
                    Format PDF
                </button>
            </div>

            <!-- Tombol Tutup -->
            <button onclick="closeModal('unduhModal')"
                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition font-medium shadow">
                Tutup
            </button>
        </div>
    </div>
    </div>
</div>

<script>
function downloadExcel() {
    const year = document.getElementById('yearFilter').value;
    window.location.href = `/adminrb/rb-tematik/export/excel?year=${year}`;
    closeModal('unduhModal');
}

function downloadPdf() {
    const year = document.getElementById('yearFilter').value;
    window.location.href = `/adminrb/rb-tematik/export/pdf?year=${year}`;
    closeModal('unduhModal');
}
</script>