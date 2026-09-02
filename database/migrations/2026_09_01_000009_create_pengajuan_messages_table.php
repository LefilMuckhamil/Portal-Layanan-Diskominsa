<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('sender_role', ['user', 'admin']);
            $table->text('isi');
            $table->timestamps();
            $table->index(['pengajuan_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_messages');
    }
};
