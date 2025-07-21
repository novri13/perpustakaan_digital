<?php

namespace App\Filament\Admin\Resources\PeminjamanResource\Pages;

use App\Filament\Admin\Resources\PeminjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Buku;

class EditPeminjaman extends EditRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $peminjaman = $this->record;

        // Jika status menjadi "kembali", tambah stok bukunya kembali
        if ($peminjaman->status === 'kembali') {
            $buku = Buku::find($peminjaman->buku_id);

            if ($buku) {
                $buku->increment('stok', $peminjaman->jumlah_buku);
            }
        }
    }
}
