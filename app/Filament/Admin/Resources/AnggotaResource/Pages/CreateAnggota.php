<?php

namespace App\Filament\Admin\Resources\AnggotaResource\Pages;

use App\Filament\Admin\Resources\AnggotaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode; 
use App\Models\User;

class CreateAnggota extends CreateRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
         // Gunakan ID atau Nama sebagai isi QR
        $qrValue = $data['id'] ?? uniqid();

        // Buat file QR SVG
        $filename = 'qr_' . $qrValue . '.svg';
        $qrSvg = QrCode::format('svg')->size(300)->generate($qrValue);
        Storage::disk('public')->put('qrcodes/' . $filename, $qrSvg);

        // Simpan path ke kolom qr_code
        $data['qr_code'] = 'qrcodes/' . $filename;

        return $data;
    }

    protected function afterCreate(): void
    {
        $anggota = $this->record;

        if (!User::where('email', $anggota->email)->exists()) {
            $user = User::create([
                'name' => $anggota->nama,
                'email' => $anggota->email,
                'password' => $anggota->password,
            ]);

            $user->assignRole('anggota');

            // Hubungkan user_id ke anggota
            $anggota->user_id = $user->id;
            $anggota->save();
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
