<?php
// app/Http/Controllers/OPD/RBTematikController.php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use App\Models\AksesRb;
use App\Models\RB_Tematik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RBTematikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $selectedYear = $request->get('year', $currentYear);

        // ============== TAMBAHAN: Ambil data akses ==============
        $akses = AksesRb::where('jenis_rb', 'RB Tematik')->first();
        
        // Cek apakah akses bisa dibuka (untuk tombol Tambah)
        $canAccess = $akses && $akses->isAccessible();
        
        // Ambil pesan jika akses ditutup
        $accessMessage = null;
        if (!$canAccess && $akses) {
            if ($akses->status !== 'Dibuka') {
                $accessMessage = 'Akses RB Tematik sedang ditutup oleh admin.';
            } elseif ($akses->start_date && now()->startOfDay()->lt($akses->start_date)) {
                $accessMessage = 'Akses RB Tematik akan dibuka pada ' . $akses->start_date->format('d/m/Y');
            } elseif ($akses->end_date && now()->startOfDay()->gt($akses->end_date)) {
                $accessMessage = 'Akses RB Tematik telah ditutup pada ' . $akses->end_date->format('d/m/Y');
            }
        }
        // ============== END TAMBAHAN ==============

        // Ambil data berdasarkan tahun dari database
        $rbTematik = RB_Tematik::where('tahun', $selectedYear)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Generate tahun untuk filter (dari 2024 sampai tahun sekarang)
        $startYear = 2024;
        $years = range($startYear, $currentYear);
        rsort($years);

        return view('opd.rb-tematik.index', compact(
            'rbTematik',
            'currentYear',
            'selectedYear',
            'years',
            // ============== TAMBAHAN: Kirim ke view ==============
            'canAccess',
            'accessMessage',
            'akses'
            // ============== END TAMBAHAN ==============
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ============== TAMBAHAN: Cek akses SEBELUM menyimpan ==============
        $akses = AksesRb::where('jenis_rb', 'RB Tematik')->first();
        if (!$akses || !$akses->isAccessible()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditutup. Tidak dapat menambah data baru.'
            ], 403);
        }
        // ============== END TAMBAHAN ==============

        try {
            DB::beginTransaction();

            Log::info('Store RB Tematik - Request data:', $request->all());

            // Validasi data sesuai struktur database
            $validator = Validator::make($request->all(), [
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'permasalahan' => 'required|string',
                'sasaran_tematik' => 'nullable|string|max:100',
                'indikator' => 'nullable|string|max:100',
                'target' => 'nullable|string|max:100',
                'target_tahun' => 'nullable|string|max:100',
                'satuan' => 'nullable|string|max:100',
                'anggaran_tahun' => 'nullable|string|max:255',
                'rencana_aksi' => 'nullable|string',
                'satuan_output' => 'nullable|string|max:255',
                'indikator_output' => 'nullable|string|max:255',
                'tw1_target' => 'nullable|string|max:255',
                'realisasi_tw1_target' => 'nullable|string|max:255',
                'tw1_rp' => 'nullable|string|max:255',
                'realisasi_tw1_rp' => 'nullable|string|max:255',
                'tw2_target' => 'nullable|string|max:255',
                'realisasi_tw2_target' => 'nullable|string|max:255',
                'tw2_rp' => 'nullable|string|max:255',
                'realisasi_tw2_rp' => 'nullable|string|max:255',
                'tw3_target' => 'nullable|string|max:255',
                'realisasi_tw3_target' => 'nullable|string|max:255',
                'tw3_rp' => 'nullable|string|max:255',
                'realisasi_tw3_rp' => 'nullable|string|max:255',
                'tw4_target' => 'nullable|string|max:255',
                'realisasi_tw4_target' => 'nullable|string|max:255',
                'tw4_rp' => 'nullable|string|max:255',
                'realisasi_tw4_rp' => 'nullable|string|max:255',
                'rumus' => 'nullable|string',
                'koordinator' => 'nullable|string|max:100',
                'pelaksana' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                Log::warning('Store validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Hitung total anggaran dari triwulan
            $anggaran_total = 0;
            $anggaran_total += $this->cleanRupiah($validated['tw1_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['tw2_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['tw3_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['tw4_rp'] ?? 0);

            // Jika anggaran_tahun tidak diisi, gunakan total dari triwulan
            $anggaran_tahun = $validated['anggaran_tahun'] ?? $anggaran_total;
            if (empty($anggaran_tahun)) {
                $anggaran_tahun = $anggaran_total;
            }

            // Buat data baru
            $rbTematik = RB_Tematik::create([
                'tahun' => $request->tahun ?? date('Y'),
                'permasalahan' => $validated['permasalahan'],
                'sasaran_tematik' => $validated['sasaran_tematik'],
                'indikator' => $validated['indikator'],
                'target' => $validated['target'],
                'satuan' => $validated['satuan'],
                'target_tahun' => $validated['target_tahun'],
                'anggaran_tahun' => $this->cleanRupiah($anggaran_tahun),
                'anggaran_total' => $anggaran_total,
                'renaksi_tw1_target' => $validated['tw1_target'],
                'realisasi_renaksi_tw1_target' => $validated['realisasi_tw1_target'],
                'renaksi_tw1_rp' => $this->cleanRupiah($validated['tw1_rp'] ?? 0),
                'realisasi_renaksi_tw1_rp' => $this->cleanRupiah($validated['realisasi_tw1_rp'] ?? 0),
                'renaksi_tw2_target' => $validated['tw2_target'],
                'realisasi_renaksi_tw2_target' => $validated['realisasi_tw2_target'],
                'renaksi_tw2_rp' => $this->cleanRupiah($validated['tw2_rp'] ?? 0),
                'realisasi_renaksi_tw2_rp' => $this->cleanRupiah($validated['realisasi_tw2_rp'] ?? 0),
                'renaksi_tw3_target' => $validated['tw3_target'],
                'realisasi_renaksi_tw3_target' => $validated['realisasi_tw3_target'],
                'renaksi_tw3_rp' => $this->cleanRupiah($validated['tw3_rp'] ?? 0),
                'realisasi_renaksi_tw3_rp' => $this->cleanRupiah($validated['realisasi_tw3_rp'] ?? 0),
                'renaksi_tw4_target' => $validated['tw4_target'],
                'realisasi_renaksi_tw4_target' => $validated['realisasi_tw4_target'],
                'renaksi_tw4_rp' => $this->cleanRupiah($validated['tw4_rp'] ?? 0),
                'realisasi_renaksi_tw4_rp' => $this->cleanRupiah($validated['realisasi_tw4_rp'] ?? 0),
                'rumus' => $validated['rumus'],
                'rencana_aksi' => $validated['rencana_aksi'],
                'satuan_output' => $validated['satuan_output'],
                'indikator_output' => $validated['indikator_output'],
                'koordinator' => $validated['koordinator'],
                'pelaksana' => $validated['pelaksana'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $rbTematik
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store RB Tematik: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $rbTematik = RB_Tematik::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $rbTematik
            ]);

        } catch (\Exception $e) {
            Log::error('Error show RB Tematik: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get data for editing.
     */
    public function edit($id)
    {
        try {
            $rbTematik = RB_Tematik::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $rbTematik
            ]);

        } catch (\Exception $e) {
            Log::error('Error edit RB Tematik: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // ============== TAMBAHAN: Cek akses SEBELUM update ==============
        $akses = AksesRb::where('jenis_rb', 'RB Tematik')->first();
        if (!$akses || !$akses->isAccessible()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditutup. Tidak dapat mengubah data.'
            ], 403);
        }
        // ============== END TAMBAHAN ==============

        try {
            DB::beginTransaction();

            Log::info('Update RB Tematik - Request data for ID ' . $id . ':', $request->all());

            $rbTematik = RB_Tematik::findOrFail($id);

            // Validasi data sesuai struktur database
            $validator = Validator::make($request->all(), [
                'permasalahan' => 'required|string',
                'sasaran_tematik' => 'nullable|string|max:100',
                'indikator' => 'nullable|string|max:100',
                'target' => 'nullable|string|max:100',
                'target_tahun' => 'nullable|string|max:100',
                'satuan' => 'nullable|string|max:100',
                'rencana_aksi' => 'nullable|string',
                'satuan_output' => 'nullable|string|max:255',
                'indikator_output' => 'nullable|string|max:255',
                'anggaran_tahun' => 'nullable|string|max:255',
                'tw1_target' => 'nullable|string|max:255',
                'realisasi_tw1_target' => 'nullable|string|max:255',
                'tw1_rp' => 'nullable|string|max:255',
                'realisasi_tw1_rp' => 'nullable|string|max:255',
                'tw2_target' => 'nullable|string|max:255',
                'realisasi_tw2_target' => 'nullable|string|max:255',
                'tw2_rp' => 'nullable|string|max:255',
                'realisasi_tw2_rp' => 'nullable|string|max:255',
                'tw3_target' => 'nullable|string|max:255',
                'realisasi_tw3_target' => 'nullable|string|max:255',
                'tw3_rp' => 'nullable|string|max:255',
                'realisasi_tw3_rp' => 'nullable|string|max:255',
                'tw4_target' => 'nullable|string|max:255',
                'realisasi_tw4_target' => 'nullable|string|max:255',
                'tw4_rp' => 'nullable|string|max:255',
                'realisasi_tw4_rp' => 'nullable|string|max:255',
                'rumus' => 'nullable|string',
                'koordinator' => 'nullable|string|max:100',
                'pelaksana' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                Log::warning('Update validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Hitung total anggaran dari triwulan
            $anggaran_total = 0;
            $anggaran_total += $this->cleanRupiah($validated['tw1_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['tw2_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['tw3_rp'] ?? 0);
            $anggaran_total += $this->cleanRupiah($validated['tw4_rp'] ?? 0);

            // Jika anggaran_tahun tidak diisi, gunakan total dari triwulan
            $anggaran_tahun = $validated['anggaran_tahun'] ?? $anggaran_total;
            if (empty($anggaran_tahun)) {
                $anggaran_tahun = $anggaran_total;
            }

            // Update data
            $rbTematik->update([
                'permasalahan' => $validated['permasalahan'],
                'sasaran_tematik' => $validated['sasaran_tematik'],
                'indikator' => $validated['indikator'],
                'target' => $validated['target'],
                'satuan' => $validated['satuan'],
                'target_tahun' => $validated['target_tahun'],
                'anggaran_tahun' => $this->cleanRupiah($anggaran_tahun),
                'anggaran_total' => $anggaran_total,
                'renaksi_tw1_target' => $validated['tw1_target'],
                'realisasi_renaksi_tw1_target' => $validated['realisasi_tw1_target'],
                'renaksi_tw1_rp' => $this->cleanRupiah($validated['tw1_rp'] ?? 0),
                'realisasi_renaksi_tw1_rp' => $this->cleanRupiah($validated['realisasi_tw1_rp'] ?? 0),
                'renaksi_tw2_target' => $validated['tw2_target'],
                'realisasi_renaksi_tw2_target' => $validated['realisasi_tw2_target'],
                'renaksi_tw2_rp' => $this->cleanRupiah($validated['tw2_rp'] ?? 0),
                'realisasi_renaksi_tw2_rp' => $this->cleanRupiah($validated['realisasi_tw2_rp'] ?? 0),
                'renaksi_tw3_target' => $validated['tw3_target'],
                'realisasi_renaksi_tw3_target' => $validated['realisasi_tw3_target'],
                'renaksi_tw3_rp' => $this->cleanRupiah($validated['tw3_rp'] ?? 0),
                'realisasi_renaksi_tw3_rp' => $this->cleanRupiah($validated['realisasi_tw3_rp'] ?? 0),
                'renaksi_tw4_target' => $validated['tw4_target'],
                'realisasi_renaksi_tw4_target' => $validated['realisasi_tw4_target'],
                'renaksi_tw4_rp' => $this->cleanRupiah($validated['tw4_rp'] ?? 0),
                'realisasi_renaksi_tw4_rp' => $this->cleanRupiah($validated['realisasi_tw4_rp'] ?? 0),
                'rumus' => $validated['rumus'],
                'rencana_aksi' => $validated['rencana_aksi'],
                'satuan_output' => $validated['satuan_output'],
                'indikator_output' => $validated['indikator_output'],
                'koordinator' => $validated['koordinator'],
                'pelaksana' => $validated['pelaksana'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $rbTematik
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update RB Tematik: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // ============== TAMBAHAN: Cek akses SEBELUM hapus ==============
        $akses = AksesRb::where('jenis_rb', 'RB Tematik')->first();
        if (!$akses || !$akses->isAccessible()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditutup. Tidak dapat menghapus data.'
            ], 403);
        }
        // ============== END TAMBAHAN ==============

        try {
            DB::beginTransaction();

            $rbTematik = RB_Tematik::findOrFail($id);
            $rbTematik->delete();

            DB::commit();

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            }

            return redirect()->route('rb-tematik.index')
                ->with('success', 'Data berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete RB Tematik: ' . $e->getMessage());

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('rb-tematik.index')
                ->with('error', 'Gagal menghapus data');
        }
    }

    /**
     * Clean Rupiah format to number
     */
    private function cleanRupiah($value)
    {
        if (empty($value)) {
            return 0;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        // Remove dots and non-numeric characters, then convert to integer
        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}