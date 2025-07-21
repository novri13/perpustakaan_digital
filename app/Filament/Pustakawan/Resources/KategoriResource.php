<?php

namespace App\Filament\Pustakawan\Resources;

use App\Filament\Pustakawan\Resources\KategoriResource\Pages;
use App\Filament\Pustakawan\Resources\KategoriResource\RelationManagers;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class KategoriResource extends Resource
{
     protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id')
                ->label('Kode Kategori')
                ->required()
                ->maxLength(25),

            TextInput::make('name')
                ->label('Nama Kategori')
                ->required()
                ->maxLength(100),

            FileUpload::make('gambar')
                ->label('Gambar')
                ->image()
                ->directory('kategori'),

            Select::make('rak_id')
                ->label('Rak')
                ->relationship('rak', 'name')
                ->searchable()
                ->preload()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('Kode')->searchable(),
            TextColumn::make('name')->label('Nama Kategori')->searchable(),
            TextColumn::make('rak.name')->label('Rak')->sortable(),
            ImageColumn::make('gambar')->label('Gambar')->circular(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return !auth()->user()?->hasRole('pustakawan'); //!auth() tanda itu tidak menampilkan
    }
}
