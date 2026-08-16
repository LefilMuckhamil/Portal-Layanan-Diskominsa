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
        User::create([
            'name' => 'Administrator Layanan',
            'nip' => '199001012015011001',
            'unit_kerja' => 'Diskominsa Aceh Barat',
            'jabatan' => 'Administrator Utama',
            'no_hp' => '081234567890',
            'email' => 'admin@acehbaratkab.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}
