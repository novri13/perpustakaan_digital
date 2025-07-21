<?php

namespace App\Filament\Admin\Resources\RakResource\Pages;

use App\Filament\Admin\Resources\RakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRak extends EditRecord
{
    protected static string $resource = RakResource::class;

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
