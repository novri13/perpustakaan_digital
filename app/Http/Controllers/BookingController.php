<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $anggotaId = Auth::user()->anggota->id;

        $bookings = Booking::with('buku')
            ->where('anggota_id', $anggotaId)
            ->where('status', 'booking')
            ->get();

            return view('anggota.bookings.index', compact('bookings'));
    
    }

    // Menyimpan data booking
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
        ]);

        $anggotaId = Auth::user()->anggota->id;
        $bukuId = $request->buku_id;

        // Cek jumlah booking aktif
        $totalBooking = Booking::where('anggota_id', $anggotaId)
            ->whereIn('status', ['booking', 'dipinjam'])
            ->count();

        if ($totalBooking >= 3) {
            return back()->with('error', 'Maksimal 3 buku bisa dibooking atau dipinjam dalam satu waktu.');
        }

        // Cek apakah buku sudah dibooking/dipinjam
        $sudahBooking = Booking::where('anggota_id', $anggotaId)
            ->where('buku_id', $bukuId)
            ->whereIn('status', ['booking', 'dipinjam'])
            ->first();

        if ($sudahBooking) {
            return back()->with('error', 'Buku ini sudah kamu booking atau sedang dipinjam.');
        }

        $buku = Buku::findOrFail($bukuId);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        // Kurangi stok buku
        $buku->stok -= 1;
        $buku->save();

        // Simpan data booking
        Booking::create([
            'anggota_id' => $anggotaId,
            'buku_id' => $bukuId,
            'status' => 'booking',
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan ke riwayat booking.');
    }

    // Hapus booking (kembalikan stok)
    public function destroy(Booking $booking)
    {
        if ($booking->status == 'booking') {
            $booking->buku->increment('stok');
        }

        $booking->delete();

        return back()->with('success', 'Booking berhasil dihapus.');
    }

    // Ajukan pinjam ke admin (ubah status)
    public function pinjam(Booking $booking)
    {
        if ($booking->status !== 'booking') {
            return back()->with('error', 'Booking tidak valid.');
        }

        $booking->status = 'menunggu persetujuan';
        $booking->save();

        return back()->with('success', 'Permintaan peminjaman dikirim ke admin.');
    }
}
