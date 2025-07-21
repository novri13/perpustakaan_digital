<?php

namespace App\Filament\Pustakawan\Resources\PengembalianResource\Pages;

use App\Filament\Pustakawan\Resources\PengembalianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengembalian extends CreateRecord
{
    protected static string $resource = PengembalianResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
