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
        // Jika kosong, auto-generate ID jurusan
        if (empty($data['id'])) {
            $data['id'] = $this->generateNextJurusanId();
        } else {
            // Validasi format ID manual (harus J + 3 angka)
            if (!preg_match('/^J\d{3}$/', $data['id'])) {
                throw ValidationException::withMessages([
                    'id' => 'Format ID Jurusan harus seperti J001, J002, dst.',
                ]);
            }

            // Validasi unik
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
     * Cari ID kosong dulu, kalau tidak ada lanjut urutan terakhir
     */
    private function generateNextJurusanId(): string
    {
        $allIds = Jurusan::pluck('id')->toArray();

        $nextNumber = 1;
        while (true) {
            $candidate = 'J' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            if (! in_array($candidate, $allIds)) {
                return $candidate; // pakai yang kosong
            }
            $nextNumber++;
        }
    }
}
