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
        Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->string('anggota_id', 20);$table->foreign('anggota_id')->references('id')->on('anggota')->onDelete('cascade');
        $table->string('buku_id', 20);$table->foreign('buku_id')->references('id')->on('buku')->onDelete('cascade');
        $table->enum('status', ['booking', 'dipinjam', 'gagal'])->default('booking');
        $table->text('catatan')->nullable(); // alasan penolakan
        $table->timestamp('approved_at')->nullable(); // waktu disetujui
        $table->timestamp('rejected_at')->nullable(); // waktu ditolak
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
