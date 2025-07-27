<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use App\Models\Rak;
use App\Models\Penerbit; 
use App\Models\Peminjaman;   


class Buku extends Model
{
    use HasFactory;
    protected $table = 'buku';
    

    protected $primaryKey = 'id';
    public $incrementing = false; // karena id string (ISBN)
    protected $keyType = 'string';
    public $timestamps = false;  // NONAKTIFKAN timestamps
    protected $fillable = [
        'id', 'judul', 'gambar', 'pengarang', 'stok',
        'edisi', 'bahasa', 'tahun_terbit', 'tahun_masuk', 'tahun_berubah',
        'deskripsi', 'qr_code', 'id_kategori', 'id_rak', 'id_penerbit'
    ];

    // Relasi ke Kategori
    public function kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // Relasi ke Rak
    public function rak() {
        return $this->belongsTo(Rak::class, 'id_rak');
    }

    // Relasi ke Penerbit
    public function penerbit() {
        return $this->belongsTo(Penerbit::class, 'id_penerbit');
    }

    public function peminjaman()
    {
    return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    public function bookings()
   {
    return $this->hasMany(Booking::class, 'buku_id', 'id');
    }
    
}
