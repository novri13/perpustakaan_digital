<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda'; 

    protected $fillable = [
        'jenis_denda',
        'harga',
    ];

    /**
     * Relasi ke Peminjaman (jika peminjaman punya kolom denda_id)
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'denda_id');
    }

    /**
     * Relasi ke Pengembalian (jika pengembalian punya kolom denda_id)
     */
    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class, 'denda_id');
    }

    public function transaksiDenda()
    {
    return $this->hasMany(TransaksiDenda::class, 'denda_id');
    }
}
