<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Auth\AnggotaAuthController;
use App\Http\Controllers\Anggota\AnggotaController;
use App\Http\Controllers\Admin\QRCodeController;
use App\Http\Controllers\LaporanExportController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LaporanKepalaController;

// LOGIN & DASHBOARD ANGGOTA
Route::get('/anggota/login', [AnggotaAuthController::class, 'showLoginForm'])->name('anggota.login.form'); 
Route::post('/anggota/login', [AnggotaAuthController::class, 'login'])->name('anggota.login.submit');
Route::get('/anggota/dashboard', function () {
    return view('anggota.dashboard');
})->middleware(['auth', 'role:anggota'])->name('anggota.dashboard');
Route::middleware(['auth'])->prefix('anggota')->group(function () {
    Route::get('/dashboard', [AnggotaController::class, 'index'])->name('anggota.dashboard');
    Route::get('/profil', [AnggotaController::class, 'profile'])->name('anggota.profil');
    Route::put('/profil/update', [AnggotaController::class, 'update'])->name('anggota.profil.update');
    // ✅ Riwayat transaksi anggota
    Route::get('/history-transaksi', [AnggotaController::class, 'riwayat'])->name('anggota.history-transaksi');
    Route::post('/logout', [AnggotaAuthController::class, 'logout'])->name('logout');
    
   
});

Route::get('/admin/qrcode/pdf', [QRCodeController::class, 'exportPdf'])->name('admin.qrcode.pdf');
// Laporan Peminjaman
Route::get('/admin/laporan-peminjaman/pdf', [LaporanExportController::class, 'peminjamanPdf'])->name('admin.laporan-peminjaman');
Route::get('/admin/laporan-peminjaman/excel', [LaporanExportController::class, 'peminjamanExcel'])->name('admin.laporan-peminjaman.excel');

// Laporan Pengembalian
Route::get('/admin/laporan-pengembalian/pdf', [LaporanExportController::class, 'pengembalianPdf'])->name('admin.laporan-pengembalian.pdf');
Route::get('/admin/laporan-pengembalian/excel', [LaporanExportController::class, 'pengembalianExcel'])->name('admin.laporan-pengembalian.excel');


// HALAMAN WEBSITE PUBLIK
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('katalog', [WebsiteController::class, 'katalogBuku'])->name('katalog');
Route::get('/pustakawan', [WebsiteController::class, 'pustakawan'])->name('pustakawan');
Route::get('/denah-perpustakaan', [WebsiteController::class, 'denah'])->name('denah');
Route::get('detail-buku/{id}', [WebsiteController::class, 'detailBuku'])->name('detail-buku');
Route::get('/buku/search', [WebsiteController::class, 'searchBuku'])->name('buku.search');
Route::get('/anggota/{id}/riwayat', [WebsiteController::class, 'riwayatPeminjaman'])->name('anggota.riwayat');
Route::get('/buku/{id}/detail-json', [WebsiteController::class, 'detailBukuJson'])
    ->name('buku.detail.json');


// LAPORAN UNTUK KEPALA SEKOLAH
Route::get('/export/laporan-peminjaman-pdf', [LaporanExportController::class, 'kepalaPdf'])->name('laporan.peminjaman.pdf');
Route::get('/kepala/laporan-peminjaman/pdf', [LaporanExportController::class, 'kepalaPdf'])->name('kepala.laporan.pdf');






// Route::get('/login', function () {
// return redirect('/admin/login');
// })->name('login');

// Route::get('/login', function (){
//     return redirect('/pustakawan/login');
// })->name('login');

// Route::get('/login', function (){
//     return redirect('/kepalaSekolah/login');
// })->name('login');

// Route::get('/login', function (){
//     return redirect('/anggota/login');
// })->name('login');

