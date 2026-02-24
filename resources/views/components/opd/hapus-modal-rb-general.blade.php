<div id="hapusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h3>
        </div>

        <!-- Body -->
        <div class="text-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-3"></i>
            <p class="text-gray-700">Apakah Anda yakin ingin menghapus data ini?</p>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" 
                onclick="closeModal('hapusModal')" 
                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                Batal
            </button>

            {{-- Form Hapus --}}
            <form id="hapusForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="button" 
                    id="hapusSubmitBtn"
                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Variabel global untuk menyimpan ID yang akan dihapus
let currentDeleteId = null;

// Fungsi untuk membuka modal hapus
function openHapusModal(id) {
    currentDeleteId = id;
    
    // Update form action
    const form = document.getElementById("hapusForm");
    if (form) {
        form.action = `/rb-general/${id}`;
    }
    
    openModal("hapusModal");
}

// Handle submit form hapus
document.addEventListener('DOMContentLoaded', function() {
    const hapusSubmitBtn = document.getElementById('hapusSubmitBtn');
    
    if (hapusSubmitBtn) {
        hapusSubmitBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            // Show loading
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
            this.disabled = true;
            
            try {
                const form = document.getElementById('hapusForm');
                const formAction = form.action;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                 document.querySelector('input[name="_token"]')?.value;
                
                if (!csrfToken) {
                    throw new Error('CSRF token tidak ditemukan');
                }
                
                // Buat FormData untuk mengirim data
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', csrfToken);
                
                const response = await fetch(formAction, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // TIDAK ADA ALERT - LANGSUNG TUTUP MODAL DAN RELOAD
                    closeModal('hapusModal');
                    
                    // Refresh halaman setelah 500ms
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                    
                } else {
                    // Hanya tampilkan alert jika error
                    alert('Gagal menghapus data: ' + result.message);
                    // Restore button
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                // Tampilkan alert hanya untuk error
                alert('Terjadi kesalahan saat menghapus data: ' + error.message);
                // Restore button
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });
    }
});
</script>