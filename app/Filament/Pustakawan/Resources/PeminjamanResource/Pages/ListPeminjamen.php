<?php

namespace App\Filament\Pustakawan\Resources\PeminjamanResource\Pages;

use App\Filament\Pustakawan\Resources\PeminjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamen extends ListRecords
{
    protected static string $resource = PeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
