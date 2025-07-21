<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\Jurusan;
use App\Models\Peminjaman;
use App\Models\User;

class Anggota extends Model
{
    use HasFactory,Notifiable;

    public $timestamps = false;  // NONAKTIFKAN timestamps
    
    protected $table = 'anggota';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'nama', 'gambar', 'kelas', 'jenkel',
        'alamat', 'no_telp', 'qr_code', 'jabatan',
        'email', 'password', 'status', 'id_jurusan','user_id',
    ];

    protected $hidden = ['password'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
