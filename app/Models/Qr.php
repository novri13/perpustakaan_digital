<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
