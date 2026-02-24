<!-- UNGGAH MODAL -->
<div id="unggahModal"
    class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 px-4 py-6">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">

        <!-- Header -->
        <div class="flex items-center gap-3 mb-5">
            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                <i class="fas fa-cloud-upload-alt text-2xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700">
                Unggah Laporan
            </h2>
        </div>

        <form action="{{ route('opd.pelayananpublik.upload') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf

            <!-- Nama OPD -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama OPD</label>
                <input type="text" value="{{ Auth::user()->nama_opd }}"
                    class="w-full border border-gray-300 p-2.5 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed"
                    readonly>
            </div>

            <!-- KATEGORI DOKUMEN -->
            <div class="mb-4 text-left">
                <label class="block text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-tags text-blue-600"></i>
                    Kategori Dokumen
                </label>

                <div class="relative">
                    <select name="kategori" required class="w-full appearance-none bg-white-50 border border-black-300 text-gray-700
                   rounded-lg px-4 py-2.5 pr-10
                   focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-white-500
                   hover:bg-black-100 transition">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategories as $kategori)
                            <option value="{{ $kategori->id_kategori }}">
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Icon panah -->
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-black-600">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-1">
                    Pilih kategori sesuai jenis dokumen yang diunggah
                </p>
            </div>


            <!-- File -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Pilih File Dokumen</label>
                <input type="file" name="laporan" accept=".pdf,.doc,.docx"
                    class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    required>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeUploadModal()"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Unggah
                </button>
            </div>

        </form>
    </div>
</div>