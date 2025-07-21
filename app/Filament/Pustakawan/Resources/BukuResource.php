<?php

namespace App\Filament\Pustakawan\Resources;

use App\Filament\Pustakawan\Resources\BukuResource\Pages;
use App\Filament\Pustakawan\Resources\BukuResource\Pages\ViewBuku;
use App\Filament\Pustakawan\Resources\BukuResource\RelationManagers;
use App\Models\Buku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class BukuResource extends Resource
{
    protected static ?string $model = Buku::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Data Buku';

     public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id')->label('ID Buku')->required()->maxLength(20),
            TextInput::make('judul')->label('Judul Buku')->required(),
            FileUpload::make('gambar')->label('Gambar')->image()->directory('buku'),
            TextInput::make('pengarang')
            ->label('Pengarang')
            ->dehydrateStateUsing(fn ($state) => $state ?: '-')
            ->maxLength(255),
            TextInput::make('stok')->label('Stok')->numeric()->minValue(0)->required(),
            TextInput::make('edisi')->label('Edisi')->nullable(),
            TextInput::make('bahasa')->label('Bahasa')->nullable(),
            Select::make('tahun_terbit')->label('Tahun Terbit')->options(array_combine(range(date('Y'), 1980),range(date('Y'), 1980)))->searchable()->nullable(),
            DatePicker::make('tahun_masuk')->label('Buku Masuk')->nullable(),
            DatePicker::make('tahun_berubah')->label('Buku Berubah')->nullable(),
            Textarea::make('deskripsi')->label('Deskripsi')->nullable(),
            Select::make('id_kategori')->label('Kategori')->options(\App\Models\Kategori::pluck('name', 'id')->toArray())->searchable()->nullable(),
            Select::make('id_rak')->label('Rak')->options(\App\Models\Rak::pluck('name', 'id')->toArray())->searchable()->nullable(),
            Select::make('id_penerbit')->label('Penerbit')->options(\App\Models\Penerbit::pluck('name', 'id')->toArray())->searchable()->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('ID'),
            TextColumn::make('judul')->label('Judul')->searchable(),
            ImageColumn::make('gambar')->label('Gambar')->size(50),
            TextColumn::make('pengarang'),
            TextColumn::make('stok'),
            TextColumn::make('kategori.name')->label('Kategori'),
            TextColumn::make('rak.name')->label('Rak'),
            TextColumn::make('penerbit.name')->label('Penerbit'),
            ImageColumn::make('qr_code')->label('QR Code'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBukus::route('/'),
            'create' => Pages\CreateBuku::route('/create'),
            'edit' => Pages\EditBuku::route('/{record}/edit'),
            'view' => Pages\ViewBuku::route('/{record}'),
        ];
    }
}
