<?php

namespace App\Filament\Admin\Resources\TransaksiDendaResource\Pages;

use App\Filament\Admin\Resources\TransaksiDendaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiDenda extends EditRecord
{
    protected static string $resource = TransaksiDendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
