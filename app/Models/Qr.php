<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
     use HasFactory;

    protected $table = 'qr';

    protected $fillable = [
        'kode_qr',
        'tipe',
        'referensi_id',
    ];
}
