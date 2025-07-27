<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiDenda extends Model
{
   protected $fillable = [
        'peminjaman_id', 'jumlah_denda', 'status_bayar'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function anggota()
    {
        return $this->hasOneThrough(Anggota::class, Peminjaman::class, 'id', 'id', 'peminjaman_id', 'anggota_id');
    }

    /** Relasi ke User (Anggota yang kena denda) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Relasi ke Pengembalian */
    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class);
    }

    /** Relasi ke Master Denda */
    public function denda()
    {
        return $this->belongsTo(Denda::class);
    }
}
