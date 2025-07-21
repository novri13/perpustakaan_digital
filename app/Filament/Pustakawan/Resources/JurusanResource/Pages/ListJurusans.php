<?php

namespace App\Filament\Pustakawan\Resources\JurusanResource\Pages;

use App\Filament\Pustakawan\Resources\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJurusans extends ListRecords
{
    protected static string $resource = JurusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
