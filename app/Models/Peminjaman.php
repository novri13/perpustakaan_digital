<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'jumlah_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'status_denda',
        'denda_id',
    ];

    public function getKodePeminjamanAttribute(): string
    {
        return 'PEM' . str_pad($this->id, 2, '0', STR_PAD_LEFT);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    public function transaksiDenda()
    {
    return $this->hasMany(TransaksiDenda::class, 'peminjaman_id');
    }

    public function hitungTerlambatHari(): int
    {
    $jatuhTempo = \Carbon\Carbon::parse($this->tanggal_kembali);
    $hariTerlambat = now()->diffInDays($jatuhTempo, false);

    return $hariTerlambat > 0 ? $hariTerlambat : abs($hariTerlambat);
    }
}
