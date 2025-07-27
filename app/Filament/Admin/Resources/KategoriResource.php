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
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?int $navigationSort = 1;

    public static function getLabel(): ?string
    {
        return 'Kategori';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Kategori';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // ✅ ID otomatis seperti K001, K002
            TextInput::make('id')
                ->label('ID Kategori')
                ->default(fn () => self::generateNextKategoriId())
                ->disabled()
                ->dehydrated(true),

            TextInput::make('name')
                ->label('Nama Kategori')
                ->required()
                ->unique(
                    table: 'kategori',
                    column: 'name',
                    ignoreRecord: true
                )
                ->validationMessages([
                    'required' => 'Nama kategori wajib diisi.',
                    'unique'   => 'Nama Kategori ini sudah ada, silakan gunakan nama lain.',
                ]),

            FileUpload::make('gambar')
                ->label('Gambar')
                ->image()
                ->directory('kategori'),

            Select::make('rak_id')
                ->label('Rak Buku')
                ->options(\App\Models\Rak::pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),
        ]);
    }

    /** ✅ Generate ID otomatis seperti K001, K002 */
    public static function generateNextKategoriId(): string
    {
        $allIds = Kategori::pluck('id')->toArray();
        $nextNumber = 1;

        while (true) {
            $candidate = 'K' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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
            TextColumn::make('name')->label('Nama')->searchable(),
            ImageColumn::make('gambar')->label('Gambar')->size(50),
            TextColumn::make('rak.name')->label('Rak')->sortable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
                ->modalHeading('Hapus Kategori')
                ->modalDescription('Apakah Anda yakin ingin menghapus kategori ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()
                ->modalHeading('Hapus Beberapa Kategori')
                ->modalDescription('Apakah Anda yakin ingin menghapus semua kategori yang dipilih?')
                ->modalSubmitActionLabel('Ya, Hapus Semua')
                ->modalCancelActionLabel('Batal'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }

    /** Batasi hanya admin */
    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
