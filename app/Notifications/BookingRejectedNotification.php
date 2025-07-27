<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Buku Ditolak')
            ->greeting("Halo {$notifiable->nama},")
            ->line("Maaf, booking buku **{$this->booking->buku->judul}** ditolak.")
            ->line("Alasan: {$this->booking->catatan}")
            ->action('Lihat Booking', url('/anggota/bookings'))
            ->line('Silakan coba booking buku lain.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Booking buku {$this->booking->buku->judul} ditolak. Alasan: {$this->booking->catatan}",
            'booking_id' => $this->booking->id,
        ];
    }
}
