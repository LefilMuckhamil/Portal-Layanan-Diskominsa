<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->index('jenis_layanan');
            $table->index('status');
            $table->index('nomor_tiket');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropIndex(['jenis_layanan']);
            $table->dropIndex(['status']);
            $table->dropIndex(['nomor_tiket']);
            $table->dropIndex(['user_id']);
        });
    }
};