<?php

namespace App\Http\Controllers\AdminPelayananPublik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Template_Laporan;
use App\Models\Laporan;

class LaporanController extends Controller
{
    // Daftar kategori yang akan ditampilkan sebagai tab
    private $kategoriTabs = [
        'Semua' => 'Semua Laporan',
        'Laporan FKP' => 'Laporan FKP',
        'SOP' => 'SOP',
        'Probis' => 'Probis',
        'SK Tim Kerja' => 'SK Tim Kerja',
        'Kode Etik' => 'Kode Etik',
        'Inovasi OPD' => 'Inovasi OPD',
        'Tindak Lanjut FKP' => 'Tindak Lanjut FKP',
        'Laporan SKM' => 'Laporan SKM',
        'Data Lain Lainnya' => 'Data Lain Lainnya'
    ];

    public function index(Request $request)
    {
        // Ambil kategori yang dipilih dari tab (default: semua)
        $selectedKategori = $request->get('kategori', 'semua');

        // Query laporan
        $query = Laporan::with(['user', 'skmReport'])
            ->whereNotIn('kategori', ['Petajab', 'Anjab & ABK', 'Evajab'])
            ->orderBy('tanggal_upload', 'desc');

        // Filter berdasarkan kategori jika dipilih dan bukan 'semua'
        if ($selectedKategori !== 'semua') {
            if ($selectedKategori === 'Lainnya') {
                // Kategori "Lainnya" adalah semua kategori yang tidak termasuk dalam daftar utama
                $mainCategories = array_keys(array_slice($this->kategoriTabs, 1, -1)); // exclude 'semua' dan 'Lainnya'
                $query->whereNotIn('kategori', $mainCategories);
            } else {
                $query->where('kategori', $selectedKategori);
            }
        }

        $laporans = $query->paginate(10);

        // Decode JSON untuk SKM
        foreach ($laporans as $laporan) {
            if ($laporan->kategori === 'Laporan SKM' && $laporan->skmReport) {
                $laporan->skmReport->anggota_tim = json_decode($laporan->skmReport->anggota_tim, true) ?? [];
                $laporan->skmReport->rencana_tindak_lanjut = json_decode($laporan->skmReport->rencana_tindak_lanjut, true) ?? [];
            }
        }

        // Hitung jumlah laporan per kategori untuk badge
        $countPerKategori = [];
        foreach ($this->kategoriTabs as $key => $label) {
            if ($key === 'semua') {
                $countPerKategori[$key] = Laporan::whereNotIn('kategori', ['Petajab', 'Anjab & ABK', 'Evajab'])->count();
            } elseif ($key === 'Lainnya') {
                $mainCategories = array_keys(array_slice($this->kategoriTabs, 1, -1));
                $countPerKategori[$key] = Laporan::whereNotIn('kategori', $mainCategories)
                    ->whereNotIn('kategori', ['Petajab', 'Anjab & ABK', 'Evajab'])
                    ->count();
            } else {
                $countPerKategori[$key] = Laporan::where('kategori', $key)
                    ->whereNotIn('kategori', ['Petajab', 'Anjab & ABK', 'Evajab'])
                    ->count();
            }
        }

        return view('adminpelayananpublik.laporan.index', compact(
            'laporans',
            'selectedKategori',
            'countPerKategori'
        ));
    }

    // Method lainnya tetap sama...
    public function detail($id)
    {
        $laporan = Laporan::with(['user', 'skmReport'])->findOrFail($id);

        if ($laporan->kategori === 'Laporan SKM' && $laporan->skmReport) {
            $laporan->skmReport->anggota_tim = json_decode($laporan->skmReport->anggota_tim, true) ?? [];
            $laporan->skmReport->rencana_tindak_lanjut = json_decode($laporan->skmReport->rencana_tindak_lanjut, true) ?? [];
        }

        return view('adminpelayananpublik.laporan.detail', compact('laporan'));
    }

    public function verifikasi(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Diproses,Disetujui,Ditolak,Revisi',
            'catatan' => 'nullable|string|max:1000'
        ]);

        $laporan->status = $request->status;

        if ($laporan->status !== 'Revisi') {
            $laporan->catatan = null;
        } else {
            $laporan->catatan = $request->catatan;
        }

        $laporan->save();

        $kategori = request()->get('kategori', 'semua');
        return redirect()->route('adminpelayananpublik.laporan.index', ['kategori' => $kategori])
            ->with('success', "Status laporan berhasil diperbarui.");
    }

    public function hapus($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->kategori === 'Laporan SKM') {
            $filePath = storage_path('app/public/laporan/skm/' . basename($laporan->file_path));
        } else {
            $filePath = storage_path('app/public/laporan/' . $laporan->file_path);
        }

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $laporan->delete();

        $kategori = request()->get('kategori', 'semua');
        return redirect()->route('adminpelayananpublik.laporan.index', ['kategori' => $kategori])
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function edit($id)
    {
        $laporan = Laporan::with('skmReport')->findOrFail($id);

        if ($laporan->kategori === 'Laporan SKM' && $laporan->skmReport) {
            $laporan->skmReport->anggota_tim = json_decode($laporan->skmReport->anggota_tim, true) ?? [];
            $laporan->skmReport->rencana_tindak_lanjut = json_decode($laporan->skmReport->rencana_tindak_lanjut, true) ?? [];
        }

        return view('adminpelayananpublik.laporan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Diproses,Disetujui,Ditolak,Revisi',
            'catatan' => 'nullable|string|max:1000'
        ]);

        $laporan->status = $request->status;

        if ($laporan->status !== 'Revisi') {
            $laporan->catatan = null;
        } else {
            $laporan->catatan = $request->catatan;
        }

        $laporan->save();

        $kategori = request()->get('kategori', 'semua');
        return redirect()->route('adminpelayananpublik.laporan.index', ['kategori' => $kategori])
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function download($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->kategori === 'Laporan SKM') {
            $filePath = storage_path('app/public/laporan/skm/' . basename($laporan->file_path));
        } else {
            $filePath = storage_path('app/public/laporan/' . $laporan->file_path);
        }

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, $laporan->judul . '.pdf');
    }

    public function viewPdf($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->kategori === 'Laporan SKM') {
            $filePath = storage_path('app/public/laporan/skm/' . basename($laporan->file_path));
        } else {
            $filePath = storage_path('app/public/laporan/' . $laporan->file_path);
        }

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }
}