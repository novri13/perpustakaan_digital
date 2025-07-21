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
        Schema::create('qr', function (Blueprint $table) {
            $table->id();

            $table->string('kode_qr')->unique();
            $table->enum('tipe', ['buku', 'anggota']); 
            $table->string('referensi_id'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr');
    }
};
