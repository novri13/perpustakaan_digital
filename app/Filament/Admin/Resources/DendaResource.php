<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DendaResource\Pages;
use App\Models\Denda;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DendaResource extends Resource
{
    protected static ?string $model = Denda::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Denda';
    protected static ?int $navigationSort = 7;
    

    public static function getLabel(): ?string
    {
        return 'Denda';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Denda';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Jenis denda, wajib diisi
            TextInput::make('jenis_denda')
                ->label('Jenis Denda')
                ->required()
                ->unique(
                    table: 'denda',
                    column: 'jenis_denda',
                    ignoreRecord: true
                )
                ->validationMessages([
                    'required' => 'Jenis denda wajib diisi.',
                    'unique' => 'Jenis denda ini sudah ada.',
                ]),

            // Harga denda, wajib angka
            TextInput::make('harga')
                ->label('Harga Denda')
                ->numeric()
                ->required()
                ->prefix('Rp ')
                ->validationMessages([
                    'required' => 'Harga denda wajib diisi.',
                    'numeric' => 'Harga harus berupa angka.',
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('jenis_denda')->label('Jenis Denda')->searchable(),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', true) // format Rp 50.000
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Hapus Denda')
                    ->modalDescription('Apakah Anda yakin ingin menghapus jenis denda ini? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->modalHeading('Hapus Beberapa Denda')
                    ->modalDescription('Apakah Anda yakin ingin menghapus semua denda yang dipilih?')
                    ->modalSubmitActionLabel('Ya, Hapus Semua')
                    ->modalCancelActionLabel('Batal'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDendas::route('/'),
            'create' => Pages\CreateDenda::route('/create'),
            'edit' => Pages\EditDenda::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
