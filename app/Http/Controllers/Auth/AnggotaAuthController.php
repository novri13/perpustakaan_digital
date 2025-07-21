<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AnggotaAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('anggota.login-anggota'); 
    }

    public function login(Request $request)
    {
        $request->validate([
        'nisn' => 'required',
        'password' => 'required',
    ]);

    // Cari user berdasarkan relasi anggota (id = NISN/NIP)
    $user = User::whereHas('anggota', function ($query) use ($request) {
        $query->where('id', $request->nisn);
    })->first();

    if (! $user) {
        return back()->withErrors(['nisn' => 'NISN/NIP tidak ditemukan.'])->withInput();
    }

    // Pastikan status anggota aktif
    if ($user->anggota && $user->anggota->status !== 'aktif') {
        return back()->withErrors(['nisn' => 'Akun Anda tidak aktif, hubungi petugas perpustakaan.']);
    }

    // Pastikan password benar
    if (! Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'NISN/NIP atau Password anda salah'])->withInput();
    }

    // Pastikan user punya role anggota
    if (! $user->hasRole('anggota')) {
        return back()->withErrors(['nisn' => 'Anda bukan anggota perpustakaan.'])->withInput();
    }

    // Login sukses
    Auth::login($user);

        return redirect()->route('anggota.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }
}
