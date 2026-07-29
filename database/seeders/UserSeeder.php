<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Diskominsa
        User::create([
            'name' => 'Admin Diskominsa',
            'email' => 'admin@diskominsa.go.id',
            'password' => Hash::make('password123'), // Passwordnya: password123
            'role' => 'admin',
        ]);

        // 2. Akun User / ASN / Instansi
        User::create([
            'name' => 'ASN Instansi',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password123'), // Passwordnya: password123
            'role' => 'user',
        ]);
    }
}