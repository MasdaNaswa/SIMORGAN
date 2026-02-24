{{-- resources/views/components/adminrb/ubah-modal-akses.blade.php --}}
@foreach ($aksesRb as $akses)
<div id="aksesModal-{{ $akses->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Ubah Akses - {{ ucfirst($akses->jenis_rb) }}</h3>

            <form action="{{ route('rb-access.update', $akses->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="Dibuka" {{ $akses->status == 'Dibuka' ? 'selected' : '' }}>Dibuka</option>
                        <option value="Ditutup" {{ $akses->status == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $akses->start_date ? $akses->start_date->format('Y-m-d') : '' }}" 
                           class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Deadline</label>
                    <input type="date" name="end_date" value="{{ $akses->end_date ? $akses->end_date->format('Y-m-d') : '' }}" 
                           class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('aksesModal-{{ $akses->id }}')" 
                        class="px-4 py-2 border rounded hover:bg-gray-100">Batal</button>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach