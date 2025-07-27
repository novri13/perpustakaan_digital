<?php

namespace App\Filament\Admin\Resources\TransaksiDendaResource\Pages;

use App\Filament\Admin\Resources\TransaksiDendaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiDenda extends ListRecords
{
    protected static string $resource = TransaksiDendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
