<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
     use HasFactory;

    public $timestamps = false;  // NONAKTIFKAN timestamps

    protected $table = 'rak';
    protected $fillable = ['id', 'name'];

    public $incrementing = false; // karena id berupa string (R001)
    protected $keyType = 'string';

    // Setiap rak punya banyak kategori
    public function kategoris()
    {
        return $this->hasMany(Kategori::class, 'rak_id');
    }
}
