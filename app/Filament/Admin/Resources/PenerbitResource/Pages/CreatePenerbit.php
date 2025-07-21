<?php

namespace App\Filament\Admin\Resources\PenerbitResource\Pages;

use App\Filament\Admin\Resources\PenerbitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenerbit extends CreateRecord
{
    protected static string $resource = PenerbitResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
