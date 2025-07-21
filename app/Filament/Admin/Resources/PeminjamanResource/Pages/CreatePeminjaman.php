<?php

namespace App\Filament\Admin\Resources\PeminjamanResource\Pages;

use App\Filament\Admin\Resources\PeminjamanResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Anggota;

class CreatePeminjaman extends CreateRecord
{
    protected static string $resource = PeminjamanResource::class;

    public $scanAnggotaId = null;
    public $scanBukuId = null;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected $listeners = [
        'setAnggotaId' => 'setAnggotaId',
        'setBukuId' => 'setBukuId',
    ];

    public function setAnggotaId($id)
    {
        $anggota = Anggota::where('id', $id)->where('status', 'aktif')->first();

        if (!$anggota) {
            Notification::make()
                ->title('❌ Anggota tidak aktif atau tidak ditemukan!')
                ->danger()
                ->body('Hanya anggota dengan status AKTIF yang bisa meminjam buku.')
                ->send();
            return;
        }

        // Cek jumlah peminjaman aktif
        $pinjamanAktif = Peminjaman::where('anggota_id', $id)
            ->whereIn('status', ['dipinjam', 'diperpanjang'])
            ->count();

        if ($pinjamanAktif >= 3) {
            Notification::make()
                ->title('⚠️ Batas Maksimal Peminjaman!')
                ->danger()
                ->body("{$anggota->nama} sudah meminjam {$pinjamanAktif} buku. Maksimal hanya 3 buku aktif.")
                ->send();
            return;
        }

        $this->scanAnggotaId = $id;

        Notification::make()
            ->title('✅ Anggota terdeteksi')
            ->success()
            ->body("{$anggota->nama} siap meminjam buku. (Saat ini sudah pinjam {$pinjamanAktif} buku)")
            ->send();
    }

    public function setBukuId($id)
    {
        $buku = Buku::where('id', $id)->where('stok', '>', 0)->first();

        if (!$buku) {
            Notification::make()
                ->title('❌ Buku tidak ditemukan atau stok habis!')
                ->danger()
                ->send();
            return;
        }

        $this->scanBukuId = $id;

        Notification::make()
            ->title('✅ Buku terdeteksi')
            ->success()
            ->body("{$buku->judul} siap dipinjam.")
            ->send();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Kalau scan anggota, pakai hasil scan
        if ($this->scanAnggotaId) {
            $data['anggota_id'] = $this->scanAnggotaId;
        }

        // Kalau scan buku, pakai hasil scan
        if ($this->scanBukuId) {
            $data['buku_id'] = $this->scanBukuId;
        }

        // Cek limit pinjaman sebelum create
        $pinjamanAktif = Peminjaman::where('anggota_id', $data['anggota_id'])
            ->whereIn('status', ['dipinjam', 'diperpanjang'])
            ->count();

        if ($pinjamanAktif >= 3) {
            Notification::make()
                ->title('❌ Tidak bisa meminjam!')
                ->danger()
                ->body("Anggota ini sudah memiliki {$pinjamanAktif} buku aktif. Maksimal hanya 3 buku.")
                ->send();

            $this->halt();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Kurangi stok buku
        if ($this->record->buku_id) {
            $buku = Buku::find($this->record->buku_id);

            if ($buku && $buku->stok > 0) {
                $buku->decrement('stok');

                Notification::make()
                    ->title('✅ Peminjaman berhasil!')
                    ->success()
                    ->body("{$buku->judul} berhasil dipinjam & stok otomatis diperbarui.")
                    ->send();
            } else {
                Notification::make()
                    ->title('⚠️ Buku stok habis!')
                    ->warning()
                    ->body('Buku tidak ditemukan atau stok habis.')
                    ->send();
            }
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('anggota_id')
                ->label('Anggota Aktif')
                ->options(
                    Anggota::where('status', 'aktif')
                        ->get()
                        ->pluck('nama', 'id')
                )
                ->searchable()
                ->required(),

            Forms\Components\Select::make('buku_id')
                ->label('Buku (stok tersedia)')
                ->options(
                    Buku::where('stok', '>', 0)
                        ->get()
                        ->pluck('judul', 'id')
                )
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('tanggal_pinjam')
                ->default(now())
                ->label('Tanggal Pinjam')
                ->required(),

            Forms\Components\DatePicker::make('tanggal_kembali')
                ->default(now()->addDays(7))
                ->label('Tanggal Kembali')
                ->required(),

            ViewField::make('qr_scanner')
                ->view('filament.partials.qr-scanner')
                ->label('Scan QR Anggota / Buku'),
        ];
    }
}
