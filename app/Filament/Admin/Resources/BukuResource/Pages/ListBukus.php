<?php

namespace App\Filament\Admin\Resources\BukuResource\Pages;

use App\Filament\Admin\Resources\BukuResource;
use App\Models\Buku;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListBukus extends ListRecords
{
    protected static string $resource = BukuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Lihat QR Code')
                ->label('Daftar QR Code')
                ->icon('heroicon-o-qr-code')
                ->modalHeading('QR Code Semua Buku')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->modalContent(function () {
                    $bukus = Buku::all();

                    return view('filament.admin.buku.modal-qr', compact('bukus'));
                }),
        ];
    }
}
