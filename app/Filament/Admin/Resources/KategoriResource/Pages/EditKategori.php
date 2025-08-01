<?php

namespace App\Filament\Admin\Resources\KategoriResource\Pages;

use App\Filament\Admin\Resources\KategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategori extends EditRecord
{
    protected static string $resource = KategoriResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
