<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('peminjaman', function (Blueprint $table) {
        if (!Schema::hasColumn('peminjaman', 'tanggal_dikembalikan')) {
            $table->date('tanggal_dikembalikan')->nullable()->after('tanggal_kembali');
        }

        // if (!Schema::hasColumn('peminjaman', 'status_denda')) {
        //     $table->enum('status_denda', ['lunas', 'belum_lunas'])->nullable()->after('tanggal_dikembalikan');
        // }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            //
        });
    }
};
