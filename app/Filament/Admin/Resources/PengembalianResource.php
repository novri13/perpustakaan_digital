<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengembalianResource\Pages;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Pengembalian;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Modal\Modal;

class PengembalianResource extends Resource
{
    protected static ?string $model = Pengembalian::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pengembalian Buku';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Peminjaman::query()->whereIn('status', ['dipinjam', 'diperpanjang']))
            ->columns([
                TextColumn::make('kode_peminjaman')
                    ->label('Kode Pinjam')
                    ->getStateUsing(fn ($record) => 'P' . str_pad($record->id, 6, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('anggota.id')
                    ->label('NIP/NISN')
                    ->searchable(),

                TextColumn::make('anggota.nama')
                    ->label('Nama Peminjam')
                    ->searchable(),

                TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->date('d-m-Y'),

                TextColumn::make('tanggal_kembali')
                    ->label('Tanggal Kembali')
                    ->date('d-m-Y'),

                TextColumn::make('denda.harga')
                    ->label('Denda')
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : 'Rp 0'),

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
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Peminjaman')
                    ->modalCancelAction(false) // <--- Ini menghilangkan tombol Cancel
                    ->modalSubmitAction(
                        fn () => null
                    )
                    ->modalSubmitActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('filament.admin.components.detail-peminjaman-modal', compact('record'))),

                Action::make('kembalikan')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $today = now()->startOfDay();
                        $jatuhTempo = \Carbon\Carbon::parse($record->tanggal_kembali)->startOfDay();

                        $selisihHari = $today->greaterThan($jatuhTempo)
                            ? $today->diffInDays($jatuhTempo)
                            : 0;

                        $dendaAmount = 0;

                        if ($selisihHari > 0) {
                            $denda = Denda::create([
                                'lama_waktu' => $selisihHari,
                                'harga' => $selisihHari * 1000,
                            ]);
                            $record->denda_id = $denda->id;
                            $dendaAmount = $denda->harga;
                        }

                        $record->status = 'kembali';
                        $record->save();

                        $buku = \App\Models\Buku::find($record->buku_id);
                        if ($buku) {
                            $buku->increment('stok', $record->jumlah_buku);
                        }

                        // Kirim notifikasi ke anggota
                        if ($record->anggota) {
                            $record->anggota->notify(new \App\Notifications\BukuDikembalikanNotification($record, $dendaAmount));
                        }
                    })
                    ->visible(fn ($record) => $record->status === 'dipinjam'),

                DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengembalians::route('/'),
        ];
    }
}
