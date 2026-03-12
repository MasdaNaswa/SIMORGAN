<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\AksesRb;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // CEK DAN UPDATE STATUS YANG EXPIRED TERLEBIH DAHULU
        $this->checkExpiredStatus();
        
        $user = Auth::user();
        $nama_opd = $user->nama_opd ?? $user->name;

        // Ambil data akses RB dari database
        $aksesGeneral = AksesRb::where('jenis_rb', 'RB General')->first();
        $aksesTematik = AksesRb::where('jenis_rb', 'RB Tematik')->first();
        $aksesPK = AksesRb::where('jenis_rb', 'PK Bupati')->first();

        // Tentukan status dan deadline
        $statusGeneral = $aksesGeneral ? $aksesGeneral->status : 'Ditutup';
        $statusTematik = $aksesTematik ? $aksesTematik->status : 'Ditutup';
        $statusPK = $aksesPK ? $aksesPK->status : 'Ditutup';

        // Deadline
        $deadlineGeneral = $aksesGeneral && $aksesGeneral->end_date 
            ? \Carbon\Carbon::parse($aksesGeneral->end_date)->format('d/m/Y') 
            : 'Tidak ada';
        $deadlineTematik = $aksesTematik && $aksesTematik->end_date 
            ? \Carbon\Carbon::parse($aksesTematik->end_date)->format('d/m/Y') 
            : 'Tidak ada';
        $deadlinePK = $aksesPK && $aksesPK->end_date 
            ? \Carbon\Carbon::parse($aksesPK->end_date)->format('d/m/Y') 
            : 'Tidak ada';

        // Status umum
        if ($statusGeneral === 'Ditutup' && $statusTematik === 'Ditutup' && $statusPK === 'Ditutup') {
            $statusUmum = 'Ditutup';
        } elseif ($statusGeneral === 'Dibuka' || $statusTematik === 'Dibuka' || $statusPK === 'Dibuka') {
            $statusUmum = 'Sebagian Dibuka';
        } else {
            $statusUmum = 'Aktif';
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
            'statusGeneral',
            'statusTematik',
            'statusPK',
            'deadlineGeneral',
            'deadlineTematik',
            'deadlinePK',
            'statusUmum'
        ));
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
}