<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RakResource\Pages;
use App\Models\Rak;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                ->label('ID Rak')
                ->required()
                ->maxLength(10),

            TextInput::make('name')
                ->label('Nama Rak')
                ->required()
                ->maxLength(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID Rak')->sortable(),
                TextColumn::make('name')->label('Nama Rak')->searchable(),
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
