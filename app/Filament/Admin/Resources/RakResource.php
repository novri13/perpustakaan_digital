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
    protected static ?string $navigationLabel = 'Rak';
    protected static ?int $navigationSort = 2;

    public static function getLabel(): ?string
    {
        return 'Rak';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Rak';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id')
                ->label('ID Rak')
                ->default(fn () => self::generateNextRakId())
                ->disabled()
                ->dehydrated(true),

            TextInput::make('name')
                ->label('Nama Rak')
                ->required()
                ->unique(
                    table: 'rak',
                    column: 'name',
                    ignoreRecord: true
                )
                ->validationMessages([
                    'required' => 'Nama Rak wajib diisi.',
                    'unique' => 'Nama Rak ini sudah ada, silakan gunakan nama lain.',
                ]),
        ]);
    }

    /** Generate ID otomatis seperti R001 */
    public static function generateNextRakId(): string
    {
        $allIds = Rak::pluck('id')->toArray();
        $nextNumber = 1;

        while (true) {
            $candidate = 'R' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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
            TextColumn::make('name')->label('Nama Rak')->searchable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
                ->modalHeading('Hapus Rak')
                ->modalDescription('Apakah Anda yakin ingin menghapus rak ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()
                ->modalHeading('Hapus Beberapa Rak')
                ->modalDescription('Apakah Anda yakin ingin menghapus semua rak yang dipilih?')
                ->modalSubmitActionLabel('Ya, Hapus Semua')
                ->modalCancelActionLabel('Batal'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRaks::route('/'),
            'create' => Pages\CreateRak::route('/create'),
            'edit' => Pages\EditRak::route('/{record}/edit'),
        ];
    }

    /** Batasi hanya admin & pustakawan */
    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole('admin');
    }
}
