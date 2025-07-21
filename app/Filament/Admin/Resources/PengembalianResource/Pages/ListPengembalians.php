<?php

namespace App\Filament\Admin\Resources\PengembalianResource\Pages;

use App\Filament\Admin\Resources\PengembalianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengembalians extends ListRecords
{
    protected static string $resource = PengembalianResource::class;

    protected function getHeaderActions(): array
    {
        return [
                // Actions\CreateAction::make(),
            ];
    }
}
