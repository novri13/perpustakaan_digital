<?php

namespace App\Filament\KepalaSekolah\Resources\LaporanResource\Pages;

use App\Filament\KepalaSekolah\Resources\LaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporan extends EditRecord
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
