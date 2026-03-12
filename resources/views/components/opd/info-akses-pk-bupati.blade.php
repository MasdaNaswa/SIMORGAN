{{-- resources/views/components/opd/info-akses-rb-general.blade.php --}}
<div id="infoAksesModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('infoAksesModal')"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Icon peringatan -->
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Akses Ditutup
                        </h3>
                        
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="infoAksesMessage">
                                Maaf, akses PK Bupati sedang ditutup. Anda tidak dapat menambah data baru saat ini.
                            </p>
                            
                            <!-- Informasi tambahan -->
                            <p class="text-xs text-gray-400 mt-3">
                                * Anda masih dapat melihat data yang sudah ada.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeModal('infoAksesModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Script untuk mengupdate konten modal saat dibuka
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi ini akan dipanggil dari openInfoAksesModal di index
    window.updateInfoAksesModal = function(message, deadline, startDate) {
        const messageEl = document.getElementById('infoAksesMessage');
        const deadlineContainer = document.getElementById('infoAksesDeadlineContainer');
        const deadlineEl = document.getElementById('infoAksesDeadline');
        const startDateContainer = document.getElementById('infoAksesStartDateContainer');
        const startDateEl = document.getElementById('infoAksesStartDate');
        
        if (messageEl) messageEl.textContent = message;
        
        if (deadline && deadlineContainer && deadlineEl) {
            deadlineContainer.classList.remove('hidden');
            deadlineEl.textContent = deadline;
        } else if (deadlineContainer) {
            deadlineContainer.classList.add('hidden');
        }
        
        if (startDate && startDateContainer && startDateEl) {
            startDateContainer.classList.remove('hidden');
            startDateEl.textContent = startDate;
        } else if (startDateContainer) {
            startDateContainer.classList.add('hidden');
        }
    };
});
</script>