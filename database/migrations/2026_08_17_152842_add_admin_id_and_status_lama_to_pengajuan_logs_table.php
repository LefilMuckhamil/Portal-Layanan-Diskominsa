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
        Schema::table('pengajuan_logs', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->after('pengajuan_id')->constrained('users')->nullOnDelete();
            $table->string('status_lama')->nullable()->after('admin_id');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_logs', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['admin_id', 'status_lama']);
        });
    }
};
