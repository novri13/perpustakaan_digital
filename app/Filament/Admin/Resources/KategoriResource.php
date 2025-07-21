<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KategoriResource\Pages;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Kategori Buku';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id')
                ->label('ID Kategori')
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
                ->options(\App\Models\Rak::pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('name')->label('Nama')->searchable(),
            ImageColumn::make('gambar')->label('Gambar')->size(50),
            TextColumn::make('rak.name')->label('Rak')->sortable(),
        ])
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

    // 🛡️ Batasi akses hanya untuk admin
    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
