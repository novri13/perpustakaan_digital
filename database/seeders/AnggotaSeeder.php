<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          Anggota::create([
            'id'        => 'AG001',
            'nama'      => 'Sisri Hayati',
            'email'     => 'sisri@gmail.com',
            'password'  => Hash::make('sisri'),
            'jabatan'   => 'siswa', // atau 'guru'
            'status'    => 'aktif',
            'jenkel'    => 'P',
            'kelas'     => 'XII IPA',
            'alamat'    => 'Jl.projo No.23',
        ]);
    }
}
