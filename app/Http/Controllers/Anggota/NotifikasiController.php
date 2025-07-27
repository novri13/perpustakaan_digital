<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = auth()->user()->notifications()->latest()->paginate(10);
        return view('anggota.notifikasi.index', compact('notifikasi'));
    }
}
