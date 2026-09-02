<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->unique()->cascadeOnDelete();
            $table->foreignId('kategori_bantuan_id')->nullable()->constrained('kategori_bantuan')->nullOnDelete();
            $table->string('email_reset', 150);
            $table->text('deskripsi_kendala')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_bantuan');
    }
};
