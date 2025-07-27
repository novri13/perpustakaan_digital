<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\Action;
use App\Models\Peminjaman;
use Filament\Notifications\Notification;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $label = 'Booking Buku';
    protected static ?string $pluralLabel = 'Daftar Booking Buku';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('anggota_id')
                ->relationship('anggota', 'nama'),

            Forms\Components\Select::make('buku_id')
                ->relationship('buku', 'judul'),

            Forms\Components\Textarea::make('catatan')
                ->label('Catatan / Alasan Penolakan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anggota.nama')
                    ->label('Anggota')
                    ->searchable(),

                Tables\Columns\TextColumn::make('buku.judul')
                    ->label('Buku')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Booking')
                    ->dateTime('d M Y H:i'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'booking',
                        'success' => 'dipinjam',
                        'danger' => 'gagal',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'booking' => 'Booking',
                        'dipinjam' => 'Disetujui',
                        'gagal' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                // Approve Booking langsung dari List
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'booking')
                    ->requiresConfirmation()
                    ->action(fn (Booking $record) => self::approveBooking($record)),

                // Reject Booking langsung dari List
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'booking')
                    ->form([
                        Forms\Components\Textarea::make('catatan'),
                    ])
                    ->action(fn (Booking $record, array $data) => self::rejectBooking($record, $data['catatan'])),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->status !== 'dipinjam'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function approveBooking(Booking $record)
    {
        // Cek stok buku dulu
        $buku = $record->buku;
        if ($buku->stok < 1) {
            Notification::make()->title('Stok buku habis')->danger()->send();
            return;
        }

        // Kurangi stok buku
        $buku->decrement('stok', 1);

        // Update status
        $record->update([
            'status' => 'dipinjam',
            'approved_at' => now(),
        ]);

        // Simpan ke tabel peminjaman jika ada
        Peminjaman::create([
            'anggota_id' => $record->anggota_id,
            'buku_id' => $record->buku_id,
            'tgl_pinjam' => now(),
            'status' => 'dipinjam',
        ]);

        Notification::make()->title('Booking disetujui')->success()->send();
    }

    public static function rejectBooking(Booking $record, string $reason)
    {
        $buku = $record->buku;

    // Kembalikan stok jika sebelumnya sudah dikurangi
    if ($record->status === 'dipinjam') {
        $buku->increment('stok', 1);
    }

    $record->update([
        'status' => 'gagal',
        'catatan' => $reason,
        'rejected_at' => now(),
    ]);

    Notification::make()->title('Booking ditolak')->warning()->send();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'view' => Pages\ViewBooking::route('/{record}'),
        ];
    }
}
