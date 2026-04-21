<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\OPD\DashboardController;
use App\Http\Controllers\OPD\RBGeneralController;
use App\Http\Controllers\OPD\RBTematikController;
use App\Http\Controllers\OPD\PKBupatiController;
use App\Http\Controllers\OPD\AnjabdanABKController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\OPD\ProfileController;
use App\Http\Controllers\OPD\PetajabController;
use App\Http\Controllers\OPD\KematanganKelembagaanController;
use App\Http\Controllers\OPD\EvajabController;
use App\Http\Controllers\OPD\PelayananPublikController;
use App\Http\Controllers\AdminRB\RBAksesController;
use App\Http\Controllers\AdminRB\RBGeneralController as AdminRBRBGeneralController;
use App\Http\Controllers\AdminRB\RBTematikController as AdminRBRBTematikController;
use App\Http\Controllers\AdminRB\PKBupatiController as AdminRBPKBupatiController;
use App\Http\Controllers\AdminRB\KelolaDataController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminRB\KelolaAkunController;
use App\Http\Controllers\AdminPelayananPublik\KategoriController;
use App\Http\Controllers\AdminPelayananPublik\KelolaAkunController as AdminPelayananPublikKelolaAkunController;
use App\Http\Controllers\AdminRB\DashboardController as AdminRBDashboardController;
use App\Http\Controllers\AdminPelayananPublik\DashboardController as AdminPelayananPublikDashboardController;
use App\Http\Controllers\AdminPelayananPublik\LaporanController;
use App\Http\Controllers\AdminPelayananPublik\TemplateController;
use App\Http\Controllers\AdminKelembagaan\DashboardController as AdminKelembagaanDashboardController;
use App\Http\Controllers\AdminKelembagaan\KelolaAkunController as AdminKelembagaanKelolaAkunController;
use App\Http\Controllers\AdminKelembagaan\KematanganKelembagaanController as AdminKelembagaanKematanganKelembagaanController;
use App\Http\Controllers\AdminKelembagaan\DokumenController;
use function PHPUnit\Framework\callback;

Route::get('/fix-passwords', function () {
    // Ganti hash di bawah sesuai dengan milik masing-masing admin
    $hashAdminRB = '$2y$12$P53M44nYppz.GGA/d/BqdOSfNNECLp37UrSj1i2GCrl2gUjpeOpum';
    $hashAdminPelayanan = '$2y$12$I2YqeCRPHm.gbX8HKzHMoO0xrnu0i3p6M5hM6pNhuxgWOLmrdkH7y';
    $hashAdminKelembagaan = '$2y$12$bMgu6/J5T53d3lvblC5DBujTlvXHieJdYrSIIZPvkPvmspihMuvrW';

    try {
        DB::table('pengguna')->where('email', 'organisasi.karimun@gmail.com')->update(['password' => $hashAdminRB]);
        DB::table('pengguna')->where('email', 'yanlikkarimun2022@gmail.com')->update(['password' => $hashAdminPelayanan]);
        DB::table('pengguna')->where('email', 'bagorkabupatenkarimun@gmail.com')->update(['password' => $hashAdminKelembagaan]);
        
        return "✅ Password untuk ketiga admin telah diperbarui dengan hash masing-masing.";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/gmail/connect', [GmailController::class, 'connect'])->name('gmail.connect');
    Route::get('/gmail/callback', [GmailController::class, 'callback'])->name('gmail.callback');
    Route::post('/gmail/disconnect', [GmailController::class, 'disconnectGmail'])->name('gmail.disconnect');
});


// Dashboard OPD Route 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Dashboard AdminRB Route
Route::get('/adminrb/dashboard', [AdminRBDashboardController::class, 'index'])->name('adminrb.dashboard');

// Dashboard Admin Pelayanan Publik Route
Route::get('/adminpelayananpublik/dashboard', [AdminPelayananPublikDashboardController::class, 'index'])->name('adminpelayananpublik.dashboard');

// Admin Pelayanan Publik Kelola Akun Routes
Route::prefix('adminpelayananpublik')->middleware(['auth'])->group(function () {
    Route::get('/kelola-akun', [AdminPelayananPublikKelolaAkunController::class, 'index'])->name('adminpelayananpublik.kelola-akun.index');
    Route::get('/kelola-akun/check-email', [AdminPelayananPublikKelolaAkunController::class, 'checkEmail'])->name('adminpelayananpublik.kelola-akun.check-email');
    Route::post('/kelola-akun', [AdminPelayananPublikKelolaAkunController::class, 'store'])->name('adminpelayananpublik.kelola-akun.store');
    Route::delete('/kelola-akun/{id}', [AdminPelayananPublikKelolaAkunController::class, 'destroy'])->name('adminpelayananpublik.kelola-akun.destroy');
});

// Kategori Laporan Admin Pelayanan Publik Routes
Route::prefix('adminpelayananpublik')->group(function () {
    Route::get('/kategori', [KategoriController::class, 'index'])->name('adminpelayananpublik.kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('adminpelayananpublik.kategori.store');
    Route::put('/kategori/{id_kategori}', [KategoriController::class, 'update'])->name('adminpelayananpublik.kategori.update');
    Route::delete('/kategori/{id_kategori}', [KategoriController::class, 'destroy'])->name('adminpelayananpublik.kategori.destroy');
});

// Admin Pelayanan Publik Laporan Routes
Route::prefix('adminpelayananpublik')->name('adminpelayananpublik.')->group(function () {
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/{id}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
    Route::post('laporan/{id}/update', [LaporanController::class, 'update'])->name('laporan.update');
    Route::post('laporan/{id}/verifikasi', [LaporanController::class, 'verifikasi'])->name('laporan.verifikasi');
    Route::delete('laporan/{id}', [LaporanController::class, 'hapus'])->name('laporan.hapus');

});

// Admin Pelayanan Publik Template Routes
Route::prefix('adminpelayananpublik')->name('adminpelayananpublik.')->group(function () {
    Route::get('template', [TemplateController::class, 'index'])->name('template.index');
    Route::post('template/tambah', [TemplateController::class, 'store'])->name('template.store');
    Route::put('template/{id}/update', [TemplateController::class, 'update'])
        ->name('template.update');
    Route::delete('template/{id}/hapus', [TemplateController::class, 'destroy'])->name('template.destroy');
});

// Admin RB AksesRB Routes
Route::prefix('adminrb')->group(function () {
    // Kontrol Akses RB
    Route::get('/aksesrb', [RBAksesController::class, 'index'])->name('adminrb.aksesrb.index');
    Route::put('/aksesrb/{id}', [RBAksesController::class, 'update'])->name('rb-access.update');
});

// Admin RB Kelola Akun Routes
Route::prefix('adminrb')->group(function () {
    Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('adminrb.kelola-akun.index');

    // Tambahkan route store
    Route::post('/kelola-akun', [KelolaAkunController::class, 'store'])->name('akun.store');

    // Optional: update dan destroy
    Route::put('/kelola-akun/{id}', [KelolaAkunController::class, 'update'])->name('akun.update');
    Route::delete('/kelola-akun/{id_user}', [KelolaAkunController::class, 'destroy'])->name('akun.destroy');
     Route::get('kelola-akun/check-email', [KelolaAkunController::class, 'checkEmail'])->name('kelola-akun.check-email');
});

// Admin RB General
Route::prefix('adminrb')->name('adminrb.')->group(function () {
    // Route untuk index (halaman utama)
     Route::get('/rb-general', [AdminRBRBGeneralController::class, 'index'])->name('rb-general.index');
    Route::post('/rb-general', [AdminRBRBGeneralController::class, 'store'])->name('rb-general.store');
    Route::get('/rb-general/{id}', [AdminRBRBGeneralController::class, 'show'])->name('rb-general.show');
    Route::get('/rb-general/{id}/edit', [AdminRBRBGeneralController::class, 'edit'])->name('rb-general.edit');
    Route::put('/rb-general/{id}', [AdminRBRBGeneralController::class, 'update'])->name('rb-general.update');
    Route::delete('/rb-general/{id}', [AdminRBRBGeneralController::class, 'destroy'])->name('rb-general.destroy');
    Route::get('/rb-general/export/excel', [AdminRBRBGeneralController::class, 'exportExcel'])->name('rb-general.export.excel');
    Route::get('/rb-general/export/pdf', [AdminRBRBGeneralController::class, 'exportPdf'])->name('rb-general.export.pdf');
});

// Admin RB Tematik
Route::prefix('adminrb')->name('adminrb.')->group(function () {
    // Route untuk halaman utama
   Route::get('/rb-tematik', [AdminRBRBTematikController::class, 'index'])->name('rb-tematik.index');
    Route::post('/rb-tematik', [AdminRBRBTematikController::class, 'store'])->name('rb-tematik.store');
    Route::get('/rb-tematik/{id}', [AdminRBRBTematikController::class, 'show'])->name('rb-tematik.show');
    Route::get('/rb-tematik/{id}/edit', [AdminRBRBTematikController::class, 'edit'])->name('rb-tematik.edit');
    Route::put('/rb-tematik/{id}', [AdminRBRBTematikController::class, 'update'])->name('rb-tematik.update');
    Route::delete('/rb-tematik/{id}', [AdminRBRBTematikController::class, 'destroy'])->name('rb-tematik.destroy');
    Route::get('/rb-tematik/export/excel', [AdminRBRBTematikController::class, 'exportExcel'])->name('rb-tematik.export.excel');
    Route::get('/rb-tematik/export/pdf', [AdminRBRBTematikController::class, 'exportPdf'])->name('rb-tematik.export.pdf');
});

// Admin PK Bupati
Route::prefix('adminrb')->name('adminrb.')->group(function () {
    Route::get('/pk-bupati', [App\Http\Controllers\AdminRB\PKBupatiController::class, 'index'])->name('pk-bupati.index');
    Route::post('/pk-bupati', [App\Http\Controllers\AdminRB\PKBupatiController::class, 'store'])->name('pk-bupati.store'); // TAMBAHKAN INI
    Route::get('/pk-bupati/{id}', [App\Http\Controllers\AdminRB\PKBupatiController::class, 'show'])->name('pk-bupati.show');
    Route::get('/pk-bupati/{id}/edit', [App\Http\Controllers\AdminRB\PKBupatiController::class, 'edit'])->name('pk-bupati.edit');
    Route::put('/pk-bupati/{id}', [App\Http\Controllers\AdminRB\PKBupatiController::class, 'update'])->name('pk-bupati.update');
    Route::delete('/pk-bupati/{id}', [App\Http\Controllers\AdminRB\PKBupatiController::class, 'destroy'])->name('pk-bupati.destroy');
    Route::get('/pk-bupati/export/pdf', [App\Http\Controllers\AdminRB\PKBupatiController::class, 'exportPdf'])->name('pk-bupati.export.pdf');
    Route::get('/pk-bupati/export/excel', [App\Http\Controllers\AdminRB\PKBupatiController::class, 'exportExcel'])->name('pk-bupati.export.excel');
});


// Admin Kelembagaan Routes
Route::middleware(['auth'])->prefix('adminkelembagaan')->name('adminkelembagaan.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminKelembagaanDashboardController::class, 'index'])
        ->name('dashboard');

    // Kelola Akun
   Route::get('/kelola-akun', [AdminKelembagaanKelolaAkunController::class, 'index'])
        ->name('kelola-akun.index');
    Route::get('/kelola-akun/check-email', [AdminKelembagaanKelolaAkunController::class, 'checkEmail'])
        ->name('kelola-akun.check-email');
    Route::post('/kelola-akun', [AdminKelembagaanKelolaAkunController::class, 'store'])
        ->name('kelola-akun.store');
    Route::delete('/kelola-akun/{id}', [AdminKelembagaanKelolaAkunController::class, 'destroy'])
        ->name('kelola-akun.destroy');

    // Dokumen
    Route::controller(DokumenController::class)->group(function () {
        Route::get('/dokumen', 'index')->name('dokumen.index');
        Route::put('/dokumen/{id}', 'update')->name('dokumen.update');
        Route::delete('/dokumen/{id}', 'destroy')->name('dokumen.destroy');
        Route::get('/dokumen/preview/{judul}', 'preview')->name('dokumen.preview');
    });

    // Kematangan Kelembagaan - HASIL SURVEI
    Route::prefix('kematangan-kelembagaan')->name('kematangan-kelembagaan.')->group(function () {
        Route::get('/', [AdminKelembagaanKematanganKelembagaanController::class, 'index'])
            ->name('index');

        // Detail routes
        Route::get('/kemenpan/{id}', [AdminKelembagaanKematanganKelembagaanController::class, 'showKemenpanJson'])
            ->name('show-kemenpan');

        Route::get('/kemendagri/{id}', [AdminKelembagaanKematanganKelembagaanController::class, 'showKemendagriJson'])
            ->name('show-kemendagri');

        // Delete route dengan parameter berbeda untuk menghindari konflik
        Route::delete('/delete/{id}', [AdminKelembagaanKematanganKelembagaanController::class, 'destroy'])
            ->name('destroy');
    });
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/lihat-akun', function () {
    $users = DB::table('pengguna')->select('email', 'nama_opd', 'role')->get();
    
    if ($users->isEmpty()) {
        return '❌ TIDAK ADA AKUN! Database kosong.';
    }
    
    return response()->json($users);
});

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout Route - Diperbaiki
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// RB Routes (RB General)
Route::prefix('rb-general')->name('rb-general.')->group(function () {
    Route::get('/', [RBGeneralController::class, 'index'])->name('index');
    Route::post('/', [RBGeneralController::class, 'store'])->name('store');
    Route::get('/{id}', [RBGeneralController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [RBGeneralController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RBGeneralController::class, 'update'])->name('update');
    Route::delete('/{id}', [RBGeneralController::class, 'destroy'])->name('destroy');
});

// RB Tematik Routes
Route::prefix('rb-tematik')->group(function () {
    Route::get('/', [RBTematikController::class, 'index'])->name('rb-tematik.index');
    Route::post('/', [RBTematikController::class, 'store'])->name('rb-tematik.store');
    Route::get('/{id}', [RBTematikController::class, 'show'])->name('rb-tematik.show');
    Route::get('/{id}/edit', [RBTematikController::class, 'edit'])->name('rb-tematik.edit');
    Route::put('/{id}', [RBTematikController::class, 'update'])->name('rb-tematik.update');
    Route::delete('/{id}', [RBTematikController::class, 'destroy'])->name('rb-tematik.destroy');
});

// SAKIP Routes
Route::prefix('pk-bupati')->name('pk-bupati.')->group(function () {
    Route::get('/', [PKBupatiController::class, 'index'])->name('index');
    
    // Route untuk CRUD (sesuaikan dengan prefix)
    Route::post('/store', [PKBupatiController::class, 'store'])->name('store');
    Route::get('/show/{id}', [PKBupatiController::class, 'show'])->name('show');
    Route::put('/update/{id}', [PKBupatiController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [PKBupatiController::class, 'destroy'])->name('destroy');
    Route::get('/filter', [PKBupatiController::class, 'getDataByFilter'])->name('filter');
    
    // Route untuk download
    Route::get('/download/pdf', [DownloadController::class, 'downloadPKPdf'])->name('download.pdf');
    Route::get('/download/excel', [DownloadController::class, 'downloadPKExcel'])->name('download.excel');
});

// Kelembagaan
// Analisis Jabatan dan Analisis Beban Kerja Routes
Route::prefix('anjab-abk')->group(function () {
    Route::get('/', [AnjabdanABKController::class, 'index'])->name('anjab-abk.index');
    Route::post('/upload', [AnjabdanABKController::class, 'laporanUpload'])->name('anjab-abk.upload');
    Route::get('/download/{id}', [AnjabdanABKController::class, 'laporanDownload'])->name('anjab-abk.download');
    Route::delete('/hapus/{id}', [AnjabdanABKController::class, 'laporanDestroy'])->name('anjab-abk.destroy');
});

// Peta Jabatan Route
Route::prefix('petajab')->group(function () {
    Route::get('/', [PetajabController::class, 'index'])->name('petajab.index');
    Route::post('/upload', [PetajabController::class, 'laporanUpload'])->name('petajab.upload');
    Route::get('/download/{id}', [PetajabController::class, 'laporanDownload'])->name('petajab.download');
    Route::delete('/hapus/{id}', [PetajabController::class, 'laporanDestroy'])->name('petajab.destroy');
});

// Evaluasi Jabatan Route
Route::prefix('evajab')->group(function () {

    // Halaman index
    Route::get('/', [EvajabController::class, 'index'])->name('evajab.index');

    // Upload laporan
    Route::post('/upload', [EvajabController::class, 'laporanUpload'])->name('evajab.upload');

    // Download laporan
    Route::get('/download/{id}', [EvajabController::class, 'laporanDownload'])->name('evajab.download');

    // Hapus laporan
    Route::delete('/hapus/{id}', [EvajabController::class, 'laporanDestroy'])->name('evajab.destroy');

});

// Kematangan Kelembagaan Route
Route::middleware(['auth'])->prefix('kematangan')->group(function () {
    // Halaman utama survei
    Route::get('/', [KematanganKelembagaanController::class, 'index'])->name('kematangan.index');

    // Submit form KemenPAN
    Route::post('/kemenpan/submit', [KematanganKelembagaanController::class, 'submitKemenpan'])
        ->name('kematangan.kemenpan.submit');

    // Submit form Kemendagri
    Route::post('/kemendagri/submit', [KematanganKelembagaanController::class, 'submitKemendagri'])
        ->name('kematangan.kemendagri.submit');
});


// Pelayanan Publik Routes
Route::prefix('pelayanan-publik')->group(function () {
    Route::get('/', [PelayananPublikController::class, 'index'])->name('pelayanan-publik.index');
    Route::post('upload', [PelayananPublikController::class, 'upload'])->name('opd.pelayananpublik.upload');
    Route::delete('/laporan/{id}/hapus', [PelayananPublikController::class, 'hapusLaporan'])->name('laporan.hapus');
    Route::get('/laporan/{id}/download', [PelayananPublikController::class, 'download'])->name('laporan.download');
    Route::get('/laporan/{id}/view', [PelayananPublikController::class, 'viewPdf'])->name('laporan.view');
    
    // SKM Routes
    Route::post('/skm/generate', [PelayananPublikController::class, 'generateSkm'])->name('laporan.skm.generate');
    Route::delete('/skm/{id}/hapus', [PelayananPublikController::class, 'hapusSkm'])
    ->name('skm.destroy');

});


Route::middleware(['auth'])->prefix('opd')->name('opd.')->group(function () {

    // Halaman edit profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // Update profile
    Route::post('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Update password
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update.password');
});


Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

// Fallback untuk route yang belum diimplementasi
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});