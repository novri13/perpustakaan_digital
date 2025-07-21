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
        Schema::create('rak', function (Blueprint $table) {
            $table->string('id', 10)->primary(); // Contoh: R001, R002
            $table->string('name', 100);
            // $table->timestamps();        -> untuk menampilkan create dan update data
        });

        // Update kategori untuk tambahkan kolom rak_id
        Schema::table('kategori', function (Blueprint $table) {
            $table->string('rak_id', 10)->nullable();
            $table->foreign('rak_id')->references('id')->on('rak')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori', function (Blueprint $table) {
            $table->dropForeign(['rak_id']);
            $table->dropColumn('rak_id');
        });

        Schema::dropIfExists('rak');
    }
};
