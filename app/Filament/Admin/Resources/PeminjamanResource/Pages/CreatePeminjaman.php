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
    

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected $listeners = [
        'setAnggotaId' => 'setAnggotaId',
        'setBukuId' => 'setBukuId',
        'qr-scanned' => 'handleQrScan',
    ];

    public $scanAnggotaId = null;
    public $scanBukuId = null;

    public function scanQrResult($qrValue)
    {
        // Cek apakah anggota
        if ($anggota = \App\Models\Anggota::find($qrValue)) {
            $this->form->fill([
                'anggota_id' => $anggota->id,
            ]);
        }

        // Cek apakah buku
        if ($buku = \App\Models\Buku::find($qrValue)) {
            $this->form->fill([
                'buku_id' => $buku->id,
            ]);
        }
    }

    // Dipanggil dari view scan
    public function handleQrScan($qrValue, $mode = 'anggota')
    {
        if ($mode === 'anggota') {
            $anggota = Anggota::find($qrValue);

            if (! $anggota) {
                // QR ternyata buku, bukan anggota
                if (Buku::find($qrValue)) {
                    Notification::make()
                        ->title('QR Code ini bukan ID Anggota')
                        ->body("Ini adalah ID Buku dengan kode {$qrValue}")
                        ->danger()
                        ->send();
                } else {
                    Notification::make()
                        ->title('QR Code tidak valid')
                        ->body("Kode {$qrValue} tidak ditemukan di sistem.")
                        ->danger()
                        ->send();
                }
                return;
            }

            // Jika valid anggota → isi otomatis
            $this->scanAnggotaId = $anggota->id;
            $this->form->fill([
                'anggota_id' => $anggota->id,
            ]);

            Notification::make()
                ->title('Anggota berhasil terdeteksi')
                ->body("Anggota: {$anggota->nama}")
                ->success()
                ->send();
        }

        if ($mode === 'buku') {
            $buku = Buku::find($qrValue);

            if (! $buku) {
                // QR ternyata anggota, bukan buku
                if (Anggota::find($qrValue)) {
                    Notification::make()
                        ->title('QR Code ini bukan ID Buku')
                        ->body("Ini adalah ID Anggota dengan kode {$qrValue}")
                        ->danger()
                        ->send();
                } else {
                    Notification::make()
                        ->title('QR Code tidak valid')
                        ->body("Kode {$qrValue} tidak ditemukan di sistem.")
                        ->danger()
                        ->send();
                }
                return;
            }

            // Jika valid buku → isi otomatis
            $this->scanBukuId = $buku->id;
            $this->form->fill([
                'buku_id' => $buku->id,
            ]);

            Notification::make()
                ->title('Buku berhasil terdeteksi')
                ->body("Buku: {$buku->judul}")
                ->success()
                ->send();
        }
    }

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
        $this->form->fill(['anggota_id' => $id]);

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
        $this->form->fill(['buku_id' => $id]);

        Notification::make()
            ->title('✅ Buku terdeteksi')
            ->success()
            ->body("{$buku->judul} siap dipinjam.")
            ->send();
    }

    protected function afterCreate(): void
    {
        // Kurangi stok buku
        if ($this->record->buku_id) {
            $buku = Buku::find($this->record->buku_id);

            if ($buku && $buku->stok > 0) {
                $buku->decrement('stok');

                Notification::make()
                    ->title("{$buku->judul} berhasil dipinjam & stok otomatis diperbarui.")
                    ->success()
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
        // dd();
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

    public function updatedScanAnggotaId($value)
    {
        // Jika berhasil scan anggota, set ke form
        $this->form->fill([
            'anggota_id' => $value,
        ]);
    }

    public function updatedScanBukuId($value)
    {
        // Jika berhasil scan buku, set ke form
        $this->form->fill([
            'buku_id' => $value,
        ]);
    }

    //Tabel Create Peminjaman
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
