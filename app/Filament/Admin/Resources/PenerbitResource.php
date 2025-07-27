<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PenerbitResource\Pages;
use App\Models\Penerbit;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PenerbitResource extends Resource
{
    protected static ?string $model = Penerbit::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Penerbit';
    protected static ?int $navigationSort = 3;

    public static function getLabel(): ?string
    {
        return 'Penerbit';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Penerbit';
    }

    public static function form(Form $form): Form
    {
         return $form->schema([
            TextInput::make('id')
                ->label('ID Penerbit')
                ->default(fn () => self::generateNextPenerbitId())
                ->disabled()
                ->dehydrated(true),

            TextInput::make('name')
                ->label('Nama Penerbit')
                ->required()
                ->unique(
                    table: 'penerbit', // ✅ gunakan nama tabel plural
                    column: 'name',
                    ignoreRecord: true
                )
                ->validationMessages([
                    'required' => 'Nama Penerbit wajib diisi.',
                    'unique' => 'Nama Penerbit ini sudah ada.',
                ]),
        ]);
    }

    /** Generate ID otomatis seperti P001, P002, dst */
    public static function generateNextPenerbitId(): string
    {
        $allIds = Penerbit::pluck('id')->toArray();
        $nextNumber = 1;

        while (true) {
            $candidate = 'P' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            if (!in_array($candidate, $allIds)) {
                return $candidate;
            }
            $nextNumber++;
        }
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('name')->label('Nama Penerbit')->searchable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
                ->modalHeading('Hapus Penerbit')
                ->modalDescription('Apakah Anda yakin ingin menghapus penerbit ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()
                ->modalHeading('Hapus Beberapa Penerbit')
                ->modalDescription('Apakah Anda yakin ingin menghapus semua penerbit yang dipilih?')
                ->modalSubmitActionLabel('Ya, Hapus Semua')
                ->modalCancelActionLabel('Batal'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenerbits::route('/'),
            'create' => Pages\CreatePenerbit::route('/create'),
            'edit' => Pages\EditPenerbit::route('/{record}/edit'),
        ];
    }

    /** Batasi hanya admin & pustakawan */
    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole('admin');
    }
}
