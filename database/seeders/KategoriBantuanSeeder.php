<?php

namespace Database\Seeders;

use App\Models\KategoriBantuan;
use Illuminate\Database\Seeder;

class KategoriBantuanSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Reset Password Email',
            'Reset OTP',
        ];

        foreach ($kategori as $nama) {
            KategoriBantuan::updateOrCreate(
                ['nama_kategori' => $nama],
                ['is_active' => true]
            );
        }
    }
}
