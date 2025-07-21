<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'), 
        ]);
        $admin->assignRole('admin');

        // Buat user pustakawan
        $pustakawan = User::create([
            'name' => 'Pustakawan',
            'email' => 'pustakawan@gmail.com',
            'password' => bcrypt('pustakawan'),
        ]);
        $pustakawan->assignRole('pustakawan');

        // Buat user kepala sekolah
        $kepsek = User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@example.com',
            'password' => bcrypt('kepsek'),
        ]);
        $kepsek->assignRole('kepala_sekolah');
    }
}
