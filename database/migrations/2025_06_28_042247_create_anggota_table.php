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
        Schema::create('anggota', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // NISN/NIP
            $table->string('nama');
            $table->string('gambar')->nullable();
            $table->string('kelas')->nullable();
            $table->enum('jenkel', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->uuid('qr_code')->nullable();
            $table->enum('jabatan', ['siswa', 'guru']);
            $table->string('email')->nullable();
            $table->string('password'); // untuk login
            $table->enum('status', ['aktif', 'tidak']);
            
            $table->string('id_jurusan')->nullable();
            $table->foreign('id_jurusan')->references('id')->on('jurusan')->nullOnDelete();
            
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
