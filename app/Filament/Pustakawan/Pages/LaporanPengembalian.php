<?php

namespace App\Filament\Pustakawan\Pages;

use App\Models\Peminjaman;
use Filament\Pages\Page;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class LaporanPengembalian extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static string $view = 'filament.admin.pages.laporan-pengembalian';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Pengembalian';

    public ?string $tanggalAwal = null;
    public ?string $tanggalAkhir = null;
    public array $data = [];
    public bool $showData = false; // ✅ Tambahkan ini

    public function mount()
    {
        $this->tanggalAwal = now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = now()->endOfMonth()->format('Y-m-d');
    }

    public function getData()
    {
        $this->data = Peminjaman::with(['anggota', 'denda'])
            ->where('status', 'kembali')
            ->whereBetween('tanggal_kembali', [$this->tanggalAwal, $this->tanggalAkhir])
            ->get()
            ->toArray();

        $this->showData = true; // ✅ Jangan lupa aktifkan ini
    }
}
