<?php

namespace App\Filament\Pustakawan\Resources\AnggotaResource\Pages;

use App\Filament\Pustakawan\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnggotas extends ListRecords
{
    protected static string $resource = AnggotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
