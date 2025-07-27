<?php

namespace App\Filament\Admin\Resources\KategoriResource\Pages;

use App\Filament\Admin\Resources\KategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKategori extends CreateRecord
{
    protected static string $resource = KategoriResource::class;
    
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
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
