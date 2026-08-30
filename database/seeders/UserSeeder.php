<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Dibuat paling pertama -> Otomatis ID = 1
        $user = User::create([
            'name' => 'Administrator Layanan',
            'nip' => '199001012015011001',
            'unit_kerja' => 'Diskominsa Aceh Barat',
            'jabatan' => 'Administrator Utama',
            'no_hp' => '081234567890',
            'email' => 'admin@acehbaratkab.go.id',
            'password' => Hash::make('password123'),
        ]);

        // role tidak ada di $fillable sehingga harus di-forceFill
        // Admin default otomatis berstatus 'aktif' agar tidak terkunci.
        $user->forceFill([
            'role' => 'admin',
            'status_akun' => 'aktif',
            'approved_at' => now(),
            'approved_by' => $user->id,
        ])->save();
    }
}
