<div class="card-body p-6">
    <form action="{{ route('opd.profile.update') }}" method="POST" class="form grid gap-5">
        @csrf

        <div class="form-row grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="form-group mb-4">
                <label for="name" class="font-medium text-slate-800 mb-2 block text-sm">Nama Lengkap</label>
                <input type="text" id="name" name="nama"
                    value="{{ $user->nama_opd }}" readonly
                    placeholder="Masukkan nama lengkap"
                    class="w-full py-3 px-4 border border-gray-300 rounded-lg text-sm transition-all duration-300 bg-gray-50 focus:outline-none focus:border-primary focus:ring-3 focus:ring-primary/15">
            </div>

            <div class="form-group mb-4">
                <label for="email" class="font-medium text-slate-800 mb-2 block text-sm">Email</label>
                <input type="email" id="email" name="email"
                    value="{{ $user->email }}" readonly
                    placeholder="Masukkan email"
                    class="w-full py-3 px-4 border border-gray-300 rounded-lg text-sm transition-all duration-300 bg-gray-50 focus:outline-none focus:border-primary focus:ring-3 focus:ring-primary/15">
            </div>
        </div>

        <div class="flex justify-end mt-4 gap-3">
            <button type="submit" 
                class="btn btn-primary py-2 px-4 rounded-lg font-medium cursor-pointer inline-flex items-center justify-center gap-2 transition-all duration-300 bg-primary text-white border-none hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow text-sm">
                Simpan
            </button>
        </div>
    </form>
</div>