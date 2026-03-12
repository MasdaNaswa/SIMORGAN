<?php
// app/Http/Controllers/AdminRB/RBAksesController.php

namespace App\Http\Controllers\AdminRB;

use App\Http\Controllers\Controller;
use App\Models\AksesRb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RBAksesController extends Controller
{
    /**
     * Tampilkan daftar kontrol akses RB.
     */
    public function index()
    {
        // CEK DAN UPDATE STATUS YANG EXPIRED TERLEBIH DAHULU
        $this->checkExpiredStatus();
        
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
     * Cek dan update status yang sudah melewati deadline
     */
    private function checkExpiredStatus()
    {
        $now = now()->startOfDay();
        
        // Cari semua akses yang statusnya 'Dibuka' tapi sudah melewati deadline
        $expiredAccess = AksesRb::where('status', 'Dibuka')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', $now)
            ->get();
        
        foreach ($expiredAccess as $akses) {
            $akses->update(['status' => 'Ditutup']);
        }
    }

    /**
     * Create default data if not exists
     */
    private function createDefaultData()
    {
        $defaultData = [
            [
                'jenis_rb' => 'RB General',
                'status' => 'Dibuka',
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
            AksesRb::firstOrCreate(
                ['jenis_rb' => $data['jenis_rb']],
                $data
            );
        }
    }

    /**
     * Update status akses RB.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:Dibuka,Ditutup',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            DB::beginTransaction();

            $akses = AksesRb::findOrFail($id);
            
            // Jika mengubah status menjadi Dibuka, cek dulu apakah belum melewati deadline
            if ($request->status === 'Dibuka' && $request->end_date) {
                $endDate = \Carbon\Carbon::parse($request->end_date)->startOfDay();
                $now = now()->startOfDay();
                
                if ($now->greaterThan($endDate)) {
                    return redirect()->back()
                        ->with('error', 'Tidak dapat membuka akses karena sudah melewati deadline!');
                }
            }
            
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