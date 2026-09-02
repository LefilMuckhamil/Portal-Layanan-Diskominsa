<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_hosting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->unique()->cascadeOnDelete();
            $table->string('email_google', 150);
            $table->string('nama_aplikasi', 255);
            $table->string('runtime', 255);
            $table->string('database_type', 255);
            $table->string('storage_quota', 255);
            $table->string('domain_terkait', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_hosting');
    }
};
