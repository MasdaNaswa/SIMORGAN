<div id="addOPDModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[9999] p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-y-auto">
    <div class="flex justify-between items-center bg-green-600 text-white p-4 rounded-t-lg">
      <h3 class="text-lg font-semibold">Tambah Akun OPD</h3>
      <button type="button" onclick="closeModal('addOPDModal')" class="text-white hover:text-gray-200">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <form id="formTambahAkun" method="POST" class="p-6 space-y-2">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-600">Nama OPD</label>
        <input type="text" name="nama_opd" id="nama_opd" class="w-full p-2 border rounded-md" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-600">Email</label>
        <input type="email" name="email" id="emailInput" class="w-full p-2 border rounded-md" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-600">Password</label>
        <input type="password" name="password" id="password" class="w-full p-2 border rounded-md" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-600">Role</label>
        <select name="role" class="w-full p-2 border rounded-md">
          <option value="OPD">OPD</option>
        </select>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal('addOPDModal')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-gray-600">Batal</button>
        <button type="button" onclick="cekEmailSebelumSubmit()" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Tambah</button>
      </div>
    </form>
  </div>
</div>