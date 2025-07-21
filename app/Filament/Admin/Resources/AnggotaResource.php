<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AnggotaResource\Pages;
use App\Models\Anggota;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\Action;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Anggota Perpustakaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id')->label('NIP/NISN')->required(),

            TextInput::make('nama')->label('Nama')->required(),

            FileUpload::make('gambar')->label('Foto')
                ->image()
                ->disk('public'),

            Select::make('kelas')->label('Kelas')->options([
                'X' => 'Kelas 10',
                'XI' => 'Kelas 11',
                'XII' => 'Kelas 12',
            ])->nullable(),

            Select::make('jenkel')->label('Jenis Kelamin')->options([
                'L' => 'Laki-laki',
                'P' => 'Perempuan',
            ])->required(),

            Textarea::make('alamat')->label('Alamat')->nullable(),

            TextInput::make('no_telp')->label('No. Telepon')->nullable(),

            Select::make('jabatan')->label('Jabatan')->options([
                'siswa' => 'Siswa',
                'guru' => 'Guru',
            ])->required(),

            Select::make('status')->label('Status')->options([
                'aktif' => 'Aktif',
                'tidak' => 'Tidak Aktif',
            ])->required(),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            TextInput::make('password')->label('Password')
                ->password()
                // hanya required saat create
                ->required(fn (string $context) => $context === 'create')  
                // hanya hash kalau ada isinya
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                // kalau kosong saat edit, tidak diubah
                ->dehydrated(fn ($state) => filled($state)),


            Select::make('id_jurusan')->label('Jurusan')
                ->options(\App\Models\Jurusan::pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('NIP/NISN'),
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
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),

            Action::make('showKartu')
                ->label('Detail')
                ->icon('heroicon-o-identification')
                ->modalHeading('Kartu Anggota Perpustakaan')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->form([]) // Tidak ada form
                ->modalContent(function ($record) {
                    return view('filament.admin.components.kartu-anggota-modal', ['anggota' => $record]);
                }),
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
            'index' => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    
}
