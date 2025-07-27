<?php

namespace App\Filament\Admin\Resources\DendaResource\Pages;

use App\Filament\Admin\Resources\DendaResource;
use Filament\Resources\Pages\EditRecord;

class EditDenda extends EditRecord
{
    protected static string $resource = DendaResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}