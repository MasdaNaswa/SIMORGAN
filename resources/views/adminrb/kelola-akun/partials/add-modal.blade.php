<div id="addOPDModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[9999] p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-y-auto">
    <div class="flex justify-between items-center bg-green-600 text-white p-4 rounded-t-lg">
      <h3 class="text-lg font-semibold">Tambah Akun OPD</h3>
    </div>
    <form id="formTambahAkun" method="POST" class="p-6 space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama OPD</label>
        <input type="text" name="nama_opd" id="nama_opd"  placeholder="Masukkan nama OPD" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-green-500" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" id="emailInput"  placeholder="Masukkan email" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-green-500" required>
        <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
        <input type="password" name="password" id="password"  placeholder="Masukkan kata sandi" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-green-500" required>
        <p id="passwordError" class="text-red-500 text-xs mt-1 hidden"></p>
        <p class="text-gray-500 text-xs mt-1">Minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol (@$!%*?&#)</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
        <select name="role" class="w-full p-2 border rounded-md bg-gray-100" readonly>
          <option value="OPD">OPD</option>
        </select>
      </div>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeModal('addOPDModal')" class="px-4 py-2 bg-gray-500 text-black rounded-md hover:bg-gray-600 transition">
          Batal
        </button>
        <button type="button" onclick="cekEmailSebelumSubmit()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
          Tambah
        </button>
      </div>
    </form>
  </div>
</div>