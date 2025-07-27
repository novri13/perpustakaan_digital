<?php

namespace App\Filament\Admin\Resources\PenerbitResource\Pages;

use App\Filament\Admin\Resources\PenerbitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenerbit extends EditRecord
{
    protected static string $resource = PenerbitResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
