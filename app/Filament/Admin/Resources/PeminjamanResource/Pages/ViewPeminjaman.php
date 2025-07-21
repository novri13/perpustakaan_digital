<?php

namespace App\Filament\Resources\PeminjamanResource\Pages;

use App\Filament\Admin\Resources\PeminjamanResource as ResourcesPeminjamanResource;
use App\Filament\Resources\PeminjamanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewPeminjaman extends ViewRecord
{
    protected static string $resource = ResourcesPeminjamanResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \Filament\Widgets\StatsOverviewWidget::make()
                ->stats([
                    \Filament\Widgets\StatsOverviewWidget\Stat::make(
                        'Total Denda',
                        'Rp ' . number_format($this->record->hitungDenda(), 0, ',', '.')
                    )
                    ->description('Hitung otomatis berdasarkan keterlambatan')
                    ->color($this->record->hitungDenda() > 0 ? 'danger' : 'success'),
                ]),
        ];
    }

    protected function getActions(): array
    {
        $actions = [];

        // ✅ Kalau ada denda & belum dibayar → tampilkan tombol Bayar Denda
        if ($this->record->hitungDenda() > 0 && !$this->record->denda_dibayar) {
            $actions[] = \Filament\Actions\Action::make('bayar_denda')
                ->label('Bayar Denda')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'denda_dibayar' => true,
                    ]);

                    Notification::make()
                        ->title('Denda telah ditandai sebagai dibayar')
                        ->success()
                        ->send();
                });
        }

        // ✅ Kalau sudah dibayar → tampilkan badge
        if ($this->record->denda_dibayar) {
            $actions[] = \Filament\Actions\Action::make('sudah_dibayar')
                ->label('✅ Denda Sudah Dibayar')
                ->disabled()
                ->color('success');
        }

        return array_merge($actions, parent::getActions());
    }
}
