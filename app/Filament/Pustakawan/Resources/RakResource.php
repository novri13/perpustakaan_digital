<?php

namespace App\Filament\Pustakawan\Resources;

use App\Filament\Pustakawan\Resources\RakResource\Pages;
use App\Filament\Pustakawan\Resources\RakResource\RelationManagers;
use App\Models\Rak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RakResource extends Resource
{
    protected static ?string $model = Rak::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Rak Buku';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id')
                ->label('Kode Rak')
                ->maxLength(10)
                ->required(),

            TextInput::make('name')
                ->label('Nama Rak')
                ->maxLength(100)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('Kode')->searchable()->sortable(),
            TextColumn::make('name')->label('Nama Rak')->searchable()->sortable(),
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
            'index' => Pages\ListRaks::route('/'),
            'create' => Pages\CreateRak::route('/create'),
            'edit' => Pages\EditRak::route('/{record}/edit'),
        ];
    }
}
