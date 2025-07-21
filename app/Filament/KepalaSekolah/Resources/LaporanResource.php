<?php

namespace App\Filament\KepalaSekolah\Resources;

use App\Filament\KepalaSekolah\Resources\LaporanResource\Pages;
use App\Models\Peminjaman;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use Filament\Tables\Actions\Action;

class LaporanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anggota.nama')->label('Nama Anggota')->searchable(),
                Tables\Columns\TextColumn::make('buku.judul')->label('Judul Buku')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pinjam')->label('Tanggal Pinjam')->date(),
                Tables\Columns\TextColumn::make('tanggal_kembali')->label('Tanggal Kembali')->date(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'dipinjam' => 'warning',
                        'diperpanjang' => 'info',
                        'kembali' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'diperpanjang' => 'Diperpanjang',
                        'kembali' => 'Kembali',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('tanggal_pinjam')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('to')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->where('tanggal_pinjam', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->where('tanggal_pinjam', '<=', $data['to']));
                    }),
            ])
            ->headerActions([
                ExportAction::make('export')->label('Export Excel'),

                 Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->url(fn () => route('kepala.laporan.pdf', request()->query()))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('tanggal_pinjam', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('kepala_sekolah');
    }
}
