<?php

namespace App\Filament\Admin\Resources\JurusanResource\Pages;

use App\Filament\Admin\Resources\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Jurusan;
use Illuminate\Validation\ValidationException;


class CreateJurusan extends CreateRecord
{
    protected static string $resource = JurusanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika ID dikosongkan → auto generate
        if (empty($data['id'])) {
            $data['id'] = $this->generateNextJurusanId();
        } else {
            // Validasi format ID manual (harus J001, J002, dst)
            if (!preg_match('/^J\d{3}$/', $data['id'])) {
                throw ValidationException::withMessages([
                    'id' => 'Format ID Jurusan harus seperti J001, J002, dst.',
                ]);
            }

            // Pastikan tidak duplikat
            if (Jurusan::where('id', $data['id'])->exists()) {
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

    /**
     * Cari ID kosong terdekat, contoh:
     * - Jika J001, J003 ada → pilih J002
     * - Jika semua urut → lanjut J004
     */
    private function generateNextJurusanId(): string
    {
        $existingIds = Jurusan::pluck('id')->toArray();

        $nextNumber = 1;
        while (true) {
            $candidate = 'J' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            if (!in_array($candidate, $existingIds)) {
                return $candidate; // pakai slot kosong
            }
            $nextNumber++;
        }
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
