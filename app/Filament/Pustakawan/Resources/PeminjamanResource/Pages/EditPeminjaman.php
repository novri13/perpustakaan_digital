<?php

namespace App\Filament\Pustakawan\Resources\PeminjamanResource\Pages;

use App\Filament\Pustakawan\Resources\PeminjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
}
