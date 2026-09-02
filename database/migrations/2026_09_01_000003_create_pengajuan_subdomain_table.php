<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_subdomain', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->unique()->cascadeOnDelete();
            $table->string('email_google', 150)->nullable();
            $table->string('domain', 255);
            $table->string('ip_address', 45);
            $table->string('nama_aplikasi', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_subdomain');
    }
};
