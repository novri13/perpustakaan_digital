<?php

namespace App\Filament\Admin\Resources\PengembalianResource\Pages;

use App\Filament\Admin\Resources\PengembalianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengembalian extends EditRecord
{
    protected static string $resource = PengembalianResource::class;

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
