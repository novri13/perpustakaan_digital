<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
     use HasFactory;

    protected $table = 'lampiran';

    protected $fillable = [
        'buku_id',
        'lampiran_buku',
        'tgl_masuk',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
