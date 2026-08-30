<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status_akun', ['pending', 'aktif', 'ditolak'])->default('pending')->after('role');
            $table->timestamp('approved_at')->nullable()->after('status_akun');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            $table->index('status_akun');
        });

        // Migrasi aman: semua akun lama berstatus 'pending' (default kolom baru)
        // diaktifkan otomatis agar tidak terkunci oleh sistem verifikasi baru.
        DB::table('users')->where('status_akun', 'pending')->update([
            'status_akun' => 'aktif',
            'approved_at' => DB::raw('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status_akun']);
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn(['status_akun', 'approved_at']);
        });
    }
};
