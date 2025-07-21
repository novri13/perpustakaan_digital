<?php

namespace App\Filament\Admin\Resources\BukuResource\Pages;

use App\Filament\Admin\Resources\BukuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode; 

class CreateBuku extends CreateRecord
{
    protected static string $resource = BukuResource::class;
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

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
