<?php

namespace App\Filament\Admin\Resources\TransaksiDendaResource\Pages;

use App\Filament\Admin\Resources\TransaksiDendaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaksiDenda extends CreateRecord
{
    protected static string $resource = TransaksiDendaResource::class;

    // Hanya tampilkan tombol Create & Cancel (hilangkan Create & create another)
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Simpan')   // Ubah label tombol Create jadi "Simpan"
                ->submit('create'),
            $this->getCancelFormAction(),
        ];
    }
}
