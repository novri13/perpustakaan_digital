<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BukuDikembalikanNotification extends Notification
{
    use Queueable;

    protected $record;
    protected $denda;

    public function __construct($record, $denda = 0)
    {
        $this->record = $record;
        $this->denda = $denda;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan di database
    }

    public function toDatabase($notifiable)
    {
        return [
            'judul' => 'Pengembalian Buku',
            'pesan' => 'Buku "' . $this->record->buku->judul . '" telah dikembalikan. ' .
                      ($this->denda > 0
                          ? "Terdapat denda sebesar Rp " . number_format($this->denda, 0, ',', '.') . " karena keterlambatan."
                          : "Terima kasih telah mengembalikan buku tepat waktu."),
            'tanggal' => now(),
        ];
    }
}

