<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // UBAH 'pengajuans' MENJADI 'pengajuan' (TANPA S)
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jenis_layanan');
            $table->string('status')->default('Pending');
            $table->json('data_pengajuan')->nullable();
            $table->string('file_pendukung')->nullable();
            $table->json('logs')->nullable();
            $table->json('pesan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
