<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Jalankan RoleSeeder dulu (harus ada role sebelum assign ke user)
        $this->call(RoleSeeder::class);

        // Jalankan UserSeeder untuk buat user + assign role
        $this->call(UserSeeder::class);

        // Opsional: User testing dummy
        // \App\Models\User::factory(5)->create();
    }
}
