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
            $table->string('nomor_tiket', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanan')->restrictOnDelete();
            $table->string('status', 20)->default('Pending')->index();
            $table->string('file_pendukung')->nullable();
            $table->string('file_hasil')->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
