<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Auth\AnggotaAuthController;
use App\Http\Controllers\Anggota\AnggotaController;
use App\Http\Controllers\Admin\QRCodeController;
use App\Http\Controllers\Anggota\NotifikasiController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LaporanExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LaporanKepalaController;


Route::post('/notifications/mark-as-read', function (Request $request) {
    $request->user()->unreadNotifications->markAsRead();
    return response()->json(['success' => true]);
})->middleware('auth')->name('notifications.markAsRead');

// HALAMAN WEBSITE PUBLIK
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('katalog', [WebsiteController::class, 'katalogBuku'])->name('katalog');
Route::get('/pustakawan', [WebsiteController::class, 'pustakawan'])->name('pustakawan');
Route::get('/denah-perpustakaan', [WebsiteController::class, 'denah'])->name('denah');
Route::get('detail-buku/{id}', [WebsiteController::class, 'detailBuku'])->name('detail-buku');
Route::get('/buku/search', [WebsiteController::class, 'searchBuku'])->name('buku.search');
Route::get('/anggota/{id}/riwayat', [WebsiteController::class, 'riwayatPeminjaman'])->name('anggota.riwayat');
Route::get('/buku/{id}/detail-json', [WebsiteController::class, 'detailBukuJson'])->name('buku.detail.json');
Route::post('/logout', [AnggotaAuthController::class, 'logout'])->name('logout');


// LOGIN & DASHBOARD ANGGOTA
Route::get('/anggota/login', [AnggotaAuthController::class, 'showLoginForm'])->name('anggota.login.form'); 
Route::post('/anggota/login', [AnggotaAuthController::class, 'login'])->name('anggota.login.submit');


Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->group(function () {
    // Dashboard Anggota
    Route::get('/dashboard', [AnggotaController::class, 'index'])->name('anggota.dashboard');
    // Booking Buku (Prefix anggota.bookings)
    Route::get('/bookings', [BookingController::class, 'index'])->name('anggota.bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('anggota.bookings.store');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('anggota.bookings.destroy');
    Route::post('/bookings/{booking}/pinjam', [BookingController::class, 'pinjam'])->name('anggota.bookings.pinjam');

    
     // Riwayat Booking Buku
    Route::get('/riwayat-booking', [AnggotaController::class, 'riwayatBooking'])->name('anggota.bookings.riwayat');
    // Profil Anggota
    Route::get('/profil', [AnggotaController::class, 'profile'])->name('anggota.profil');
    Route::put('/profil/update', [AnggotaController::class, 'update'])->name('anggota.profil.update');
    // Riwayat Peminjaman
    Route::get('/history-transaksi', [AnggotaController::class, 'riwayat'])->name('anggota.history-transaksi');
    // Notifikasi Anggota
    Route::get('/notifikasi', [AnggotaController::class, 'notifikasi'])->name('anggota.notifikasi');
    // Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('anggota.notifikasi.index');

    // route untuk mark as read
    Route::post('/notifikasi/{id}/read', function ($id, Request $request) {
        $notif = $request->user()->notifications()->findOrFail($id);
        $notif->markAsRead();
        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    })->name('notifications.read');

    // web.php
// Route::get('/admin/qrcode/get_data/{id}', function($id) {
//     $anggota = \App\Models\Anggota::find($id);
//     if(!$anggota){
//         return response()->json(['success' => false]);
//     }
//     return response()->json([
//         'success' => true,
//         'data' => [
//             'nama' => $anggota->nama,
//             'nis' => $anggota->nis,
//             'kelas' => $anggota->kelas
//         ]
//     ]);
// });

    // Logout Anggota
    // Route::post('/logout', [AnggotaAuthController::class, 'logout'])->name('anggota.logout');
});


// Admin / Pustakawan / Kepala Sekolah

Route::get('/admin/qrcode/pdf', [QRCodeController::class, 'exportPdf'])->name('admin.qrcode.pdf');
// Laporan Peminjaman
Route::get('/admin/laporan-peminjaman/pdf', [LaporanExportController::class, 'peminjamanPdf'])->name('admin.laporan-peminjaman');
Route::get('/admin/laporan-peminjaman/excel', [LaporanExportController::class, 'peminjamanExcel'])->name('admin.laporan-peminjaman.excel');
// Laporan Pengembalian
Route::get('/admin/laporan-pengembalian/pdf', [LaporanExportController::class, 'pengembalianPdf'])->name('admin.laporan-pengembalian.pdf');
Route::get('/admin/laporan-pengembalian/excel', [LaporanExportController::class, 'pengembalianExcel'])->name('admin.laporan-pengembalian.excel');

// Route::middleware(['auth', 'role:admin|pustakawan'])->group(function () {
//     Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
//     Route::post('/admin/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('admin.bookings.approve');
//     Route::post('/admin/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('admin.bookings.reject');
//     });

// LAPORAN UNTUK KEPALA SEKOLAH
// Route::get('/export/laporan-peminjaman-pdf', [LaporanExportController::class, 'kepalaPdf'])->name('laporan.peminjaman.pdf');
// Route::get('/kepala/laporan-peminjaman/pdf', [LaporanExportController::class, 'kepalaPdf'])->name('kepala.laporan.pdf');



Route::get('/admin/qrcode/get_data/{id}', function($id){
    echo $id;
    // "SELECT * from anggota where id = '$id'"
    // $berita = DB::table('anggota')->where('id',$id)->get();
} );



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

