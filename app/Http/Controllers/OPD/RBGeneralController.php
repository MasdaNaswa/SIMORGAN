<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use App\Models\AksesRb;
use App\Models\RB_General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RBGeneralController extends Controller
{
    /**
     * Tampilkan daftar RB General untuk OPD yang login
     */
    public function index()
    {
        $selectedYear = request()->get('year', date('Y'));
        $user = Auth::user();

        // Ambil data akses
        $akses = AksesRb::where('jenis_rb', 'RB General')->first();
        
        // Cek apakah akses bisa dibuka
        $canAccess = $akses && $akses->isAccessible();
        
        // Tentukan permission berdasarkan unit_kerja
        $isInspektorat = $user->isInspektorat();
        
        // Ambil data sesuai role
        if ($isInspektorat) {
            // INSPEKTORAT: Lihat SEMUA data dari semua OPD
            $rbData = RB_General::where('tahun', $selectedYear)
                ->orderBy('unit_kerja')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // OPD LAIN: Hanya lihat data yang mereka input sendiri (berdasarkan opd_penginput)
            $rbData = RB_General::where('tahun', $selectedYear)
                ->where('opd_penginput', $user->unit_kerja)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('opd.rb-general.index', compact(
            'rbData', 
            'selectedYear',
            'canAccess',
            'isInspektorat',
            'user'
        ));
    }

    /**
     * Simpan data baru RB General
     * Hanya untuk OPD non-Inspektorat
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // CEK AKSES - Inspektorat tidak boleh menambah data
        if ($user->isInspektorat()) {
            return response()->json([
                'success' => false,
                'message' => 'Inspektorat tidak memiliki izin untuk menambah data.'
            ], 403);
        }

        // CEK AKSES - Cek apakah akses dibuka
        $akses = AksesRb::where('jenis_rb', 'RB General')->first();
        if (!$akses || !$akses->isAccessible()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditutup. Tidak dapat menambah data baru.'
            ], 403);
        }

        try {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required|integer',
                'no' => 'nullable|string',
                'sasaran_strategi' => 'required|string',
                'indikator_capaian' => 'required|string',
                'target' => 'required|string',
                'satuan' => 'required|string',
                'rencana_aksi' => 'required|string',
                'target_tahun' => 'nullable|string',
                'anggaran_tahun' => 'nullable|string',
                'renaksi_tw1_target' => 'nullable|string',
                'renaksi_tw2_target' => 'nullable|string',
                'renaksi_tw3_target' => 'nullable|string',
                'renaksi_tw4_target' => 'nullable|string',
                'tw1_rp' => 'nullable|string',
                'tw2_rp' => 'nullable|string',
                'tw3_rp' => 'nullable|string',
                'tw4_rp' => 'nullable|string',
                'realisasi_tw1_target' => 'nullable|string',
                'realisasi_tw1_rp' => 'nullable|string',
                'realisasi_tw2_target' => 'nullable|string',
                'realisasi_tw2_rp' => 'nullable|string',
                'realisasi_tw3_target' => 'nullable|string',
                'realisasi_tw3_rp' => 'nullable|string',
                'realisasi_tw4_target' => 'nullable|string',
                'realisasi_tw4_rp' => 'nullable|string',
                'rumus' => 'nullable|string',
                'catatan_evaluasi' => 'nullable|string',
                'catatan_perbaikan' => 'nullable|string',
                'unit_kerja' => 'required|string',
                'satuan_output' => 'nullable|string',
                'indikator_output' => 'nullable|string',
                'pelaksana' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            
            // Tambahkan OPD yang menginput
            $validated['opd_penginput'] = $user->unit_kerja;
            
            $rbGeneral = RB_General::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data RB General berhasil disimpan',
                'data' => $rbGeneral
            ], 201);

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
            $user = Auth::user();
            
            if ($user->isInspektorat()) {
                // INSPEKTORAT: Bisa lihat semua data
                $rb = RB_General::findOrFail($id);
            } else {
                // OPD LAIN: Hanya bisa lihat data yang mereka input
                $rb = RB_General::where('id', $id)
                    ->where('opd_penginput', $user->unit_kerja)
                    ->firstOrFail();
            }

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
                    'opdPenginput' => $rb->opd_penginput,
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data untuk edit modal (AJAX)
     * Untuk Inspektorat: semua data dikirim tapi akan di-set readonly di frontend
     * Untuk OPD lain: semua data bisa diedit
     */
    public function edit($id)
    {
        try {
            $user = Auth::user();
            
            if ($user->isInspektorat()) {
                // INSPEKTORAT: Bisa edit semua data (tapi nanti di frontend di-set readonly kecuali catatan)
                $rb = RB_General::findOrFail($id);
            } else {
                // OPD LAIN: Hanya bisa edit data yang mereka input
                $rb = RB_General::where('id', $id)
                    ->where('opd_penginput', $user->unit_kerja)
                    ->firstOrFail();
            }

            // Kirim semua data, plus flag isInspektorat
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $rb->id,
                    'no' => $rb->no,
                    'tahun' => $rb->tahun,
                    'sasaran_strategi' => $rb->sasaran_strategi,
                    'indikator_capaian' => $rb->indikator_capaian,
                    'target' => $rb->target,
                    'satuan' => $rb->satuan,
                    'target_tahun' => $rb->target_tahun,
                    'rencana_aksi' => $rb->rencana_aksi,
                    'satuan_output' => $rb->satuan_output,
                    'indikator_output' => $rb->indikator_output,
                    'renaksi_tw1_target' => $rb->renaksi_tw1_target,
                    'tw1_rp' => $rb->tw1_rp,
                    'renaksi_tw2_target' => $rb->renaksi_tw2_target,
                    'tw2_rp' => $rb->tw2_rp,
                    'renaksi_tw3_target' => $rb->renaksi_tw3_target,
                    'tw3_rp' => $rb->tw3_rp,
                    'renaksi_tw4_target' => $rb->renaksi_tw4_target,
                    'tw4_rp' => $rb->tw4_rp,
                    'realisasi_tw1_target' => $rb->realisasi_tw1_target,
                    'realisasi_tw1_rp' => $rb->realisasi_tw1_rp,
                    'realisasi_tw2_target' => $rb->realisasi_tw2_target,
                    'realisasi_tw2_rp' => $rb->realisasi_tw2_rp,
                    'realisasi_tw3_target' => $rb->realisasi_tw3_target,
                    'realisasi_tw3_rp' => $rb->realisasi_tw3_rp,
                    'realisasi_tw4_target' => $rb->realisasi_tw4_target,
                    'realisasi_tw4_rp' => $rb->realisasi_tw4_rp,
                    'anggaran_tahun' => $rb->anggaran_tahun,
                    'rumus' => $rb->rumus,
                    'catatan_evaluasi' => $rb->catatan_evaluasi,
                    'catatan_perbaikan' => $rb->catatan_perbaikan,
                    'unit_kerja' => $rb->unit_kerja,
                    'pelaksana' => $rb->pelaksana,
                    'isInspektorat' => $user->isInspektorat()
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data RB General
     * Inspektorat hanya bisa update catatan, OPD lain bisa update semua
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        try {
            $rb = RB_General::findOrFail($id);

            // CEK AKSES UNTUK OPD NON-INSPEKTORAT
            if (!$user->isInspektorat()) {
                // Pastikan data milik OPD yang menginput
                if ($rb->opd_penginput !== $user->unit_kerja) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke data ini.'
                    ], 403);
                }
            }

            // CEK AKSES - Cek apakah akses dibuka
            $akses = AksesRb::where('jenis_rb', 'RB General')->first();
            if (!$akses || !$akses->isAccessible()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditutup. Tidak dapat mengubah data.'
                ], 403);
            }

            if ($user->isInspektorat()) {
                // Inspektorat: Hanya bisa update catatan
                $validator = Validator::make($request->all(), [
                    'catatan_evaluasi' => 'nullable|string',
                    'catatan_perbaikan' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $rb->update([
                    'catatan_evaluasi' => $request->catatan_evaluasi,
                    'catatan_perbaikan' => $request->catatan_perbaikan,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Catatan berhasil diupdate'
                ]);

            } else {
                // OPD lain: Bisa update semua field
                $validator = Validator::make($request->all(), [
                    'sasaran_strategi' => 'required|string',
                    'indikator_capaian' => 'required|string',
                    'target' => 'required|string',
                    'satuan' => 'required|string',
                    'rencana_aksi' => 'required|string',
                    'target_tahun' => 'nullable|string',
                    'anggaran_tahun' => 'nullable|string',
                    'renaksi_tw1_target' => 'nullable|string',
                    'renaksi_tw2_target' => 'nullable|string',
                    'renaksi_tw3_target' => 'nullable|string',
                    'renaksi_tw4_target' => 'nullable|string',
                    'tw1_rp' => 'nullable|string',
                    'tw2_rp' => 'nullable|string',
                    'tw3_rp' => 'nullable|string',
                    'tw4_rp' => 'nullable|string',
                    'realisasi_tw1_target' => 'nullable|string',
                    'realisasi_tw1_rp' => 'nullable|string',
                    'realisasi_tw2_target' => 'nullable|string',
                    'realisasi_tw2_rp' => 'nullable|string',
                    'realisasi_tw3_target' => 'nullable|string',
                    'realisasi_tw3_rp' => 'nullable|string',
                    'realisasi_tw4_target' => 'nullable|string',
                    'realisasi_tw4_rp' => 'nullable|string',
                    'rumus' => 'nullable|string',
                    'catatan_evaluasi' => 'nullable|string',
                    'catatan_perbaikan' => 'nullable|string',
                    'unit_kerja' => 'required|string',
                    'satuan_output' => 'nullable|string',
                    'indikator_output' => 'nullable|string',
                    'pelaksana' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $validated = $validator->validated();
                
                // Jangan update opd_penginput
                unset($validated['opd_penginput']);
                
                $rb->update($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Data RB General berhasil diupdate'
                ]);
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus data RB General
     * Inspektorat tidak boleh hapus data
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        try {
            // CEK AKSES - Inspektorat tidak boleh hapus data
            if ($user->isInspektorat()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inspektorat tidak memiliki izin untuk menghapus data.'
                ], 403);
            }

            // CEK AKSES - Cek apakah akses dibuka
            $akses = AksesRb::where('jenis_rb', 'RB General')->first();
            if (!$akses || !$akses->isAccessible()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditutup. Tidak dapat menghapus data.'
                ], 403);
            }

            $rb = RB_General::where('id', $id)
                ->where('opd_penginput', $user->unit_kerja)
                ->firstOrFail();

            $rb->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data RB General berhasil dihapus'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik Anda'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}