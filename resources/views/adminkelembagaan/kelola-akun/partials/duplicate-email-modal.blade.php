<!-- Modal Peringatan Email Duplikat -->
<div id="duplicateEmailModal"
    class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[9999] p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-yellow-500 p-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i>
                Peringatan!
            </h3>
        </div>

        <div class="p-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-base font-semibold text-gray-800 mb-2">Email Sudah Digunakan!</h4>
                    <p class="text-sm text-gray-600 mb-3" id="duplicateEmailMessage"></p>

                    <!-- Detail informasi akun yang sudah ada -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-3 text-sm">
                        <div class="flex justify-between items-center mb-2 pb-2 border-b border-gray-200">
                            <span class="font-medium text-gray-700">Detail Akun yang Sudah Ada:</span>
                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full"
                                id="existingAccountRole"></span>
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex">
                                <span class="w-24 text-gray-600">Nama OPD:</span>
                                <span class="font-medium text-gray-800" id="existingAccountName"></span>
                            </div>
                            <div class="flex">
                                <span class="w-24 text-gray-600">Email:</span>
                                <span class="font-medium text-gray-800" id="existingAccountEmail"></span>
                            </div>
                            <div class="flex">
                                <span class="w-24 text-gray-600">Dibuat oleh:</span>
                                <span class="font-medium text-gray-800" id="existingAccountCreatedBy"></span>
                            </div>
                            <div class="flex">
                                <span class="w-24 text-gray-600">Tanggal:</span>
                                <span class="font-medium text-gray-800" id="existingAccountCreatedAt"></span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-800 italic">
                        * Gunakan email yang berbeda untuk membuat akun OPD baru.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2">
            <button type="button" onclick="closeModal('duplicateEmailModal')"
                class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded hover:bg-gray-50 hover:border-gray-400 transition text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>