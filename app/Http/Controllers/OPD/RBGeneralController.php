<?php
// app/Http/Controllers/OPD/RBGeneralController.php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use App\Models\AksesRb; 
use App\Models\RB_General;
use Illuminate\Http\Request;

class RBGeneralController extends Controller
{
    /**
     * Tampilkan daftar RB General
     */
    public function index()
    {
        $selectedYear = request()->get('year', date('Y'));

        // Ambil data akses
        $akses = AksesRb::where('jenis_rb', 'RB General')->first();
        
        // Cek apakah akses bisa dibuka (untuk semua operasi)
        $canAccess = $akses && $akses->isAccessible();
        
        // Ambil pesan jika akses ditutup
        $accessMessage = null;
        if (!$canAccess && $akses) {
            if ($akses->status !== 'Dibuka') {
                $accessMessage = 'Akses RB General sedang ditutup oleh admin.';
            } elseif ($akses->start_date && now()->startOfDay()->lt($akses->start_date)) {
                $accessMessage = 'Akses RB General akan dibuka pada ' . $akses->start_date->format('d/m/Y');
            } elseif ($akses->end_date && now()->startOfDay()->gt($akses->end_date)) {
                $accessMessage = 'Akses RB General telah ditutup pada ' . $akses->end_date->format('d/m/Y');
            }
        }

        // Ambil data
        $rbData = RB_General::where('tahun', $selectedYear)
            ->orWhere('tahun', null)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('opd.rb-general.index', compact(
            'rbData', 
            'selectedYear',
            'canAccess',
            'accessMessage',
            'akses'
        ));
    }

    /**
     * Simpan data baru RB General
     */
    public function store(Request $request)
    {
        // CEK AKSES - TAMBAH DATA
        $akses = AksesRb::where('jenis_rb', 'RB General')->first();
        if (!$akses || !$akses->isAccessible()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditutup. Tidak dapat menambah data baru.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'sasaran_strategi' => 'required|string|max:500',
                'indikator_capaian' => 'required|string|max:500',
                'target' => 'required|string|max:100',
                'satuan' => 'required|string|max:50',
                'rencana_aksi' => 'required|string',
                'target_tahun' => 'nullable|string|max:100',
                'anggaran_tahun' => 'nullable|string|max:100',
                'renaksi_tw1_target' => 'nullable|string|max:100',
                'renaksi_tw2_target' => 'nullable|string|max:100',
                'renaksi_tw3_target' => 'nullable|string|max:100',
                'renaksi_tw4_target' => 'nullable|string|max:100',
                'realisasi_tw1_target' => 'nullable|string|max:100',
                'realisasi_tw1_rp' => 'nullable|string|max:100',
                'realisasi_tw2_target' => 'nullable|string|max:100',
                'realisasi_tw2_rp' => 'nullable|string|max:100',
                'realisasi_tw3_target' => 'nullable|string|max:100',
                'realisasi_tw3_rp' => 'nullable|string|max:100',
                'realisasi_tw4_target' => 'nullable|string|max:100',
                'realisasi_tw4_rp' => 'nullable|string|max:100',
                'rumus' => 'nullable|string|max:500',
                'catatan_evaluasi' => 'nullable|string',
                'catatan_perbaikan' => 'nullable|string',
                'unit_kerja' => 'required|string|max:200',
                'tw1_rp' => 'nullable|string|max:100',
                'tw2_rp' => 'nullable|string|max:100',
                'tw3_rp' => 'nullable|string|max:100',
                'tw4_rp' => 'nullable|string|max:100',
                'no' => 'nullable|string|max:20',
                'satuan_output' => 'nullable|string|max:50',
                'indikator_output' => 'nullable|string|max:500',
                'pelaksana' => 'nullable|string|max:200',
            ]);

            $tahun = $request->tahun ?: date('Y');

            $rbGeneral = RB_General::create([
                'tahun' => $tahun,
                'sasaran_strategi' => $validated['sasaran_strategi'],
                'indikator_capaian' => $validated['indikator_capaian'],
                'target' => $validated['target'],
                'satuan' => $validated['satuan'],
                'rencana_aksi' => $validated['rencana_aksi'],
                'target_tahun' => $validated['target_tahun'] ?? null,
                'anggaran_tahun' => $validated['anggaran_tahun'] ?? null,
                'renaksi_tw1_target' => $validated['renaksi_tw1_target'] ?? null,
                'renaksi_tw2_target' => $validated['renaksi_tw2_target'] ?? null,
                'renaksi_tw3_target' => $validated['renaksi_tw3_target'] ?? null,
                'renaksi_tw4_target' => $validated['renaksi_tw4_target'] ?? null,
                'realisasi_tw1_target' => $validated['realisasi_tw1_target'] ?? null,
                'realisasi_tw1_rp' => $validated['realisasi_tw1_rp'] ?? null,
                'realisasi_tw2_target' => $validated['realisasi_tw2_target'] ?? null,
                'realisasi_tw2_rp' => $validated['realisasi_tw2_rp'] ?? null,
                'realisasi_tw3_target' => $validated['realisasi_tw3_target'] ?? null,
                'realisasi_tw3_rp' => $validated['realisasi_tw3_rp'] ?? null,
                'realisasi_tw4_target' => $validated['realisasi_tw4_target'] ?? null,
                'realisasi_tw4_rp' => $validated['realisasi_tw4_rp'] ?? null,
                'rumus' => $validated['rumus'] ?? null,
                'catatan_evaluasi' => $validated['catatan_evaluasi'] ?? null,
                'catatan_perbaikan' => $validated['catatan_perbaikan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'],
                'tw1_rp' => $validated['tw1_rp'] ?? null,
                'tw2_rp' => $validated['tw2_rp'] ?? null,
                'tw3_rp' => $validated['tw3_rp'] ?? null,
                'tw4_rp' => $validated['tw4_rp'] ?? null,
                'no' => $validated['no'] ?? null,
                'satuan_output' => $validated['satuan_output'] ?? null,
                'indikator_output' => $validated['indikator_output'] ?? null,
                'pelaksana' => $validated['pelaksana'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data RB General berhasil disimpan',
                'data' => $rbGeneral
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get data untuk detail modal (AJAX)
     */
    public function show($id)
    {
        try {
            $rb = RB_General::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $rb->id,
                    'no' => $rb->no,
                    'sasaranStrategi' => $rb->sasaran_strategi,
                    'indikator' => $rb->indikator_capaian,
                    'target' => $rb->target,
                    'satuan' => $rb->satuan,
                    'rencanaAksi' => $rb->rencana_aksi,
                    'targetTahun' => $rb->target_tahun,
                    'anggaran_tahun' => $rb->anggaran_tahun,
                    'satuanOutput' => $rb->satuan_output,
                    'indikatorOutput' => $rb->indikator_output,
                    'renaksiTw1Target' => $rb->renaksi_tw1_target,
                    'tw1Rp' => $rb->tw1_rp,
                    'renaksiTw2Target' => $rb->renaksi_tw2_target,
                    'tw2Rp' => $rb->tw2_rp,
                    'renaksiTw3Target' => $rb->renaksi_tw3_target,
                    'tw3Rp' => $rb->tw3_rp,
                    'renaksiTw4Target' => $rb->renaksi_tw4_target,
                    'tw4Rp' => $rb->tw4_rp,
                    'realisasiTw1Target' => $rb->realisasi_tw1_target,
                    'realisasiTw1Rp' => $rb->realisasi_tw1_rp,
                    'realisasiTw2Target' => $rb->realisasi_tw2_target,
                    'realisasiTw2Rp' => $rb->realisasi_tw2_rp,
                    'realisasiTw3Target' => $rb->realisasi_tw3_target,
                    'realisasiTw3Rp' => $rb->realisasi_tw3_rp,
                    'realisasiTw4Target' => $rb->realisasi_tw4_target,
                    'realisasiTw4Rp' => $rb->realisasi_tw4_rp,
                    'rumus' => $rb->rumus,
                    'catatanEvaluasi' => $rb->catatan_evaluasi,
                    'catatanPerbaikan' => $rb->catatan_perbaikan,
                    'unitKerja' => $rb->unit_kerja,
                    'pelaksana' => $rb->pelaksana,
                    'tahun' => $rb->tahun,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }
    
    /**
     * Get data untuk edit modal (AJAX)
     */
    public function edit($id)
    {
        try {
            $rb = RB_General::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $rb->id,
                    'no' => $rb->no,
                    'sasaran_strategi' => $rb->sasaran_strategi,
                    'indikator_capaian' => $rb->indikator_capaian,
                    'target' => $rb->target,
                    'satuan' => $rb->satuan,
                    'rencana_aksi' => $rb->rencana_aksi,
                    'target_tahun' => $rb->target_tahun,
                    'anggaran_tahun' => $rb->anggaran_tahun,
                    'satuan_output' => $rb->satuan_output,
                    'indikator_output' => $rb->indikator_output,
                    'renaksi_tw1_target' => $rb->renaksi_tw1_target,
                    'renaksi_tw2_target' => $rb->renaksi_tw2_target,
                    'renaksi_tw3_target' => $rb->renaksi_tw3_target,
                    'renaksi_tw4_target' => $rb->renaksi_tw4_target,
                    'tw1_rp' => $rb->tw1_rp,
                    'tw2_rp' => $rb->tw2_rp,
                    'tw3_rp' => $rb->tw3_rp,
                    'tw4_rp' => $rb->tw4_rp,
                    'realisasi_tw1_target' => $rb->realisasi_tw1_target,
                    'realisasi_tw1_rp' => $rb->realisasi_tw1_rp,
                    'realisasi_tw2_target' => $rb->realisasi_tw2_target,
                    'realisasi_tw2_rp' => $rb->realisasi_tw2_rp,
                    'realisasi_tw3_target' => $rb->realisasi_tw3_target,
                    'realisasi_tw3_rp' => $rb->realisasi_tw3_rp,
                    'realisasi_tw4_target' => $rb->realisasi_tw4_target,
                    'realisasi_tw4_rp' => $rb->realisasi_tw4_rp,
                    'rumus' => $rb->rumus,
                    'catatan_evaluasi' => $rb->catatan_evaluasi,
                    'catatan_perbaikan' => $rb->catatan_perbaikan,
                    'unit_kerja' => $rb->unit_kerja,
                    'pelaksana' => $rb->pelaksana,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update data RB General
     */
    public function update(Request $request, $id)
    {
        // CEK AKSES - UPDATE DATA
        $akses = AksesRb::where('jenis_rb', 'RB General')->first();
        if (!$akses || !$akses->isAccessible()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditutup. Tidak dapat mengubah data.'
            ], 403);
        }
        
        try {
            $validated = $request->validate([
                'sasaran_strategi' => 'required|string|max:500',
                'indikator_capaian' => 'required|string|max:500',
                'target' => 'required|string|max:100',
                'satuan' => 'required|string|max:50',
                'rencana_aksi' => 'required|string',
                'target_tahun' => 'nullable|string|max:100',
                'anggaran_tahun' => 'nullable|string|max:100',
                'renaksi_tw1_target' => 'nullable|string|max:100',
                'renaksi_tw2_target' => 'nullable|string|max:100',
                'renaksi_tw3_target' => 'nullable|string|max:100',
                'renaksi_tw4_target' => 'nullable|string|max:100',
                'realisasi_tw1_target' => 'nullable|string|max:100',
                'realisasi_tw1_rp' => 'nullable|string|max:100',
                'realisasi_tw2_target' => 'nullable|string|max:100',
                'realisasi_tw2_rp' => 'nullable|string|max:100',
                'realisasi_tw3_target' => 'nullable|string|max:100',
                'realisasi_tw3_rp' => 'nullable|string|max:100',
                'realisasi_tw4_target' => 'nullable|string|max:100',
                'realisasi_tw4_rp' => 'nullable|string|max:100',
                'rumus' => 'nullable|string|max:500',
                'catatan_evaluasi' => 'nullable|string',
                'catatan_perbaikan' => 'nullable|string',
                'unit_kerja' => 'required|string|max:200',
                'tw1_rp' => 'nullable|string|max:100',
                'tw2_rp' => 'nullable|string|max:100',
                'tw3_rp' => 'nullable|string|max:100',
                'tw4_rp' => 'nullable|string|max:100',
                'no' => 'nullable|string|max:20',
                'satuan_output' => 'nullable|string|max:50',
                'indikator_output' => 'nullable|string|max:500',
                'pelaksana' => 'nullable|string|max:200',
            ]);

            $rb = RB_General::findOrFail($id);

            $rb->update([
                'sasaran_strategi' => $validated['sasaran_strategi'],
                'indikator_capaian' => $validated['indikator_capaian'],
                'target' => $validated['target'],
                'satuan' => $validated['satuan'],
                'rencana_aksi' => $validated['rencana_aksi'],
                'target_tahun' => $validated['target_tahun'] ?? null,
                'anggaran_tahun' => $validated['anggaran_tahun'] ?? null,
                'renaksi_tw1_target' => $validated['renaksi_tw1_target'] ?? null,
                'renaksi_tw2_target' => $validated['renaksi_tw2_target'] ?? null,
                'renaksi_tw3_target' => $validated['renaksi_tw3_target'] ?? null,
                'renaksi_tw4_target' => $validated['renaksi_tw4_target'] ?? null,
                'realisasi_tw1_target' => $validated['realisasi_tw1_target'] ?? null,
                'realisasi_tw1_rp' => $validated['realisasi_tw1_rp'] ?? null,
                'realisasi_tw2_target' => $validated['realisasi_tw2_target'] ?? null,
                'realisasi_tw2_rp' => $validated['realisasi_tw2_rp'] ?? null,
                'realisasi_tw3_target' => $validated['realisasi_tw3_target'] ?? null,
                'realisasi_tw3_rp' => $validated['realisasi_tw3_rp'] ?? null,
                'realisasi_tw4_target' => $validated['realisasi_tw4_target'] ?? null,
                'realisasi_tw4_rp' => $validated['realisasi_tw4_rp'] ?? null,
                'rumus' => $validated['rumus'] ?? null,
                'catatan_evaluasi' => $validated['catatan_evaluasi'] ?? null,
                'catatan_perbaikan' => $validated['catatan_perbaikan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'],
                'tw1_rp' => $validated['tw1_rp'] ?? null,
                'tw2_rp' => $validated['tw2_rp'] ?? null,
                'tw3_rp' => $validated['tw3_rp'] ?? null,
                'tw4_rp' => $validated['tw4_rp'] ?? null,
                'no' => $validated['no'] ?? null,
                'satuan_output' => $validated['satuan_output'] ?? null,
                'indikator_output' => $validated['indikator_output'] ?? null,
                'pelaksana' => $validated['pelaksana'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data RB General berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus data RB General
     */
    public function destroy($id)
    {
        // CEK AKSES - HAPUS DATA
        $akses = AksesRb::where('jenis_rb', 'RB General')->first();
        if (!$akses || !$akses->isAccessible()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditutup. Tidak dapat menghapus data.'
            ], 403);
        }
        
        try {
            $rb = RB_General::findOrFail($id);
            $rb->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data RB General berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}