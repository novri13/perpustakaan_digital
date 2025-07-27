<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // ✅ ANGGOTA booking buku
    public function store(Request $request, Buku $buku)
    {
        $anggotaId = Auth::id();

    // 1️⃣ Cek stok buku
    if ($buku->stok <= 0) {
        return back()->with('error', '❌ Buku sedang habis, tidak bisa dipesan.');
    }

    // 2️⃣ Cek apakah anggota sudah booking/pinjam buku yang sama
    $sudahBooking = \App\Models\Peminjaman::where('anggota_id', $anggotaId)
        ->where('buku_id', $buku->id)
        ->whereIn('status', ['booking', 'dipinjam'])
        ->exists();

    if ($sudahBooking) {
        return back()->with('error', '❌ Kamu sudah memesan atau meminjam buku ini.');
    }

    // 3️⃣ (Opsional) Batasi maksimal peminjaman aktif, misal max 3
    $pinjamanAktif = \App\Models\Peminjaman::where('anggota_id', $anggotaId)
        ->whereIn('status', ['booking', 'dipinjam'])
        ->count();

    if ($pinjamanAktif >= 3) {
        return back()->with('error', '❌ Maksimal hanya boleh meminjam 3 buku sekaligus.');
    }

    // 4️⃣ Simpan permintaan booking
    \App\Models\Peminjaman::create([
        'anggota_id' => $anggotaId,
        'buku_id' => $buku->id,
        'status' => 'booking',
        'tanggal_pinjam' => null,
        'tanggal_kembali' => null,
    ]);

    return back()->with('success', '✅ Permintaan peminjaman berhasil! Menunggu persetujuan pustakawan.');
    }

    // ✅ PUSTAKAWAN menyetujui peminjaman
    public function approve(Peminjaman $peminjaman)
    {
        // Hanya bisa approve booking
    if ($peminjaman->status !== 'booking') {
        return back()->with('error', '❌ Peminjaman ini tidak dalam status booking.');
    }

    // Cek stok buku sebelum approve
    if ($peminjaman->buku->stok <= 0) {
        return back()->with('error', '❌ Stok buku habis, tidak bisa disetujui.');
    }

    $peminjaman->update([
        'status' => 'dipinjam',
        'approved_by' => Auth::id(),
        'tanggal_approve' => now(),
        'tanggal_pinjam' => now(),
        'tanggal_kembali' => now()->addDays(7), // default 7 hari
    ]);

    // Kurangi stok buku
    $peminjaman->buku->decrement('stok', 1);

    return back()->with('success', '✅ Peminjaman disetujui.');
    }

    // ✅ Kembalikan buku
    public function returnBook(Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'status' => 'kembali',
            'tanggal_dikembalikan' => now(),
        ]);

        // Tambahkan stok buku kembali
        $peminjaman->buku->increment('stok', 1);

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    public function cancel(Peminjaman $peminjaman)
    {
    // Hanya anggota yang booking & status booking yang bisa batal
    if ($peminjaman->anggota_id !== Auth::id() || $peminjaman->status !== 'booking') {
        return back()->with('error', '❌ Tidak bisa membatalkan peminjaman ini.');
    }

    $peminjaman->delete();

    return back()->with('success', '✅ Booking dibatalkan.');
    }

    // ✅ (Opsional) Daftar semua peminjaman
    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjaman'));
    }
}
