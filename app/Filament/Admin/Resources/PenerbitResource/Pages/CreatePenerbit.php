<?php

namespace App\Filament\Admin\Resources\PenerbitResource\Pages;

use App\Filament\Admin\Resources\PenerbitResource;
use App\Models\Penerbit;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenerbit extends CreateRecord
{
    protected static string $resource = PenerbitResource::class;

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
