<?php

namespace App\Filament\Admin\Resources\PenerbitResource\Pages;

use App\Filament\Admin\Resources\PenerbitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenerbits extends ListRecords
{
    protected static string $resource = PenerbitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
