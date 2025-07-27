<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\JurusanResource\Pages;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JurusanResource extends Resource
{
    protected static ?string $model = Jurusan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Jurusan';
    protected static ?int $navigationSort = 4;

    public static function getLabel(): ?string
    {
        return 'Jurusan';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Jurusan';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
        TextInput::make('id')
            ->label('ID Jurusan')
            ->default(fn () => self::generateNextJurusanId()) // AUTO GENERATE
            ->disabled() // Tidak bisa diubah manual
            ->dehydrated(true), // Tetap disimpan ke DB
          
        TextInput::make('name')
            ->label('Nama Jurusan')
            ->required()
            ->unique(
                table: 'jurusan',
                column: 'name',
                ignoreRecord: true
            )
            ->validationMessages([
                'required' => 'Nama Jurusan wajib diisi.',
                'unique' => 'Nama Jurusan ini sudah ada, silakan gunakan nama lain.',
            ]),
    ]);
    }

    // Generate ID Jurusan berikutnya, misal J001, J002, dst.
    public static function generateNextJurusanId(): string
    {
        $allIds = \App\Models\Jurusan::pluck('id')->toArray(); // semua ID ada
    $nextNumber = 1;

    while (true) {
        $candidate = 'J' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        if (! in_array($candidate, $allIds)) {
            return $candidate; // ambil ID yang kosong dulu
        }
        $nextNumber++;
    }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Nama Jurusan')->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->modalHeading('Hapus Jurusan')
                ->modalDescription('Apakah Anda yakin ingin menghapus jurusan ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                ->modalHeading('Hapus Beberapa Jurusan')
                ->modalDescription('Apakah Anda yakin ingin menghapus semua jurusan yang dipilih?')
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
            'index' => Pages\ListJurusans::route('/'),
            'create' => Pages\CreateJurusan::route('/create'),
            'edit' => Pages\EditJurusan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
