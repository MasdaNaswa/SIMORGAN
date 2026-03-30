{{-- resources/views/components/adminrb/ubah-modal-akses.blade.php --}}
@foreach ($aksesRb as $akses)
<div id="aksesModal-{{ $akses->id }}" class="fixed inset-0 z-[9999] hidden">
    <div class="flex items-center justify-center w-full h-full bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Ubah Akses - {{ ucfirst($akses->jenis_rb) }}</h3>

            <form action="{{ route('rb-access.update', $akses->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Dibuka" {{ $akses->status == 'Dibuka' ? 'selected' : '' }}>Dibuka</option>
                        <option value="Ditutup" {{ $akses->status == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $akses->start_date ? $akses->start_date->format('Y-m-d') : '' }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                    <input type="date" name="end_date" value="{{ $akses->end_date ? $akses->end_date->format('Y-m-d') : '' }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeModal('aksesModal-{{ $akses->id }}')" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach