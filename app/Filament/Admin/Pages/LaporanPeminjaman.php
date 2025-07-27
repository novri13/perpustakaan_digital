<?php

namespace App\Filament\Admin\Pages;

use App\Models\Peminjaman;
use Filament\Pages\Page;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class LaporanPeminjaman extends Page
{
    use WithPagination;

    public ?string $tanggalAwal = null;
    public ?string $tanggalAkhir = null;
    public array $data = [];

    public bool $showData = false; // 👈 Tambahkan flag ini

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.admin.pages.laporan-peminjaman';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Peminjaman';

    public function mount()
    {
        // Default kosongkan, hanya atur tanggal
        $this->tanggalAwal = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function getData()
    {
        $this->data = Peminjaman::with(['anggota', 'buku', 'denda'])
            ->where('status', 'Dipinjam')
            ->whereBetween('tanggal_pinjam', [$this->tanggalAwal, $this->tanggalAkhir])
            ->latest()
            ->get()
            ->toArray();

        $this->showData = true; // aktifkan tampilan data
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['admin','pustakawan','kepala_sekolah']);
    }
}

