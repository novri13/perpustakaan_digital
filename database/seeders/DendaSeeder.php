<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ Tambahkan ini
use Illuminate\Support\Carbon;

class DendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('denda')->insert([
            [
                'jenis_denda' => 'Kehilangan Buku',
                'harga' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_denda' => 'Kerusakan Buku',
                'harga' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_denda' => 'Terlambat Mengembalikan Buku',
                'harga' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
