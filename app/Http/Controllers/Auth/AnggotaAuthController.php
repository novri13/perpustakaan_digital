<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AnggotaAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (Auth::check() && Auth::user()->hasRole('anggota')) {
        return redirect()->route('anggota.dashboard');
    }

        // Kirimkan redirect URL ke view kalau ada
        $redirectUrl = $request->query('redirect');

        return view('anggota.login-anggota', compact('redirectUrl'));
    }

    public function login(Request $request)
    {
        $request->validate([
        'nisn' => 'required',
        'password' => 'required',
    ]);

    $user = User::whereHas('anggota', function ($query) use ($request) {
        $query->where('id', $request->nisn);
    })->first();

    if (! $user) {
        return back()->withErrors(['nisn' => 'NISN/NIP tidak ditemukan.'])->withInput();
    }

    if ($user->anggota && $user->anggota->status !== 'aktif') {
        return back()->withErrors(['nisn' => 'Akun Anda tidak aktif, hubungi petugas perpustakaan.']);
    }

    if (! Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'NISN/NIP atau Password salah'])->withInput();
    }

    if (! $user->hasRole('anggota')) {
        return back()->withErrors(['nisn' => 'Anda bukan anggota perpustakaan.'])->withInput();
    }

    Auth::login($user);

    // ✅ CEK kalau ada redirect URL → kembali ke sana
    if ($request->filled('redirect')) {
        return redirect($request->redirect);
    }

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
