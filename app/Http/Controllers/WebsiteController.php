<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Penerbit;
use App\Models\Rak;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BukuDipinjamNotification; 
use Illuminate\Http\Request;
use Carbon\Carbon;


class WebsiteController extends Controller
{
    // Beranda utama
    public function home(Request $request)
    {
    
    $allKategori = Kategori::orderBy('id')->get();
    $kategoriUtama = $allKategori->take(4);

    // ✅ Ambil 10 buku terbaru + hitung total anggota unik yang sudah meminjam
    $terbaru  = Buku::withCount([
        'peminjaman as total_peminjam_unique' => function($q) {
            $q->select(DB::raw('COUNT(DISTINCT anggota_id)'));
        }
    ])
    ->orderByDesc('id')
    ->take(10)
    ->get();

    $populer  = Buku::inRandomOrder()->take(10)->get();

    // Ambil periode dari query, default 12 bulan (1 tahun)
    $periodeBulan = $request->input('periode', 12);
    $tanggalMulai = now()->subMonths($periodeBulan);

    $penikmat = Anggota::withCount(['peminjaman' => function($q) use ($tanggalMulai) {
            $q->where('created_at', '>=', $tanggalMulai);
        }])
        ->having('peminjaman_count', '>', 0)
        ->orderByDesc('peminjaman_count')
        ->take(6)
        ->get();

    return view('home', [
        'kategoriUtama' => $kategoriUtama,
        'allKategori'   => $allKategori,
        'terbaru'       => $terbaru,
        'populer'       => $populer,
        'penikmat'      => $penikmat,
        'periodeBulan'  => $periodeBulan, // kirim ke view biar bisa ditampilkan
    ]);  
    }

    // Riwayat peminjaman anggota
    public function riwayatPeminjaman($id, Request $request)
    {
    // Jika user login & bukan anggota → tolak
    // if (auth()->check() && !auth()->user()->hasRole('anggota')) {
    //     abort(403, 'Halaman ini hanya untuk anggota atau pengunjung.');
    // }
    $periode = $request->periode ?? 6; // default 6 bulan
    $startDate = now()->subMonths($periode);

    $riwayat = Peminjaman::with('buku')
        ->where('anggota_id', $id)
        ->where('created_at', '>=', $startDate)
        ->orderByDesc('created_at')
        ->get();

    return response()->json([
        'riwayat' => $riwayat->map(fn($p) => [
            'isbn'      => $p->buku->id ?? $p->buku->id ?? '-',
            'judul' => $p->buku->judul ?? '-',
            'pengarang' => $p->buku->pengarang ?? '-',
            'tanggal' => $p->created_at->format('d M Y'),
            'status' => $p->status,
        ])
    ]);
    }
    
    // Katalog buku dengan filter
    public function katalogBuku(Request $request)
    {
    // Jika user login & bukan anggota → tolak
    // if (auth()->check() && !auth()->user()->hasRole('anggota')) {
    //     abort(403, 'Halaman ini hanya untuk anggota atau pengunjung.');
    // }

    // ✅ Query dasar dengan relasi kategori & penerbit
    $query = Buku::query()->with('kategori', 'penerbit');

    // --- Filter kategori ---
    $selectedKategori = null;
    if ($request->filled('kategori')) {
        $selectedKategori = Kategori::find($request->kategori);
        $query->where('id_kategori', $request->kategori);
    }

    // --- Filter Tahun Terbit ---
    $selectedTahunTerbit = null;
    if ($request->filled('tahun')) {
        $selectedTahunTerbit = $request->tahun;
        $query->whereYear('tahun_terbit', $request->tahun);
    }

    // --- Filter Rak ---
    $selectedRak = null;
    if ($request->filled('rak')) {
        $selectedRak = Rak::find($request->rak);
        $query->where('id_rak', $request->rak);
    }

    // --- Filter Bahasa ---
    $selectedBahasa = null;
    if ($request->filled('bahasa')) {
        $selectedBahasa = $request->bahasa;
        $query->where('bahasa', $request->bahasa);
    }

    // --- Filter Pencarian ---
    if ($request->filled('q')) {
        $query->where(function ($q) use ($request) {
            $q->where('judul', 'like', "%{$request->q}%")
              ->orWhere('pengarang', 'like', "%{$request->q}%");
        });
    }

    // ✅ Ambil hasil dengan pagination
    $bukus = $query->paginate(10)->withQueryString();

    // ✅ Data untuk filter
    $tahunList  = Buku::selectRaw('YEAR(tahun_terbit) as tahun')
                    ->distinct()
                    ->orderBy('tahun', 'desc')
                    ->pluck('tahun')
                    ->toArray();

    $rakList    = Rak::pluck('name', 'id')->toArray();
    $bahasaList = Buku::select('bahasa')->distinct()->pluck('bahasa')->toArray();

    // ✅ Kirim ke view katalog
    return view('katalog', compact(
        'bukus',
        'tahunList',
        'rakList',
        'bahasaList',
        'selectedKategori',
        'selectedTahunTerbit',
        'selectedRak',
        'selectedBahasa'
    ));
    }

    // Detail buku
    public function detailBuku($id)
    {
    // Jika user login & bukan anggota → tolak akses
    // if (auth()->check() && !auth()->user()->hasRole('anggota')) {
    //  abort(403, 'Halaman ini hanya untuk anggota atau pengunjung.');
    // }
    
    $buku = Buku::findOrFail($id);
    $sedangDipinjam = false; // atau sesuai kebutuhanmu

    $sudahBooking = false;
    $jumlahBooking = 0;

    if (Auth::check() && Auth::user()->hasRole('anggota')) {
        $anggotaId = Auth::user()->anggota->id;

        $sudahBooking = Booking::where('anggota_id', $anggotaId)
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['booking', 'dipinjam'])
            ->exists();

        $jumlahBooking = Booking::where('anggota_id', $anggotaId)
            ->where('status', 'booking')
            ->count();
    }

    return view('detail-buku', compact('buku', 'sedangDipinjam', 'sudahBooking', 'jumlahBooking'));
    }


    // Detail buku versi JSON (untuk AJAX)
    public function detailBukuJson($id)
    {
    // Jika user login & bukan anggota → tolak akses
    // if (auth()->check() && !auth()->user()->hasRole('anggota')) {
    //  abort(403, 'Halaman ini hanya untuk anggota atau pengunjung.');
    // }
    
    $buku = Buku::with(['kategori', 'rak', 'penerbit'])->findOrFail($id);

    // Hitung berapa sedang dipinjam (status 'dipinjam')
    $dipinjamCount = Peminjaman::where('buku_id', $buku->id)
        ->where('status', 'dipinjam')
        ->count();

    // Hitung total anggota yang pernah meminjam buku ini
    $totalPeminjamUnique = Peminjaman::where('buku_id', $buku->id)
        ->distinct('anggota_id')
        ->count('anggota_id');

    // Hitung stok tersedia
    $totalStok = $buku->stok ?? 0;
    $tersedia = max($totalStok - $dipinjamCount, 0);

    // Ambil daftar 5 peminjam terakhir
    $peminjam = Peminjaman::with('anggota')
        ->where('buku_id', $buku->id)
        ->orderByDesc('created_at')
        ->take(5)
        ->get()
        ->map(fn($p) => [
            'nama' => $p->anggota->nama ?? '-',
            'tanggal' => $p->created_at->format('d M Y'),
            'status' => $p->status,
        ]);

    return response()->json([
        'id'         => $buku->id,
        'judul'      => $buku->judul ?? 'Tanpa Judul',
        'pengarang'  => $buku->pengarang ?? '-',
        'penerbit' => $buku->penerbit->name ?? '-',
        'kategori'   => $buku->kategori->name ?? '-',
        'rak' => $buku->rak->name ?? '-',
        'deskripsi'  => $buku->deskripsi ?? '-',
        'gambar'     => $buku->gambar && file_exists(storage_path('app/public/'.$buku->gambar))
                            ? asset('storage/'.$buku->gambar)
                            : asset('storage/no-cover.png'),

        // Info stok
        'stok_total'    => $totalStok,
        'dipinjam'      => $dipinjamCount,
        'tersedia'      => $tersedia,
        'stok_format'   => "{$tersedia}/{$totalStok}",

        // Info total anggota yg pernah pinjam
        'total_peminjam_unique' => $totalPeminjamUnique,

        'peminjam' => $peminjam
    ]);
    }

    // Pencarian buku
    public function searchBuku(Request $request)
    {
        $keyword = $request->input('q');
        $bukus = Buku::where('judul', 'like', "%$keyword%")
                      ->orWhere('pengarang', 'like', "%$keyword%")
                      ->paginate(12);

        return view('website.buku.index', compact('bukus'));
    }

    // Daftar pustakawan (admin/petugas)
    public function pustakawan()
    {
        // Jika user login & bukan anggota → tolak akses
        // if (auth()->check() && !auth()->user()->hasRole('anggota')) {
        //     abort(403, 'Halaman ini hanya untuk anggota atau pengunjung.');
        // }

        // Ambil semua user dengan role admin, kepala sekolah, atau pustakawan
        $pustakawan = User::role(['admin', 'kepala_sekolah', 'pustakawan'])->get();

        // Kirim ke view
        return view('pustakawan', compact('pustakawan'));
    }

    // Denah perpustakaan
    public function denah()
    {
        
    // Jika user login & bukan anggota → tolak akses
    // if (auth()->check() && !auth()->user()->hasRole('anggota')) {
    //     abort(403, 'Halaman ini hanya untuk anggota atau pengunjung.');
    // }

    // Kalau guest atau anggota → boleh akses
    return view('denah-perpustakaan');
    }   

}
