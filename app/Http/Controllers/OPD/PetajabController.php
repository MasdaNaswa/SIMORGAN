<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Laporan;

class PetajabController extends Controller
{
    /**
     * Tampilkan halaman index Peta Jabatan
     */
    public function index()
    {
        // Ambil laporan user yang kategori Petajab
        $laporan = Laporan::where('id_user', Auth::id())
            ->where('kategori', 'Petajab')
            ->latest('tanggal_upload')
            ->paginate(10);

        return view('opd.petajab.index', compact('laporan'));
    }

    /**
     * Upload laporan Peta Jabatan
     */
    public function laporanUpload(Request $request)
    {
        $request->validate([
            'laporan' => 'required|mimes:doc,docx,pdf,xls,xlsx|max:10240',
        ]);

        $file = $request->file('laporan');

        if (!$file || !$file->isValid()) {
            return back();
        }

        // Nama file unik
        $filename = time() . '_' . $file->getClientOriginalName();
        $destination = storage_path('app/public/laporan');

        // Buat folder jika belum ada
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        // Pindahkan file ke folder
        $file->move($destination, $filename);

        // Simpan ke database
        Laporan::create([
            'id_user' => Auth::id(),
            'judul' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'kategori' => 'Petajab',
            'file_path' => $filename,
            'tanggal_upload' => now(),
            'status' => 'Diproses',
        ]);

        return back();
    }

    /**
     * Download laporan Peta Jabatan
     */
    public function laporanDownload($id)
    {
        $laporan = Laporan::findOrFail($id);
        $path = storage_path('app/public/laporan/' . $laporan->file_path);

        if (!file_exists($path)) {
            return back();
        }

        return response()->download(
            $path,
            $laporan->judul . '.' . pathinfo($laporan->file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Hapus laporan Peta Jabatan
     */
    public function laporanDestroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $filePath = storage_path('app/public/laporan/' . $laporan->file_path);

        // Hapus file jika ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus data dari database
        $laporan->delete();

        return back();
    }
}