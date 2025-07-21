<?php

namespace App\Filament\Pustakawan\Resources;

use App\Filament\Pustakawan\Resources\PeminjamanResource\Pages;

use App\Filament\Pustakawan\Resources\PeminjamanResource\RelationManagers;
use App\Models\Peminjaman;
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
use Filament\Tables\Actions\Action;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Peminjaman Buku';
    

    public static function form(Form $form): Form
    {
        return $form->schema([
            View::make('filament.admin.components.scan-peminjaman'),

            //Hanya menampilkan anggota aktif
            Select::make('anggota_id')
            ->label('Anggota Aktif')
            ->options(
                \App\Models\Anggota::where('status', 'aktif')
                    ->get()
                    ->mapWithKeys(fn($anggota) => [
                        $anggota->id => "{$anggota->nama} ({$anggota->id})"
                    ])
            )
            ->searchable()
            ->required(),

            //Hanya menampilkan buku dengan stok > 0
            Select::make('buku_id')
            ->label('Buku (Stok Tersedia)')
            ->options(
                \App\Models\Buku::where('stok', '>', 0)
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
                    'kembali' => 'Dikembalikan',
                ])
                ->default('dipinjam')
                ->required(),

            Select::make('denda_id')
                ->label('Denda (Jika Ada)')
                ->relationship('denda', 'harga')
                ->nullable()
                ->hidden(fn ($get) => $get('status') !== 'kembali'),
        ]);
    }

    public static function table(Table $table): Table
{
    return $table->columns([
        // Kode Pinjam hasil generate dari id
        TextColumn::make('kode_peminjaman')
            ->label('Kode Pinjam')
            ->getStateUsing(fn ($record) => 'P' . str_pad($record->id, 6, '0', STR_PAD_LEFT))
            ->sortable()
            ->searchable(),

        // ID Anggota
        TextColumn::make('anggota.id') // pastikan kolom ini ada di tabel `anggota`
            ->label('NIP/NISN')
            ->searchable(),

        // Nama Peminjam
        TextColumn::make('anggota.nama')
            ->label('Nama Peminjam')
            ->searchable(),

        // Tanggal Pinjam
        TextColumn::make('tanggal_pinjam')
            ->label('Tanggal Pinjam')
            ->date('d-m-Y'),

        // Jatuh Tempo
        TextColumn::make('tanggal_kembali')
            ->label('Jatuh Tempo')
            ->date('d-m-Y'),

        // Jumlah Buku
        TextColumn::make('jumlah_buku')
            ->label('Jumlah Buku'),

        // Denda
        TextColumn::make('denda.harga')
            ->label('Denda')
            ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : 'Tidak ada Denda'),

        // Status
        TextColumn::make('status')
            ->label('Status')
            ->badge()
            ->color(fn ($state) => match ($state) {
                'dipinjam' => 'warning',
                'diperpanjang' => 'info',
                'kembali' => 'success',
                default => 'gray',
            }),
    ])
    ->actions([
        // Detail Modal (View Only)
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Peminjaman')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('filament.admin.components.peminjaman-detail', [
                        'record' => $record,
                    ])),

    // Perpanjang hanya jika belum dikembalikan
    Tables\Actions\Action::make('perpanjang')
        ->label('Perpanjang')
        ->icon('heroicon-o-arrow-path-rounded-square')
        ->color('info')
        ->visible(fn ($record) => $record->status !== 'kembali')
        ->requiresConfirmation()
        ->action(function ($record) {
            $record->update([
                'status' => 'diperpanjang',
                'tanggal_kembali' => now()->addDays(7),
            ]);
        }),

    // Hapus hanya jika belum dikembalikan
    Tables\Actions\DeleteAction::make()
        ->label('Hapus')
        ->visible(fn ($record) => $record->status !== 'kembali'),
    ])
    ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
    ]);
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamen::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }

}
