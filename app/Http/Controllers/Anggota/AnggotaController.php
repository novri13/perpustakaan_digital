<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Peminjaman;


class AnggotaController extends Controller
{
    public function index()
    {
          $user = Auth::user(); 

        return view('anggota.dashboard', compact('user'));
    }

    public function riwayat()
    {
      $anggota = Auth::user()->anggota;

    $peminjamanAktif = $anggota->peminjaman()
        ->whereIn('status', ['dipinjam', 'diperpanjang'])
        ->with('buku')
        ->orderByDesc('tanggal_pinjam')
        ->get();

    $peminjamanSelesai = $anggota->peminjaman()
        ->whereIn('status', ['kembali', 'selesai'])
        ->with('buku')
        ->orderByDesc('tanggal_pinjam')
        ->get();

    return view('anggota.history-transaksi', compact('peminjamanAktif', 'peminjamanSelesai'));
    }

    public function profile()
    {
         $user = Auth::user();

    if (!$user->hasRole('anggota')) {
        abort(403, 'Bukan anggota');
    }

    $anggota = $user->anggota;

    return view('anggota.profil', compact('anggota'));
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'password' => ['nullable', 'confirmed', 'min:6'],
    ]);

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    return redirect()->back()->with('success', 'Profil berhasil diperbarui');
}


    public function notifikasi()
    {
        $user = Auth::user();
        $notifikasi = $user->notifikasi()->latest()->get();
        return view('anggota.notifikasi', compact('user', 'notifikasi'));
    }

    public function detailAnggota($id)
    {
        $anggota = User :: findOrFail($id);
        if (!$anggota->hasRole('anggota')) {
            abort(403, 'Bukan anggota');
        }
    }
}
