<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // ✅ Email + disimpan di DB
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Buku Disetujui')
            ->greeting("Halo {$notifiable->nama},")
            ->line("Booking buku **{$this->booking->buku->judul}** telah disetujui.")
            ->line("Silakan ambil buku di perpustakaan dalam 1x24 jam.")
            ->action('Lihat Booking', url('/anggota/bookings'))
            ->line('Terima kasih telah menggunakan layanan perpustakaan digital.');
    }

    public function toArray(object $notifiable): array
    {
        return [
        'judul' => 'Booking Disetujui ✅',
        'pesan' => "Booking buku **{$this->booking->buku->judul}** telah disetujui. Silakan ambil dalam 1x24 jam.",
        'booking_id' => $this->booking->id,
        'url' => url('/anggota/bookings/' . $this->booking->id),
        ];
    }
}
