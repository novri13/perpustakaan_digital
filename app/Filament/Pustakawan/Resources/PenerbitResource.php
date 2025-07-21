<?php

namespace App\Filament\Pustakawan\Resources;

use App\Filament\Pustakawan\Resources\PenerbitResource\Pages;
use App\Filament\Pustakawan\Resources\PenerbitResource\RelationManagers;
use App\Models\Penerbit;
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

class PenerbitResource extends Resource
{
     protected static ?string $model = Penerbit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Penerbit';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id')
                ->label('Kode Penerbit')
                ->required()
                ->maxLength(25),

            TextInput::make('name')
                ->label('Nama Penerbit')
                ->required()
                ->maxLength(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('Kode')->searchable()->sortable(),
            TextColumn::make('name')->label('Nama Penerbit')->searchable()->sortable(),
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
            'index' => Pages\ListPenerbits::route('/'),
            'create' => Pages\CreatePenerbit::route('/create'),
            'edit' => Pages\EditPenerbit::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return !auth()->user()?->hasRole('pustakawan'); //!auth() tanda itu tidak menampilkan
    }
}
