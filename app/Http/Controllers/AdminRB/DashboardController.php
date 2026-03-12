<?php

namespace App\Http\Controllers\AdminRB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;
use App\Models\RB_General;
use App\Models\RB_Tematik;
use App\Models\PK_Bupati;
use App\Models\AksesRb;

class DashboardController extends Controller
{
    public function index()
    {
        // CEK DAN UPDATE STATUS YANG EXPIRED TERLEBIH DAHULU
        $this->checkExpiredStatus();
        
        // Total akun OPD yang dibuat oleh role user saat ini
        $totalAkun = Pengguna::where('role', 'OPD')
            ->where('created_by', Auth::user()->role)
            ->count();

        // Total RB masuk dari semua jenis
        $totalRBGeneral = RB_General::count();
        $totalRBTematik = RB_Tematik::count();
        $totalRBPK = PK_Bupati::count();
        $totalRB = $totalRBGeneral + $totalRBTematik + $totalRBPK;

        // Ambil data akses RB dari database
        $aksesGeneral = AksesRb::where('jenis_rb', 'RB General')->first();
        $aksesTematik = AksesRb::where('jenis_rb', 'RB Tematik')->first();
        $aksesPK = AksesRb::where('jenis_rb', 'PK Bupati')->first();

        // Jika data masih kosong, buat default
        if (!$aksesGeneral || !$aksesTematik || !$aksesPK) {
            $this->createDefaultAkses();
            
            // Ambil ulang data setelah dibuat
            $aksesGeneral = AksesRb::where('jenis_rb', 'RB General')->first();
            $aksesTematik = AksesRb::where('jenis_rb', 'RB Tematik')->first();
            $aksesPK = AksesRb::where('jenis_rb', 'PK Bupati')->first();
        }

        // Tentukan status
        $statusGeneral = $aksesGeneral ? $aksesGeneral->status : 'Ditutup';
        $statusTematik = $aksesTematik ? $aksesTematik->status : 'Ditutup';
        $statusPK = $aksesPK ? $aksesPK->status : 'Ditutup';

        // Format deadline
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

        // Ambil semua data akses untuk ditampilkan di timeline
        $aksesRb = AksesRb::orderBy('id')->get();

        return view('adminrb.dashboard', compact(
            'totalAkun',
            'totalRB',
            'totalRBGeneral',
            'totalRBTematik',
            'totalRBPK',
            'statusUmum',
            'statusGeneral',
            'statusTematik',
            'statusPK',
            'deadlineGeneral',
            'deadlineTematik',
            'deadlinePK',
            'aksesRb'
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

    /**
     * Create default access data if not exists
     */
    private function createDefaultAkses()
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
}