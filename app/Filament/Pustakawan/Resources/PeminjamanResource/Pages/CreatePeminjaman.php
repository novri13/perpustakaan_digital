<?php

namespace App\Filament\Pustakawan\Resources\PeminjamanResource\Pages;

use App\Filament\Pustakawan\Resources\PeminjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\Buku;
use Filament\Notifications\Notification;

class CreatePeminjaman extends CreateRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected $listeners = ['setAnggota', 'setBuku'];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $buku = Buku::find($data['buku_id']);

        if ($buku && $data['jumlah_buku'] > $buku->stok) {
            Notification::make()
                ->title('Stok buku tidak mencukupi')
                ->body('Jumlah buku yang diminta melebihi stok yang tersedia.')
                ->danger()
                ->send();

            // Batalkan proses create
            $this->halt(); // hentikan proses simpan
        }

        $data['status'] = 'dipinjam';
        return $data;
    }

    protected function afterCreate(): void
    {
        $buku = Buku::find($this->record->buku_id);

        if ($buku && $this->record->jumlah_buku <= $buku->stok) {
            $buku->decrement('stok', $this->record->jumlah_buku);
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    public function setAnggota($anggotaId)
    {
        $this->form->fill(['anggota_id' => $anggotaId]);
    }

    public function setBuku($bukuId)
    {
        $this->form->fill(['buku_id' => $bukuId]);
    }
}
