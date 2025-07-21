<?php

namespace App\Filament\Admin\Resources\RakResource\Pages;

use App\Filament\Admin\Resources\RakResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRak extends CreateRecord
{
    protected static string $resource = RakResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
