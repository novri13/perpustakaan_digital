<?php

namespace App\Filament\Pustakawan\Resources\PenerbitResource\Pages;

use App\Filament\Pustakawan\Resources\PenerbitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenerbit extends EditRecord
{
    protected static string $resource = PenerbitResource::class;

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
