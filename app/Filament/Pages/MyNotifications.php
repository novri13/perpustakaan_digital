<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MyNotifications extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.my-notifications';

    protected static ?string $title = 'Notifikasi Saya';
    protected static ?string $navigationLabel = 'Notifikasi';
    protected static ?string $navigationGroup = 'Profil';

    public static function shouldRegisterNavigation(): bool
    {
        // ✅ Hanya tampil untuk anggota
        return auth()->checkdate() && auth()->user()->hasRole('anggota');
    }
}
