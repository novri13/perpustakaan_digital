<?php

namespace App\Filament\Admin\Resources\AnggotaResource\Pages;

use App\Filament\Admin\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;

class EditAnggota extends EditRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $anggota = $this->record;

        $user = User::where('email', $anggota->email)->first();

        if ($user) {
            $user->update([
                'name' => $anggota->nama,
                'password' => $anggota->password, 
            ]);
        }
    }

    protected function beforeDelete(): void
    {
        $anggota = $this->record;

        if ($anggota->user_id) {
            $user = User::find($anggota->user_id);

            if ($user && $user->hasRole('anggota')) {
                $user->delete();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
