<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransaksiDendaResource\Pages;
use App\Models\TransaksiDenda;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class TransaksiDendaResource extends Resource
{
    protected static ?string $model = TransaksiDenda::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Denda Anggota';

    public static function getLabel(): ?string
    {
        return 'Transaksi Denda';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Transaksi Denda';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('peminjaman_id')
                ->label('Peminjaman')
                ->relationship('peminjaman', 'id')
                ->disabled(),

            TextInput::make('jumlah_denda')
                ->label('Jumlah Denda (Rp)')
                ->numeric()
                ->disabled(),

            Select::make('status_bayar')
                ->label('Status Pembayaran')
                ->options([
                    'belum' => 'Belum Dibayar',
                    'sudah' => 'Sudah Dibayar',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn ($query) =>
            $query->whereHas('peminjaman', function ($q) {
                $q->whereIn('status', ['dipinjam', 'diperpanjang', 'pending', 'kembali']);
            })
        )
        ->columns([
            TextColumn::make('peminjaman.kode_peminjaman')
                ->label('Kode Peminjaman')
                ->sortable()
                ->searchable(),

            TextColumn::make('peminjaman.anggota.nama')
                ->label('Nama Anggota')
                ->sortable()
                ->searchable(),

            TextColumn::make('jumlah_denda')
                ->label('Jumlah Denda')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

            TextColumn::make('status_bayar')
                ->label('Status')
                ->badge()
                ->color(fn ($state) => $state === 'sudah' ? 'success' : 'danger'),
        ])
        ->filters([
            Filter::make('belum')
                ->label('Belum Dibayar')
                ->query(fn ($query) => $query->where('status_bayar', 'belum')),

            Filter::make('sudah')
                ->label('Sudah Dibayar')
                ->query(fn ($query) => $query->where('status_bayar', 'sudah')),
        ])
        ->headerActions([
            // ✅ Tombol kecil di kiri Search
            Action::make('semua')
                ->label('Semua')
                ->color('secondary')
                ->size('sm')
                ->url(fn () => url('/admin/transaksi-dendas')),

            Action::make('belum')
                ->label('Belum')
                ->color('danger')
                ->size('sm')
                ->url(fn () => url('/admin/transaksi-dendas?tableFilters[belum][isActive]=true')),

            Action::make('sudah')
                ->label('Sudah')
                ->color('success')
                ->size('sm')
                ->url(fn () => url('/admin/transaksi-dendas?tableFilters[sudah][isActive]=true')),
        ])
        ->actions([
            Action::make('tandai_lunas')
                ->label('Tandai Lunas')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status_bayar === 'belum')
                ->action(function ($record) {
                    $record->update(['status_bayar' => 'sudah']);

                    $peminjaman = $record->peminjaman;
                    $masihAdaDendaBelumLunas = $peminjaman
                        ->transaksiDenda()
                        ->where('status_bayar', 'belum')
                        ->exists();

                    if (! $masihAdaDendaBelumLunas) {
                        $buku = $peminjaman->buku;
                        if ($buku) {
                            $buku->stok += $peminjaman->jumlah_buku;
                            $buku->save();
                        }

                        $peminjaman->update([
                            'status_denda' => 'lunas',
                            'status' => 'kembali',
                            'tanggal_dikembalikan' => now(),
                            ]);
                        }
                    }),

                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksiDenda::route('/'),
            'edit' => Pages\EditTransaksiDenda::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['admin', 'pustakawan']);
    }
}
