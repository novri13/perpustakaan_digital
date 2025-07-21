<?php

namespace App\Filament\Pustakawan\Resources\KategoriResource\Pages;

use App\Filament\Pustakawan\Resources\KategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKategori extends CreateRecord
{
    protected static string $resource = KategoriResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
