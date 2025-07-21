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
            $table->integer('jumlah_buku')->after('tanggal_kembali')->default(0);
            $table->foreignId('denda_id')->nullable()->constrained('denda')->after('jumlah_buku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('jumlah_buku');
            $table->dropForeign(['denda_id']);
            $table->dropColumn('denda_id');
        });
    }
};
