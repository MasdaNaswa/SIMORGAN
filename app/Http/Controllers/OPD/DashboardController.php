<?php
// app/Http/Controllers/OPD/DashboardController.php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\AksesRb; // Tambahkan model AksesRb
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $nama_opd = $user->nama_opd ?? $user->name;

        // Ambil data akses RB dari database
        $aksesGeneral = AksesRb::where('jenis_rb', 'RB General')->first();
        $aksesTematik = AksesRb::where('jenis_rb', 'RB Tematik')->first();
        $aksesPK = AksesRb::where('jenis_rb', 'PK Bupati')->first();

        // Tentukan status dan deadline
        $statusGeneral = $aksesGeneral ? $aksesGeneral->status : 'Dibuka';
        $statusTematik = $aksesTematik ? $aksesTematik->status : 'Dibuka';
        $statusPK = $aksesPK ? $aksesPK->status : 'Dibuka';

        // Deadline
        $deadlineGeneral = $aksesGeneral && $aksesGeneral->end_date 
            ? $aksesGeneral->end_date->format('d/m/Y') 
            : 'Tidak ada';
        $deadlineTematik = $aksesTematik && $aksesTematik->end_date 
            ? $aksesTematik->end_date->format('d/m/Y') 
            : 'Tidak ada';
        $deadlinePK = $aksesPK && $aksesPK->end_date 
            ? $aksesPK->end_date->format('d/m/Y') 
            : 'Tidak ada';

        // Status umum (untuk card)
        $statusUmum = 'Aktif';
        if ($statusGeneral === 'Ditutup' && $statusTematik === 'Ditutup' && $statusPK === 'Ditutup') {
            $statusUmum = 'Ditutup';
        } elseif ($statusGeneral === 'Dibuka' || $statusTematik === 'Dibuka' || $statusPK === 'Dibuka') {
            $statusUmum = 'Sebagian Dibuka';
        }

        // Kategori Kelembagaan
        $subKategoriKelembagaan = ['Anjab & ABK','Petajab','Evajab'];

        // Filter dokumen hanya milik OPD yang login
        $totalDokumenKelembagaan = Laporan::whereIn('kategori', $subKategoriKelembagaan)
            ->whereHas('user', function($query) use ($nama_opd) {
                $query->where('nama_opd', $nama_opd);
            })->count();

        $dokumenProsesKelembagaan = Laporan::whereIn('kategori', $subKategoriKelembagaan)
            ->where('status', 'Diproses')
            ->whereHas('user', function($query) use ($nama_opd) {
                $query->where('nama_opd', $nama_opd);
            })->count();

        $dokumenDirevisiKelembagaan = Laporan::whereIn('kategori', $subKategoriKelembagaan)
            ->where('status', 'Direvisi')
            ->whereHas('user', function($query) use ($nama_opd) {
                $query->where('nama_opd', $nama_opd);
            })->count();

        $dokumenDisetujuiKelembagaan = Laporan::whereIn('kategori', $subKategoriKelembagaan)
            ->where('status', 'Disetujui')
            ->whereHas('user', function($query) use ($nama_opd) {
                $query->where('nama_opd', $nama_opd);
            })->count();

        // Kategori Pelayanan Publik
        $totalDokumenPublik = Laporan::whereNotIn('kategori', $subKategoriKelembagaan)
            ->whereHas('user', function($query) use ($nama_opd) {
                $query->where('nama_opd', $nama_opd);
            })->count();

        $dokumenProsesPublik = Laporan::whereNotIn('kategori', $subKategoriKelembagaan)
            ->where('status', 'Diproses')
            ->whereHas('user', function($query) use ($nama_opd) {
                $query->where('nama_opd', $nama_opd);
            })->count();

        $dokumenDirevisiPublik = Laporan::whereNotIn('kategori', $subKategoriKelembagaan)
            ->where('status', 'Direvisi')
            ->whereHas('user', function($query) use ($nama_opd) {
                $query->where('nama_opd', $nama_opd);
            })->count();

        $dokumenDisetujuiPublik = Laporan::whereNotIn('kategori', $subKategoriKelembagaan)
            ->where('status', 'Disetujui')
            ->whereHas('user', function($query) use ($nama_opd) {
                $query->where('nama_opd', $nama_opd);
            })->count();

        return view('opd.dashboard', compact(
            'nama_opd',
            'totalDokumenKelembagaan',
            'dokumenProsesKelembagaan',
            'dokumenDirevisiKelembagaan',
            'dokumenDisetujuiKelembagaan',
            'totalDokumenPublik',
            'dokumenProsesPublik',
            'dokumenDirevisiPublik',
            'dokumenDisetujuiPublik',
            // Tambahkan data akses RB
            'statusGeneral',
            'statusTematik',
            'statusPK',
            'deadlineGeneral',
            'deadlineTematik',
            'deadlinePK',
            'statusUmum'
        ));
    }
}