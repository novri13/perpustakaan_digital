<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerbit extends Model
{
    use HasFactory;

    public $timestamps = false;  // NONAKTIFKAN timestamps

    protected $table = 'penerbit';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'name'];
}
