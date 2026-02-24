<?php

namespace App\Http\Controllers\AdminPelayananPublik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Template_Laporan;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index()
    {
        // Ambil semua kategori & template (untuk tampilan dropdown/menu)
        $kategories = Kategori::orderBy('nama_kategori')->get();
        $templates = Template_Laporan::with('kategori')->get();

        // Ambil semua laporan, termasuk relasi user dan skmReport
        $laporans = Laporan::with(['user', 'skmReport'])
            ->whereNotIn('kategori', ['Petajab', 'Anjab & ABK', 'Evajab']) // kecuali kategori internal
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(10);



        // Decode JSON anggota_tim & rencana_tindak_lanjut untuk SKM
        foreach ($laporans as $laporan) {
            if ($laporan->kategori === 'SKM' && $laporan->skmReport) {
                $laporan->skmReport->anggota_tim = json_decode($laporan->skmReport->anggota_tim, true) ?? [];
                $laporan->skmReport->rencana_tindak_lanjut = json_decode($laporan->skmReport->rencana_tindak_lanjut, true) ?? [];
            }
        }

        return view('adminpelayananpublik.laporan.index', compact(
            'kategories',
            'templates',
            'laporans'
        ));
    }

    public function detail($id)
    {
        $laporan = Laporan::with(['user', 'skmReport'])->findOrFail($id);

        if ($laporan->kategori === 'SKM' && $laporan->skmReport) {
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

        return redirect()->back()->with('success', "Status laporan berhasil diperbarui.");
    }

    public function hapus($id)
    {
        $laporan = Laporan::findOrFail($id);

        // Tentukan path file
        if ($laporan->kategori === 'SKM') {
            $filePath = storage_path('app/public/laporan/skm/' . basename($laporan->file_path));
        } else {
            $filePath = storage_path('app/public/laporan/' . $laporan->file_path);
        }

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $laporan->delete();

        return redirect()->route('adminpelayananpublik.laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function edit($id)
    {
        $laporan = Laporan::with('skmReport')->findOrFail($id);

        if ($laporan->kategori === 'SKM' && $laporan->skmReport) {
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

        return redirect()->route('adminpelayananpublik.laporan.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function download($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->kategori === 'SKM') {
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

        if ($laporan->kategori === 'SKM') {
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
