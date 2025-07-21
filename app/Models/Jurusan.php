<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    public $timestamps = false;  // NONAKTIFKAN timestamps
    
    protected $table = 'jurusan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'name'];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_jurusan', 'id');
    }

}
