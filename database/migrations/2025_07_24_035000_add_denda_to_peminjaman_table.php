<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Tambah kolom denda hanya jika belum ada
            if (!Schema::hasColumn('peminjaman', 'denda')) {
                $table->decimal('denda', 10, 2)->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Hapus kolom hanya jika ada
            if (Schema::hasColumn('peminjaman', 'denda')) {
                $table->dropColumn('denda');
            }
        });
    }
};
