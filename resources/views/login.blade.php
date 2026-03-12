<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Bagor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body
    class="bg-gradient-to-br from-gray-100 to-blue-200 flex justify-center items-center min-h-screen font-sans text-gray-800">

    <div class="login-container relative bg-white p-10 rounded-xl shadow-lg w-[500px] overflow-hidden">

        <!-- Top gradient bar -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-green-400"></div>

        <!-- Logo -->
        <div class="logo text-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                class="h-32 mx-auto transition-transform duration-300 hover:scale-105">
        </div>

        <h2 class="text-center text-2xl font-semibold mb-8">LOGIN SIMORGAN</h2>

        <!-- 🔹 Tambahkan action dan csrf -->
        <form id="loginForm" action="{{ route('login.submit') }}" method="POST">
            @csrf

            <!-- Email input -->
            <div class="mb-6 relative">
                <label for="email" class="block mb-2 font-medium text-gray-700">Email</label>
                <div class="relative">
                    <i class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 fas fa-envelope"></i>
                    <input type="text" id="email" name="email" placeholder="Masukkan email" required
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 bg-gray-100 focus:bg-white focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none transition" />
                </div>
            </div>

            <!-- Password input with eye icon -->
            <div class="mb-6 relative">
                <label for="password" class="block mb-2 font-medium text-gray-700">Kata Sandi</label>
                <div class="relative">
                    <i class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required
                        class="w-full pl-10 pr-12 py-3 rounded-lg border border-gray-200 bg-gray-100 focus:bg-white focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none transition" />
                    <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Role select with dropdown icon -->
            <div class="mb-6 relative">
                <label for="role" class="block mb-2 font-medium text-gray-700">Login Sebagai</label>
                <div class="relative">
                    <i class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 fas fa-user-tag"></i>
                    <select id="role" name="role" required
                        class="w-full py-3 pl-10 pr-10 rounded-lg border border-gray-200 bg-gray-100 focus:bg-white focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none appearance-none">
                        <option value="">-- Pilih Role --</option>
                        <option value="bagor">Bagian Organisasi (Bagor)</option>
                        <option value="opd">OPD</option>
                    </select>
                    <i class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 fas fa-chevron-down pointer-events-none"></i>
                </div>
            </div>

            <!-- Bagor Role select -->
            <div class="mb-6 hidden" id="bagor-role-list">
                <label for="bagor-role" class="block mb-2 font-medium text-gray-700">Pilih Bagor Role</label>
                <div class="relative">
                    <i class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 fas fa-user-tie"></i>
                    <select id="bagor-role" name="bagor_role"
                        class="w-full py-3 pl-10 pr-10 rounded-lg border border-gray-200 bg-gray-100 focus:bg-white focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none appearance-none">
                        <option value="">-- Pilih Bagor Role --</option>
                        <option value="adminrb">Admin RB</option>
                        <option value="admin_pelayanan">Admin Pelayanan Publik</option>
                        <option value="admin_kelembagaan">Admin Kelembagaan</option>
                    </select>
                    <i class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 fas fa-chevron-down pointer-events-none"></i>
                </div>
            </div>

            <!-- OPD select -->
            <div class="mb-6 hidden" id="opd-list">
                <label for="opd" class="block mb-2 font-medium text-gray-700">Pilih OPD</label>
                <div class="relative">
                    <i class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 fas fa-building"></i>
                    <select name="nama_opd" class="w-full py-3 pl-10 pr-10 rounded-lg border border-gray-200 bg-gray-100 focus:bg-white focus:border-blue-500 focus:ring focus:ring-blue-200 outline-none appearance-none" required>
                        @foreach($opdList as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <i class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 fas fa-chevron-down pointer-events-none"></i>
                </div>
            </div>

            <button type="submit"
                class="btn-login w-full py-3 rounded-lg bg-blue-500 text-white font-medium hover:bg-blue-600 transition flex items-center justify-center gap-2">
                <i class="fas fa-sign-in-alt"></i> MASUK
            </button>
        </form>

        <!-- 🔸 Pesan error dari backend -->
        @if ($errors->any())
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-semibold">Login gagal:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        // Role select functionality
        const roleSelect = document.getElementById('role');
        const opdList = document.getElementById('opd-list');
        const bagorRoleList = document.getElementById('bagor-role-list');

        roleSelect.addEventListener('change', function () {
            opdList.classList.toggle('hidden', this.value !== 'opd');
            bagorRoleList.classList.toggle('hidden', this.value !== 'bagor');
        });
    </script>
</body>

</html>