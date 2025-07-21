<?php

namespace App\Filament\Pustakawan\Resources\BukuResource\Pages;

use App\Filament\Pustakawan\Resources\BukuResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBuku extends ViewRecord
{
    protected static string $resource = BukuResource::class;

     // Tentukan custom blade
    protected static string $view = 'filament.admin.buku.view-buku';

    // Kirim data tambahan ke blade
    protected function getViewData(): array
    {
        $buku = $this->record;

    // Hitung jumlah buku yang sedang dipinjam
    $sedangDipinjam = \App\Models\Peminjaman::where('buku_id', $buku->id)
    ->where('status', 'dipinjam')
    ->count();

    return [
        'record' => $buku,
        'buku' => $buku,
        'sedangDipinjam' => $sedangDipinjam,
    ];
    }
}