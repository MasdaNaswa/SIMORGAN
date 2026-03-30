<div class="card-body p-6">
    <form action="{{ route('opd.profile.update.password') }}" method="POST" class="form grid gap-5">
        @csrf

        <!-- Password Lama -->
        <div class="form-group mb-4">
            <label for="password_lama" class="font-medium text-slate-800 mb-2 block text-sm">Kata Sandi Saat Ini</label>
            <div class="relative">
                <input type="password" id="password_lama" name="password_lama"
                    placeholder="Masukkan kata sandi saat ini"
                    class="w-full py-3 px-4 pr-10 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-primary focus:ring-3 focus:ring-primary/15 focus:bg-white">
                <button type="button"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 focus:outline-none"
                    onclick="togglePassword('password_lama', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Password Baru -->
        <div class="form-group mb-4">
            <label for="password_baru" class="font-medium text-slate-800 mb-2 block text-sm">Kata Sandi Baru</label>
            <div class="relative">
                <input type="password" id="password_baru" name="password_baru"
                    placeholder="Masukkan kata sandi baru"
                    class="w-full py-3 px-4 pr-10 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-primary focus:ring-3 focus:ring-primary/15 focus:bg-white">
                <button type="button"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 focus:outline-none"
                    onclick="togglePassword('password_baru', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Konfirmasi Password Baru -->
        <div class="form-group mb-4">
            <label for="password_baru_confirmation" class="font-medium text-slate-800 mb-2 block text-sm">Konfirmasi Kata Sandi Baru</label>
            <div class="relative">
                <input type="password" id="password_baru_confirmation" name="password_baru_confirmation"
                    placeholder="Konfirmasi kata sandi baru"
                    class="w-full py-3 px-4 pr-10 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none focus:border-primary focus:ring-3 focus:ring-primary/15 focus:bg-white">
                <button type="button"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 focus:outline-none"
                    onclick="togglePassword('password_baru_confirmation', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit"
                class="btn btn-primary py-2 px-4 rounded-lg font-medium inline-flex items-center justify-center gap-2 bg-primary text-white hover:bg-primary-dark">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>