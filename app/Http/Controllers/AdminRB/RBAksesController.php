<?php
// app/Http/Controllers/AdminRB/RBAksesController.php

namespace App\Http\Controllers\AdminRB;

use App\Http\Controllers\Controller;
use App\Models\AksesRb; // Sesuai dengan nama model Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RBAksesController extends Controller
{
    /**
     * Tampilkan daftar kontrol akses RB.
     */
    public function index()
    {
        // Ambil data dari database
        $aksesRb = AksesRb::orderBy('id')->get();
        
        // Jika data masih kosong, buat default data
        if ($aksesRb->isEmpty()) {
            $this->createDefaultData();
            $aksesRb = AksesRb::orderBy('id')->get();
        }

        return view('adminrb.aksesrb.index', compact('aksesRb'));
    }

    /**
     * Create default data if not exists
     */
    private function createDefaultData()
    {
        $defaultData = [
            [
                'jenis_rb' => 'RB General',
                'status' => 'Dibuka', // Menggunakan status, bukan is_open
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->endOfMonth()->toDateString(),
            ],
            [
                'jenis_rb' => 'RB Tematik',
                'status' => 'Dibuka',
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->endOfMonth()->toDateString(),
            ],
            [
                'jenis_rb' => 'PK Bupati',
                'status' => 'Dibuka',
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->endOfMonth()->toDateString(),
            ],
        ];

        foreach ($defaultData as $data) {
            AksesRb::create($data);
        }
    }

    /**
     * Update status akses RB.
     */
    public function update(Request $request, $id)
    {
        // Validasi input - menggunakan status enum, bukan is_open boolean
        $request->validate([
            'status' => 'required|in:Dibuka,Ditutup',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            DB::beginTransaction();

            $akses = AksesRb::findOrFail($id);
            
            $akses->update([
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            DB::commit();

            return redirect()->route('adminrb.aksesrb.index')
                             ->with('success', 'Akses RB berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Gagal memperbarui akses RB: ' . $e->getMessage());
        }
    }
}