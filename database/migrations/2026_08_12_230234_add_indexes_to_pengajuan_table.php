<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pengajuan')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                $table->index('jenis_layanan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pengajuan')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                $table->dropIndex(['jenis_layanan']);
            });
        }
    }
};
