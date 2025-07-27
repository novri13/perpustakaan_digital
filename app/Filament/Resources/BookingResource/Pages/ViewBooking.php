<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;


class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    // ✅ Tambahkan tombol Approve & Reject di halaman View
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve Booking')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn ($record) => $record->status === 'booking')
                ->requiresConfirmation()
                ->action(fn ($record) => BookingResource::approveBooking($record)),

            Actions\Action::make('reject')
                ->label('Reject Booking')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => $record->status === 'booking')
                ->form([
                    Actions\Action\Forms\Components\Textarea::make('catatan')
                        ->required()
                        ->label('Alasan Penolakan'),
                ])
                ->action(fn ($record, array $data) => BookingResource::rejectBooking($record, $data['catatan'])),
        ];
    }

    protected function getFormSchema(): array
    {
        return BookingResource::getViewSchema();
    }
}
