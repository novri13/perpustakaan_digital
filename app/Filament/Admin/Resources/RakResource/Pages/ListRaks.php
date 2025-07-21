<?php

namespace App\Filament\Admin\Resources\RakResource\Pages;

use App\Filament\Admin\Resources\RakResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRaks extends ListRecords
{
    protected static string $resource = RakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
