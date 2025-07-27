<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PeminjamanResource\Pages;
use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\TransaksiDenda;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;


class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Peminjaman Buku';

    public static function getLabel(): ?string
    {
        return 'Peminjaman Buku';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Peminjaman Buku';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Scan QR Code untuk peminjaman
            View::make('filament.admin.components.scan-peminjaman'),

            Select::make('anggota_id')
                ->label('Anggota Aktif')
                ->options(
                    Anggota::where('status', 'aktif')
                        ->get()
                        ->mapWithKeys(fn ($anggota) => [
                            $anggota->id => "{$anggota->nama} - {$anggota->id}"
                        ])
                )
                ->searchable()
                ->required(),

            Select::make('buku_id')
                ->label('Buku (Stok Tersedia)')
                ->options(
                    Buku::where('stok', '>', 0)
                        ->get()
                        ->mapWithKeys(fn($buku) => [
                            $buku->id => "{$buku->judul} (Stok: {$buku->stok})"
                        ])
                )
                ->searchable()
                ->required(),

            TextInput::make('jumlah_buku')
                ->label('Jumlah Buku')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required(),

            DatePicker::make('tanggal_pinjam')
                ->label('Tanggal Pinjam')
                ->default(now())
                ->required(),

            DatePicker::make('tanggal_kembali')
                ->label('Tanggal Kembali')
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'dipinjam' => 'Dipinjam',
                    'diperpanjang' => 'Diperpanjang',
                    'pending' => 'Pending',
                    'kembali' => 'Dikembalikan',
                ])
                ->default('dipinjam'),
        ]);
    }

    public static function table(Table $table): Table
    {
        static::cekDanBuatDenda();

        return $table
        // ✅ FILTER OTOMATIS: hanya tampilkan yang belum selesai
            ->query(
                Peminjaman::query()
                    ->where(function ($q) {
                        $q->where('status', '!=', 'kembali')     // jangan tampilkan yang sudah dikembalikan
                          ->orWhere('status_denda', '!=', 'lunas'); // kalau dendanya belum lunas tetap tampil
                    })
                    ->with(['anggota', 'buku']) // eager loading untuk mempercepat
            )
        
        ->columns([
            TextColumn::make('kode_peminjaman')
                ->label('Kode Pinjam')
                ->getStateUsing(fn ($record) => 'P' . str_pad($record->id, 6, '0', STR_PAD_LEFT))
                ->sortable()
                ->searchable(),

            TextColumn::make('anggota.id')->label('NIP/NISN')->searchable(),
            TextColumn::make('anggota.nama')->label('Nama Peminjam')->searchable(),
            TextColumn::make('buku.judul')->label('Judul Buku'),
            TextColumn::make('jumlah_buku')->label('Jumlah Buku'),

            TextColumn::make('tanggal_pinjam')->label('Tanggal Pinjam')->date('d-m-Y'),
            TextColumn::make('tanggal_kembali')->label('Jatuh Tempo')->date('d-m-Y'),

            TextColumn::make('tanggal_dikembalikan')
                ->label('Tanggal Dikembalikan')
                ->date('d-m-Y')
                ->placeholder('-'),

            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'dipinjam' => 'warning',
                    'diperpanjang' => 'info',
                    'pending' => 'danger',
                    'kembali' => 'success',
                    default => 'gray',
                }),

            TextColumn::make('status_denda')
                ->label('Status Denda')
                ->badge()
                ->color(fn ($state) => $state === 'lunas' ? 'success' : 'danger')
                ->formatStateUsing(fn ($state) => $state === 'lunas' ? 'Lunas' : 'Belum Lunas'),
        ])
        ->actions([
            Action::make('detail')
                ->label('Detail')
                ->icon('heroicon-o-eye')
                ->modalHeading('Detail Peminjaman')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->modalContent(fn ($record) => view('filament.admin.components.peminjaman-detail', [
                    'record' => $record,
                ])),

            Action::make('perpanjang')
                ->label('Perpanjang 7 Hari')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color('info')
                ->visible(fn ($record) => $record->status !== 'kembali')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->update([
                        'status' => 'diperpanjang',
                        'tanggal_kembali' => Carbon::parse($record->tanggal_kembali)->addDays(7),
                    ]);
                }),
            
            Action::make('kembalikan')
                ->label('Kembalikan Buku')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn ($record) => $record->status !== 'kembali')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $adaDendaBelumLunas = $record->transaksiDenda()
                        ->where('status_bayar', 'belum')
                        ->exists();

                    if ($adaDendaBelumLunas) {
                        // ✅ Kalau ada denda belum lunas, jadi pending
                        $record->update(['status' => 'pending']);
                    } else {
                        // ✅ Tambahkan stok langsung jika tidak ada denda
                        $buku = Buku::find($record->buku_id);
                        if ($buku) {
                            $buku->stok += $record->jumlah_buku;
                            $buku->save();
                        }

                        $record->update([
                            'status' => 'kembali',
                            'tanggal_dikembalikan' => now(),
                            'status_denda' => 'lunas'
                        ]);
                    }
                }),

            DeleteAction::make()
                ->label('Hapus')
                ->visible(fn ($record) => $record->status !== 'kembali')
                ->before(function ($record) {
                    $record->buku->increment('stok', $record->jumlah_buku);
                }),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    protected static function cekDanBuatDenda()
    {
        $peminjamanTerlambat = Peminjaman::whereIn('status', ['dipinjam','diperpanjang'])
            ->whereDate('tanggal_kembali', '<', now())
            ->get();

        foreach ($peminjamanTerlambat as $p) {
            $hariTerlambat = $p->hitungTerlambatHari();

            $existingDenda = $p->transaksiDenda()->where('status_bayar', 'belum')->exists();
            if ($existingDenda) continue;

            $jumlahDenda = $hariTerlambat * 1000; // contoh Rp1000/hari
            TransaksiDenda::create([
                'peminjaman_id' => $p->id,
                'jumlah_denda'  => $jumlahDenda,
                'status_bayar'  => 'belum',
            ]);

            $p->update(['status_denda' => 'belum_lunas']);
        }
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamans::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['admin','pustakawan']);
    }
    //1
}