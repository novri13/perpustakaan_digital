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
        Schema::create('buku', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // dari ISBN/ISSN
            $table->string('judul');
            $table->string('gambar')->nullable(); // file path atau URL
            $table->string('pengarang');
            $table->integer('stok');
            $table->string('edisi', 50)->nullable();
            $table->string('bahasa', 50)->nullable();
            $table->date('tahun_terbit')->nullable();
            $table->date('tahun_masuk')->nullable();
            $table->date('tahun_berubah')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('qr_code')->nullable(); // misalnya path ke QR image atau teks QR
             
            // Foreign key dengan tipe string, harus manual
            $table->string('id_kategori', 25);
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade');

            $table->string('id_rak', 25);
            $table->foreign('id_rak')->references('id')->on('rak')->onDelete('cascade');

            $table->string('id_penerbit', 25);
            $table->foreign('id_penerbit')->references('id')->on('penerbit')->onDelete('cascade');
            // $table->timestamps();        -> untuk menampilkan create dan update data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
