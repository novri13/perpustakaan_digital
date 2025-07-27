<?php

namespace App\Filament\Admin\Resources\DendaResource\Pages;

use App\Filament\Admin\Resources\DendaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDenda extends CreateRecord
{
    protected static string $resource = DendaResource::class;

    // Setelah create langsung ke halaman list
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

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