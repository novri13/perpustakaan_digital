<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengembalianResource\Pages;
use App\Models\Peminjaman;
use App\Models\TransaksiDenda;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Carbon\Carbon;

class PengembalianResource extends Resource
{
    protected static ?string $model = Peminjaman::class; // langsung pakai Peminjaman

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pengembalian Buku';

    public static function getLabel(): ?string
    {
        return 'Pengembalian Buku';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Pengembalian Buku';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Tidak ada form khusus
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Peminjaman::query()
                    ->where('status', 'kembali')     // ✅ hanya yg sudah kembali
                    ->where('status_denda', 'lunas') // ✅ hanya yg sudah lunas
                    ->with(['anggota', 'buku'])      // ✅ eager loading supaya tidak N+1
            )
            ->columns([
                TextColumn::make('kode_peminjaman')
                    ->label('Kode Pinjam')
                    ->getStateUsing(fn ($record) => 'P' . str_pad($record->id, 6, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('anggota.id')
                    ->label('NIS/NIP')
                    ->default('-')
                    ->searchable(),

                TextColumn::make('anggota.nama')
                    ->label('Nama Peminjam')
                    ->default('-')
                    ->searchable(),

                TextColumn::make('buku.judul')
                    ->label('Judul Buku')
                    ->wrap()
                    ->default('-'),

                TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->date('d-m-Y'),

                TextColumn::make('tanggal_kembali')
                    ->label('Jatuh Tempo')
                    ->date('d-m-Y'),

                TextColumn::make('tanggal_dikembalikan')
                    ->label('Tanggal Dikembalikan')
                    ->date('d-m-Y'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'kembali',
                        'warning' => 'pending',
                        'info'    => 'dipinjam',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('status_denda')
                    ->label('Status Denda')
                    ->badge()
                    ->colors([
                        'success' => 'lunas',
                        'danger'  => 'belum_lunas',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'lunas' ? 'Lunas' : 'Belum Lunas'),
            ])
            ->actions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Pengembalian')
                    ->modalSubmitActionLabel('Tutup')
                    ->modalContent(fn ($record) => view(
                        'filament.admin.components.detail-peminjaman-modal',
                        compact('record')
                    )),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengembalians::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['admin', 'pustakawan']);
    }
}
