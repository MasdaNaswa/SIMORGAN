<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        $opdList = [
            'bagian_hukum_sekda' => 'Bagian Hukum Sekretariat Daerah',
            'bagian_pbj_setda' => 'Bagian PBJ Setda',
            'bagian_perekonomian_setda' => 'Bagian Perekonomian Setda',
            'bagor' => 'Bagian Organisasi Sekda',
            'bakesbangpol' => 'Bakesbangpol',
            'baperlitbang' => 'Baperlitbang',
            'bpkad' => 'BPKAD',
            'disdukcapil' => 'Dinas Kependudukan dan Pencatatan Sipil',
            'dinas_pemberdayaan_perempuan_dan_perlindungan_anak' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak',
            'diskominfo' => 'Diskominfo',
            'disnakerin' => 'Dinas Tenaga Kerja dan Perindustrian',
            'dispora' => 'Dinas Kepemudaan, dan Olahraga',
            'dispusip' => 'Dinas Perpustakaan dan Arsip Daerah',
            'dinas_kerprind' => 'Dinas Tenaga Kerja dan Perindustrian',
            'dinas_kesehatan' => 'Dinas Kesehatan',
            'dinas_koperasi' => 'Dinas Koperasi, Usaha Mikro, Perdagangan, dan ESDM',
            'dinas_lingkungan_hidup' => 'Dinas Lingkungan Hidup',
            'dinas_pangan' => 'Dinas Pangan',
            'dinas_pangan_dan_pertanian' => 'Dinas Pangan dan Pertanian',
            'dinas_pariwisata' => 'Dinas Pariwisata',
            'dinas_pemberdayaan_masyarakat_desa' => 'Dinas Pemberdayaan Masyarakat Desa',
            'dinas_pengendalian_penduduk' => 'Dinas Pengendalian Penduduk, KB, PP, dan PA',
            'dinas_perhubungan' => 'Dinas Perhubungan',
            'dinas_perikanan' => 'Dinas Perikanan',
            'dinas_perumahan_rakyat_dan_kawasan_permukiman' => 'Dinas Perumahan Rakyat dan Kawasan Pemukiman',
            'dinas_pertanian' => 'Dinas Pertanian',
            'dinas_pendidikan_dan_kebudayaan' => 'Dinas Pendidikan dan Kebudayaan',
            'dinas_pupr' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
            'dinas_sosial' => 'Dinas Sosial',
            'dpmptsp' => 'DPMPTSP',
            'inspektorat' => 'Inspektorat Daerah',
            'kecamatan_belat' => 'Kecamatan Belat',
            'kecamatan_buru' => 'Kecamatan Buru',
            'kecamatan_durai' => 'Kecamatan Durai',
            'kecamatan_karimun' => 'Kecamatan Karimun',
            'kecamatan_kundur' => 'Kecamatan Kundur',
            'kecamatan_kundur_barat' => 'Kecamatan Kundur Barat',
            'kecamatan_kundur_utara' => 'Kecamatan Kundur Utara',
            'kecamatan_meral' => 'Kecamatan Meral',
            'kecamatan_meral_barat' => 'Kecamatan Meral Barat',
            'kecamatan_moro' => 'Kecamatan Moro',
            'kecamatan_selat_gelam' => 'Kecamatan Selat Gelam',
            'kecamatan_sugie_besar' => 'Kecamatan Sugie Besar',
            'kecamatan_tebing' => 'Kecamatan Tebing',
            'kecamatan_ungar' => 'Kecamatan Ungar',
            'ptsp' => 'PTSP',
            'rsud' => 'RSUD M.SANI',
            'rsud_tj_batu_kundur' => 'RSUD Tanjung Batu Kundur',
            'satpolpp' => 'Satpol PP',
            'sekda' => 'Sekretariat Daerah',
            'sekretariat_dprd' => 'Sekretariat DPRD',
            'ukpbj' => 'UKPBJ',
            'unit_pelayan_publik' => 'Unit Pelayan Publik',
            'upt_pkm_karimun' => 'UPT PUSKESMAS KARIMUN',
            'upt_pkm_tebing' => 'UPT PUSKESMAS TEBING',
            'upt_pkm_meral' => 'UPT PUSKESMAS MERAL',
            'upt_pkm_meral_barat' => 'UPT PUSKESMAS MERAL BARAT',
            'upt_pkm_buru' => 'UPT PUSKESMAS BURU',
            'upt_pkm_tj_batu' => 'UPT PUSKESMAS TANJUNG BATU',
            'upt_pkm_kundur_barat' => 'UPT PUSKESMAS KUNDUR BARAT',
            'upt_pkm_tj_berlian' => 'UPT PUSKESMAS TANJUNG BERLIAN',
            'upt_pkm_belat' => 'UPT PUSKESMAS BELAT',
            'upt_pkm_ungar' => 'UPT PUSKESMAS UNGAR',
            'upt_pkm_moro' => 'UPT PUSKESMAS MORO',
            'upt_pkm_durai' => 'UPT PUSKESMAS DURAI',
            'upt_pkm_niur_permai' => 'UPT PUSKESMAS NIUR PERMAI',
        ];

        return view('login', compact('opdList'));
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'required|string',
        ]);

        $email = $request->email;
        $password = $request->password;
        $role = $request->role;
        $opd = $request->nama_opd ?? null;
        $bagorRole = $request->bagor_role ?? null;

        // Cari user di database
        $user = Pengguna::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
        }

        // ===== LOGIN OPD =====
        if ($role === 'opd') {
            if ($user->role !== 'OPD') {
                return back()->withErrors(['role' => 'Anda tidak memiliki akses sebagai OPD'])->withInput();
            }

            if (!$opd) {
                return back()->withErrors(['opd' => 'OPD tidak valid'])->withInput();
            }

            // Case-insensitive check untuk nama OPD
            if (strtolower($user->nama_opd) !== strtolower($this->getOpdName($opd))) {
                return back()->withErrors(['opd' => 'OPD tidak sesuai dengan akun Anda'])->withInput();
            }

            Auth::login($user);
            return redirect()->route('dashboard');
        }

        // ===== LOGIN BAGOR =====
        if ($role === 'bagor') {
            if ($bagorRole === 'adminrb' && $user->role === 'ADMIN_RB') {
                Auth::login($user);
                return redirect()->route('adminrb.dashboard');
            } elseif ($bagorRole === 'admin_pelayanan' && $user->role === 'ADMIN_PELAYANAN_PUBLIK') {
                Auth::login($user);
                return redirect()->route('adminpelayananpublik.dashboard');
            } elseif ($bagorRole === 'admin_kelembagaan' && $user->role === 'ADMIN_KELEMBAGAAN') {
                Auth::login($user);
                return redirect()->route('adminkelembagaan.dashboard');
            } else {
                return back()->withErrors(['bagor_role' => 'Role Bagor tidak valid'])->withInput();
            }
        }

        return back()->withErrors(['role' => 'Role tidak valid'])->withInput();
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    // Helper: mapping nilai select ke nama OPD sebenarnya
    private function getOpdName($opd)
    {
        $mapping = [
            'bagian_hukum_sekda' => 'Bagian Hukum Sekretariat Daerah',
            'bagian_pbj_setda' => 'Bagian PBJ Setda',
            'bagian_perekonomian_setda' => 'Bagian Perekonomian Setda',
            'bagor' => 'Bagian Organisasi Sekda',
            'bakesbangpol' => 'Bakesbangpol',
            'bkpsdm' => 'BKPSDM',
            'bapenda' => 'Bapenda',
            'baperlitbang' => 'Baperlitbang',
            'bpbd_damkar' => 'BPBD Damkar',
            'bpkad' => 'BPKAD',
            'disdukcapil' => 'Dinas Kependudukan dan Pencatatan Sipil',
            'diskominfo' => 'Diskominfo',
            'disnakerin' => 'Dinas Tenaga Kerja dan Perindustrian',
            'dispora' => 'Dinas Kepemudaan, dan Olahraga',
            'dispusip' => 'Dinas Perpustakaan dan Arsip Daerah',
            'dinas_kerprind' => 'Dinas Tenaga Kerja dan Perindustrian',
            'dinas_kesehatan' => 'Dinas Kesehatan',
            'dinas_koperasi' => 'Dinas Koperasi Usaha Mikro, Perdagangan, dan ESDM',
            'dinas_lingkungan_hidup' => 'Dinas Lingkungan Hidup',
            'dinas_pangan' => 'Dinas Pangan',
            'dinas_pangan_dan_pertanian' => 'Dinas Pangan dan Pertanian',
            'dinas_pemberdayaan_masyarakat_desa' => 'Dinas Pemberdayaan Masyarakat Desa',
            'dinas_pengendalian_penduduk' => 'Dinas Pengendalian Penduduk, KB, PP, dan PA',
            'dinas_perhubungan' => 'Dinas Perhubungan',
            'dinas_perikanan' => 'Dinas Perikanan',
            'dinas_perumahan_rakyat_dan_kawasan_permukiman' => 'Dinas Perumahan Rakyat dan Kawasan Pemukiman',
            'dinas_pertanian' => 'Dinas Pertanian',
            'dinas_pendidikan_dan_kebudayaan' => 'Dinas Pendidikan dan Kebudayaan',
            'dinas_pupr' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
            'dinas_sosial' => 'Dinas Sosial',
            'dpmptsp' => 'DPMPTSP',
            'inspektorat' => 'Inspektorat Daerah',
            'kecamatan_belat' => 'Kecamatan Belat',
            'kecamatan_buru' => 'Kecamatan Buru',
            'kecamatan_durai' => 'Kecamatan Durai',
            'kecamatan_karimun' => 'Kecamatan Karimun',
            'kecamatan_kundur' => 'Kecamatan Kundur',
            'kecamatan_kundur_barat' => 'Kecamatan Kundur Barat',
            'kecamatan_kundur_utara' => 'Kecamatan Kundur Utara',
            'kecamatan_meral' => 'Kecamatan Meral',
            'kecamatan_meral_barat' => 'Kecamatan Meral Barat',
            'kecamatan_moro' => 'Kecamatan Moro',
            'kecamatan_selat_gelam' => 'Kecamatan Selat Gelam',
            'kecamatan_sugie_besar' => 'Kecamatan Sugie Besar',
            'kecamatan_tebing' => 'Kecamatan Tebing',
            'kecamatan_unggar' => 'Kecamatan Unggaran',
            'ptsp' => 'PTSP',
            'rsud' => 'RSUD M.SANI',
            'rsud_tj_batu_kundur' => 'RSUD Tanjung Batu Kundur',
            'satpolpp' => 'Satpol PP',
            'sekda' => 'Sekretariat Daerah',
            'sekretariat_dprd' => 'Sekretariat DPRD',
            'ukpbj' => 'UKPBJ',
            'unit_pelayan_publik' => 'Unit Pelayan Publik',
            'upt_pkm_karimun' => 'UPT PUSKESMAS KARIMUN',
            'upt_pkm_tebing' => 'UPT PUSKESMAS TEBING',
            'upt_pkm_meral' => 'UPT PUSKESMAS MERAL',
            'upt_pkm_meral_barat' => 'UPT PUSKESMAS MERAL BARAT',
            'upt_pkm_buru' => 'UPT PUSKESMAS BURU',
            'upt_pkm_tj_batu' => 'UPT PUSKESMAS TANJUNG BATU',
            'upt_pkm_kundur_barat' => 'UPT PUSKESMAS KUNDUR BARAT',
            'upt_pkm_tj_berlian' => 'UPT PUSKESMAS TANJUNG BERLIAN',
            'upt_pkm_belat' => 'UPT PUSKESMAS BELAT',
            'upt_pkm_ungar' => 'UPT PUSKESMAS UNGAR',
            'upt_pkm_moro' => 'UPT PUSKESMAS MORO',
            'upt_pkm_durai' => 'UPT PUSKESMAS DURAI',
            'upt_pkm_niur_permai' => 'UPT PUSKESMAS NIUR PERMAI',
        ];

        return $mapping[$opd] ?? $opd;
    }
}
