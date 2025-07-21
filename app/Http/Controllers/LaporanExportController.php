<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanExport;
use App\Exports\PengembalianExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanExportController extends Controller
{
    public function peminjamanPdf(Request $request)
    {
        // Gunakan tanggal request jika tersedia, jika tidak pakai bulan sekarang
    $tanggalAwal = $request->query('tanggal_awal')
        ? Carbon::parse($request->query('tanggal_awal'))->startOfDay()
        : Carbon::now()->startOfMonth();

    $tanggalAkhir = $request->query('tanggal_akhir')
        ? Carbon::parse($request->query('tanggal_akhir'))->endOfDay()
        : Carbon::now()->endOfMonth();

    // Ambil data berdasarkan tanggal_pinjam
    $data = Peminjaman::with(['anggota', 'buku', 'denda'])
        ->where('status', 'Dipinjam') // hanya yang masih dipinjam
        ->whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir])
        ->get();

    // Format tanggal untuk ditampilkan
    $tanggalAwalFormat = $tanggalAwal->translatedFormat('d F Y');
    $tanggalAkhirFormat = $tanggalAkhir->translatedFormat('d F Y');
    $tanggalCetak = Carbon::now()->translatedFormat('l, d F Y');

    // Generate PDF
    $pdf = Pdf::loadView('exports.laporan-peminjaman', [
        'data' => $data,
        'tanggalAwal' => $tanggalAwalFormat,
        'tanggalAkhir' => $tanggalAkhirFormat,
        'tanggalCetak' => $tanggalCetak,
    ])->setPaper('a4', 'landscape');

    return $pdf->download('laporan-peminjaman.pdf');
    }

    public function peminjamanExcel(Request $request)
    {
        $tanggalAwal = $request->query('tanggal_awal');
        $tanggalAkhir = $request->query('tanggal_akhir');

        return Excel::download(new PeminjamanExport($tanggalAwal, $tanggalAkhir), 'laporan-peminjaman.xlsx');
    }

    public function pengembalianPdf(Request $request)
    {
         // Gunakan tanggal saat ini jika parameter tidak diberikan
    $tanggalAwal = $request->query('tanggal_awal')
        ? Carbon::parse($request->query('tanggal_awal'))->startOfDay()
        : Carbon::now()->startOfMonth();

    $tanggalAkhir = $request->query('tanggal_akhir')
        ? Carbon::parse($request->query('tanggal_akhir'))->endOfDay()
        : Carbon::now()->endOfMonth();

    // Ambil data pengembalian berdasarkan tanggal kembali
    $data = Peminjaman::with(['anggota', 'buku', 'denda'])
        ->whereIn('status', ['selesai', 'kembali']) // pastikan status pengembalian
        ->whereBetween('tanggal_kembali', [$tanggalAwal, $tanggalAkhir])
        ->get();

    // Format tanggal
    $tanggalAwalFormat = $tanggalAwal->translatedFormat('d F Y');
    $tanggalAkhirFormat = $tanggalAkhir->translatedFormat('d F Y');
    $tanggalCetak = Carbon::now()->translatedFormat('l, d F Y');

    // Generate PDF
    $pdf = Pdf::loadView('exports.laporan-pengembalian', [
        'data' => $data,
        'tanggalAwal' => $tanggalAwalFormat,
        'tanggalAkhir' => $tanggalAkhirFormat,
        'tanggalCetak' => $tanggalCetak,
    ])->setPaper('a4', 'landscape');

    return $pdf->download('laporan-pengembalian.pdf');
    }

    public function pengembalianExcel(Request $request)
    {
        $tanggalAwal = $request->query('tanggal_awal');
        $tanggalAkhir = $request->query('tanggal_akhir');

        return Excel::download(new PengembalianExport($tanggalAwal, $tanggalAkhir), 'laporan-pengembalian.xlsx');
    }

    public function kepalaPdf(Request $request)
    {
    $query = Peminjaman::with(['anggota', 'buku']);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('from')) {
        $query->where('tanggal_pinjam', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->where('tanggal_pinjam', '<=', $request->to);
    }

    $data = $query->orderByDesc('tanggal_pinjam')->get();

    $pdf = Pdf::loadView('exports.kepala-laporan-peminjaman', [
        'data' => $data,
        'tanggalAwal' => $request->from,       // tambahkan ini
        'tanggalAkhir' => $request->to,        // tambahkan ini
        'status' => $request->status ?? null   // optional jika ingin ditampilkan di view
    ])->setPaper('a4', 'landscape');

    return $pdf->download('laporan-peminjaman-kepala.pdf');
    }
}
