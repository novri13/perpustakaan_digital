<?php

namespace App\Filament\Admin\Resources\JurusanResource\Pages;

use App\Filament\Admin\Resources\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Jurusan;
use Illuminate\Validation\ValidationException;

class EditJurusan extends EditRecord
{
    protected static string $resource = JurusanResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $oldId = $this->record->id;

        // Jika ID diganti
        if ($data['id'] !== $oldId) {
            // Validasi format ID (harus Jxxx)
            if (!preg_match('/^J\d{3}$/', $data['id'])) {
                throw ValidationException::withMessages([
                    'id' => 'Format ID Jurusan harus seperti J001, J002, dst.',
                ]);
            }

            // Pastikan tidak dipakai jurusan lain
            if (Jurusan::where('id', $data['id'])->where('id', '!=', $oldId)->exists()) {
                throw ValidationException::withMessages([
                    'id' => 'ID Jurusan ' . $data['id'] . ' sudah digunakan.',
                ]);
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
