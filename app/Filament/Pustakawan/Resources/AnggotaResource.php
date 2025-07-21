<?php

namespace App\Filament\Pustakawan\Resources;

use App\Filament\Pustakawan\Resources\AnggotaResource\Pages;
use App\Models\Anggota;
use App\Models\Jurusan;
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
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Data Anggota';
    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id')
                ->label('NIP/NISN')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('nama')
                ->label('Nama')
                ->required(),

            FileUpload::make('gambar')
                ->label('Foto')
                ->image()
                ->disk('public'),

            TextInput::make('kelas')
                ->label('Kelas')
                ->nullable(),

            Select::make('jenkel')
                ->label('Jenis Kelamin')
                ->options([
                    'L' => 'Laki-laki',
                    'P' => 'Perempuan',
                ])
                ->required(),

            Textarea::make('alamat')
                ->label('Alamat')
                ->nullable(),

            TextInput::make('no_telp')
                ->label('No. Telepon')
                ->nullable(),

            Select::make('jabatan')
                ->label('Jabatan')
                ->options([
                    'siswa' => 'Siswa',
                    'guru' => 'Guru',
                ])
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'aktif' => 'Aktif',
                    'tidak' => 'Tidak Aktif',
                ])
                ->required(),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->nullable(),

            Select::make('id_jurusan')
                ->label('Jurusan')
                ->options(Jurusan::pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),

            Hidden::make('qr_code'), // untuk menyimpan QR jika dibuat otomatis
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id'),
            TextColumn::make('nama'),
            ImageColumn::make('gambar')->label('Foto'),
            TextColumn::make('kelas'),
            TextColumn::make('jenkel')->label('JK'),
            TextColumn::make('jabatan'),
            TextColumn::make('jurusan.name')->label('Jurusan'),
            ImageColumn::make('qr_code')->label('QR Code'),
            TextColumn::make('status')->badge()->color(fn ($state) => $state === 'aktif' ? 'success' : 'danger'),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
            Action::make('showKartu')
                ->label('Detail')
                ->icon('heroicon-o-identification')
                ->modalHeading('Kartu Anggota Perpustakaan')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->form([])
                ->modalContent(function ($record) {
                    return new HtmlString(view('filament.admin.components.kartu-anggota-modal', [
                        'anggota' => $record,
                    ])->render());
                }),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return !auth()->user()?->hasRole('pustakawan'); //!auth() tanda itu tidak menampilkan
    }
}
