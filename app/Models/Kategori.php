<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    public $timestamps = false;  // NONAKTIFKAN timestamps
    

    protected $table = 'kategori';
    protected $fillable = ['id', 'name', 'gambar','rak_id'];

    public $incrementing = false; // karena id berupa string (K001)
    protected $keyType = 'string';

    public function rak()
    {
    return $this->belongsTo(Rak::class, 'rak_id');
    }
}
