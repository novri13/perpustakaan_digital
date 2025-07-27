<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Cek dulu sebelum menambah kolom
        if (!Schema::hasColumn('peminjaman', 'denda')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->decimal('denda', 10, 2)
                    ->nullable()
                    ->after('status');
            });
        }
    }

    public function down(): void
    {
        // ✅ Cek dulu sebelum menghapus kolom
        if (Schema::hasColumn('peminjaman', 'denda')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->dropColumn('denda');
            });
        }
    }
};
