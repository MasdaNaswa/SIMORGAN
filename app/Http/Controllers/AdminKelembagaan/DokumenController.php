<?php

namespace App\Http\Controllers\AdminKelembagaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Laporan;

class DokumenController extends Controller
{
    // Tampilkan semua dokumen OPD dengan filter kategori
    public function index(Request $request)
    {
        $selectedKategori = $request->get('kategori', 'semua');

        // Urutan OPD FIX sesuai daftar
        $urutanOPD = [
            "Sekretariat Daerah",
            "Sekretariat DPRD",
            "Inspektorat Daerah",
            "Dinas Pendidikan dan Kebudayaan",
            "Dinas Kesehatan",
            "Rumah Sakit Umum Daerah Muhammad Sani",
            "Rumah Sakit Umum Daerah Tanjung Batu Kundur",
            "Dinas Pekerjaan Umum dan Penataan Ruang",
            "Dinas Perumahan Rakyat dan Kawasan Pemukiman",
            "Dinas Sosial",
            "Dinas Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan dan Perlindungan Anak",
            "Dinas Lingkungan Hidup",
            "Dinas Kependudukan dan Pencatatan Sipil",
            "Dinas Pemberdayaan Masyarakat dan Desa",
            "Dinas Perhubungan",
            "Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu",
            "Dinas Kepemudaan dan Olahraga",
            "Dinas Pariwisata",
            "Dinas Perpustakaan dan Kearsipan",
            "Dinas Perikanan",
            "Dinas Pangan dan Pertanian",
            "Dinas Koperasi Usaha Mikro, Perdagangan dan Energi Sumber Daya Mineral",
            "Dinas Tenaga Kerja dan Perindustrian",
            "Diskominfo",
            "Satuan Polisi Pamong Praja",
            "Badan Perencanaan, Penelitian dan Pengembangan",
            "Badan Pendapatan Daerah",
            "Badan Pengelola Keuangan dan Aset Daerah",
            "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
            "Badan Kesatuan Bangsa dan Politik",
            "Badan Penanggulangan Bencana Daerah dan Pemadam Kebakaran",
            "Kecamatan Karimun",
            "Kecamatan Tebing",
            "Kecamatan Meral",
            "Kecamatan Meral Barat",
            "Kecamatan Buru",
            "Kecamatan Kundur",
            "Kecamatan Kundur Barat",
            "Kecamatan Kundur Utara",
            "Kecamatan Belat",
            "Kecamatan Ungar",
            "Kecamatan Moro",
            "Kecamatan Durai",
            "Kecamatan Selat Gelam",
            "Kecamatan Sugie Besar"
        ];

        // Query berdasarkan kategori yang dipilih
        $query = Laporan::with('user')
            ->whereIn('kategori', ['Petajab', 'Anjab & ABK', 'Evaluasi Jabatan']);

        if ($selectedKategori !== 'semua') {
            $query->where('kategori', $selectedKategori);
        }

        $dokumen = $query->get()->map(function ($item) {
            if ($item->user && isset($item->user->nama_opd)) {
                $cleanName = preg_replace('/[\x00-\x1F\x7F\x{200B}\x{00A0}]/u', '', $item->user->nama_opd);
                $item->user->nama_opd = trim($cleanName);
            }
            return $item;
        });

        // Urutkan sesuai $urutanOPD
        $dokumen = $dokumen->sortBy(function ($item) use ($urutanOPD) {
            $namaOpd = $item->user->nama_opd ?? '';
            $pos = array_search($namaOpd, $urutanOPD);
            return $pos !== false ? $pos : 999;
        })->values();

        // Pagination manual 10 per halaman
        $perPage = 10;
        $page = $request->get('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $dokumen->forPage($page, $perPage),
            $dokumen->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('adminkelembagaan.dokumen.index', [
            'dokumen' => $paginated,
            'selectedKategori' => $selectedKategori
        ]);
    }

    // Hapus dokumen
    public function destroy($id)
    {
        $dokumen = Laporan::findOrFail($id);
        
        // Hapus file dari storage
        $filePath = storage_path('app/public/laporan/' . $dokumen->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $dokumen->delete();

        return back();
    }

    // Update status & catatan dokumen
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $dokumen = Laporan::findOrFail($id);
        $dokumen->status = $request->status;
        
        if ($request->status === 'Revisi') {
            $dokumen->catatan = $request->catatan;
        } else {
            $dokumen->catatan = null;
        }
        
        $dokumen->save();

        return back();
    }
    
    // Preview dokumen
    public function preview($judul)
    {
        $laporan = Laporan::where('judul', $judul)->firstOrFail();

        $filePath = 'laporan/' . $laporan->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $fullPath = Storage::disk('public')->path($filePath);

        return response()->file($fullPath, [
            "Content-Disposition" => "inline; filename=\"{$laporan->file_path}\""
        ]);
    }
}