<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('nip');
                $table->index('no_hp');
            });
        }

        if (Schema::hasTable('pengajuan')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['nip']);
                $table->dropIndex(['no_hp']);
            });
        }

        if (Schema::hasTable('pengajuan')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                $table->dropIndex(['status']);
            });
        }
    }
};
